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
use com\mohiva\elixir\document\expression\EncoderFactory;
use com\mohiva\elixir\document\expression\ValueContext;
use com\mohiva\elixir\document\expression\DefaultValueFactory;
use com\mohiva\elixir\document\expression\values\ArrayValue;

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
class ArrayValueTest extends AbstractValueTest {

	/**
	 * Test if the constructor throws an `UnexpectedValueException` if the given value isn't a array value.
	 *
	 * @expectedException \com\mohiva\common\exceptions\UnexpectedValueException
	 */
	public function testConstructorThrowsExceptionIfValueIsNotArray() {

		new ArrayValue('no array', $this->valueContext, $this->config);
	}

	/**
	 * Test if the `__call` method throws an `NullPointerException` if the `getByPropertyMethod` will be called.
	 *
	 * @expectedException \com\mohiva\common\exceptions\UnexpectedValueException
	 */
	public function testMagicCallMethodThrowsExceptionWhenAccessingGetByPropertyMethod() {

		$value = new ArrayValue([], $this->valueContext, $this->config);
		/** @noinspection PhpUndefinedMethodInspection */
		$value->getByProperty();
	}

	/**
	 * Test if the `__call` method throws an `NullPointerException` if a not existing method will be called.
	 *
	 * @expectedException \com\mohiva\common\exceptions\BadMethodCallException
	 */
	public function testMagicCallMethodThrowsExceptionOnNotExistingMethod() {

		$value = new ArrayValue([], $this->valueContext, $this->config);
		/** @noinspection PhpUndefinedMethodInspection */
		$value->notExistingMethod();
	}

	/**
	 * Test if the `__toString` method returns an empty string if the strict mode is disabled.
	 */
	public function testMagicToStringReturnsEmptyStringIfStrictModeIsDisabled() {

		$this->config->setStrictMode(false);

		$value = new ArrayValue([], $this->valueContext, $this->config);

		$this->assertSame('', $value->__toString());
	}

	/**
	 * Test if the `__toString` method uses the default strategy if no global or context based strategy is defined.
	 */
	public function testMagicToStringUsesDefaultStrategyIfNoStrategyIsDefined() {

		$encoderFactory = $this->getEncoderFactory();
		$this->config->setEncoderFactory($encoderFactory);
		$this->config->setStrictMode(true);

		$value = new ArrayValue([], $this->valueContext, $this->config);

		$this->assertSame(self::STRATEGY_RAW, $value->__toString());
	}

	/**
	 * Test if the `__toString` method uses the the global strategy for unsafe values.
	 */
	public function testMagicToStringUsesGlobalStrategyForUnsafeValues() {

		$encoderFactory = $this->getEncoderFactory();
		$this->config->setEncoderFactory($encoderFactory);
		$this->config->setEscapingStrategy(self::STRATEGY_CUSTOM);
		$this->config->setStrictMode(true);
		$this->valueContext->setContext(ValueContext::USER);

		$value = new ArrayValue([], $this->valueContext, $this->config);

		$this->assertSame(self::STRATEGY_CUSTOM, $value->__toString());
	}

	/**
	 * Test if the `__toString` method uses the the context strategy for unsafe values.
	 */
	public function testMagicToStringUsesContextStrategyForUnsafeValues() {

		$encoderFactory = $this->getEncoderFactory();
		$this->config->setEncoderFactory($encoderFactory);
		$this->config->setStrictMode(true);
		$this->valueContext->setContext(ValueContext::USER);
		$this->valueContext->setEscapingStrategy(self::STRATEGY_CUSTOM);

		$value = new ArrayValue([], $this->valueContext, $this->config);

		$this->assertSame(self::STRATEGY_CUSTOM, $value->__toString());
	}

	/**
	 * Test if the `__toString` method uses the `raw` encoder for save values if no context based
	 * encoding strategy is not defined.
	 */
	public function testMagicToStringUsesRawEncoderForSaveValueIfContextBasedEncodingStrategyIsNotDefined() {

		$encoderFactory = $this->getEncoderFactory();
		$this->config->setEncoderFactory($encoderFactory);
		$this->config->setStrictMode(true);
		$this->valueContext->setContext(ValueContext::DOC);

		$value = new ArrayValue([], $this->valueContext, $this->config);

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
		$this->valueContext->setContext(ValueContext::DOC);

		$value = new ArrayValue([], $this->valueContext, $this->config);

		$this->assertSame(self::STRATEGY_CUSTOM, $value->__toString());
	}

	/**
	 * Test if the `isSave` method returns `true` if the context is set to `ValueContext::DOC`.
	 */
	public function testIsSaveReturnsTrue() {

		$this->valueContext->setContext(ValueContext::DOC);

		$value = new ArrayValue([], $this->valueContext, $this->config);

		$this->assertTrue($value->isSave());
	}

	/**
	 * Test if the `isSave` method returns `false` if the context is set to `ValueContext::USER`.
	 */
	public function testIsSaveReturnsFalse() {

		$this->valueContext->setContext(ValueContext::USER);

		$value = new ArrayValue([], $this->valueContext, $this->config);

		$this->assertFalse($value->isSave());
	}

	/**
	 * Test if the `toObject` method returns a new `ObjectValue` object.
	 */
	public function testToObjectReturnsNewObjectValue() {

		$value = new ArrayValue([], $this->valueContext, $this->config);

		$this->assertInstanceOf('\com\mohiva\elixir\document\expression\values\ObjectValue', $value->toObject());
	}

	/**
	 * Test if the `toArray` method returns a new `ArrayValue` object.
	 */
	public function testToArrayReturnsNewArrayValue() {

		$value = new ArrayValue([], $this->valueContext, $this->config);

		$this->assertInstanceOf('\com\mohiva\elixir\document\expression\values\ArrayValue', $value->toArray());
	}

	/**
	 * Test if the `toString` method throws an `InvalidCastException`.
	 *
	 * @expectedException com\mohiva\elixir\document\exceptions\InvalidCastException
	 */
	public function testToStringThrowsException() {

		$value = new ArrayValue([], $this->valueContext, $this->config);
		$value->toString();
	}

	/**
	 * Test if the `toNumber` method throws an `InvalidCastException`.
	 *
	 * @expectedException com\mohiva\elixir\document\exceptions\InvalidCastException
	 */
	public function testToNumberThrowsException() {

		$value = new ArrayValue([], $this->valueContext, $this->config);
		$value->toNumber();
	}

	/**
	 * Test if the `toBool` method throws an `InvalidCastException`.
	 *
	 * @expectedException com\mohiva\elixir\document\exceptions\InvalidCastException
	 */
	public function testToBoolThrowsException() {

		$value = new ArrayValue([], $this->valueContext, $this->config);
		$value->toBool();
	}

	/**
	 * Test if the `getByKey` method throws an `UnexpectedValueException` if the a doesn't exists.
	 *
	 * @expectedException \com\mohiva\common\exceptions\UnexpectedValueException
	 */
	public function testGetByKeyThrowsException() {

		$value = new ArrayValue([], $this->valueContext, $this->config);
		$value->getByKey('not existing');
	}

	/**
	 * Test if the `getByKey` method returns a new `StringValue` object.
	 */
	public function testGetByKeyReturnsNewValue() {

		$this->config->setValueFactory(new DefaultValueFactory());

		$value = new ArrayValue(['a string'], $this->valueContext, $this->config);

		$this->assertInstanceOf('\com\mohiva\elixir\document\expression\values\StringValue',
			$value->getByKey(0))
		;
	}
}
