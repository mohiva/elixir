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
use com\mohiva\elixir\document\expression\nodes\TernaryIfNode;

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
class TernaryIfNodeTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test if the `evaluate` method returns the correct value for the operation.
	 */
	public function testEvaluate() {

		$condition = mt_rand(1, 100);
		$if = mt_rand(1, 100);
		$else = mt_rand(1, 100);
		$node = new TernaryIfNode(new OperandNode($condition), new OperandNode($if), new OperandNode($else));

		$expected  = "function() use (\$vars) {" . PHP_EOL;
		$expected .= "\tif ({$condition}) {" . PHP_EOL;
		$expected .= "\t\treturn {$if};" . PHP_EOL;
		$expected .= "\t} else {" . PHP_EOL;
		$expected .= "\t\treturn {$else};" . PHP_EOL;
		$expected .= "\t}" . PHP_EOL;
		$expected .= "}";

		$this->assertSame($expected, $node->evaluate());
	}
}
