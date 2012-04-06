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
use com\mohiva\common\parser\TokenStream;
use com\mohiva\common\xml\XMLDocument;
use com\mohiva\pyramid\Parser as ExpressionParser;
use com\mohiva\elixir\document\Lexer as DocumentLexer;
use com\mohiva\elixir\document\Parser as DocumentParser;
use com\mohiva\elixir\document\Node;
use com\mohiva\elixir\document\expression\Lexer as ExpressionLexer;
use com\mohiva\elixir\document\expression\Grammar as ExpressionGrammar;

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
class ParserTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test if the `NodeTree` object contains the correct XML version.
	 */
	public function testTreeContainsXMLVersion() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/properties.xml');

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);

		$this->assertEquals('1.0', $tree->getVersion());
	}

	/**
	 * Test if the `NodeTree` object contains the correct XML encoding.
	 */
	public function testTreeContainsXMLEncoding() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/properties.xml');

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);

		$this->assertEquals('iso-8859-15', $tree->getEncoding());
	}

	/**
	 * Test if the `NodeTree` object contains the root node.
	 */
	public function testTreeContainsRootNode() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/root_node.xml');

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);

		$this->assertNotNull($tree->getRoot());
		$this->assertEquals(DocumentLexer::T_ROOT_NODE, $tree->getRoot()->getCode());
	}

	/**
	 * Test if the parser creates the correct node tree from token stream.
	 */
	public function testParserCreatesNodeTree() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/node_tree.xml');

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);

		$expectedPaths = array(
			'node:/root',
			'node:/root/test:Node',
			'node:/root/test:Node/test:Node',
			'node:/root/test:Node/test:Node[2]',
			'node:/root/test:Node/test:Node[2]/test:Node',
			'node:/root/test:Node/test:Node[2]/test:Node[2]',
			'node:/root/test:Node/test:Node[3]',
			'node:/root/test:Node[2]',
			'node:/root/test:Node[2]/test:Node'
		);
		$actualPaths = $this->getNodePaths($tree->getRoot());

		$this->assertEquals($expectedPaths, $actualPaths);
	}

	/**
	 * Test if the parser adds the helpers to its related nodes.
	 */
	public function testParserAddsHelpersToRelatedNodes() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/node_helpers.xml');

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);

		$expectedPaths = array(
			'node:/root',
			'node:/root/test:Node',
			'helper:/root/test:Node',
			'helper:/root/test:Node/@test:Locale',
			'node:/root/test:Node/test:Node',
			'helper:/root/test:Node/test:Node',
			'helper:/root/test:Node/test:Node/@test:Locale',
			'helper:/root/test:Node/test:Node/@test:Scope',
			'node:/root/test:Node/test:Node[2]',
			'helper:/root/test:Node/test:Node[2]',
			'node:/root/test:Node/test:Node[2]/test:Node',
			'helper:/root/test:Node/test:Node[2]/test:Node',
			'helper:/root/test:Node/test:Node[2]/test:Node/@test:Locale',
			'node:/root/test:Node/test:Node[2]/test:Node[2]',
			'helper:/root/test:Node/test:Node[2]/test:Node[2]',
			'helper:/root/test:Node/test:Node[2]/test:Node[2]/@test:Scope',
			'node:/root/test:Node/test:Node[3]',
			'helper:/root/test:Node/test:Node[3]',
			'helper:/root/test:Node/test:Node[3]/@test:Locale',
			'node:/root/test:Node[2]',
			'helper:/root/test:Node[2]',
			'helper:/root/test:Node[2]/@test:Scope',
			'node:/root/test:Node[2]/test:Node',
			'helper:/root/test:Node[2]/test:Node',
			'helper:/root/test:Node[2]/test:Node/@test:Locale'
		);
		$actualPaths = $this->getNodeAndHelperPaths($tree->getRoot());

		$this->assertEquals($expectedPaths, $actualPaths);
	}

	/**
	 * Test if the parser sets the correct node data.
	 */
	public function testParserSetsCorrectNodeData() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/node_data.xml');

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);
		/* @var \com\mohiva\elixir\document\tokens\NodeToken $token */
		$token = $stream->getLookahead(2);

		$parser = new DocumentParser(new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);
		$node = $tree->getRoot();

		$this->assertSame($token->getId(), $node->getId());
		$this->assertSame($token->getCode(), $node->getCode());
		$this->assertSame($token->getContent(), $node->getContent());
		$this->assertSame($token->getLine(), $node->getLine());
		$this->assertSame($token->getPath(), $node->getPath());
	}

	/**
	 * Test if the parser sets the correct node ancestor.
	 */
	public function testParserSetsCorrectNodeAncestor() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/default.xml');

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);
		$root = $tree->getRoot();
		$children = $root->getChildren();

		$this->assertSame($root, $children[0]->getAncestor());
	}

	/**
	 * Test if the parser sets the correct node children.
	 */
	public function testParserSetsCorrectNodeChildren() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/default.xml');

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);
		$root = $tree->getRoot();
		$children = $root->getChildren();

		$this->assertInstanceOf('com\mohiva\elixir\document\Node', $children[0]);
	}

	/**
	 * Test if the parser sets the correct node expression.
	 * TODO Implement test
	 */
	public function testParserSetsCorrectNodeExpression() {


	}

	/**
	 * Test if the parser sets the correct attribute helper expression.
	 * TODO Implement test
	 */
	public function testParserSetsCorrectAttributeHelperExpression() {


	}

	/**
	 * Test if the parser sets the correct element helper expression.
	 * TODO Implement test
	 */
	public function testParserSetsCorrectElementHelperExpression() {


	}

	/**
	 * Test if can load helpers from a registered URL namespace.
	 */
	public function testRegisterNamespaceWithLeadingAndTrailingSlashes() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/with_url_namespace.xml');

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionParser(new ExpressionGrammar));
		$parser->registerNamespace(
			'http://elixir.mohiva.com/test', '\com\mohiva\test\resources\elixir\document\helpers\\'
		);
		$tree = $parser->parse($stream);

		$this->assertNotNull($tree->getRoot());
	}

	/**
	 * Test if can load helpers from a registered URL namespace.
	 */
	public function testRegisterNamespaceWithoutLeadingAndTrailingSlashes() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/with_url_namespace.xml');

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionParser(new ExpressionGrammar));
		$parser->registerNamespace(
			'http://elixir.mohiva.com/test', 'com\mohiva\test\resources\elixir\document\helpers'
		);
		$tree = $parser->parse($stream);

		$this->assertNotNull($tree->getRoot());
	}

	/**
	 * Test if throws an exception if no PHP namespace is registered for an URL namespace.
	 *
	 * @expectedException \com\mohiva\elixir\document\exceptions\MissingNamespaceException
	 */
	public function testThrowsExceptionForNotRegisteredPHPNamespace() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/with_url_namespace.xml');

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionParser(new ExpressionGrammar));
		$parser->parse($stream);
	}

	/**
	 * Test if throws an exception if no URL or no URN is given for an XML namespace.
	 *
	 * @expectedException \com\mohiva\elixir\document\exceptions\InvalidNamespaceException
	 */
	public function testThrowsExceptionForInvalidNamespaceUsage() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/invalid_namespace.xml');

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionParser(new ExpressionGrammar));
		$parser->parse($stream);
	}

	/**
	 * Test if parse a document with valid namespaces without an error.
	 */
	public function testValidNamespaces() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/valid_namespaces.xml');

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionParser(new ExpressionGrammar));
		$parser->registerNamespace(
			'http://elixir.mohiva.com/test', 'com\mohiva\test\resources\elixir\document\helpers'
		);
		$parser->registerNamespace(
			'https://elixir.mohiva.com/test', 'com\mohiva\test\resources\elixir\document\helpers'
		);

		$this->assertInstanceOf('\com\mohiva\elixir\document\NodeTree', $parser->parse($stream));
	}

	/**
	 * Test if can load a class derived from `ElementHelper`.
	 */
	public function testLoadElementHelper() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/valid_element_helper.xml');

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);
		$children = $tree->getRoot()->getChildren();
		/* @var \com\mohiva\elixir\document\Node $node */
		$node = $children[0];
		$helpers = $node->getHelpers();

		$this->assertInstanceOf('\com\mohiva\elixir\document\helpers\ElementHelper', $helpers[0]);
	}

	/**
	 * Test if can load a class derived from `AttributeHelper`.
	 */
	public function testLoadAttributeHelper() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/valid_attribute_helper.xml');

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);
		$children = $tree->getRoot()->getChildren();
		/* @var \com\mohiva\elixir\document\Node $node */
		$node = $children[0];
		$helpers = $node->getHelpers();

		$this->assertInstanceOf('\com\mohiva\elixir\document\helpers\AttributeHelper', $helpers[0]);
	}

	/**
	 * Test if throws an `UnexpectedTokenException` exception if a unexpected token code was given.
	 *
	 * @expectedException \com\mohiva\elixir\document\exceptions\UnexpectedTokenException
	 */
	public function testThrowsInvalidTokenException() {

		/* @var \com\mohiva\common\parser\Token $token */
		$token = $this->getMock('\com\mohiva\common\parser\Token', array(), array(), '', false);
		$token->expects($this->any())
			->method('getCode')
			->will($this->returnValue(100));

		$stream = new TokenStream();
		$stream->push($token);

		$parser = new DocumentParser(new ExpressionParser(new ExpressionGrammar));
		$parser->parse($stream);
	}

	/**
	 * Test if throws an exception if a helper class isn't derived from `ElementHelper`.
	 *
	 * @expectedException \com\mohiva\elixir\document\exceptions\UnexpectedHelperTypeException
	 */
	public function testThrowsExceptionOnInvalidElementHelper() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/invalid_element_helper.xml');

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionParser(new ExpressionGrammar));
		$parser->parse($stream);
	}

	/**
	 * Test if throws an exception if a helper class isn't derived from `AttributeHelper`.
	 *
	 * @expectedException \com\mohiva\elixir\document\exceptions\UnexpectedHelperTypeException
	 */
	public function testThrowsExceptionOnInvalidAttributeHelper() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/invalid_attribute_helper.xml');

		$lexer = new DocumentLexer(new ExpressionLexer());
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionParser(new ExpressionGrammar));
		$parser->parse($stream);
	}

	/**
	 * Helper method which build an array with XML paths from node tree.
	 *
	 * @param \com\mohiva\elixir\document\Node $node The root of the node.
	 * @return array A list with XML paths.
	 */
	private function getNodePaths(Node $node) {

		$paths = array('node:' . $node->getPath());
		$children = $node->getChildren();
		foreach ($children as $childNode) {
			$paths = array_merge($paths, $this->getNodePaths($childNode));
		}

		return $paths;
	}

	/**
	 * Helper method which build an array with XML paths from node tree containing the node helpers.
	 *
	 * @param \com\mohiva\elixir\document\Node $node The root of the node.
	 * @return array A list with XML paths.
	 */
	private function getNodeAndHelperPaths(Node $node) {

		$paths = array('node:' . $node->getPath());
		$helpers = $node->getHelpers();
		foreach ($helpers as $helper) {
			/* @var \com\mohiva\elixir\document\Helper $helper */
			$paths[] = 'helper:' . $helper->getPath();
		}
		$children = $node->getChildren();
		foreach ($children as $childNode) {
			$paths = array_merge($paths, $this->getNodeAndHelperPaths($childNode));
		}

		return $paths;
	}
}
