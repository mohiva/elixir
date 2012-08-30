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
use com\mohiva\elixir\document\expression\EncoderFactory;
use com\mohiva\elixir\document\expression\Value;
use com\mohiva\elixir\document\expression\ValueContext;
use com\mohiva\common\exceptions\UnexpectedValueException;

/**
 * Represents an array value.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression/Values
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class ArrayValue extends AbstractValue {

	/**
	 * The class constructor.
	 *
	 * @param array $value The array value.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @throws UnexpectedValueException if value isn't an array.
	 */
	public function __construct($value, ValueContext $context, Config $config) {

		if (!is_array($value)) {
			throw new UnexpectedValueException('Array value expected but ' . gettype($value) . ' value given');
		}

		parent::__construct($value, $context, $config);
	}

	/**
	 * {@inheritDoc}
	 *
	 * If strict mode is enabled this method returns the dump of the array, otherwise
	 * it returns an empty string.
	 *
	 * @return string The string representation of the value.
	 */
	public function __toString() {

		if (!$this->config->isStrictMode()) {
			return '';
		}

		$strategy = parent::getEscapingStrategy();
		$value = var_export($this->value, true);

		return parent::encodeValue($value, $strategy);
	}

	/**
	 * {@inheritDoc}
	 *
	 * Arrays are safe if they are defined in a document.
	 *
	 * @return boolean True if the value is saved, false otherwise.
	 */
	public function isSave() {

		if ($this->context->getContext() === ValueContext::DOC) {
			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return ObjectValue The value as object value.
	 */
	public function toObject() {

		$value = (object) $this->value;

		return $this->config->getValueFactory()->createObjectValue($value, $this->context, $this->config);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return ArrayValue The value as array value.
	 */
	public function toArray() {

		return $this->config->getValueFactory()->createArrayValue($this->value, $this->context, $this->config);
	}

	/**
	 * Gets a value from an array by the given key.
	 *
	 * @param string $key The array key.
	 * @return Value The value for the given key.
	 * @throws UnexpectedValueException value is not an array.
	 * @throws UnexpectedValueException if the key doesn't exists in array.
	 */
	public function getByKey($key) {

		if (!array_key_exists($key, $this->value)) {
			throw new UnexpectedValueException('Key `' . $key . '` does not exists in array');
		}

		return $this->config->getValueFactory()->getByValue($this->value[$key], $this->context, $this->config);
	}
}
