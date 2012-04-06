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
 * @package   Mohiva/Elixir/Test
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\test\elixir\document;

use com\mohiva\test\elixir\Bootstrap;
use com\mohiva\elixir\document\Lexer as DocumentLexer;
use com\mohiva\elixir\document\expression\Lexer as ExpressionLexer;
use com\mohiva\common\parser\TokenStream;
use com\mohiva\common\xml\XMLDocument;

/**
 * Unit test case for the Mohiva Elixir project.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Test
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class LexerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test a file with a defined XML version.
	 */
	public function testXMLVersion() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/encoding_and_version.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\PropertyToken $token */
		$token = $stream->current();

		$this->assertEquals('1.0', $token->getValue());
	}

	/**
	 * Test a file with a defined XML encoding.
	 */
	public function testXMLEncoding() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/encoding_and_version.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\PropertyToken $token */
		$token = $stream->getLookahead();

		$this->assertEquals('iso-8859-15', $token->getValue());
	}

	/**
	 * Test a file with a default namespace. The lexer mustn't detect a helper here,
	 * this is a special case because every element has a namespace.
	 */
	public function testDefaultHTMLNamespace() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/default_html_namespace.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$this->assertNull($stream->getLookahead(3));
	}

	/**
	 * Test if the third token is the root node token.
	 */
	public function testRootNodeToken() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/token_root_node.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$this->assertEquals(DocumentLexer::T_ROOT_NODE, $stream->getLookahead(2)->getCode());
	}

	/**
	 * Test if the root node token has no ancestor.
	 */
	public function testRootNodeTokenHasNoAncestor() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/token_root_node.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\NodeToken $token */
		$token = $stream->getLookahead(2);

		$this->assertNull($token->getAncestor());
	}

	/**
	 * Test if the second, third and fourth tokens are element node tokens.
	 */
	public function testElementNodeToken() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/token_element_node.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$this->assertEquals(DocumentLexer::T_ELEMENT_NODE, $stream->getLookahead(3)->getCode());
		$this->assertEquals(DocumentLexer::T_ELEMENT_NODE, $stream->getLookahead(5)->getCode());
		$this->assertEquals(DocumentLexer::T_ELEMENT_NODE, $stream->getLookahead(7)->getCode());
	}

	/**
	 * Test if a helper is a element helper.
	 */
	public function testElementHelperToken() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/token_element_helper.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\HelperToken $token */
		$token = $stream->getLookahead(3);

		$this->assertEquals(DocumentLexer::T_ELEMENT_HELPER, $token->getCode());
		$this->assertEquals(sha1($token->getPath()), $token->getId());
		$this->assertEquals('/test:ElementHelper', $token->getPath());
		$this->assertEquals('2', $token->getLine());
		$this->assertEquals('ElementHelper', $token->getName());
		$this->assertEquals('urn:test', $token->getNamespace());
		$this->assertEquals(array(), $token->getAttributes());
	}

	/**
	 * Test if a helper is an attribute helper.
	 */
	public function testAttributeHelperToken() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/token_attribute_helper.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\HelperToken $token */
		$token = $stream->getLookahead(3);

		$this->assertEquals(DocumentLexer::T_ATTRIBUTE_HELPER, $token->getCode());
		$this->assertEquals(sha1($token->getPath()), $token->getId());
		$this->assertEquals('/root/@test:AttributeHelper', $token->getPath());
		$this->assertEquals('2', $token->getLine());
		$this->assertEquals('AttributeHelper', $token->getName());
		$this->assertEquals('urn:test', $token->getNamespace());
		$this->assertEquals('value', $token->getValue());
	}

	/**
	 * Test if a placeholder is an expression.
	 */
	public function testExpressionToken() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/token_expression.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$this->assertEquals(DocumentLexer::T_EXPRESSION, $stream->getLookahead(3)->getCode());
	}

	/**
	 * Test if all expressions inside text nodes will be found.
	 */
	public function testTextNodeExpressions() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/text_node_expressions.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		// Must found two expressions in the content
		$this->assertEquals(DocumentLexer::T_EXPRESSION, $stream->getLookahead(3)->getCode());
		$this->assertEquals(DocumentLexer::T_EXPRESSION, $stream->getLookahead(4)->getCode());

		// Must found one expression inside the helper content(two times)
		$this->assertEquals(DocumentLexer::T_EXPRESSION, $stream->getLookahead(6)->getCode());
		$this->assertEquals(DocumentLexer::T_EXPRESSION, $stream->getLookahead(9)->getCode());
	}

	/**
	 * Test if all expressions inside attribute nodes will be found.
	 */
	public function testAttributeNodeExpression() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/attribute_node_expressions.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		// Must found two expressions in the content
		$this->assertEquals(DocumentLexer::T_EXPRESSION, $stream->getLookahead(3)->getCode());
		$this->assertEquals(DocumentLexer::T_EXPRESSION, $stream->getLookahead(4)->getCode());

		// Must found one expression inside the helper content(two times)
		$this->assertEquals(DocumentLexer::T_EXPRESSION, $stream->getLookahead(7)->getCode());
		$this->assertEquals(DocumentLexer::T_EXPRESSION, $stream->getLookahead(10)->getCode());
	}

	/**
	 * Test if all attribute expressions will be found if they are located on a node.
	 */
	public function testAttributeNodeExpressionsOnTheSameNode() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/attribute_node_expression_same_node.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$this->assertEquals(DocumentLexer::T_EXPRESSION, $stream->getLookahead(3)->getCode());
		$this->assertEquals(DocumentLexer::T_EXPRESSION, $stream->getLookahead(5)->getCode());
		$this->assertEquals(DocumentLexer::T_EXPRESSION, $stream->getLookahead(6)->getCode());
	}

	/**
	 * Test whether all elements belong to a non helper namespaces are not helpers.
	 */
	public function testNonHelperNamespaces() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/non_helper_namespaces.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$this->assertNull($stream->getLookahead(3));
	}

	/**
	 * Test if a root node with no namespace is a ancestor.
	 */
	public function testIfRootWithNoNamespaceIsAncestor() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/ancestor_without_namespace.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\NodeToken $token */
		$token = $stream->getLookahead(3);

		$this->assertNotNull($token->getAncestor());
	}

	/**
	 * Test if the lexer finds the ancestors of a element.
	 */
	public function testFindAncestors() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/find_ancestors.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\NodeToken $token */
		$token = $stream->getLookahead(2);
		$this->assertNull($token->getAncestor());

		$token = $stream->getLookahead(4);
		$this->assertNotNull($token->getAncestor());

		$token = $stream->getLookahead(6);
		$this->assertNotNull($token->getAncestor());

		$token = $stream->getLookahead(8);
		$this->assertNotNull($token->getAncestor());
	}

	/**
	 * Test if the lexer finds all children of a element.
	 */
	public function testFindChildren() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/find_children.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\NodeToken $token */
		$token = $stream->getLookahead(2);
		$this->assertEquals(count($token->getChildren()), 4);

		$token = $stream->getLookahead(3);
		$this->assertEquals(count($token->getChildren()), 0);

		$token = $stream->getLookahead(5);
		$this->assertEquals(count($token->getChildren()), 0);

		$token = $stream->getLookahead(7);
		$this->assertEquals(count($token->getChildren()), 1);

		$token = $stream->getLookahead(9);
		$this->assertEquals(count($token->getChildren()), 0);

		$token = $stream->getLookahead(11);
		$this->assertEquals(count($token->getChildren()), 0);
	}

	/**
	 * Test if the lexer finds the content of a node.
	 */
	public function testFindContent() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/find_content.xml';

		$doc = new XMLDocument();
		$doc->preserveWhiteSpace = false;
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$firstContent  = '<root>';
		$firstContent .= '<__node__>6a5d3ea5544a23b536835030b4d503db8fda53fc</__node__>';
		$firstContent .= '<__node__>ab3325e2d97bb917a40d19c6d468082cde28866c</__node__>';
		$firstContent .= '<div>The root content.</div>';
		$firstContent .= '</root>';

		/* @var \com\mohiva\elixir\document\tokens\NodeToken $token */
		$token = $stream->getLookahead(2);
		$this->assertEquals($token->getContent(), $firstContent);

		$token = $stream->getLookahead(3);
		$this->assertEquals($token->getContent(), '<node><div>The node content.</div></node>');

		$token = $stream->getLookahead(5);
		$this->assertEquals($token->getContent(), '<div>The node content.</div>');
	}

	/**
	 * Test if the lexer preserves the comments in a document.
	 */
	public function testPreserveComments() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/preserve_comments.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\NodeToken $token */
		$token = $stream->getLookahead(3);

		// We add the root tag, because the resulting XML isn't valid without it.
		$expected = '<root><div/><!-- <test:Helper /> --><div/></root>';
		$actual = '<root>' . $token->getContent() . '</root>';

		$this->assertXmlStringEqualsXmlString($expected, $actual);
	}

	/**
	 * Test if all helper namespaces will be removed from content.
	 */
	public function testRemoveNamespaces() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/remove_namespaces.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		foreach ($stream as $token) {
			/* @var \com\mohiva\elixir\document\tokens\NodeToken $token */
			if ($token->getCode() != DocumentLexer::T_ELEMENT_NODE &&
				$token->getCode() != DocumentLexer::T_ROOT_NODE) {

				continue;
			}

			$this->assertNotRegExp('/xmlns:test[0-9]="urn:test[0-9]"/', $token->getContent());
		}
	}

	/**
	 * Test if all element helpers tags are removed from content.
	 */
	public function testRemoveElementHelpers() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/remove_element_helpers.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		foreach ($stream as $token) {
			/* @var \com\mohiva\elixir\document\tokens\NodeToken $token */
			if ($token->getCode() != DocumentLexer::T_ELEMENT_NODE &&
				$token->getCode() != DocumentLexer::T_ROOT_NODE) {

				continue;
			}

			$this->assertNotRegExp('/test:Helper[0-9]/', $token->getContent());
		}
	}

	/**
	 * Test if all attribute helpers are removed from content.
	 */
	public function testRemoveAttributeHelpers() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/remove_attribute_helpers.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		foreach ($stream as $token) {
			/* @var \com\mohiva\elixir\document\tokens\NodeToken $token */
			if ($token->getCode() != DocumentLexer::T_ELEMENT_NODE &&
				$token->getCode() != DocumentLexer::T_ROOT_NODE) {

				continue;
			}

			$this->assertNotRegExp('/test:Helper[0-9]/', $token->getContent());
		}
	}

	/**
	 * Test if multiple helpers on a single tag are correctly recognized.
	 */
	public function testMultipleHelpers() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/multiple_helpers.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$this->assertInstanceOf('\com\mohiva\elixir\document\tokens\HelperToken', $stream->getLookahead(3));
		$this->assertInstanceOf('\com\mohiva\elixir\document\tokens\HelperToken', $stream->getLookahead(4));
		$this->assertInstanceOf('\com\mohiva\elixir\document\tokens\HelperToken', $stream->getLookahead(5));
	}
}
