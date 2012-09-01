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
namespace com\mohiva\test\elixir;

use Phake;
use com\mohiva\elixir\Config;

/**
 * Unit test case for the Mohiva Elixir project.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Test
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class ConfigTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test the `setCharset` and `getCharset` accessors.
	 */
	public function testCharsetAccessors() {

		$charset = array(sha1(microtime(true)));

		$config = new Config();
		$config->setCharset($charset);

		$this->assertSame($charset, $config->getCharset());
	}

	/**
	 * Test the `setValueFactory` and `getValueFactory` accessors.
	 */
	public function testValueFactoryAccessors() {

		/* @var \com\mohiva\elixir\document\expression\ValueFactory $factory */
		$factory = Phake::mock('\com\mohiva\elixir\document\expression\ValueFactory');

		$config = new Config();
		$config->setValueFactory($factory);

		$this->assertSame($factory, $config->getValueFactory());
	}

	/**
	 * Test the `setEncoderFactory` and `getEncoderFactory` accessors.
	 */
	public function testEncoderFactoryAccessors() {

		/* @var \com\mohiva\elixir\document\expression\EncoderFactory $factory */
		$factory = Phake::mock('\com\mohiva\elixir\document\expression\EncoderFactory');

		$config = new Config();
		$config->setEncoderFactory($factory);

		$this->assertSame($factory, $config->getEncoderFactory());
	}

	/**
	 * Test the `setEscapingStrategy` and `getEscapingStrategy` accessors.
	 */
	public function testEscapingStrategyAccessors() {

		$strategy = sha1(microtime(true));

		$config = new Config();
		$config->setEscapingStrategy($strategy);

		$this->assertSame($strategy, $config->getEscapingStrategy());
	}

	/**
	 * Test the `setStrictMode` and `isStrictMode` accessors.
	 */
	public function testStrictModeAccessors() {

		$mode = (bool) rand(0, 1);

		$config = new Config();
		$config->setStrictMode($mode);

		$this->assertSame($mode, $config->isStrictMode());
	}
}
