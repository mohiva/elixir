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
 * @package   Mohiva/Elixir/Values
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\values;

use com\mohiva\elixir\Config;
use com\mohiva\elixir\Value;
use com\mohiva\elixir\ValueContext;
use com\mohiva\elixir\values\exceptions\InvalidCastException;
use com\mohiva\common\exceptions\UnexpectedValueException;
use com\mohiva\common\exceptions\BadMethodCallException;

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
	 * @var mixed
	 */
	protected $value = null;

	/**
	 * Context based information about the value.
	 *
	 * @var ValueContext
	 */
	protected $context = null;

	/**
	 * The document config.
	 *
	 * @var Config
	 */
	protected $config = null;

	protected $encodingStrategy;

	/**
	 * The class constructor.
	 *
	 * @param mixed $value The value.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 */
	public function __construct($value, ValueContext $context, Config $config) {

		$this->value = $value;
		$this->context = $context;
		$this->config = $config;
	}

	/**
	 * Handle the call to the not existing methods.
	 *
	 * @param string $name The name of the method
	 * @param array $args The arguments of the method.
	 * @throws UnexpectedValueException it trying to access a property of a non object.
	 * @throws UnexpectedValueException it trying to access a key of a non array.
	 * @throws BadMethodCallException if the method doesn't exists.
	 */
	public function __call($name, array $args) {

		if ($name == 'getByProperty') {
			throw new UnexpectedValueException('Try to get a property of a none object');
		} else if ($name == 'getByKey') {
			throw new UnexpectedValueException('Try to get a key of a non array');
		} else {
			$class = get_class($this);
			throw new BadMethodCallException("Method `{$name}` does not exists for object `{$class}`");
		}
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return string The string representation of the value.
	 */
	public function __toString() {

		// Context is safe
		if ($this->context->getContext() == ValueContext::DOC) {
			return (string) $this->value;
		}

		// Get the encoding strategy

		$encoder = $this->config->getEncoderFactory()->getEncoder('');
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param string $strategy The strategy to use for encoding.
	 * @return Value This instance to provide a fluent interface.
	 */
	public function encode($strategy) {



		return $this;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return Value This instance to provide a fluent interface.
	 */
	public function raw() {

		return $this;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param Value $value The value to use if the object value is null.
	 * @return Value If the object value is null then the given value, otherwise this instance.
	 */
	public function whenNull(Value $value) {

		if ($this->value === null) {
			return $value;
		}

		return $this;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param Value $value The value to use if the object value is empty.
	 * @return Value If the object value is empty then the given value, otherwise this instance.
	 */
	public function whenEmpty(Value $value) {

		if (empty($this->value)) {
			return $value;
		}

		return $this;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return boolean True if the value is null, false otherwise.
	 */
	public function isNull() {

		return $this->value === null;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return ObjectValue The value as object value.
	 * @throws InvalidCastException if the value can't be casted to `ObjectValue`.
	 */
	public function toObject() {

		throw new InvalidCastException('Type `' . gettype($this->value) . '` cannot be casted to `ObjectValue`');
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return ArrayValue The value as array value.
	 * @throws InvalidCastException if the value can't be casted to `ArrayValue`.
	 */
	public function toArray() {

		throw new InvalidCastException('Type `' . gettype($this->value) . '` cannot be casted to `ArrayValue`');
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return StringValue The value as string value.
	 * @throws InvalidCastException if the value can't be casted to `StringValue`.
	 */
	public function toString() {

		throw new InvalidCastException('Type `' . gettype($this->value) . '` cannot be casted to `StringValue`');
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return NumberValue The value as number value.
	 * @throws InvalidCastException if the value can't be casted to `NumberValue`.
	 */
	public function toNumber() {

		throw new InvalidCastException('Type `' . gettype($this->value) . '` cannot be casted to `NumberValue`');
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return BooleanValue The value as boolean value.
	 * @throws InvalidCastException if the value can't be casted to `BooleanValue`.
	 */
	public function toBool() {

		throw new InvalidCastException('Type `' . gettype($this->value) . '` cannot be casted to `BooleanValue`');
	}

	/**
	 * Gets the encoding strategy.
	 *
	 * @param string $strategy The strategy set
	 */
	private function getEncodingStrategy($strategy) {


	}
}
