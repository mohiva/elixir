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
 * Represents a boolean value.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression/Values
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class BooleanValue extends AbstractValue {

	/**
	 * The class constructor.
	 *
	 * @param bool|null $value The boolean value.
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 * @throws UnexpectedValueException if value isn't an boolean value.
	 */
	public function __construct($value, ValueContext $context, Config $config) {

		if ($value === 'true') {
			$value = true;
		} else if ($value === 'false') {
			$value = false;
		} else if (!is_bool($value)) {
			throw new UnexpectedValueException('Boolean value expected but ' . gettype($value) . ' value given');
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
		$value = $this->value ? 'true' : 'false';

		return parent::encodeValue($value, $strategy);
	}

	/**
	 * {@inheritDoc}
	 *
	 * Boolean values are save by default.
	 *
	 * @return boolean True if the value is saved, false otherwise.
	 */
	public function isSave() {

		return true;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return StringValue The value as string value.
	 */
	public function toString() {

		$value = $this->value ? 'true' : 'false';

		return $this->config->getValueFactory()->createStringValue($value, $this->context, $this->config);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return NumberValue The value as number value.
	 */
	public function toNumber() {

		$value = $this->value ? 1 : 0;

		return $this->config->getValueFactory()->createNumberValue($value, $this->context, $this->config);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return BooleanValue The value as boolean value.
	 */
	public function toBool() {

		return $this->config->getValueFactory()->createBooleanValue($this->value, $this->context, $this->config);
	}
}
