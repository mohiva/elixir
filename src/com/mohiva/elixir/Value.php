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
 * @package   Mohiva/Elixir
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir;

/**
 * Interface for the value objects.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
interface Value {

	/**
	 * Returns the string representation of the value.
	 *
	 * @return string The string representation of the value.
	 */
	public function __toString();

	/**
	 * Sets the strategy to use to encode this value.
	 *
	 * This method doesn't encode the value. It sets only the strategy which should be used
	 * for encoding when the value is inserted in the template.
	 *
	 * @param string $strategy The strategy to use for encoding.
	 * @return Value This instance to provide a fluent interface.
	 */
	public function encode($strategy);

	/**
	 * This method is the opposite to the encode method. It resets the encoding strategy for this value.
	 *
	 * @return Value This instance to provide a fluent interface.
	 */
	public function raw();

	/**
	 * If the current object value is null then the new set value will be used for further processing.
	 *
	 * @param Value $value The value to use if the object value is null.
	 * @return Value If the object value is null then the given value, otherwise this instance.
	 */
	public function whenNull(Value $value);

	/**
	 * If the current object value is empty then the new set value will be used for further processing.
	 *
	 * This method uses PHPs empty() language construct. Please consult the documentation for the
	 * definition of which values are empty for which types.
	 *
	 * @param Value $value The value to use if the object value is empty.
	 * @return Value If the object value is empty then the given value, otherwise this instance.
	 */
	public function whenEmpty(Value $value);

	/**
	 * Indicates if the value is NULL or not.
	 *
	 * @return boolean True if the value is null, false otherwise.
	 */
	public function isNull();

	/**
	 * Casts the value to an object value.
	 *
	 * @return values\ObjectValue The value as object value.
	 * @throws values\exceptions\InvalidCastException if the value can't be casted to `ObjectValue`.
	 */
	public function toObject();

	/**
	 * Casts the value to an array value.
	 *
	 * @return values\ArrayValue The value as array value.
	 * @throws values\exceptions\InvalidCastException if the value can't be casted to `ArrayValue`.
	 */
	public function toArray();

	/**
	 * Casts the value to a string value.
	 *
	 * @return values\StringValue The value as string value.
	 * @throws values\exceptions\InvalidCastException if the value can't be casted to `StringValue`.
	 */
	public function toString();

	/**
	 * Casts the value to a number value.
	 *
	 * @return values\NumberValue The value as number value.
	 * @throws values\exceptions\InvalidCastException if the value can't be casted to `NumberValue`.
	 */
	public function toNumber();

	/**
	 * Casts the value to an boolean value.
	 *
	 * @return values\BooleanValue The value as boolean value.
	 * @throws values\exceptions\InvalidCastException if the value can't be casted to `BooleanValue`.
	 */
	public function toBool();
}
