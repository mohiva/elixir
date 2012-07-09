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
 * @package   Mohiva/Elixir/Document/Helpers
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\document\helpers;

use com\mohiva\elixir\document\Helper;
use com\mohiva\elixir\document\Expression;

/**
 * Base class for all attribute helper classes.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Helpers
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
abstract class AttributeHelper implements Helper {

	/**
	 * The id of the helper.
	 *
	 * @var string
	 */
	private $id = null;

	/**
	 * The value of the attribute.
	 *
	 * @var string
	 */
	private $value = null;

	/**
	 * Contains the line number of the source file in which the helper is located.
	 *
	 * @var int
	 */
	private $line = null;

	/**
	 * The path to this helper in the source file.
	 *
	 * @var string
	 */
	private $path = null;

	/**
	 * All expressions found for the helper.
	 *
	 * @var array
	 */
	private $expressions = array();

	/**
	 * The class constructor.
	 *
	 * @param string $id The id of the helper.
	 * @param string $value The value of the helper.
	 * @param int $line The line of the source file in which the helper is located.
	 * @param string $path The path to this helper in the source file.
	 */
	public function __construct($id, $value, $line, $path) {

		$this->id = $id;
		$this->value = $value;
		$this->line = $line;
		$this->path = $path;
	}

	/**
	 * Gets the id of the helper.
	 *
	 * @return string The id of the helper.
	 */
	public function getId() {

		return $this->id;
	}

	/**
	 * Gets the value of the attribute.
	 *
	 * @return string The value of the attribute.
	 */
	public function getValue() {

		return $this->value;
	}

	/**
	 * Gets the line number of the source file in which the helper is located.
	 *
	 * @return int The line number of the source file in which the helper is located.
	 */
	public function getLine() {

		return $this->line;
	}

	/**
	 * Gets the path to this helper in the source file.
	 *
	 * @return string The path to this helper in the source file.
	 */
	public function getPath() {

		return $this->path;
	}

	/**
	 * Adds a list with found expressions to the existing expression list.
	 *
	 * @param Expression[] $expressions The list with found expressions.
	 */
	public function addExpressions(array $expressions) {

		$this->expressions = array_merge($this->expressions, $expressions);
	}

	/**
	 * Gets the list with found expressions.
	 *
	 * @return Expression[] The list with found expressions.
	 */
	public function getExpressions() {

		return $this->expressions;
	}
}
