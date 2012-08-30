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
use com\mohiva\elixir\document\exceptions\InvalidCastException;
use com\mohiva\elixir\document\expression\EncoderFactory;
use com\mohiva\elixir\document\expression\Value;
use com\mohiva\elixir\document\expression\ValueContext;
use com\mohiva\common\exceptions\UnexpectedValueException;

/**
 * Represents a string value.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression/Values
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class StringValue extends AbstractValue {

	/**
	 * The class constructor.
	 *
	 * @param string $value The string value.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @throws UnexpectedValueException if value isn't a string value.
	 */
	public function __construct($value, ValueContext $context, Config $config) {

		if (!is_string($value)) {
			throw new UnexpectedValueException('String value expected but ' . gettype($value) . ' value given');
		}

		parent::__construct($value, $context, $config);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return string The string representation of the value.
	 */
	public function __toString() {

		$strategy = parent::getEscapingStrategy();

		return parent::encodeValue($this->value, $strategy);
	}

	/**
	 * {@inheritDoc}
	 *
	 * Strings are safe if they are defined in a document.
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
	 * @return StringValue The value as string value.
	 */
	public function toString() {

		return $this->config->getValueFactory()->createStringValue($this->value, $this->context, $this->config);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return NumberValue The value as number value.
	 * @throws InvalidCastException if
	 */
	public function toNumber() {

		if (!is_numeric($this->value)) {
			throw new InvalidCastException("Not numeric string value `{$this->value}` cannot be casted to `number`");
		}

		return $this->config->getValueFactory()->createNumberValue($this->value, $this->context, $this->config);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return BooleanValue The value as boolean value.
	 */
	public function toBool() {

		if ($this->value === 'true') {
			$value = 'true';
		} else if ($this->value === 'false') {
			$value = 'false';
		} else {
			$value = (bool) $this->value;
		}

		return $this->config->getValueFactory()->createBooleanValue($value, $this->context, $this->config);
	}
}
