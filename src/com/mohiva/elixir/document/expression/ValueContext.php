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
 * @package   Mohiva/Elixir/Document/Expression
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\document\expression;

/**
 * Stores context based information about a value.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class ValueContext {

	/**
	 * Indicates that a value is defined in a document. This type of values needn't be escaped.
	 *
	 * @var int
	 */
	const DOC = 1;

	/**
	 * Indicates that a value comes from outside the document. This type of values should be escaped.
	 *
	 * @var int
	 */
	const USER = 2;

	/**
	 * The context in which the variable is defined.
	 *
	 * This is defined as unsafe by default.
	 *
	 * @var int
	 */
	private $context = self::USER;

	/**
	 * The context based escaping strategy for unsafe values.
	 *
	 * @var string
	 */
	private $escapingStrategy = null;

	/**
	 * Sets the context in which the variable is defined.
	 *
	 * @param int $context The context in which the variable is defined.
	 */
	public function setContext($context) {

		$this->context = $context;
	}

	/**
	 * Gets the context in which the variable is defined.
	 *
	 * @return int The context in which the variable is defined.
	 */
	public function getContext() {

		return $this->context;
	}

	/**
	 * Sets the context based escaping strategy for unsafe values.
	 *
	 * @param string $strategy The context based escaping strategy for unsafe values.
	 */
	public function setEscapingStrategy($strategy) {

		$this->escapingStrategy = $strategy;
	}

	/**
	 * Gets the context based escaping strategy for unsafe values.
	 *
	 * @return string The context based escaping strategy for unsafe values.
	 */
	public function getEscapingStrategy() {

		return $this->escapingStrategy;
	}
}
