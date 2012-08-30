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
namespace com\mohiva\test\elixir\document\expression\values;

use com\mohiva\elixir\Config;
use com\mohiva\common\exceptions\UnexpectedValueException;
use com\mohiva\elixir\document\expression\EncoderFactory;
use com\mohiva\elixir\document\expression\ValueContext;
use com\mohiva\elixir\document\expression\DefaultEncoderFactory;
use com\mohiva\elixir\document\expression\values\NullValue;

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
class NullValueTest extends AbstractValueTest {

	/**
	 * Test if the `__call` method throws an `NullPointerException` if the `getByPropertyMethod` will be called.
	 *
	 * @expectedException \com\mohiva\common\exceptions\NullPointerException
	 */
	public function testMagicCallMethodThrowsExceptionWhenAccessingGetByPropertyMethod() {

		$value = new NullValue($this->valueContext, $this->config);
		/** @noinspection PhpUndefinedMethodInspection */
		$value->getByProperty();
	}

	/**
	 * Test if the `__call` method throws an `NullPointerException` if the `getByKeyMethod` will be called.
	 *
	 * @expectedException \com\mohiva\common\exceptions\NullPointerException
	 */
	public function testMagicCallMethodThrowsExceptionWhenAccessingGetByKeyMethod() {

		$value = new NullValue($this->valueContext, $this->config);
		/** @noinspection PhpUndefinedMethodInspection */
		$value->getByKey();
	}

	/**
	 * Test if the `__call` method throws an `NullPointerException` if a not existing method will be called.
	 *
	 * @expectedException \com\mohiva\common\exceptions\NullPointerException
	 */
	public function testMagicCallMethodThrowsExceptionOnNotExistingMethod() {

		$value = new NullValue($this->valueContext, $this->config);
		/** @noinspection PhpUndefinedMethodInspection */
		$value->notExistingMethod();
	}

	/**
	 * Test if the `__toString` method returns an empty string if the strict mode is disabled.
	 */
	public function testMagicToStringReturnsEmptyStringIfStrictModeIsDisabled() {

		$this->config->setStrictMode(false);

		$value = new NullValue($this->valueContext, $this->config);

		$this->assertSame('', $value->__toString());
	}

	/**
	 * Test if the `__toString` method returns the null value as string if the strict mode is enabled.
	 */
	public function testMagicToStringReturnsNullAsStringIfStrictModeIsEnabled() {

		$this->config->setEncoderFactory(new DefaultEncoderFactory);
		$this->config->setStrictMode(true);

		$value = new NullValue($this->valueContext, $this->config);

		$this->assertSame('null', $value->__toString());
	}

	/**
	 * Test if the `__toString` method uses the `raw` encoder for save values if no context based
	 * encoding strategy is not defined.
	 */
	public function testMagicToStringUsesRawEncoderForSaveValueIfContextBasedEncodingStrategyIsNotDefined() {

		$encoderFactory = $this->getEncoderFactory();
		$this->config->setEncoderFactory($encoderFactory);
		$this->config->setStrictMode(true);

		$value = new NullValue($this->valueContext, $this->config);

		$this->assertSame(self::STRATEGY_RAW, $value->__toString());
	}

	/**
	 * Test if the `__toString` method uses the `custom` encoder for save values if a context based
	 * encoding strategy is defined.
	 */
	public function testMagicToStringUsesCustomEncoderForSaveValueIfContextBasedEncodingStrategyIsDefined() {

		$encoderFactory = $this->getEncoderFactory();
		$this->config->setEncoderFactory($encoderFactory);
		$this->config->setStrictMode(true);
		$this->valueContext->setEscapingStrategy(self::STRATEGY_CUSTOM);

		$value = new NullValue($this->valueContext, $this->config);

		$this->assertSame(self::STRATEGY_CUSTOM, $value->__toString());
	}

	/**
	 * Test if the `isSave` method returns true even if the context is set to `ValueContext::USER`.
	 */
	public function testIsSaveReturnsTrue() {

		$this->valueContext->setContext(ValueContext::USER);

		$value = new NullValue($this->valueContext, $this->config);

		$this->assertTrue($value->isSave());
	}

	/**
	 * Test if the `toObject` method throws an `InvalidCastException`.
	 *
	 * @expectedException \com\mohiva\elixir\document\exceptions\InvalidCastException
	 */
	public function testToObjectThrowsException() {

		$value = new NullValue($this->valueContext, $this->config);
		$value->toObject();
	}

	/**
	 * Test if the `toArray` method throws an `InvalidCastException`.
	 *
	 * @expectedException \com\mohiva\elixir\document\exceptions\InvalidCastException
	 */
	public function testToArrayThrowsException() {

		$value = new NullValue($this->valueContext, $this->config);
		$value->toArray();
	}

	/**
	 * Test if the `toString` method throws an `InvalidCastException`.
	 *
	 * @expectedException \com\mohiva\elixir\document\exceptions\InvalidCastException
	 */
	public function testToStringThrowsException() {

		$value = new NullValue($this->valueContext, $this->config);
		$value->toString();
	}

	/**
	 * Test if the `toNumber` method throws an `InvalidCastException`.
	 *
	 * @expectedException \com\mohiva\elixir\document\exceptions\InvalidCastException
	 */
	public function testToNumberThrowsException() {

		$value = new NullValue($this->valueContext, $this->config);
		$value->toNumber();
	}

	/**
	 * Test if the `toBool` method throws an `InvalidCastException`.
	 *
	 * @expectedException \com\mohiva\elixir\document\exceptions\InvalidCastException
	 */
	public function testToBoolThrowsException() {

		$value = new NullValue($this->valueContext, $this->config);
		$value->toBool();
	}
}
