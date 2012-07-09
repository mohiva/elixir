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

use com\mohiva\elixir\encoders\XMLEncoder;
use com\mohiva\elixir\encoders\HTMLEncoder;

/**
 * Creates the instances of the encoder objects.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class EncoderFactory {

	/**
	 * Encoding strategies.
	 */
	const STRATEGY_XML  = 'xml';
	const STRATEGY_HTML = 'html';

	/**
	 * Stores the encoder instances for reuse.
	 *
	 * @var array
	 */
	private $instances = array();

	/**
	 * Gets the encoder for the given strategy.
	 *
	 * @param string $strategy The encoder strategy.
	 * @return Encoder The encoder for the given strategy.
	 * @throws \Exception
	 */
	public function getEncoder($strategy) {

		switch ($strategy) {
			case self::STRATEGY_XML:
				return $this->getXMLEncoder();

			case self::STRATEGY_HTML:
				return $this->getHTMLEncoder();

			default:
				throw new \Exception();
		}
	}

	/**
	 * Gets the XML encoder.
	 *
	 * @return XMLEncoder The XML encoder instance.
	 */
	public function getXMLEncoder() {

		if (!isset($this->instances[self::STRATEGY_XML])) {
			$this->instances[self::STRATEGY_XML] = new XMLEncoder();
		}

		return $this->instances[self::STRATEGY_XML];
	}

	/**
	 * Gets the HTML encoder.
	 *
	 * @return XMLEncoder The HTML encoder instance.
	 */
	public function getHTMLEncoder() {

		if (!isset($this->instances[self::STRATEGY_HTML])) {
			$this->instances[self::STRATEGY_HTML] = new HTMLEncoder();
		}

		return $this->instances[self::STRATEGY_HTML];
	}
}
