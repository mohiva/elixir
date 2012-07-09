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
namespace com\mohiva\elixir;

/**
 * Stores context based information about a value.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class ValueContext {

	/**
	 * Indicates that the value is defined in document. This value needn't be encoded.
	 *
	 * @var int
	 */
	const DOC = 1;

	/**
	 * Indicates that the value comes from outside the document. This value should be encoded.
	 *
	 * @var int
	 */
	const USER = 2;

	/**
	 * The context in which the variable is defined.
	 *
	 * @var int
	 */
	private $context = self::USER;

	/**
	 * The context based encoding strategy for unsafe values.
	 *
	 * @var string
	 */
	private $encodingStrategy = null;

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
	 * Sets the context based encoding strategy for unsafe values.
	 *
	 * @param string $strategy The context based encoding strategy for unsafe values.
	 */
	public function setEncodingStrategy($strategy) {

		$this->encodingStrategy = $strategy;
	}

	/**
	 * Gets the context based encoding strategy for unsafe values.
	 *
	 * @return string The context based encoding strategy for unsafe values is disabled.
	 */
	public function getEncodingStrategy() {

		return $this->encodingStrategy;
	}
}
