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
 * Represents a boolean value.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Values
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
	 * @throws UnexpectedValueException if value isn't null or an boolean value.
	 */
	public function __construct($value, ValueContext $context, Config $config) {

		if ($value == 'true') {
			$value = true;
		} else if ($value == 'false') {
			$value = false;
		} else if ($value !== null && !is_bool($value)) {
			throw new UnexpectedValueException('Boolean value expected but ' . gettype($value) . ' value given');
		}

		parent::__construct($value, $context, $config);
	}

	/**
	 * {@inheritDoc}
	 *
	 * Boolean values are always safe, so they needn't be encoded.
	 *
	 * @return string The string representation of the value.
	 */
	public function __toString() {

		return $this->value ? 'true' : 'false';
	}
}
