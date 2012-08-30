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
 * @package   Mohiva/Elixir/Document/Expression/Values
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\document\expression\values;

use com\mohiva\elixir\Config;
use com\mohiva\elixir\document\expression\Value;
use com\mohiva\elixir\document\expression\ValueContext;
use com\mohiva\elixir\document\expression\EncoderFactory;
use com\mohiva\elixir\document\exceptions\InvalidCastException;
use com\mohiva\common\exceptions\UnexpectedValueException;
use com\mohiva\common\exceptions\BadMethodCallException;

/**
 * Abstract value class.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression/Values
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
	 * @param Value $value The value to use if the object value is null.
	 * @return Value If the object value is null then the given value, otherwise this instance.
	 */
	public function whenNull(Value $value) {

		if ($this->isNull()) {
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

		if ($this->isEmpty()) {
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
	 * @return boolean True if the value is empty, false otherwise.
	 */
	public function isEmpty() {

		return empty($this->value);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return ObjectValue The value as object value.
	 * @throws InvalidCastException if the value can't be casted to `ObjectValue`.
	 */
	public function toObject() {

		throw new InvalidCastException('Type `' . gettype($this->value) . '` cannot be casted to `object`');
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return ArrayValue The value as array value.
	 * @throws InvalidCastException if the value can't be casted to `ArrayValue`.
	 */
	public function toArray() {

		throw new InvalidCastException('Type `' . gettype($this->value) . '` cannot be casted to `array`');
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return StringValue The value as string value.
	 * @throws InvalidCastException if the value can't be casted to `StringValue`.
	 */
	public function toString() {

		throw new InvalidCastException('Type `' . gettype($this->value) . '` cannot be casted to `string`');
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return NumberValue The value as number value.
	 * @throws InvalidCastException if the value can't be casted to `NumberValue`.
	 */
	public function toNumber() {

		throw new InvalidCastException('Type `' . gettype($this->value) . '` cannot be casted to `number`');
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return BooleanValue The value as boolean value.
	 * @throws InvalidCastException if the value can't be casted to `BooleanValue`.
	 */
	public function toBool() {

		throw new InvalidCastException('Type `' . gettype($this->value) . '` cannot be casted to `boolean`');
	}

	/**
	 * Encodes the given value with the given escaping strategy.
	 *
	 * @param mixed $value the value to encode.
	 * @param string $strategy The strategy to use to encode the value.
	 * @return string The encodes value as string.
	 */
	protected function encodeValue($value, $strategy) {

		$encoder = $this->config->getEncoderFactory()->getEncoder($strategy);

		return $encoder->encode($value, $this->config->getCharset());
	}

	/**
	 * Gets the escaping strategy for this value.
	 *
	 * The resolution rule for the escaping strategy is defined as follow:
	 * - First it checks if a context based strategy is defined
	 * - Then it checks if the global strategy is defined
	 * - Otherwise it returns the RAW strategy
	 *
	 * @return string The escaping strategy to use for this value.
	 */
	protected function getEscapingStrategy() {

		// Force raw encoding for save values, but only if no
		// context based escaping strategy is defined
		if ($this->isSave() && $this->context->getEscapingStrategy() === null) {
			return EncoderFactory::STRATEGY_RAW;
		}

		$contextStrategy = $this->context->getEscapingStrategy();
		if ($contextStrategy !== null) {
			return $contextStrategy;
		}

		$globalStrategy = $this->config->getEscapingStrategy();
		if ($globalStrategy !== null) {
			return $globalStrategy;
		}

		return EncoderFactory::STRATEGY_RAW;
	}
}
