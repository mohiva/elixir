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
use com\mohiva\elixir\document\expression\DefaultEncoderFactory;
use com\mohiva\elixir\document\expression\EncoderFactory;
use com\mohiva\elixir\document\expression\ValueContext;
use com\mohiva\elixir\document\expression\values\StringValue;

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
class StringValueTest extends AbstractValueTest {

	/**
	 * Test if the constructor throws an `UnexpectedValueException` if the given value isn't a string value.
	 *
	 * @expectedException \com\mohiva\common\exceptions\UnexpectedValueException
	 */
	public function testConstructorThrowsExceptionIfValueIsNotString() {

		new StringValue(1, $this->valueContext, $this->config);
	}

	/**
	 * Test if the `__call` method throws an `NullPointerException` if the `getByPropertyMethod` will be called.
	 *
	 * @expectedException \com\mohiva\common\exceptions\UnexpectedValueException
	 */
	public function testMagicCallMethodThrowsExceptionWhenAccessingGetByPropertyMethod() {

		$value = new StringValue('a string', $this->valueContext, $this->config);
		/** @noinspection PhpUndefinedMethodInspection */
		$value->getByProperty();
	}

	/**
	 * Test if the `__call` method throws an `NullPointerException` if the `getByKeyMethod` will be called.
	 *
	 * @expectedException \com\mohiva\common\exceptions\UnexpectedValueException
	 */
	public function testMagicCallMethodThrowsExceptionWhenAccessingGetByKeyMethod() {

		$value = new StringValue('a string', $this->valueContext, $this->config);
		/** @noinspection PhpUndefinedMethodInspection */
		$value->getByKey();
	}

	/**
	 * Test if the `__call` method throws an `NullPointerException` if a not existing method will be called.
	 *
	 * @expectedException \com\mohiva\common\exceptions\BadMethodCallException
	 */
	public function testMagicCallMethodThrowsExceptionOnNotExistingMethod() {

		$value = new StringValue('a string', $this->valueContext, $this->config);
		/** @noinspection PhpUndefinedMethodInspection */
		$value->notExistingMethod();
	}

	/**
	 * Test if the `__toString` method uses the default strategy if no global or context based strategy is defined.
	 */
	public function testMagicToStringUsesDefaultStrategyIfNoStrategyIsDefined() {

		$encoderFactory = $this->getEncoderFactory();
		$this->config->setEncoderFactory($encoderFactory);

		$value = new StringValue('a string', $this->valueContext, $this->config);

		$this->assertSame(self::STRATEGY_RAW, $value->__toString());
	}

	/**
	 * Test if the `__toString` method uses the the global strategy for unsafe values.
	 */
	public function testMagicToStringUsesGlobalStrategyForUnsafeValues() {

		$encoderFactory = $this->getEncoderFactory();
		$this->config->setEncoderFactory($encoderFactory);
		$this->config->setEscapingStrategy(self::STRATEGY_CUSTOM);
		$this->valueContext->setContext(ValueContext::USER);

		$value = new StringValue('a string', $this->valueContext, $this->config);

		$this->assertSame(self::STRATEGY_CUSTOM, $value->__toString());
	}

	/**
	 * Test if the `__toString` method uses the the context strategy for unsafe values.
	 */
	public function testMagicToStringUsesContextStrategyForUnsafeValues() {

		$encoderFactory = $this->getEncoderFactory();
		$this->config->setEncoderFactory($encoderFactory);
		$this->valueContext->setContext(ValueContext::USER);
		$this->valueContext->setEscapingStrategy(self::STRATEGY_CUSTOM);

		$value = new StringValue('a string', $this->valueContext, $this->config);

		$this->assertSame(self::STRATEGY_CUSTOM, $value->__toString());
	}

	/**
	 * Test if the `__toString` method uses the `raw` encoder for save values if no context based
	 * encoding strategy is not defined.
	 */
	public function testMagicToStringUsesRawEncoderForSaveValueIfContextBasedEncodingStrategyIsNotDefined() {

		$encoderFactory = $this->getEncoderFactory();
		$this->config->setEncoderFactory($encoderFactory);
		$this->valueContext->setContext(ValueContext::DOC);

		$value = new StringValue('a string', $this->valueContext, $this->config);

		$this->assertSame(self::STRATEGY_RAW, $value->__toString());
	}

