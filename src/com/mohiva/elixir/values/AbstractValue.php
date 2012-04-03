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
 * @package   Mohiva/Elixir/Values
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\values;

use com\mohiva\elixir\Value;
use com\mohiva\elixir\values\exceptions\InvalidCastException;

/**
 * Abstract value class.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Values
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
abstract class AbstractValue implements Value {

	/**
	 * The value handled by this class.
	 *
	 * @var array
	 */
	protected $value = null;

	/**
	 * The class constructor.
	 *
	 * @param mixed $value The value for this object.
	 * @param mixed $default The default value. This will be used if value is null.
	 */
	public function __construct($value, $default = null) {

		if ($value === null) {
			$this->value = $default;
		} else {
			$this->value = $value;
		}
	}

	/**
	 * Handle
	 *
	 * @param $name
	 */
	public function __call($name) {

		if ($name == 'getByProperty') {
			// Try to get a property of a none object.
		} else if ($name == 'getByKey') {
			// Try to get a key of a non array
		} else {
			// Undefined method for value type ...
		}
	}

	/**
	 * Indicates if the value is NULL or not.
	 *
	 * @return boolean True if the value is null, false otherwise.
	 */
	public function isNull() {

		return $this->value === null;
	}

	/**
	 * Casts the value to a string.
	 *
	 * @return string The value as string.
	 * @throws \com\mohiva\elixir\values\exceptions\InvalidCastException if the value can't be casted to string.
	 */
	public function toString() {

		throw new InvalidCastException('Array cannot be casted to string');
	}

	/**
	 * Casts the value to a integer value.
	 *
	 * @return int The value as integer.
	 * @throws \com\mohiva\elixir\values\exceptions\InvalidCastException if the value can't be casted to int.
	 */
	public function toInt() {

		throw new InvalidCastException('Array cannot be casted to int');
	}

	/**
	 * Casts the value to a floating point value.
	 *
	 * @return float The value as float.
	 * @throws \com\mohiva\elixir\values\exceptions\InvalidCastException if the value can't be casted to float.
	 */
	public function toFloat() {

		throw new InvalidCastException('Array cannot be casted to float');
	}

	/**
	 * Casts the value to a boolean value.
	 *
	 * @return boolean The value as boolean.
	 * @throws \com\mohiva\elixir\values\exceptions\InvalidCastException if the value can't be casted to bool.
	 */
	public function toBool() {

		throw new InvalidCastException('Array cannot be casted to bool');
	}

	/**
	 * Casts the value to XML.
	 *
	 * This means it replaces all XML entities inside this value, so that it can be imported as XML node.
	 *
	 * @return string The value as XML.
	 * @throws \com\mohiva\elixir\values\exceptions\InvalidCastException if the value can't be casted to XML.
	 */
	public function toXML() {

		throw new InvalidCastException('Array cannot be casted to XML');
	}
}
