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
 * @package   Mohiva/Elixir
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir;

use com\mohiva\elixir\document\expression\EncoderFactory;
use com\mohiva\elixir\document\expression\ValueFactory;

/**
 * Config object for an elixir document.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class Config {

	/**
	 * The charset of the document.
	 *
	 * @var string
	 */
	private $charset = 'UTF-8';

	/**
	 * The value factory which creates the instances of the value objects.
	 *
	 * @var ValueFactory
	 */
	private $valueFactory = null;

	/**
	 * The encoder factory which creates the instances of the encoder objects based
	 * on the given escaping strategy.
	 *
	 * @var EncoderFactory
	 */
	private $encoderFactory = null;

	/**
	 * The global escaping strategy for unsafe values.
	 *
	 * If set to null then auto escaping for unsafe values is disabled.
	 *
	 * @var string
	 */
	private $escapingStrategy = null;

	/**
	 * Indicates if the strict mode is enabled or not.
	 *
	 * @var bool
	 */
	private $strictMode = false;

	/**
	 * Sets the charset of the document.
	 *
	 * @param string $charset The charset of the document.
	 */
	public function setCharset($charset) {

		$this->charset = $charset;
	}

	/**
	 * Gets the charset of the document.
	 *
	 * @return string The charset of the document.
	 */
	public function getCharset() {

		return $this->charset;
	}

	/**
	 * Sets the value factory.
	 *
	 * @param ValueFactory $valueFactory The value factory.
	 */
	public function setValueFactory(ValueFactory $valueFactory) {

		$this->valueFactory = $valueFactory;
	}

	/**
	 * Gets the value factory.
	 *
	 * @return ValueFactory The value factory.
	 */
	public function getValueFactory() {

		return $this->valueFactory;
	}

	/**
	 * Sets the encoder factory.
	 *
	 * @param EncoderFactory $encoderFactory The encoder factory.
	 */
	public function setEncoderFactory(EncoderFactory $encoderFactory) {

		$this->encoderFactory = $encoderFactory;
	}

	/**
	 * Gets the encoder factory.
	 *
	 * @return EncoderFactory The encoder factory.
	 */
	public function getEncoderFactory() {

		return $this->encoderFactory;
	}

	/**
	 * Sets the global escaping strategy for unsafe values.
	 *
	 * @param string $strategy The global escaping strategy or null to deactivate auto escaping for unsafe values.
	 */
	public function setEscapingStrategy($strategy) {

		$this->escapingStrategy = $strategy;
	}

	/**
	 * Gets the global escaping strategy for unsafe values.
	 *
	 * @return string The global escaping strategy or null if the auto escaping for unsafe values is disabled.
	 */
	public function getEscapingStrategy() {

		return $this->escapingStrategy;
	}

	/**
	 * Sets if the strict mode should be enabled or not.
	 *
	 * @param boolean $strictMode True if the strict mode should be enabled, false otherwise.
	 */
	public function setStrictMode($strictMode) {

		$this->strictMode = $strictMode;
	}

	/**
	 * Indicates if the strict mode is enabled or not.
	 *
	 * @return boolean True if the strict mode is enabled, false otherwise.
	 */
	public function isStrictMode() {

		return $this->strictMode;
	}
}
