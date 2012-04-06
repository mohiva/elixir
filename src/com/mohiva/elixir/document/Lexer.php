<?php
/**
 * Mohiva Elixir
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.textile.
 * It is also available through the world-wide-web at this URL:
 * https://github.com/mohiva/pyramid/blob/master/LICENSE.textile
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\document;

use DOMAttr;
use com\mohiva\common\xml\XMLDocument;
use com\mohiva\common\xml\XMLElement;
use com\mohiva\common\parser\TokenStream;
use com\mohiva\elixir\document\expression\Lexer as ExpressionLexer;
use com\mohiva\elixir\document\tokens\PropertyToken;
use com\mohiva\elixir\document\tokens\NodeToken;
use com\mohiva\elixir\document\tokens\HelperToken;
use com\mohiva\elixir\document\tokens\ExpressionToken;

/**
 * Tokenize a document.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class Lexer {

	/**
	 * All available document token codes.
	 *
	 * @var int
	 */
	const T_XML_VERSION      = 1;
	const T_XML_ENCODING     = 2;
	const T_ROOT_NODE        = 3;
	const T_ELEMENT_NODE     = 4;
	const T_ELEMENT_HELPER   = 5;
	const T_ATTRIBUTE_HELPER = 6;
	const T_EXPRESSION       = 7;

	/**
	 * The XPath pattern used to find the expressions inside the XML document.
	 *
	 * @var string
	 */
	const EXPRESSION_PATTERN = "contains(., '{%') and contains(., '%}')";

	/**
	 * Contains namespaces that can be defined in XML documents, but which
	 * can't contains helpers.
	 *
	 * @var array
	 */
	private $nonHelperNamespaces = array(
		'http://www.w3.org/2001/XInclude',
		'http://www.w3.org/XML/1998/namespace',
		'http://www.w3.org/1999/xhtml',
		'http://www.w3.org/1999/XSL/Transform',
		'http://www.w3.org/1999/Math/MathML',
		'http://www.w3.org/2000/svg',
		'http://www.w3.org/1999/xlink'
	);

	/**
	 * Contains all namespaces found within this document.
	 *
	 * @var array
	 */
	private $namespaces = array();

	/**
	 * Stores the namespace part of a XPATH query to get all elements which
	 * have a helper namespace. This query is used more than once, therefore
	 * it will be cached for reuse.
	 *
	 * @var string
	 */
	private $elNsQuery = '';

	/**
	 * Stores the namespace part of a XPATH query to get all elements or attributes
	 * which have a helper namespace. This query is used more than once, therefore
	 * it will be cached for reuse.
	 *
	 * @var string
	 */
	private $elAtNsQuery = '';

	/**
	 * The expression lexer object to create the token stream from the found expressions.
	 *
	 * @var ExpressionLexer
	 */
	private $expressionLexer = null;

	/**
	 * The class constructor.
	 *
	 * @param ExpressionLexer $expressionLexer The expression lexer object to
	 * create the token stream from the found expressions.
	 */
	public function __construct(ExpressionLexer $expressionLexer) {

		$this->expressionLexer = $expressionLexer;
	}

	/**
	 * Set the file to tokenize and start the tokenize process.
	 *
	 * Note: Use a clone of the document if you scan the file multiple times, because
	 * the lexer changes the document structure. Scanning the same document twice will
	 * not work.
	 *
	 * @param XMLDocument $doc The XML document to tokenize.
	 * @return TokenStream The resulting token stream.
	 */
	public function scan(XMLDocument $doc) {

		$this->namespaces = $doc->getNamespaces($this->nonHelperNamespaces);
		$this->elNsQuery = $this->buildNSQuery($this->namespaces, array('.'));
		$this->elAtNsQuery = $this->buildNSQuery($this->namespaces, array('.', '@*'));

		$stream = $this->tokenize($doc);
		$stream->rewind();

		return $stream;
	}

	/**
	 * Tokenize the given XML document.
	 *
	 * @param XMLDocument $doc The document to tokenize.
	 * @return TokenStream The resulting token stream.
	 */
	private function tokenize(XMLDocument $doc) {

		// The lexer tokenize the XML content from the inside-out,
		// so we iterate in reverse order to start from the root node.
		$stream = new TokenStream;
		$stream->setIteratorMode(TokenStream::IT_MODE_LIFO);
		$stream->setSource($doc->saveXML());

		// For attribute expressions in the content of a node
		$expressionQuery  = "descendant::*//@*[" . self::EXPRESSION_PATTERN . "]";

		// For attribute expressions on the same node
		$parentNSQuery = $this->buildNSQuery($this->namespaces, array('parent::*'));
		$expressionQuery .= "|@*[not({$parentNSQuery}) and " . self::EXPRESSION_PATTERN . "]";

		// For expressions in the content of a node
		$expressionQuery .= "|descendant-or-self::*//text()[" . self::EXPRESSION_PATTERN . "]";

		$nodeQuery = $this->elNsQuery ? "//*[{$this->elNsQuery}]|//@*[{$this->elNsQuery}]/parent::*|/*" : '/*';
		$nodes = $doc->xpath->query($nodeQuery);
		for ($i = $nodes->length - 1; $i >= 0; $i--) {
			/* @var XMLElement $node */
			$node = $nodes->item($i);

			// NOTE: This order shouldn't be changed, otherwise the parser cannot
			// resolve the Parent/Child relationship
			$this->tokenizeAttributeHelpers($stream, $node);
			$this->tokenizeElementHelper($stream, $node);
			$this->tokenizeExpressions($stream, $node, $expressionQuery);

			$path = $node->getNodePath();
			$id = sha1($path);
			$token = new NodeToken(
				$node === $doc->documentElement ? self::T_ROOT_NODE : self::T_ELEMENT_NODE,
				$id,
				$path,
				$node->getLineNo(),
				$this->getAncestor($node),
				$this->getContent($node),
				$this->getChildren($node)
			);

			$placeholder = $doc->createElement('__node__', $id);
			$node->parentNode->replaceChild($placeholder, $node);
			$stream->push($token);
		}

		// Iterator mode is set to LIFO, so this are the the first tokens
		$stream->push(new PropertyToken(self::T_XML_ENCODING, $doc->xmlEncoding));
		$stream->push(new PropertyToken(self::T_XML_VERSION, $doc->xmlVersion));

		return $stream;
	}

	/**
	 * Check if the given element is an element helper. If it's a helper then extract
	 * all helper relevant data and push it to the stream.
	 *
	 * @param TokenStream $stream The stream in which the tokens should be written.
	 * @param XMLElement $element The current processed XML element object.
	 */
	private function tokenizeElementHelper(TokenStream $stream, XMLElement $element) {

		if (!in_array($element->namespaceURI, $this->namespaces)) {
			return;
		}

		$query = "@*[not({$this->elNsQuery}) and " . self::EXPRESSION_PATTERN . "]";
		$this->tokenizeExpressions($stream, $element, $query);
		$token = new HelperToken(
			self::T_ELEMENT_HELPER,
			sha1($element->getNodePath()),
			$element->getNodePath(),
			$element->getLineNo(),
			$element->localName,
			$element->namespaceURI
		);

		$token->setAttributes($this->getHelperAttributes($element));
		$stream->push($token);
	}

	/**
	 * Check if the given element contains attribute helpers. If it contains
	 * helpers then extract all helper relevant data and push it to the stream.
	 * This method will also remove the found helper from element.
	 *
	 * @param TokenStream $stream The stream in which the tokens should be written.
	 * @param XMLElement $element The current processed XML element object.
	 */
	private function tokenizeAttributeHelpers(TokenStream $stream, XMLElement $element) {

		if (!$this->namespaces) {
			return;
		}

		/* @var \DomNodeList $nodes */
		$nodes = $element("@*[{$this->elNsQuery}]");
		for ($i = $nodes->length - 1; $i >= 0; $i--) {
			/* @var \DOMAttr $attribute */
			$attribute = $nodes->item($i);
			$query = "attribute::*[local-name() = '{$attribute->localName}' and " . self::EXPRESSION_PATTERN . "]";
			$this->tokenizeExpressions($stream, $element, $query);
			$token = new HelperToken(
				self::T_ATTRIBUTE_HELPER,
				sha1($attribute->getNodePath()),
				$attribute->getNodePath(),
				$attribute->getLineNo(),
				$attribute->localName,
				$attribute->namespaceURI
			);
			$token->setValue($attribute->value);
			$stream->push($token);

			$attribute->parentNode->removeAttributeNode($attribute);
		}
	}

	/**
	 * Get all expressions from the given element based on the given XPATH query and push
	 * it to the stream.
	 *
	 * @param TokenStream $stream The stream in which the tokens should be written.
	 * @param XMLElement $element The current processed element.
	 * @param string $query The query to get the expressions from element.
	 */
	private function tokenizeExpressions(TokenStream $stream, XMLElement $element, $query) {

		/* @var \DomNodeList $nodes */
		$nodes = $element($query);
		for ($i = $nodes->length - 1; $i >= 0; $i--) {
			/* @var \DOMNode $node */
			$node = $nodes->item($i);
			if (preg_match_all('/\{%(.*)%\}/Ums', $node->nodeValue, $matches, PREG_SET_ORDER)) {
				$matches = array_reverse($matches);
				foreach ($matches as $match) {
					$token = new ExpressionToken(
						self::T_EXPRESSION,
						sha1($element->getNodePath()),
						$element->getNodePath(),
						$element->getLineNo(),
						$match[0],
						$node->nodeType == XML_ATTRIBUTE_NODE ? /* @var \DOMAttr $node */ $node->name : null,
						clone $this->expressionLexer->scan($match[1])
					);
					$stream->push($token);
				}
			}
		}
	}

	/**
	 * Get all helper attributes from the given element.
	 *
	 * @param XMLElement $element The current processed XML element object.
	 * @return array All namespaced attributes as name => value pair.
	 */
	private function getHelperAttributes(XMLElement  $element) {

		$attributes = array();
		foreach ($element->attributes as $attribute) {
			/* @var \DOMAttr $attribute */
			if (!$this->isAttributeHelper($attribute)) {
				$attributes[$attribute->name] = $attribute->value;
			}
		}

		return $attributes;
	}

	/**
	 * Check if the given DOMAttr element is an attribute helper.
	 *
	 * Attribute helpers have its own namespace.
	 *
	 * @param \DOMAttr $attribute The attribute element to check.
	 * @return boolean True if the element is an attribute helper, false otherwise.
	 */
	private function isAttributeHelper(DOMAttr $attribute) {

		return $attribute->namespaceURI ? true : false;
	}

	/**
	 * Get the ancestor for the given element.
	 *
	 * @param XMLElement $element The current processed element.
	 * @return string The ancestor id of the given element.
	 */
	private function getAncestor(XMLElement $element) {

		if (!$this->namespaces || $element === $element->ownerDocument->documentElement) {
			return null;
		}

		/* @var \DOMNodeList $ancestors */
		$ancestors = $element("ancestor::*[{$this->elAtNsQuery}]");
		if ($ancestors->length) {
			$ancestor = $ancestors->item($ancestors->length - 1);
		} else {
			$ancestor = $element->ownerDocument->documentElement;
		}

		return sha1($ancestor->getNodePath());
	}

	/**
	 * Get all children from the given element. The children are the previously
	 * replaced helper tags in the form <__node__>[a-f0-9]{40}</__node__>.
	 *
	 * @param XMLElement $element The current processed element.
	 * @return array A list with child id's in ascending order.
	 */
	private function getChildren(XMLElement $element) {

		$children = array();
		preg_match_all('/\<__node__>([a-f0-9]{40})<\/__node__>/', $element->toXML(), $matches);
		if ($matches) {
			$children = $matches[1];
		}

		return $children;
	}

	/**
	 * Get the content for the given node. This method removes all helper related tags
	 * or attributes from XML and return the result as string.
	 *
	 * @param XMLElement $element The current processed element.
	 * @return string The content of the given XML node.
	 */
	private function getContent(XMLElement $element) {

		if ($this->hasHelperNamespace($element)) {
			return $this->getChildNodesAsContent($element);
		}

		return $this->getCompleteNodeAsContent($element);
	}

	/**
	 * @param XMLElement $element The current processed element.
	 * @return bool True if the element has a helper namespace.
	 */
	private function hasHelperNamespace(XMLElement $element) {

		if (in_array($element->namespaceURI, $this->namespaces)) {
			return true;
		}

		return false;
	}

	/**
	 * @param XMLElement $element The current processed element.
	 * @return string The string representation of the element.
	 */
	private function getCompleteNodeAsContent(XMLElement $element) {

		$this->removeHelperNamespaces($element);

		return $element->toXML();
	}

	/**
	 * @param XMLElement $element The current processed element.
	 * @return string The string representations of the elements child nodes.
	 */
	private function getChildNodesAsContent(XMLElement $element) {

		$content = '';
		foreach ($element->childNodes as $child) {
			/* @var XMLElement $child */
			if ($child->nodeType == XML_ELEMENT_NODE) {
				$this->removeHelperNamespaces($child);
				$content .= $child->toXML();
			} else if ($child->nodeType == XML_COMMENT_NODE) {
				$content .= '<!--' . $child->nodeValue . '-->';
			} else {
				$content .= $child->nodeValue;
			}
		}

		return $content;
	}

	/**
	 * Remove all helper namespaces from the given element and his descendants.
	 *
	 * @param XMLElement $element The current processed node.
	 */
	private function removeHelperNamespaces(XMLElement $element) {

		$namespaces = $element('descendant-or-self::*/namespace::*');
		foreach ($namespaces as $node) {
			/* @var \DOMElement $node */
			if (!in_array($node->nodeValue, $this->nonHelperNamespaces)) {
				$node->parentNode->removeAttributeNS($node->nodeValue, $node->prefix);
			}
		}
	}

	/**
	 * Create the namespace part of a XPATH query to get the given elements
	 * which have a helper namespace.
	 *
	 * @param array $namespaces A list with namespaces.
	 * @param array $elements The elements on which the namespace is located.
	 * @return string A string containing a list of namespaces separated by the or keyword.
	 */
	private function buildNSQuery(array $namespaces, array $elements) {

		$uris = array();
		foreach ($namespaces as $nsUri) {
			foreach ($elements as $element) {
				$uris[] = "namespace-uri({$element})='{$nsUri}'";
			}
		}

		$nsString = $uris ? implode(' or ', $uris) : '';

		return $nsString;
	}
}
