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
use com\mohiva\common\exceptions\UnexpectedValueException;

/**
 * Represents an array value.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Values
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class ArrayValue extends AbstractValue {

	/**
	 * The class constructor.
	 *
	 * @param array|null $value The array value.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @throws UnexpectedValueException if value isn't null or an array.
	 */
	public function __construct($value, ValueContext $context, Config $config) {

		if ($value !== null && !is_array($value)) {
			throw new UnexpectedValueException('Array value expected but ' . gettype($value) . ' value given');
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

		// Context is safe
		if ($this->context == self::CONTEXT_DOC) {
			return $value;
		}



		return $value;
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

		if ($this->value === null) {
			throw new UnexpectedValueException('Try to get a key of a non array');
		} else if (!isset($this->value[$key])) {
			throw new UnexpectedValueException('Key `' . $key . '` does not exists in array');
		}

		return $this->config->getValueFactory()->getByValue($this->value[$key], $this->context, $this->config);
	}
}
