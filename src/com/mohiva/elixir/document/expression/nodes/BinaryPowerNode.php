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
 * @package   Mohiva/Elixir/Document/Expression/Nodes
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\document\expression\nodes;

use com\mohiva\pyramid\nodes\BinaryOperatorNode;

/**
 * Represents an binary exponentiation.
 * 
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression/Nodes
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class BinaryPowerNode extends BinaryOperatorNode {
	
	/**
	 * Evaluates the node.
	 * 
	 * @return string The result of the evaluation.
	 */
	public function evaluate() {
		
		return 'pow(' . $this->left->evaluate() . ', ' . $this->right->evaluate() . ')';
	}
}
