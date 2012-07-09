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
namespace com\mohiva\test\elixir\document\expression\nodes;

use com\mohiva\elixir\document\expression\nodes\OperandNode;
use com\mohiva\elixir\document\expression\nodes\BinaryGreaterNode;

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
class BinaryGreaterNodeTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test if the `evaluate` method returns the correct value for the operation.
	 */
	public function testEvaluate() {

		$left = mt_rand(1, 100);
		$right = mt_rand(1, 100);
		$node = new BinaryGreaterNode(new OperandNode($left), new OperandNode($right));

		$this->assertSame($left . ' > ' . $right, $node->evaluate());
	}
}
