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
	 * on the given encoding strategy.
	 *
	 * @var EncoderFactory
	 */
	private $encoderFactory = null;

	/**
	 * The global encoding strategy for unsafe values.
	 *
	 * If set to null then auto encoding for unsafe values is disabled.
	 *
	 * @var string
	 */
	private $encodingStrategy = null;

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
	public function setValueFactory($valueFactory) {

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
	public function setEncoderFactory($encoderFactory) {

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
	 * Sets the global encoding strategy for unsafe values.
	 *
	 * @param string $strategy The global encoding strategy or null to deactivate auto encoding for unsafe values.
	 */
	public function setEncodingStrategy($strategy) {

		$this->encodingStrategy = $strategy;
	}

	/**
	 * Gets the global encoding strategy for unsafe values.
	 *
	 * @return string The global encoding strategy or null if the auto encoding for unsafe values is disabled.
	 */
	public function getEncodingStrategy() {

		return $this->encodingStrategy;
	}
}
