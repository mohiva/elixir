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
 * @package   Mohiva/Elixir/Document/Expression
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\document\expression;

use com\mohiva\pyramid\Node;

/**
 * Interface for containers which can contain expressions.
 * 
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
interface Container {
	
	/**
	 * Adds an expression.
	 * 
	 * @param \com\mohiva\pyramid\Node $expression The expression to add.
	 */
	public function addExpression(Node $expression);
	
	/**
	 * Gets the expressions contained in the content of this node.
	 * 
	 * @return array The expressions contained in the content of this node.
	 */
	public function getExpressions();
}
