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

use com\mohiva\pyramid\Parser as ExpressionParser;
use com\mohiva\common\parser\TokenStream;
use com\mohiva\common\exceptions\SyntaxErrorException;
use com\mohiva\elixir\document\expression\Lexer as ExpressionLexer;
use com\mohiva\elixir\document\tokens\PropertyToken;
use com\mohiva\elixir\document\tokens\NodeToken;
use com\mohiva\elixir\document\tokens\HelperToken;
use com\mohiva\elixir\document\tokens\ExpressionToken;
use com\mohiva\elixir\document\tokens\ExpressionContentToken;
use com\mohiva\elixir\document\helpers\ElementHelper;
use com\mohiva\elixir\document\helpers\AttributeHelper;
use com\mohiva\elixir\document\exceptions\UnexpectedTokenException;
use com\mohiva\elixir\document\exceptions\MissingNamespaceException;
use com\mohiva\elixir\document\exceptions\InvalidNamespaceException;
use com\mohiva\elixir\document\exceptions\UnexpectedHelperTypeException;

/**
 * Parse a document.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class Parser {

	/**
	 * The URL namespace for all Elixir core helpers.
	 *
	 * @var string
	 */
	const URL_NAMESPACE = 'http://elixir.mohiva.com';

	/**
	 * The PHP namespace for all Elixir core helper classes.
	 *
	 * @var string
	 */
	const PHP_NAMESPACE = 'com\mohiva\elixir\document\helpers';

	/**
	 * The PHP namespace separator.
	 *
	 * @var string
	 */
	const NS_SEPARATOR = '\\';

	/**
	 * The Uniform Resource Name identifier as regex.
	 *
	 * @var string
	 */
	const URN_IDENTIFIER = 'urn:';

	/**
	 * The URL identifier for all registered internal namespaces as regex.
	 *
	 * @var string
	 */
	const URL_IDENTIFIER = 'http[s]?:\/\/';

	/**
	 * The prefix for the helper classes.
	 *
	 * @var string
	 */
	const HELPER_PREFIX = 'Helper';

	/**
	 * The lexer to tokenize the expressions.
	 *
	 * @var ExpressionLexer
	 */
	private $expressionLexer = null;

	/**
	 * The parser to parse the expressions.
	 *
	 * @var ExpressionParser
	 */
	private $expressionParser = null;

	/**
	 * Contains a list with all registered internal URL namespaces and
	 * their related PHP namespaces.
	 *
	 * @var array
	 */
	private $namespaces = array(
		self::URL_NAMESPACE => self::PHP_NAMESPACE
	);

	/**
	 * The class constructor.
	 *
	 * @param ExpressionLexer $expressionLexer The lexer to tokenize the expressions.
	 * @param ExpressionParser $expressionParser The parser to parse the expressions.
	 */
	public function __construct(ExpressionLexer $expressionLexer, ExpressionParser $expressionParser) {

		$this->expressionLexer = $expressionLexer;
		$this->expressionParser = $expressionParser;
	}

	/**
	 * Register a PHP namespace for an internal URL namespace.
	 *
	 * @param string $urlNamespace The internal URL namespace.
	 * @param string $phpNamespace The related PHP namespace.
	 */
	public function registerNamespace($urlNamespace, $phpNamespace) {

		$this->namespaces[$urlNamespace] = $phpNamespace;
	}

	/**
	 * Parse the token stream and return the resulting parse tree.
	 *
	 * @param TokenStream $stream The stream to parse.
	 * @return NodeTree The tree object which contains the parser result.
	 * @throws UnexpectedTokenException
	 */
	public function parse(TokenStream $stream) {

		/* @var \com\mohiva\elixir\document\expression\Container $expressionContainer */
		$expressionContainer = null;
		$node = null;
		$nodes = array();
		$tree = new NodeTree();
		foreach ($stream as $token) {
			/* @var \com\mohiva\common\parser\Token $token */
			switch ($token->getCode()) {
				case Lexer::T_XML_VERSION:
					/* @var PropertyToken $token */
					$tree->setVersion($token->getValue());
					break;

				case Lexer::T_XML_ENCODING:
					/* @var PropertyToken $token */
					$tree->setEncoding($token->getValue());
					break;

				case Lexer::T_ROOT_NODE:
					/* @var NodeToken $token */
					$node = $this->createNodeFromToken($token);
					$nodes[$token->getId()] = $node;
					$expressionContainer = $node;
					$tree->setRoot($node);
					break;

				case Lexer::T_ELEMENT_NODE:
					/* @var NodeToken $token */
					$node = $this->createNodeFromToken($token);
					$nodes[$token->getId()] = $node;
					/* @var Node $ancestorNode */
					$ancestorNode = $nodes[$token->getAncestor()];
					$ancestorNode->addChild($node);
					$expressionContainer = $node;
					$node->setAncestor($ancestorNode);
					$this->setSiblings($token, $nodes);
					break;

				case Lexer::T_ELEMENT_HELPER:
					/* @var HelperToken $token */
					$helper = $this->createElementHelperFromToken($token);
					$expressionContainer = $helper;
					$node->addHelper($helper);
					break;

				case Lexer::T_ATTRIBUTE_HELPER:
					/* @var HelperToken $token */
					$helper = $this->createAttributeHelperFromToken($token);
					$expressionContainer = $helper;
					$node->addHelper($helper);
					break;

				case Lexer::T_EXPRESSION:
					/* @var ExpressionToken $token */
					$expressionContainer->addExpressions($this->createExpressionsFromToken($token));
					break;

				default:
					throw new UnexpectedTokenException("Unexpected token code: {$token->getCode()}");
			}
		}

		return $tree;
	}

	/**
	 * Sets the previous and the next sibling node.
	 *
	 * It checks if a previous processed node is the preceding sibling of the current node. Is this true
	 * then the current processed node is the following sibling of this preceding node.
	 *
	 * @param NodeToken $token The current processed node token.
	 * @param array $nodes The list with processed nodes.
	 */
	private function setSiblings(NodeToken $token, array $nodes) {

		if (!$token->getPreviousSibling() || !isset($nodes[$token->getPreviousSibling()])) {
			return;
		}

		/* @var Node $node */
		$node = $nodes[$token->getId()];
		$node->setPreviousSibling($nodes[$token->getPreviousSibling()]);
		$node->getPreviousSibling()->setNextSibling($node);
	}

	/**
	 * Creates a new document node.
	 *
	 * @param NodeToken $token The node token.
	 * @return Node The document node.
	 */
	private function createNodeFromToken(NodeToken $token) {

		$node = new Node(
			$token->getId(),
			$token->getCode(),
			$token->getContent(),
			$token->getLine(),
			$token->getPath()
		);

		return $node;
	}

	/**
	 * Creates an instance of the found element helper and return it.
	 *
	 * @param HelperToken $token The helper token.
	 * @return ElementHelper The element helper instance.
	 * @throws UnexpectedHelperTypeException if the found helper isn't derived from `ElementHelper` class.
	 */
	private function createElementHelperFromToken(HelperToken $token) {

		$helperClass = $this->getFullyQualifiedHelperName($token->getNamespace(), $token->getName());
		$helperType = 'com\mohiva\elixir\document\helpers\ElementHelper';
		if (!is_subclass_of($helperClass, $helperType)) {
			throw new UnexpectedHelperTypeException("The helper `{$helperClass}` must be of type `{$helperType}`");
		}

		/* @var ElementHelper $helper */
		$helper = new $helperClass(
			$token->getId(),
			$token->getAttributes(),
			$token->getLine(),
			$token->getPath()
		);

		return $helper;
	}

	/**
	 * Creates an instance of the found attribute helper and return it.
	 *
	 * @param HelperToken $token The helper token.
	 * @return AttributeHelper The attribute helper instance.
	 * @throws UnexpectedHelperTypeException if the found helper isn't derived from `AttributeHelper` class.
	 */
	private function createAttributeHelperFromToken(HelperToken $token) {

		$helperClass = $this->getFullyQualifiedHelperName($token->getNamespace(), $token->getName());
		$helperType = 'com\mohiva\elixir\document\helpers\AttributeHelper';
		if (!is_subclass_of($helperClass, $helperType)) {
			throw new UnexpectedHelperTypeException("The helper `{$helperClass}` must be of type `{$helperType}`");
		}

		/* @var AttributeHelper $helper */
		$helper = new $helperClass(
			$token->getId(),
			$token->getValue(),
			$token->getLine(),
			$token->getPath()
		);

		return $helper;
	}

	/**
	 * Parses all expressions found in the token stream and returns a list of
	 * expression objects. On object for each found expression.
	 *
	 * @param ExpressionToken $token The token to parse.
	 * @return Expression[] The list of found expressions.
	 */
	private function createExpressionsFromToken(ExpressionToken $token) {

		$expressions = array();
		$number = 0;
		$stream = $token->getStream();
		while ($stream->valid()) {
			if ($stream->current()->getCode() != Lexer::T_EXPRESSION_OPEN &&
				$stream->current()->getCode() != Lexer::T_EXPRESSION_CLOSE) {
				$stream->next();
				continue;
			}

			$this->expectExpressionOpener($stream);
			$content = $this->getExpressionContent($stream);
			$this->expectExpressionCloser($stream);

			$node = $this->expressionParser->parse($this->expressionLexer->scan($content));

			$path = $token->getPath() . '{% ' . $number++ . ' %}';
			$expression = new Expression(
				sha1($path),
				$token->getCode(),
				$token->getLine(),
				$path,
				$content,
				$node
			);
			$expressions[] = $expression;
		}

		return $expressions;
	}

	/**
	 * Expects that the current token is the expression opener.
	 *
	 * @param TokenStream $stream The expression stream.
	 * @throws SyntaxErrorException if the expression opener couldn't be found.
	 */
	private function expectExpressionOpener(TokenStream $stream) {

		$stream->expect(array(Lexer::T_EXPRESSION_OPEN), function(ExpressionContentToken $current) {
			$message = "Expression opener `{%` expected; got `{$current->getValue()}`";
			throw new SyntaxErrorException($message);
		});
		$stream->next();
	}

	/**
	 * Expects that the current token is the expression opener.
	 *
	 * @param TokenStream $stream The expression stream.
	 * @throws SyntaxErrorException if the expression opener couldn't be found.
	 */
	private function expectExpressionCloser(TokenStream $stream) {

		$stream->expect(array(Lexer::T_EXPRESSION_CLOSE), function(ExpressionContentToken $current = null) {
			if ($current) {
				$message = "Expression closer `%}` expected; got `{$current->getValue()}`";
			} else {
				$message = "Expression closer `%}` expected, but end of stream reached";
			}

			throw new SyntaxErrorException($message);
		});
		$stream->next();
	}

	/**
	 * Gets the expression content.
	 *
	 * @param TokenStream $stream The token stream.
	 * @return string The expression.
	 */
	private function getExpressionContent(TokenStream $stream) {

		$expression = '';
		while ($stream->valid()) {
			if ($stream->current()->getCode() != Lexer::T_EXPRESSION_CHARS) {
				break;
			}

			/* @var \com\mohiva\elixir\document\tokens\ExpressionContentToken $token */
			$token = $stream->current();
			$expression .= $token->getValue();
			$stream->next();
		}

		return $expression;
	}

	/**
	 * Get the fully qualified name of a helper node. Some packages can register
	 * an internal URL namespace in the form http://elixir.mohiva.com/package
	 * with a related PHP namespace. So if the given namespace is an internal URL
	 * namespace then the related PHP namespace will be used instead of the given
	 * URL namespace.
	 *
	 * @param string $helperNamespace
	 * @param string $helperName
	 * @return string
	 * @throws InvalidNamespaceException if a XML namespace uses no URI or URN.
	 */
	private function getFullyQualifiedHelperName($helperNamespace, $helperName) {

		$isUrl = preg_match('/^' . self::URL_IDENTIFIER . '/', $helperNamespace);
		$isUrn = preg_match('/^' . self::URN_IDENTIFIER . '/', $helperNamespace);
		if ($isUrl) {
			$helperNamespace = $this->getUrlNamespace($helperNamespace);
		} else if ($isUrn) {
			$helperNamespace = $this->getUrnNamespace($helperNamespace);
		} else {
			throw new InvalidNamespaceException('A XML namespace must start with a URL or a URN');
		}

		$helperNamespace = trim($helperNamespace, self::NS_SEPARATOR);
		$helperClass = self::NS_SEPARATOR . $helperNamespace . self::NS_SEPARATOR . $helperName . self::HELPER_PREFIX;

		return $helperClass;
	}

	/**
	 * @param string $namespace
	 * @return string
	 * @throws MissingNamespaceException
	 */
	private function getUrlNamespace($namespace) {

		if (!isset($this->namespaces[$namespace])) {
			$message = "No PHP namespace registered for the URL namespace: {$namespace}";
			throw new MissingNamespaceException($message);
		}

		$namespace = $this->namespaces[$namespace];

		return $namespace;
	}

	/**
	 * @param string $namespace
	 * @return string
	 */
	private function getUrnNamespace($namespace) {

		$namespace = str_replace(self::URN_IDENTIFIER, '', $namespace);
		$namespace = str_replace('.', self::NS_SEPARATOR, $namespace);

		return $namespace;
	}
}
