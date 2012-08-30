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

use com\mohiva\elixir\Config;
use com\mohiva\elixir\document\expression\values\NullValue;
use com\mohiva\elixir\document\expression\values\BooleanValue;
use com\mohiva\elixir\document\expression\values\ObjectValue;
use com\mohiva\elixir\document\expression\values\ArrayValue;
use com\mohiva\elixir\document\expression\values\NumberValue;
use com\mohiva\elixir\document\expression\values\StringValue;
use com\mohiva\common\exceptions\UnexpectedValueException;

/**
 * Default implementation of the `ValueFactory` which creates the instances for all
 * inherently supported value objects.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class DefaultValueFactory implements ValueFactory {

	/**
	 * {@inheritDoc}
	 *
	 * @param mixed $value The value for which the object should be returned.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @return Value The value object for the given value type.
	 * @throws UnexpectedValueException if no value object can be created for the given value type.
	 */
	public function getByValue($value, ValueContext $context, Config $config) {

		$lowerValue = strtolower($value);
		if (is_null($value) || $lowerValue === 'null') {
			// TODO Consider if a null string value from outside should be treated as null
			return $this->createNullValue($context, $config);
		} else if ($value === true || $value === false || $lowerValue === 'true' || $lowerValue === 'false') {
			// TODO Consider if boolean string values from outside should be treated as boolean
			return $this->createBooleanValue($value, $context, $config);
		} else if (is_object($value)) {
			return $this->createObjectValue($value, $context, $config);
		} else if (is_array($value)) {
			return $this->createArrayValue($value, $context, $config);
		} else if (is_numeric($value)) {
			return $this->createNumberValue($value, $context, $config);
		} else if (is_string($value)) {
			return $this->createStringValue($value, $context, $config);
		} else {
			throw new UnexpectedValueException('Cannot create value for type: ' . gettype($value));
		}
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param object $value The value to pass to the object constructor.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @return NullValue The `NullValue` object which represents the given value.
	 */
	public function createNullValue(ValueContext $context, Config $config) {

		return new NullValue($context, $config);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param boolean $value The value to pass to the object constructor.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @return BooleanValue The `BooleanValue` object which represents the given value.
	 */
	public function createBooleanValue($value, ValueContext $context, Config $config) {

		return new BooleanValue($value, $context, $config);
	}

	/**
	 * {@inheritDoc}
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
	 * {@inheritDoc}
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
	 * {@inheritDoc}
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
	 * {@inheritDoc}
	 *
	 * @param string $value The value to pass to the object constructor.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @return StringValue The `StringValue` object which represents the given value.
	 */
	public function createStringValue($value, ValueContext $context, Config $config) {

		return new StringValue($value, $context, $config);
	}
}
