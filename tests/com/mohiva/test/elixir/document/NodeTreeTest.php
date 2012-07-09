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
use com\mohiva\elixir\document\NodeTree;

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
class NodeTreeTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test the `setVersion` and `getVersion` accessors.
	 */
	public function testVersionAccessors() {

		$tree = new NodeTree();

		$this->assertNull($tree->getVersion());

		$tree->setVersion('1.0');

		$this->assertSame('1.0', $tree->getVersion());
	}

	/**
	 * Test the `addChild` and `getChildren` accessors.
	 */
	public function testEncodingAccessors() {

		$tree = new NodeTree();

		$this->assertNull($tree->getEncoding());

		$tree->setEncoding('UTF-8');

		$this->assertSame('UTF-8', $tree->getEncoding());
	}

	/**
	 * Test the `addRoot` and `getRoot` accessors.
	 */
	public function testRootAccessors() {

		$node = new Node('root', 1, '<xml></xml>', array("var" => 1), 1, '/root');
		$tree = new NodeTree();

		$this->assertNull($tree->getRoot());

		$tree->setRoot($node);

		$this->assertSame($node, $tree->getRoot());
	}
}
