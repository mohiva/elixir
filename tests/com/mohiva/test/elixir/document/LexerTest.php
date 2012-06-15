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
use com\mohiva\elixir\document\Lexer;
use com\mohiva\elixir\document\tokens\NodeToken;
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

		$lexer = new Lexer();
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

		$lexer = new Lexer();
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

		$lexer = new Lexer();
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

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		$this->assertEquals(Lexer::T_ROOT_NODE, $stream->getLookahead(2)->getCode());
	}

	/**
	 * Test if the root node token has no ancestor.
	 */
	public function testRootNodeTokenHasNoAncestor() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/token_root_node.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
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

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		$this->assertEquals(Lexer::T_ELEMENT_NODE, $stream->getLookahead(3)->getCode());
		$this->assertEquals(Lexer::T_ELEMENT_NODE, $stream->getLookahead(5)->getCode());
		$this->assertEquals(Lexer::T_ELEMENT_NODE, $stream->getLookahead(7)->getCode());
	}

	/**
	 * Test if a helper is a element helper.
	 */
	public function testElementHelperToken() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/token_element_helper.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\HelperToken $token */
		$token = $stream->getLookahead(3);

		$this->assertEquals(Lexer::T_ELEMENT_HELPER, $token->getCode());
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

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\HelperToken $token */
		$token = $stream->getLookahead(3);

		$this->assertEquals(Lexer::T_ATTRIBUTE_HELPER, $token->getCode());
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

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		$this->assertEquals(Lexer::T_EXPRESSION, $stream->getLookahead(3)->getCode());
	}

	/**
	 * Test if all expressions inside text nodes will be found.
	 */
	public function testTextNodeExpressions() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/text_node_expressions.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		// Must found two expressions in the content
		$this->assertEquals(Lexer::T_EXPRESSION, $stream->getLookahead(3)->getCode());
		$this->assertEquals(Lexer::T_EXPRESSION, $stream->getLookahead(4)->getCode());

		// Must found one expression inside the helper content(two times)
		$this->assertEquals(Lexer::T_EXPRESSION, $stream->getLookahead(6)->getCode());
		$this->assertEquals(Lexer::T_EXPRESSION, $stream->getLookahead(9)->getCode());
	}

	/**
	 * Test if all expressions inside attribute nodes will be found.
	 */
	public function testAttributeNodeExpression() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/attribute_node_expressions.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		// Must found two expressions in the content
		$this->assertEquals(Lexer::T_EXPRESSION, $stream->getLookahead(3)->getCode());
		$this->assertEquals(Lexer::T_EXPRESSION, $stream->getLookahead(4)->getCode());

		// Must found one expression inside the helper content(two times)
		$this->assertEquals(Lexer::T_EXPRESSION, $stream->getLookahead(7)->getCode());
		$this->assertEquals(Lexer::T_EXPRESSION, $stream->getLookahead(10)->getCode());
	}

	/**
	 * Test if all attribute expressions will be found if they are located on a node.
	 */
	public function testAttributeNodeExpressionsOnTheSameNode() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/attribute_node_expression_same_node.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		$this->assertEquals(Lexer::T_EXPRESSION, $stream->getLookahead(3)->getCode());
		$this->assertEquals(Lexer::T_EXPRESSION, $stream->getLookahead(5)->getCode());
		$this->assertEquals(Lexer::T_EXPRESSION, $stream->getLookahead(6)->getCode());
	}

	/**
	 * Test whether all elements belong to a non helper namespaces are not helpers.
	 */
	public function testNonHelperNamespaces() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/non_helper_namespaces.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
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

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\NodeToken $token */
		$token = $stream->getLookahead(3);

		$this->assertNotNull($token->getAncestor());
	}

	/**
	 * Test if the lexer finds the ancestors of a node.
	 */
	public function testFindAncestors() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/find_ancestors.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
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
	 * Test if the lexer finds the previous sibling of a node.
	 */
	public function testFindPreviousSibling() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/find_siblings.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\NodeToken $token */
		$token = $stream->getLookahead(3);
		$this->assertNull($token->getPreviousSibling());

		$token = $stream->getLookahead(5);
		$this->assertSame(sha1('/root/ex:If'), $token->getPreviousSibling());

		$token = $stream->getLookahead(7);
		$this->assertNull($token->getPreviousSibling());

		$token = $stream->getLookahead(9);
		$this->assertNull($token->getPreviousSibling());

		$token = $stream->getLookahead(11);
		$this->assertSame(sha1('/root/ex:ElseIf[2]'), $token->getPreviousSibling());
	}

	/**
	 * Test if the lexer finds the next sibling of a node.
	 */
	public function testFindNextSibling() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/find_siblings.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\NodeToken $token */
		$token = $stream->getLookahead(3);
		$this->assertSame(sha1('/root/ex:ElseIf'), $token->getNextSibling());

		$token = $stream->getLookahead(5);
		$this->assertNull($token->getNextSibling());

		$token = $stream->getLookahead(7);
		$this->assertSame(sha1('/root/ex:Else'), $token->getNextSibling());

		$token = $stream->getLookahead(9);
		$this->assertNull($token->getNextSibling());

		$token = $stream->getLookahead(11);
		$this->assertNull($token->getNextSibling());
	}

	/**
	 * Test if the lexer finds all children of a element.
	 */
	public function testFindChildren() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/find_children.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
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

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		$firstContent  = '<root>';
		$firstContent .= '<' . Lexer::PLACEHOLDER . ' id="6a5d3ea5544a23b536835030b4d503db8fda53fc"/>';
		$firstContent .= '<' . Lexer::PLACEHOLDER . ' id="ab3325e2d97bb917a40d19c6d468082cde28866c"/>';
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
	 * Test if the lexer preserves the xml comments in a document.
	 */
	public function testPreserveXMLComments() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/preserve_xml_comments.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		$found = 0;
		foreach($stream as $token) {
			if (!($token instanceof NodeToken)) {
				continue;
			}

			/* @var NodeToken $token */
			$content = $token->getContent();
			$found += substr_count($content, '<!--');
		}

		$this->assertEquals(3, $found);
	}

	/**
	 * Test if the lexer removes all elixir comments from document.
	 */
	public function testRemoveElixirComments() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/remove_elixir_comments.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		$found = 0;
		foreach($stream as $token) {
			if (!($token instanceof NodeToken)) {
				continue;
			}

			/* @var NodeToken $token */
			$content = $token->getContent();
			$found += substr_count($content, '<!--');
		}

		$this->assertEquals(0, $found);
	}

	/**
	 * Test if all helper namespaces will be removed from content.
	 */
	public function testRemoveNamespaces() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/remove_namespaces.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		foreach ($stream as $token) {
			/* @var \com\mohiva\elixir\document\tokens\NodeToken $token */
			if ($token->getCode() != Lexer::T_ELEMENT_NODE &&
				$token->getCode() != Lexer::T_ROOT_NODE) {

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

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		foreach ($stream as $token) {
			/* @var \com\mohiva\elixir\document\tokens\NodeToken $token */
			if ($token->getCode() != Lexer::T_ELEMENT_NODE &&
				$token->getCode() != Lexer::T_ROOT_NODE) {

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

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		foreach ($stream as $token) {
			/* @var \com\mohiva\elixir\document\tokens\NodeToken $token */
			if ($token->getCode() != Lexer::T_ELEMENT_NODE &&
				$token->getCode() != Lexer::T_ROOT_NODE) {

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

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		$this->assertInstanceOf('\com\mohiva\elixir\document\tokens\HelperToken', $stream->getLookahead(3));
		$this->assertInstanceOf('\com\mohiva\elixir\document\tokens\HelperToken', $stream->getLookahead(4));
		$this->assertInstanceOf('\com\mohiva\elixir\document\tokens\HelperToken', $stream->getLookahead(5));
	}

	/**
	 * Test if the lexer recognizes an expression in the root node if no namespace is used.
	 */
	public function testRootExpressionWithoutNamespace() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/root_expression_without_namespace.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		$this->assertInstanceOf('\com\mohiva\elixir\document\tokens\ExpressionToken', $stream->getLookahead(3));
	}

	/**
	 * Test if the lexer find the expression opener even if the closer is missing.
	 */
	public function testExpressionOpener() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/expression_opener.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\ExpressionToken $token */
		$token = $stream->getLookahead(3);
		$stream = $token->getStream();
		$token = $stream->getLookahead(0);

		$this->assertSame(Lexer::T_EXPRESSION_OPEN, $token->getCode());
	}

	/**
	 * Test if the lexer find the expression closer even if the opener is missing.
	 */
	public function testExpressionCloser() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/expression_closer.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\ExpressionToken $token */
		$token = $stream->getLookahead(3);
		$stream = $token->getStream();
		$token = $stream->getLookahead(0);

		$this->assertSame(Lexer::T_EXPRESSION_CLOSE, $token->getCode());
	}

	/**
	 * Test if the lexer recognizes that an expression is escaped and that the expressions are in the
	 * token stream of the first expression token.
	 */
	public function testExpressionEscapingInFirstToken() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/expression_escaping.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\ExpressionToken $token */
		$token = $stream->getLookahead(3);
		$this->assertInstanceOf('\com\mohiva\elixir\document\tokens\ExpressionToken', $token);

		$stream = $token->getStream();
		$actual = $this->buildActualTokens($stream);
		$expected = array(
			array(Lexer::T_EXPRESSION_CHARS => '\' test \''),
			array(Lexer::T_EXPRESSION_OPEN => '{%'),
			array(Lexer::T_EXPRESSION_CHARS => ' gum '),
			array(Lexer::T_EXPRESSION_CLOSE => '%}'),

			array(Lexer::T_EXPRESSION_CHARS => '\' test \''),

			array(Lexer::T_EXPRESSION_CHARS => '\'{% index %}\''),

			array(Lexer::T_EXPRESSION_CHARS => '\'test {% index %} test\''),

			array(Lexer::T_EXPRESSION_OPEN => '{%'),
			array(Lexer::T_EXPRESSION_CHARS => '\'{% index %}\''),
			array(Lexer::T_EXPRESSION_CLOSE => '%}'),

			array(Lexer::T_EXPRESSION_OPEN => '{%'),
			array(Lexer::T_EXPRESSION_CHARS => '\'test  test\''),
			array(Lexer::T_EXPRESSION_CLOSE => '%}')
		);

		$this->assertSame($expected, $actual);
	}

	/**
	 * Test if the lexer recognizes that an expression is escaped and that the expressions are in the
	 * token stream of the second expression token.
	 */
	public function testExpressionEscapingInSecondToken() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/expression_escaping.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\ExpressionToken $token */
		$token = $stream->getLookahead(4);
		$this->assertInstanceOf('\com\mohiva\elixir\document\tokens\ExpressionToken', $token);

		$stream = $token->getStream();
		$actual = $this->buildActualTokens($stream);
		$expected = array(
			array(Lexer::T_EXPRESSION_OPEN => '{%'),
			array(Lexer::T_EXPRESSION_CHARS => '\'test  test\''),
			array(Lexer::T_EXPRESSION_CLOSE => '%}')
		);

		$this->assertSame($expected, $actual);
	}

	/**
	 * Test if the lexer recognizes that an XML tag inside an expression is escaped.
	 */
	public function testExpressionEscapingWithInnerXML() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/expression_escaping_with_inner_xml.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\ExpressionToken $token */
		$token = $stream->getLookahead(3);
		$this->assertInstanceOf('\com\mohiva\elixir\document\tokens\ExpressionToken', $token);

		$stream = $token->getStream();
		$actual = $this->buildActualTokens($stream);
		$expected = array(
			array(Lexer::T_EXPRESSION_OPEN => '{%'),

			array(Lexer::T_EXPRESSION_OPEN => '{%'),
			array(Lexer::T_EXPRESSION_CHARS => '\'<tag xmlns:test="urn:test" test:Locale="tag1"/>\''),
			array(Lexer::T_EXPRESSION_CLOSE => '%}'),

			array(Lexer::T_EXPRESSION_CLOSE => '%}'),

			array(Lexer::T_EXPRESSION_OPEN => '{%'),
			array(Lexer::T_EXPRESSION_CHARS => ' var '),
			array(Lexer::T_EXPRESSION_CLOSE => '%}'),

			array(Lexer::T_EXPRESSION_OPEN => '{%'),
			array(Lexer::T_EXPRESSION_CHARS => ' var '),
			array(Lexer::T_EXPRESSION_CLOSE => '%}')
		);

		$this->assertSame($expected, $actual);
	}

	/**
	 * Test if the lexer recognizes a greedy expression.
	 */
	public function testGreedyExpression() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/greedy_expression.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\ExpressionToken $token */
		$token = $stream->getLookahead(3);
		$this->assertInstanceOf('\com\mohiva\elixir\document\tokens\ExpressionToken', $token);

		$stream = $token->getStream();
		$actual = $this->buildActualTokens($stream);
		$expected = array(
			array(Lexer::T_EXPRESSION_OPEN => '{%'),
			array(Lexer::T_EXPRESSION_CHARS => ' index '),
			array(Lexer::T_EXPRESSION_CLOSE => '%}'),

			array(Lexer::T_EXPRESSION_OPEN => '{%'),
			array(Lexer::T_EXPRESSION_CHARS => ' index '),
			array(Lexer::T_EXPRESSION_CLOSE => '%}')
		);

		$this->assertSame($expected, $actual);
	}

	/**
	 * Test if the lexer recognizes a multiline expression.
	 */
	public function testMultilineExpression() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/multiline_expression.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\ExpressionToken $token */
		$token = $stream->getLookahead(3);
		$this->assertInstanceOf('\com\mohiva\elixir\document\tokens\ExpressionToken', $token);

		$stream = $token->getStream();
		$actual = $this->buildActualTokens($stream);
		$expected = array(
			array(Lexer::T_EXPRESSION_OPEN => '{%'),
			array(Lexer::T_EXPRESSION_CHARS => "\n\t\tindex\n\t"),
			array(Lexer::T_EXPRESSION_CLOSE => '%}')
		);

		$this->assertSame($expected, $actual);
	}

	/**
	 * Test if the lexer recognizes all the content inside the raw helper as raw content if the helper
	 * is on the root node of the document.
	 */
	public function testRawHelperAsRoot() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/raw_helper_as_root.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		$this->assertSame(4, $stream->count());
	}

	/**
	 * Test if the lexer recognizes all the content inside the raw helper as raw content if the helper
	 * is child node of the document.
	 */
	public function testRawHelperAsChild() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/raw_helper_as_child.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		$this->assertSame(5, $stream->count());
	}

	/**
	 * Test if the lexer does not remove the helper namespaces on nodes inside the raw helper.
	 */
	public function testRawHelperKeepsNamespace() {

		$xmlFile = Bootstrap::$resourceDir . '/elixir/document/lexer/raw_helper_keep_namespaces.xml';

		$doc = new XMLDocument();
		$doc->load($xmlFile);

		$lexer = new Lexer();
		$stream = $lexer->scan($doc);

		/* @var \com\mohiva\elixir\document\tokens\NodeToken $token */
		$token = $stream->getLookahead(2);
		$content = $token->getContent();

		$this->assertContains('xmlns:ex="http://elixir.mohiva.com"', $content);
	}

	/**
	 * Create an array from the token stream which contains only the tokens and the operators/values.
	 *
	 * @param \com\mohiva\common\parser\TokenStream $stream The stream containing the lexer tokens.
	 * @return array The actual list with tokens and operators/values.
	 */
	private function buildActualTokens(TokenStream $stream) {

		$actual = array();
		while ($stream->valid()) {
			/* @var \com\mohiva\pyramid\Token $current */
			$current = $stream->current();
			$stream->next();
			$actual[] = array($current->getCode() => $current->getValue());
		}

		return $actual;
	}
}
