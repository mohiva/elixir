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

use com\mohiva\elixir\values\ObjectValue;
use com\mohiva\elixir\values\ArrayValue;
use com\mohiva\elixir\values\StringValue;
use com\mohiva\elixir\values\NumberValue;
use com\mohiva\elixir\values\BooleanValue;

/**
 * Creates the instances of the value objects.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class ValueFactory {

	/**
	 * Returns the value object based on the given value type.
	 *
	 * @param mixed $value The value for which the object should be returned.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @return Value The value object for the given value type.
	 */
	public function getByValue($value, ValueContext $context, Config $config) {

		if (is_object($value)) {
			return $this->createObjectValue($value, $context, $config);
		} else if (is_array($value)) {
			return $this->createArrayValue($value, $context, $config);
		} else if (is_numeric($value)) {
			return $this->createNumberValue($value, $context, $config);
		} else if ($value === true || $value === false || $value == 'true' || $value == 'false') {
			return $this->createBooleanValue($value, $context, $config);
		} else {
			return $this->createStringValue($value, $context, $config);
		}
	}

	/**
	 * Creates an `ObjectValue` object from the given value.
	 *
	 * @param object $value The value to pass to the object constructor.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @return ObjectValue The `ObjectValue` object which represents the given value.
	 */
	public function createObjectValue($value, ValueContext $context, Config $config) {

		return new ObjectValue($value, $context, $config);
	}

	/**
	 * Creates an `ArrayValue` object from the given value.
	 *
	 * @param array $value The value to pass to the object constructor.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @return ArrayValue The `ArrayValue` object which represents the given value.
	 */
	public function createArrayValue(array $value, ValueContext $context, Config $config) {

		return new ArrayValue($value, $context, $config);
	}

	/**
	 * Creates a `StringValue` object from the given value.
	 *
	 * @param string $value The value to pass to the object constructor.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @return StringValue The `StringValue` object which represents the given value.
	 */
	public function createStringValue($value, ValueContext $context, Config $config) {

		return new StringValue($value, $context, $config);
	}

	/**
	 * Creates a `NumberValue` object from the given value.
	 *
	 * @param number $value The value to pass to the object constructor.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @return NumberValue The `NumberValue` object which represents the given value.
	 */
	public function createNumberValue($value, ValueContext $context, Config $config) {

		return new NumberValue($value, $context, $config);
	}

	/**
	 * Creates a `BooleanValue` object from the given value.
	 *
	 * @param boolean $value The value to pass to the object constructor.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @return StringValue The `BooleanValue` object which represents the given value.
	 */
	public function createBooleanValue($value, ValueContext $context, Config $config) {

		return new BooleanValue($value, $context, $config);
	}
}