	/**
	 * Test if the `__toString` method uses the `custom` encoder for save values if a context based
	 * encoding strategy is defined.
	 */
	public function testMagicToStringUsesCustomEncoderForSaveValueIfContextBasedEncodingStrategyIsDefined() {

		$encoderFactory = $this->getEncoderFactory();
		$this->config->setEncoderFactory($encoderFactory);
		$this->valueContext->setEscapingStrategy(self::STRATEGY_CUSTOM);
		$this->valueContext->setContext(ValueContext::DOC);

		$value = new StringValue('a string', $this->valueContext, $this->config);

		$this->assertSame(self::STRATEGY_CUSTOM, $value->__toString());
	}

	/**
	 * Test if the `isSave` method returns `true` if the context is set to `ValueContext::DOC`.
	 */
	public function testIsSaveReturnsTrue() {

		$this->valueContext->setContext(ValueContext::DOC);

		$value = new StringValue('a string', $this->valueContext, $this->config);

		$this->assertTrue($value->isSave());
	}

	/**
	 * Test if the `isSave` method returns `false` if the context is set to `ValueContext::USER`.
	 */
	public function testIsSaveReturnsFalse() {

		$this->valueContext->setContext(ValueContext::USER);

		$value = new StringValue('a string', $this->valueContext, $this->config);

		$this->assertFalse($value->isSave());
	}

	/**
	 * Test if the `toObject` method throws an `InvalidCastException`.
	 *
	 * @expectedException \com\mohiva\elixir\document\exceptions\InvalidCastException
	 */
	public function testToObjectThrowsException() {

		$value = new StringValue('a string', $this->valueContext, $this->config);
		$value->toObject();
	}

	/**
	 * Test if the `toArray` method throws an `InvalidCastException`.
	 *
	 * @expectedException \com\mohiva\elixir\document\exceptions\InvalidCastException
	 */
	public function testToArrayThrowsException() {

		$value = new StringValue('a string', $this->valueContext, $this->config);
		$value->toArray();
	}

	/**
	 * Test if the `toString` method returns a new `StringValue` object.
	 */
	public function testToStringReturnsNewStringValue() {

		$value = new StringValue('a string', $this->valueContext, $this->config);

		$this->assertInstanceOf('\com\mohiva\elixir\document\expression\values\StringValue', $value->toString());
	}

	/**
	 * Test if the `toNumber` method throws an `InvalidCastException` if the string isn't numeric.
	 *
	 * @expectedException \com\mohiva\elixir\document\exceptions\InvalidCastException
	 */
	public function testToNumberThrowsException() {

		$value = new StringValue('a string', $this->valueContext, $this->config);
		$value->toNumber();
	}

	/**
	 * Test if the `toNumber` method returns a new `NumberValue` object.
	 */
	public function testToNumberReturnsNewNumberValue() {

		$value = new StringValue('1', $this->valueContext, $this->config);

		$this->assertInstanceOf('\com\mohiva\elixir\document\expression\values\NumberValue', $value->toNumber());
	}

	/**
	 * Test if the `toBool` method returns a new `BooleanValue` object.
	 */
	public function testToBoolReturnsNewBooleanValue() {

		$value = new StringValue('a string', $this->valueContext, $this->config);

		$this->assertInstanceOf('\com\mohiva\elixir\document\expression\values\BooleanValue', $value->toBool());
	}

	/**
	 * Test if the `toBool` method returns a new `BooleanValue` object which represents the boolean representation
	 * of a string with the value `true`.
	 */
	public function testToBoolReturnsNewBooleanTrue() {

		$this->config->setEncoderFactory(new DefaultEncoderFactory);

		$value = new StringValue('true', $this->valueContext, $this->config);

		$this->assertSame('true', (string) $value->toBool());
	}

	/**
	 * Test if the `toBool` method returns a new `BooleanValue` object which represents the boolean representation
	 * of a string with the value `false`.
	 */
	public function testToBoolReturnsNewBooleanFalse() {

		$this->config->setEncoderFactory(new DefaultEncoderFactory);

		$value = new StringValue('false', $this->valueContext, $this->config);

		$this->assertSame('false', (string) $value->toBool());
	}
}
