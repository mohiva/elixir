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
 * @package   Mohiva/Elixir/Document
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\document;

use com\mohiva\elixir\document\expression\Container as ExpressionContainer;

/**
 * Document helper interface.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
interface Helper extends ExpressionContainer {

	/**
	 * Gets the id of the helper.
	 *
	 * @return string The id of the helper.
	 */
	public function getId();

	/**
	 * Gets the line number of the source file in which the helper is located.
	 *
	 * @return int The line number of the source file in which the helper is located.
	 */
	public function getLine();

	/**
	 * Gets the path to this helper in the source file.
	 *
	 * @return string The path to this helper in the source file.
	 */
	public function getPath();
}
