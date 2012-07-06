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

use com\mohiva\elixir\Config;
use com\mohiva\elixir\Value;
use com\mohiva\elixir\ValueContext;
use com\mohiva\common\exceptions\UnexpectedValueException;

/**
 * Represents an object value.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Values
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class ObjectValue extends AbstractValue {

	/**
	 * The class constructor.
	 *
	 * @param object|null $value The object value.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @throws UnexpectedValueException if value isn't null or an object value.
	 */
	public function __construct($value, ValueContext $context, Config $config) {

		if ($value !== null && !is_object($value)) {
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

		$value = var_export($this->value, true);
		$value = htmlentities($value, ENT_QUOTES, $this->config->getCharset());

		return $value;
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

		if ($this->value === null) {
			throw new UnexpectedValueException('Try to get a property of a non object');
		} else if (!isset($this->value->{$property})) {
			throw new UnexpectedValueException('Property `' . $property . '` does not exists in object');
		}

		return $this->config->getValueFactory()->getByValue($this->value->{$property}, $this->context, $this->config);
	}
}
