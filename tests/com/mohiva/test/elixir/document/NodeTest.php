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
	 * Test all getters for the values set with the constructor.
	 */
	public function testConstructorAccessors() {

		$id = sha1(microtime(true));
		$code = mt_rand(1, 30);
		$content = sha1(microtime(true));
		$line = mt_rand(1, 100);
		$path = sha1(microtime(true));
		$node = new Node($id, $code, $content, $line, $path);

		$this->assertSame($id, $node->getId());
		$this->assertSame($code, $node->getCode());
		$this->assertSame($content, $node->getContent());
		$this->assertSame($line, $node->getLine());
		$this->assertSame($path, $node->getPath());
	}

	/**
	 * Test the `setAncestor` and `getAncestor` accessors.
	 */
	public function testAncestorAccessors() {

		$node = new Node('root', 1, '<xml></xml>', 1, '/root');
		$node->setAncestor($node);

		$this->assertSame($node, $node->getAncestor());
	}

	/**
	 * Test the `setPreviousSibling` and `getPreviousSibling` accessors.
	 */
	public function testPreviousSiblingAccessors() {

		$node = new Node('root', 1, '<xml></xml>', 1, '/root');
		$node->setPreviousSibling($node);

		$this->assertSame($node, $node->getPreviousSibling());
	}

	/**
	 * Test the `setNextSibling` and `getNextSibling` accessors.
	 */
	public function testNextSiblingAccessors() {

		$node = new Node('root', 1, '<xml></xml>', 1, '/root');
		$node->setNextSibling($node);

		$this->assertSame($node, $node->getNextSibling());
	}

	/**
	 * Test the `addChild` and `getChildren` accessors.
	 */
	public function testChildAccessors() {

		$node = new Node('root', 1, '<xml></xml>', 1, '/root');
		$node->addChild($node);

		$this->assertSame(array($node), $node->getChildren());
	}

	/**
	 * Test the `addHelper` and `getHelpers` accessors.
	 */
	public function testHelperAccessors() {

		/* @var \com\mohiva\elixir\document\Helper $helper */
		$helper = $this->getMock('\com\mohiva\elixir\document\Helper', array(), array(), '', false);
		$node = new Node('root', 1, '<xml></xml>', 1, '/root');
		$node->addHelper($helper);

		$this->assertSame(array($helper), $node->getHelpers());
	}

	/**
	 * Test the `addExpression` and `getExpressions` accessors.
	 */
	public function testExpressionAccessors() {

		/* @var \com\mohiva\elixir\document\Expression $expression */
		$expression = $this->getMock('\com\mohiva\elixir\document\Expression', array(), array(), '', false);
		$node = new Node('root', 1, '<xml></xml>', 1, '/root');
		$node->addExpressions(array($expression));

		$this->assertSame(array($expression), $node->getExpressions());
	}
}
