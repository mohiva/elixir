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
use com\mohiva\common\exceptions\NullPointerException;

/**
 * Represents a null value.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression/Values
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class NullValue extends AbstractValue {

	/**
	 * The class constructor.
	 *
	 * @param ValueContext $context Context based information about the value.
	 * @param Config $config The document config.
	 */
	public function __construct(ValueContext $context, Config $config) {

		parent::__construct(null, $context, $config);
	}

	/**
	 * Handle the call to the not existing methods.
	 *
	 * @param string $name The name of the method
	 * @param array $args The arguments of the method.
	 * @throws NullPointerException it trying to access a property, key or method of a null value.
	 */
	public function __call($name, array $args) {

		if ($name == 'getByProperty') {
			throw new NullPointerException('Try to get a property of a none object');
		} else if ($name == 'getByKey') {
			throw new NullPointerException('Try to get a key of a non array');
		} else {
			$class = get_class($this);
			throw new NullPointerException("Method `{$name}` does not exists for object `{$class}`");
		}
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

		return parent::encodeValue('null', $strategy);
	}

	/**
	 * {@inheritDoc}
	 *
	 * Null values are save by default.
	 *
	 * @return boolean True if the value is saved, false otherwise.
	 */
	public function isSave() {

		return true;
	}
}
