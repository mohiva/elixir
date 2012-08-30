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
 * @package   Mohiva/Elixir/Document/Expression
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\document\expression;

use Closure;
use com\mohiva\elixir\document\expression\encoders\RawEncoder;
use com\mohiva\elixir\document\expression\encoders\XMLEncoder;
use com\mohiva\elixir\document\expression\encoders\HTMLEncoder;
use com\mohiva\elixir\document\exceptions\UnexpectedEscapingStrategyException;

/**
 * Default implementation of the `EncoderFactory` which creates the instances for all
 * inherently supported encoder objects.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class DefaultEncoderFactory implements EncoderFactory {

	/**
	 * Encoding strategies.
	 */
	const STRATEGY_XML  = 'xml';
	const STRATEGY_HTML = 'html';

	/**
	 * The list with supported encoders.
	 *
	 * @var Closure[]
	 */
	private $encoders = [];

	/**
	 * Stores the encoder instances for reuse.
	 *
	 * @var array
	 */
	private $instances = array();

	/**
	 * The class constructor.
	 */
	public function __construct() {

		$this->encoders[self::STRATEGY_RAW] = function() { return new RawEncoder; };
		$this->encoders[self::STRATEGY_XML] = function() { return new XmlEncoder; };
		$this->encoders[self::STRATEGY_HTML] = function() { return new HtmlEncoder; };
	}

	/**
	 * Gets the encoder for the given escaping strategy.
	 *
	 * @param string $strategy The escaping strategy.
	 * @return Encoder The encoder for the given escaping strategy.
	 * @throws UnexpectedEscapingStrategyException if an unsupported escaping strategy was given.
	 */
	public function getEncoder($strategy) {

		if (isset($this->encoders[$strategy])) {
			return $this->getEncoderInstance($strategy, $this->encoders[$strategy]);
		}

		throw new UnexpectedEscapingStrategyException("The escaping strategy `{$strategy}` isn't supported");
	}

	/**
	 * Gets the encoder instance.
	 *
	 * This method stores the instance of the created encoder for reuse.
	 *
	 * @param string $strategy The escaping strategy.
	 * @param Closure $initializer A closure which returns the instance of the encoder.
	 * @return Encoder The encoder for the given escaping strategy.
	 */
	private function getEncoderInstance($strategy, Closure $initializer) {

		if (!isset($this->instances[$strategy])) {
			$this->instances[$strategy] = $initializer();
		}

		return $this->instances[$strategy];
	}
}
