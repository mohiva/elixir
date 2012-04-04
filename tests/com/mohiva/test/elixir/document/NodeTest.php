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

use com\mohiva\elixir\document\Node;

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
class NodeTest extends \PHPUnit_Framework_TestCase {

	/**
	 * The node object to test.
	 *
	 * @var \com\mohiva\elixir\document\Node
	 */
	private $node;

	/**
	 * Setup the test case.
	 */
	public function setUp() {

		$this->node = new Node('root', 1, '<xml></xml>', 1, '/root');
	}

	/**
	 * Test if the `getId` method returns the id of the node.
	 */
	public function testGetId() {

		$this->assertSame('root', $this->node->getId());
	}

	/**
	 * Test if the `getCode` method returns the token code of the node.
	 */
	public function testGetCode() {

		$this->assertSame(1, $this->node->getCode());
	}

	/**
	 * Test if the `getContent` method returns the content of the node.
	 */
	public function testGetContent() {

		$this->assertSame('<xml></xml>', $this->node->getContent());
	}

	/**
	 * Test if the `getLine` method returns the line number of the node.
	 */
	public function testGetLine() {

		$this->assertSame(1, $this->node->getLine());
	}

	/**
	 * Test if the `getPath` method returns the path of the node.
	 */
	public function testGetPath() {

		$this->assertSame('/root', $this->node->getPath());
	}

	/**
	 * Test the `setAncestor` and `getAncestor` accessors.
	 */
	public function testAncestorAccessors() {

		$this->node->setAncestor($this->node);

		$this->assertSame($this->node, $this->node->getAncestor());
	}

	/**
	 * Test the `addChild` and `getChildren` accessors.
	 */
	public function testChildAccessors() {

		$this->node->addChild($this->node);

		$this->assertSame(array($this->node), $this->node->getChildren());
	}

	/**
	 * Test the `addHelper` and `getHelpers` accessors.
	 */
	public function testHelperAccessors() {

		/* @var \com\mohiva\elixir\document\Helper $stub */
		$stub = $this->getMock('\com\mohiva\elixir\document\Helper', array(), array(), '', false);
		$this->node->addHelper($stub);

		$this->assertSame(array($stub), $this->node->getHelpers());
	}

	/**
	 * Test the `addExpression` and `getExpressions` accessors.
	 */
	public function testExpressionAccessors() {

		/* @var \com\mohiva\pyramid\Node $stub */
		$stub = $this->getMock('\com\mohiva\pyramid\Node', array(), array(), '', false);
		$this->node->addExpression($stub);

		$this->assertSame(array($stub), $this->node->getExpressions());
	}
}
