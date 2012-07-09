<?php
/**
 * Mohiva Elixir
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.textile.
 * It is also available through the world-wide-web at this URL:
 * https://github.com/mohiva/elixir/blob/master/LICENSE.textile
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
use com\mohiva\common\exceptions\SyntaxErrorException;
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

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);

		$this->assertEquals('1.0', $tree->getVersion());
	}

	/**
	 * Test if the `NodeTree` object contains the correct XML encoding.
	 */
	public function testTreeContainsXMLEncoding() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/properties.xml');

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);

		$this->assertEquals('iso-8859-15', $tree->getEncoding());
	}

	/**
	 * Test if the `NodeTree` object contains the root node.
	 */
	public function testTreeContainsRootNode() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/root_node.xml');

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
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

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
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

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
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

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);
		/* @var \com\mohiva\elixir\document\tokens\NodeToken $token */
		$token = $stream->getLookahead(2);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);
		$node = $tree->getRoot();

		$this->assertSame($token->getId(), $node->getId());
		$this->assertSame($token->getCode(), $node->getCode());
		$this->assertSame($token->getContent(), $node->getContent());
		$this->assertSame($token->getLine(), $node->getLine());
		$this->assertSame($token->getPath(), $node->getPath());
	}

	/**
	 * Test if the parser sets the correct ancestor node.
	 */
	public function testParserSetsCorrectAncestorNode() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/default.xml');

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);
		$root = $tree->getRoot();
		$children = $root->getChildren();

		$this->assertSame($root, $children[0]->getAncestor());
	}

	/**
	 * Test if the parser sets the correct previous node.
	 */
	public function testParserSetsCorrectPreviousNode() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/node_siblings.xml');

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);

		$root = $tree->getRoot();
		$children = $root->getChildren();

		$this->assertNull($children[0]->getPreviousSibling());
		$this->assertSame('/root/ex:If', $children[1]->getPreviousSibling()->getPath());
		$this->assertNull($children[2]->getPreviousSibling());
		$this->assertSame('/root/ex:ElseIf[2]', $children[3]->getPreviousSibling()->getPath());
	}

	/**
	 * Test if the parser sets the correct next node.
	 */
	public function testParserSetsCorrectNextNode() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/node_siblings.xml');

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);

		$root = $tree->getRoot();
		$children = $root->getChildren();

		$this->assertSame('/root/ex:ElseIf', $children[0]->getNextSibling()->getPath());
		$this->assertNull($children[1]->getNextSibling());
		$this->assertSame('/root/ex:Else', $children[2]->getNextSibling()->getPath());
		$this->assertNull($children[3]->getNextSibling());
	}

	/**
	 * Test if the parser sets the correct children node.
	 */
	public function testParserSetsCorrectChildrenNode() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/default.xml');

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);
		$root = $tree->getRoot();
		$children = $root->getChildren();

		$this->assertInstanceOf('com\mohiva\elixir\document\Node', $children[0]);
	}

	/**
	 * Test if the parser sets the correct root node expression.
	 */
	public function testParserSetsCorrectRootNodeExpression() {

		/* @var \PHPUnit_Framework_MockObject_MockObject $expressionLexer */
		/* @var \com\mohiva\elixir\document\expression\Lexer $expressionLexer */
		$expressionLexer = $this->getMock('\com\mohiva\elixir\document\expression\Lexer', array(), array(), '', false);
		$expressionLexer->expects($this->any())
			->method('scan')
			->will($this->returnValue(new TokenStream()));

		/* @var \PHPUnit_Framework_MockObject_MockObject $expressionParser */
		/* @var \com\mohiva\pyramid\Parser $expressionParser */
		$expressionParser = $this->getMock('\com\mohiva\pyramid\Parser', array(), array(), '', false);
		$expressionParser->expects($this->any())
			->method('parse')
			->will($this->returnValue($this->getMock('\com\mohiva\pyramid\Node')));

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/root_node_expression.xml');

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser($expressionLexer, $expressionParser);
		$nodeTree = $parser->parse($stream);

		$this->assertCount(2, $nodeTree->getRoot()->getExpressions());
	}

	/**
	 * Test if the parser sets the correct element node expression.
	 */
	public function testParserSetsCorrectElementNodeExpression() {

		/* @var \PHPUnit_Framework_MockObject_MockObject $expressionLexer */
		/* @var \com\mohiva\elixir\document\expression\Lexer $expressionLexer */
		$expressionLexer = $this->getMock('\com\mohiva\elixir\document\expression\Lexer', array(), array(), '', false);
		$expressionLexer->expects($this->any())
			->method('scan')
			->will($this->returnValue(new TokenStream()));

		/* @var \PHPUnit_Framework_MockObject_MockObject $expressionParser */
		/* @var \com\mohiva\pyramid\Parser $expressionParser */
		$expressionParser = $this->getMock('\com\mohiva\pyramid\Parser', array(), array(), '', false);
		$expressionParser->expects($this->any())
			->method('parse')
			->will($this->returnValue($this->getMock('\com\mohiva\pyramid\Node')));

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/element_node_expression.xml');

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser($expressionLexer, $expressionParser);
		$nodeTree = $parser->parse($stream);
		$children = $nodeTree->getRoot()->getChildren();

		$this->assertCount(2, $children[0]->getExpressions());
	}

	/**
	 * Test if the parser sets the correct attribute helper expression.
	 */
	public function testParserSetsCorrectAttributeHelperExpression() {

		/* @var \PHPUnit_Framework_MockObject_MockObject $expressionLexer */
		/* @var \com\mohiva\elixir\document\expression\Lexer $expressionLexer */
		$expressionLexer = $this->getMock('\com\mohiva\elixir\document\expression\Lexer', array(), array(), '', false);
		$expressionLexer->expects($this->any())
			->method('scan')
			->will($this->returnValue(new TokenStream()));

		/* @var \PHPUnit_Framework_MockObject_MockObject $expressionParser */
		/* @var \com\mohiva\pyramid\Parser $expressionParser */
		$expressionParser = $this->getMock('\com\mohiva\pyramid\Parser', array(), array(), '', false);
		$expressionParser->expects($this->any())
			->method('parse')
			->will($this->returnValue($this->getMock('\com\mohiva\pyramid\Node')));

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/attribute_helper_expression.xml');

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser($expressionLexer, $expressionParser);
		$nodeTree = $parser->parse($stream);
		$helpers = $nodeTree->getRoot()->getChildren()[0]->getHelpers();

		$this->assertCount(1, $helpers[0]->getExpressions());
	}

	/**
	 * Test if the parser sets the correct element helper expression.
	 */
	public function testParserSetsCorrectElementHelperExpression() {

		/* @var \PHPUnit_Framework_MockObject_MockObject $expressionLexer */
		/* @var \com\mohiva\elixir\document\expression\Lexer $expressionLexer */
		$expressionLexer = $this->getMock('\com\mohiva\elixir\document\expression\Lexer', array(), array(), '', false);
		$expressionLexer->expects($this->any())
			->method('scan')
			->will($this->returnValue(new TokenStream()));

		/* @var \PHPUnit_Framework_MockObject_MockObject $expressionParser */
		/* @var \com\mohiva\pyramid\Parser $expressionParser */
		$expressionParser = $this->getMock('\com\mohiva\pyramid\Parser', array(), array(), '', false);
		$expressionParser->expects($this->any())
			->method('parse')
			->will($this->returnValue($this->getMock('\com\mohiva\pyramid\Node')));

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/element_helper_expression.xml');

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser($expressionLexer, $expressionParser);
		$nodeTree = $parser->parse($stream);
		$helpers = $nodeTree->getRoot()->getChildren()[0]->getHelpers();

		$this->assertCount(1, $helpers[0]->getExpressions());
	}

	/**
	 * Test if the parser throws an exception if the expression opener was expected but an other token was found.
	 */
	public function testThrowsExceptionIfExpressionOpenerWasExpectedButOtherTokenWasFound() {

		/* @var \PHPUnit_Framework_MockObject_MockObject $expressionLexer */
		/* @var \com\mohiva\elixir\document\expression\Lexer $expressionLexer */
		$expressionLexer = $this->getMock('\com\mohiva\elixir\document\expression\Lexer', array(), array(), '', false);
		$expressionLexer->expects($this->any())
			->method('scan')
			->will($this->returnValue(new TokenStream()));

		/* @var \PHPUnit_Framework_MockObject_MockObject $expressionParser */
		/* @var \com\mohiva\pyramid\Parser $expressionParser */
		$expressionParser = $this->getMock('\com\mohiva\pyramid\Parser', array(), array(), '', false);
		$expressionParser->expects($this->any())
			->method('parse')
			->will($this->returnValue($this->getMock('\com\mohiva\pyramid\Node')));

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/missing_expression_opener_1.xml');

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser($expressionLexer, $expressionParser);
		try {
			$parser->parse($stream);
			$this->fail('SyntaxErrorException expected');
		} catch (SyntaxErrorException $e) {
			$this->assertContains('got `%}`', $e->getMessage());
		}
	}

	/**
	 * Test if the parser throws an exception if the expression closer was expected but an other token was found.
	 */
	public function testThrowsExceptionIfExpressionCloserWasExpectedButOtherTokenWasFound() {

		/* @var \PHPUnit_Framework_MockObject_MockObject $expressionLexer */
		/* @var \com\mohiva\elixir\document\expression\Lexer $expressionLexer */
		$expressionLexer = $this->getMock('\com\mohiva\elixir\document\expression\Lexer', array(), array(), '', false);
		$expressionLexer->expects($this->any())
			->method('scan')
			->will($this->returnValue(new TokenStream()));

		/* @var \PHPUnit_Framework_MockObject_MockObject $expressionParser */
		/* @var \com\mohiva\pyramid\Parser $expressionParser */
		$expressionParser = $this->getMock('\com\mohiva\pyramid\Parser', array(), array(), '', false);
		$expressionParser->expects($this->any())
			->method('parse')
			->will($this->returnValue($this->getMock('\com\mohiva\pyramid\Node')));

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/missing_expression_closer_1.xml');

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser($expressionLexer, $expressionParser);
		try {
			$parser->parse($stream);
			$this->fail('SyntaxErrorException expected');
		} catch (SyntaxErrorException $e) {
			$this->assertContains('got `{%`', $e->getMessage());
		}
	}

	/**
	 * Test if the parser throws an exception if the expression closer was expected but an other token was found.
	 */
	public function testThrowsExceptionIfExpressionCloserWasExpectedButEndOfStreamWasReached() {

		/* @var \PHPUnit_Framework_MockObject_MockObject $expressionLexer */
		/* @var \com\mohiva\elixir\document\expression\Lexer $expressionLexer */
		$expressionLexer = $this->getMock('\com\mohiva\elixir\document\expression\Lexer', array(), array(), '', false);
		$expressionLexer->expects($this->any())
			->method('scan')
			->will($this->returnValue(new TokenStream()));

		/* @var \PHPUnit_Framework_MockObject_MockObject $expressionParser */
		/* @var \com\mohiva\pyramid\Parser $expressionParser */
		$expressionParser = $this->getMock('\com\mohiva\pyramid\Parser', array(), array(), '', false);
		$expressionParser->expects($this->any())
			->method('parse')
			->will($this->returnValue($this->getMock('\com\mohiva\pyramid\Node')));

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/missing_expression_closer_2.xml');

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser($expressionLexer, $expressionParser);
		try {
			$parser->parse($stream);
			$this->fail('SyntaxErrorException expected');
		} catch (SyntaxErrorException $e) {
			$this->assertContains('but end of stream reached', $e->getMessage());
		}
	}

	/**
	 * Test if the parser can assign correct expressions if multiple tokens are given for one node.
	 */
	public function testMultipleExpressionTokens() {

		/* @var \PHPUnit_Framework_MockObject_MockObject $expressionLexer */
		/* @var \com\mohiva\elixir\document\expression\Lexer $expressionLexer */
		$expressionLexer = $this->getMock('\com\mohiva\elixir\document\expression\Lexer', array(), array(), '', false);
		$expressionLexer->expects($this->any())
			->method('scan')
			->will($this->returnValue(new TokenStream()));

		/* @var \PHPUnit_Framework_MockObject_MockObject $expressionParser */
		/* @var \com\mohiva\pyramid\Parser $expressionParser */
		$expressionParser = $this->getMock('\com\mohiva\pyramid\Parser', array(), array(), '', false);
		$expressionParser->expects($this->any())
			->method('parse')
			->will($this->returnValue($this->getMock('\com\mohiva\pyramid\Node')));

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/multiple_expression_tokens.xml');

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser($expressionLexer, $expressionParser);
		$tree = $parser->parse($stream);

		$this->assertSame(4, count($tree->getRoot()->getExpressions()));
	}

	/**
	 * Test if can load helpers from a registered URL namespace.
	 */
	public function testRegisterNamespaceWithLeadingAndTrailingSlashes() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/with_url_namespace.xml');

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
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

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
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

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
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

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
		$parser->parse($stream);
	}

	/**
	 * Test if parse a document with valid namespaces without an error.
	 */
	public function testValidNamespaces() {

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/parser/valid_namespaces.xml');

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
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

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);
		$children = $tree->getRoot()->getChildren();
		/* @var Node $node */
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

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);
		$children = $tree->getRoot()->getChildren();
		/* @var Node $node */
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

		/* @var \PHPUnit_Framework_MockObject_MockObject $token */
		/* @var \com\mohiva\common\parser\Token $token */
		$token = $this->getMock('\com\mohiva\common\parser\Token', array(), array(), '', false);
		$token->expects($this->any())
			->method('getCode')
			->will($this->returnValue(100));

		$stream = new TokenStream();
		$stream->push($token);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
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

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
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

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
		$parser->parse($stream);
	}

	/**
	 * Helper method which build an array with XML paths from node tree.
	 *
	 * @param Node $node The root of the node.
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
	 * @param Node $node The root of the node.
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
