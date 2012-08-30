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
use com\mohiva\common\exceptions\UnexpectedValueException;

/**
 * Represents an object value.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression/Values
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class ObjectValue extends AbstractValue {

	/**
	 * The class constructor.
	 *
	 * @param object $value The object value.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @throws UnexpectedValueException if value isn't an object value.
	 */
	public function __construct($value, ValueContext $context, Config $config) {

		if (!is_object($value)) {
			throw new UnexpectedValueException('Object value expected but ' . gettype($value) . ' value given');
		}

		parent::__construct($value, $context, $config);
	}

	/**
	 * {@inheritDoc}
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
	 * Objects are safe if they are defined in a document.
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

		return $this->config->getValueFactory()->createObjectValue($this->value, $this->context, $this->config);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return ArrayValue The value as array value.
	 */
	public function toArray() {

		$value = (array) $this->value;

		return $this->config->getValueFactory()->createArrayValue($value, $this->context, $this->config);
	}

	/**
	 * Gets a value from an object by the given property.
	 *
	 * @param string $property The object property.
	 * @return Value The value for the given property.
	 * @throws UnexpectedValueException value is not an object.
	 * @throws UnexpectedValueException if the property doesn't exists in object.
	 */
	public function getByProperty($property) {

		if (!property_exists($this->value, $property)) {
			throw new UnexpectedValueException('Property `' . $property . '` does not exists in object');
		}

		return $this->config->getValueFactory()->getByValue($this->value->{$property}, $this->context, $this->config);
	}
}
