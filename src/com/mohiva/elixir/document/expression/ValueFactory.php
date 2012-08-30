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
use com\mohiva\elixir\document\expression\values\ObjectValue;
use com\mohiva\elixir\document\expression\values\ArrayValue;
use com\mohiva\elixir\document\expression\values\StringValue;
use com\mohiva\elixir\document\expression\values\NumberValue;
use com\mohiva\elixir\document\expression\values\BooleanValue;

/**
 * Interface for the factory which creates the `Value` objects.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
interface ValueFactory {

	/**
	 * Returns the value object based on the given value type.
	 *
	 * @param mixed $value The value for which the object should be returned.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @return Value The value object for the given value type.
	 */
	public function getByValue($value, ValueContext $context, Config $config);

	/**
	 * Creates an `NullValue` object from the given value.
	 *
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @return ObjectValue The `NullValue` object which represents the given value.
	 */
	public function createNullValue(ValueContext $context, Config $config);

	/**
	 * Creates a `BooleanValue` object from the given value.
	 *
	 * @param boolean $value The value to pass to the object constructor.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @return BooleanValue The `BooleanValue` object which represents the given value.
	 */
	public function createBooleanValue($value, ValueContext $context, Config $config);

	/**
	 * Creates an `ObjectValue` object from the given value.
	 *
	 * @param object $value The value to pass to the object constructor.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @return ObjectValue The `ObjectValue` object which represents the given value.
	 */
	public function createObjectValue($value, ValueContext $context, Config $config);

	/**
	 * Creates an `ArrayValue` object from the given value.
	 *
	 * @param array $value The value to pass to the object constructor.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @return ArrayValue The `ArrayValue` object which represents the given value.
	 */
	public function createArrayValue(array $value, ValueContext $context, Config $config);

	/**
	 * Creates a `NumberValue` object from the given value.
	 *
	 * @param number $value The value to pass to the object constructor.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @return NumberValue The `NumberValue` object which represents the given value.
	 */
	public function createNumberValue($value, ValueContext $context, Config $config);

	/**
	 * Creates a `StringValue` object from the given value.
	 *
	 * @param string $value The value to pass to the object constructor.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @return StringValue The `StringValue` object which represents the given value.
	 */
	public function createStringValue($value, ValueContext $context, Config $config);
}
