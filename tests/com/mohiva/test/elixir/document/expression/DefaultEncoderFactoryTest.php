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
 * @package   Mohiva/Elixir/Test
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\test\elixir\document\expression;

use com\mohiva\elixir\document\expression\DefaultEncoderFactory;

/**
 * Unit test case for the Mohiva Elixir project.
 *
 * @category  Mohiva
 * @package   Mohiva/Test
 * @author    Christian Kaps <akkie@framework.mohiva.com>
 * @copyright Copyright (c) 2007-2011 Christian Kaps (http://www.mohiva.com)
 * @license   http://framework.mohiva.com/license New BSD License
 * @link      http://framework.mohiva.com
 */
class DefaultEncoderFactoryTest extends \PHPUnit_Framework_TestCase {

	/**
	 * The list of all encoder classes.
	 *
	 * @var array
	 */
	private $encoders = [
		DefaultEncoderFactory::STRATEGY_RAW => '\com\mohiva\elixir\document\expression\encoders\RawEncoder',
		DefaultEncoderFactory::STRATEGY_HTML => '\com\mohiva\elixir\document\expression\encoders\HtmlEncoder',
		DefaultEncoderFactory::STRATEGY_XML => '\com\mohiva\elixir\document\expression\encoders\XmlEncoder'
	];

	/**
	 * Test if the `getEncoderMethod` throws an `UnexpectedEscapingStrategyException` if the given encoder
	 * strategy is unexpected.
	 *
	 * @expectedException \com\mohiva\elixir\document\exceptions\UnexpectedEscapingStrategyException
	 */
	public function testGetEncoderThrowsUnexpectedEscapingStrategyException() {

		$factory = new DefaultEncoderFactory();
		$factory->getEncoder('unexpected');
	}

	/**
	 * Test if the `getEncoder` method returns the `RawEncoder` object.
	 */
	public function testGetEncoderReturnsRawEncoder() {

		$factory = new DefaultEncoderFactory();
		$encoder = $factory->getEncoder(DefaultEncoderFactory::STRATEGY_RAW);

		$this->assertInstanceOf($this->encoders[DefaultEncoderFactory::STRATEGY_RAW], $encoder);
	}

	/**
	 * Test if the `getEncoder` method returns the `HtmlEncoder` object.
	 */
	public function testGetEncoderReturnsHtmlEncoder() {

		$factory = new DefaultEncoderFactory();
		$encoder = $factory->getEncoder(DefaultEncoderFactory::STRATEGY_HTML);

		$this->assertInstanceOf($this->encoders[DefaultEncoderFactory::STRATEGY_HTML], $encoder);
	}

	/**
	 * Test if the `getEncoder` method returns the `XmlEncoder` object.
	 */
	public function testGetEncoderReturnsXmlEncoder() {

		$factory = new DefaultEncoderFactory();
		$encoder = $factory->getEncoder(DefaultEncoderFactory::STRATEGY_XML);

		$this->assertInstanceOf($this->encoders[DefaultEncoderFactory::STRATEGY_XML], $encoder);
	}

	/**
	 * Test if the `getEncoder` method reuses the encoder instances.
	 */
	public function testGetEncoderReuseEncoderInstances() {

		$factory = new DefaultEncoderFactory();
		$firstInstance = $factory->getEncoder(DefaultEncoderFactory::STRATEGY_XML);
		$secondInstance = $factory->getEncoder(DefaultEncoderFactory::STRATEGY_XML);

		$this->assertSame($firstInstance, $secondInstance);
	}
}
