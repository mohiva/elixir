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
 * @package   Mohiva/Elixir
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\document;

/**
 * The container which contains the variables to parse into the document.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class Variables {

	/**
	 * Contains all variables for this container.
	 *
	 * @var array
	 */
	protected $vars = array();

	/**
	 * The class constructor.
	 *
	 * @param array $vars A set of vars to use in this container.
	 */
	public function __construct(array $vars = array()) {

		$this->vars = $vars;
	}

	/**
	 * Set a new var.
	 *
	 * @param string $name The name of the var.
	 * @param mixed $value The value of the var.
	 */
	public function setVar($name, $value) {

		$this->vars[$name] = $value;
	}
}
