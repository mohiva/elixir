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
 * @package   Mohiva/Elixir/Document/Helpers
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\document\helpers;

use com\mohiva\elixir\document\Helper;
use com\mohiva\pyramid\Node;

/**
 * Base class for all element helper classes.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Helpers
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
abstract class ElementHelper implements Helper {

	/**
	 * The id of the helper.
	 *
	 * @var string
	 */
	private $id = null;

	/**
	 * A list with all attributes available for this helper.
	 *
	 * @var string
	 */
	protected $attributes = array();

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
	 * @param array $attributes A list with all attributes available for the helper.
	 * @param int $line The line of the source file in which the helper is located.
	 * @param string $path The path to this helper in the source file.
	 */
	public function __construct($id, array $attributes, $line, $path) {

		$this->id = $id;
		$this->attributes = $attributes;
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
	 * Gets a list with all attributes available for this helper.
	 *
	 * @return array A list with all attributes available for this helper.
	 */
	public function getAttributes() {

		return $this->attributes;
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
	 * Adds a expression for the helper.
	 *
	 * @param Node $expression The expression to add.
	 */
	public function addExpression(Node $expression) {

		$this->expressions[] = $expression;
	}

	/**
	 * Gets all expressions found for the helper.
	 *
	 * @return array A list with all expressions found for the helper.
	 */
	public function getExpressions() {

		return $this->expressions;
	}
}
