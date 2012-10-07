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
 * @package   Mohiva/Elixir/Document/Expression/Nodes
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\document\expression\nodes;

use com\mohiva\pyramid\nodes\TernaryOperatorNode;
use com\mohiva\manitou\generators\php\PHPRawCode;

/**
 * Represents a ternary if operation.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression/Nodes
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class TernaryIfNode extends TernaryOperatorNode {

	/**
	 * Evaluates the node.
	 *
	 * @return string The result of the evaluation.
	 */
	public function evaluate() {

		$raw = new PHPRawCode();
		$raw->openScope('function() use ($vars) {');
		$raw->addLine('$condition = ' . $this->conditionNode->evaluate() . ';');
		$raw->openScope('if ($condition) {');
		if ($this->ifNode === null) {
			$raw->addLine('return $condition;');
		} else {
			$raw->addLine('return ' . $this->ifNode->evaluate() . ';');
		}
		$raw->openScope('} else {', true);
		$raw->addLine('return ' . $this->elseNode->evaluate() . ';');
		$raw->closeScope('}');
		$raw->closeScope('}');

		return $raw->generate();
	}
}
