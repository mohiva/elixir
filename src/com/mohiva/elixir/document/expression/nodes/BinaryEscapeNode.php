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

use com\mohiva\pyramid\nodes\BinaryOperatorNode;
use com\mohiva\manitou\generators\php\PHPRawCode;

/**
 * Represents an binary escape.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression/Nodes
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class BinaryEscapeNode extends BinaryOperatorNode {

	/**
	 * Evaluates the node.
	 *
	 * @return string The result of the evaluation.
	 */
	public function evaluate() {

		$code = new PHPRawCode();
		$code->openScope('$this->evaluateClosure(function() use ($valueContext, $vars) {');
		$code->addLine('$valueContext = clone $valueContext;');
		$code->addLine('$valueContext->setEscapingStrategy(\'' . $this->right->evaluate() . '\');');
		$code->addLine();
		$code->addLine('return $this->autoboxValue(' . $this->left->evaluate() . ', $valueContext);');
		$code->closeScope('})');

		return $code->generate();
	}
}
