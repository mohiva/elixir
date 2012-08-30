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

use Phake;
use com\mohiva\elixir\Config;
use com\mohiva\elixir\document\expression\EncoderFactory;
use com\mohiva\elixir\document\expression\ValueContext;
use com\mohiva\elixir\document\expression\DefaultValueFactory;

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
abstract class AbstractValueTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Encoding strategies.
	 */
	const STRATEGY_RAW    = EncoderFactory::STRATEGY_RAW;
	const STRATEGY_CUSTOM = 'custom';

	/**
	 * The config object.
	 *
	 * @var Config
	 */
	protected $config = null;

	/**
	 * The value context.
	 *
	 * @var ValueContext
	 */
	protected $valueContext = null;

	/**
	 * Setup the test case.
	 */
	public function setUp() {

		$this->config = new Config();
		$this->config->setValueFactory(new DefaultValueFactory());
		$this->valueContext = new ValueContext();
		$this->valueContext->setContext(ValueContext::USER);
	}

	/**
	 * Test if the `whenNull` method returns the current value object when it isn't null.
	 */
	public function testWhenNullReturnsCurrentValue() {

		/* @var \com\mohiva\elixir\document\expression\values\AbstractValue $givenValue */
		$givenValue = Phake::mock('\com\mohiva\elixir\document\expression\values\AbstractValue');

		/* @var \com\mohiva\elixir\document\expression\values\AbstractValue $currentValue */
		$currentValue = $this->getMockForAbstractClass('\com\mohiva\elixir\document\expression\values\AbstractValue',
			['a string', $this->valueContext, $this->config]
		);

		$this->assertSame($currentValue, $currentValue->whenNull($givenValue));
	}

	/**
	 * Test if the `whenNull` method returns the current value object when it is null.
	 */
	public function testWhenNullReturnsGivenValue() {

		/* @var \com\mohiva\elixir\document\expression\values\AbstractValue $givenValue */
		$givenValue = Phake::mock('\com\mohiva\elixir\document\expression\values\AbstractValue');

		/* @var \com\mohiva\elixir\document\expression\values\AbstractValue $currentValue */
		$currentValue = $this->getMockForAbstractClass('\com\mohiva\elixir\document\expression\values\AbstractValue',
			[null, $this->valueContext, $this->config]
		);

		$this->assertSame($givenValue, $currentValue->whenNull($givenValue));
	}

	/**
	 * Test if the `whenEmpty` method returns the current value object when it isn't empty.
	 */
	public function testWhenEmptyReturnsCurrentValue() {

		/* @var \com\mohiva\elixir\document\expression\values\AbstractValue $givenValue */
		$givenValue = Phake::mock('\com\mohiva\elixir\document\expression\values\AbstractValue');

		/* @var \com\mohiva\elixir\document\expression\values\AbstractValue $currentValue */
		$currentValue = $this->getMockForAbstractClass('\com\mohiva\elixir\document\expression\values\AbstractValue',
			['a string', $this->valueContext, $this->config]
		);

		$this->assertSame($currentValue, $currentValue->whenEmpty($givenValue));
	}

	/**
	 * Test if the `whenEmpty` method returns the current value object when it is empty.
	 */
	public function testWhenEmptyReturnsGivenValue() {

		/* @var \com\mohiva\elixir\document\expression\values\AbstractValue $givenValue */
		$givenValue = Phake::mock('\com\mohiva\elixir\document\expression\values\AbstractValue');

		/* @var \com\mohiva\elixir\document\expression\values\AbstractValue $currentValue */
		$currentValue = $this->getMockForAbstractClass('\com\mohiva\elixir\document\expression\values\AbstractValue',
			['', $this->valueContext, $this->config]
		);

		$this->assertSame($givenValue, $currentValue->whenEmpty($givenValue));
	}

	/**
	 * Test if the `isNull` method returns `true` if the value is `null`.
	 */
	public function testIsNullReturnsTrueIfValueIsNull() {

		/* @var \com\mohiva\elixir\document\expression\values\AbstractValue $value */
		$value = $this->getMockForAbstractClass('\com\mohiva\elixir\document\expression\values\AbstractValue',
			[null, $this->valueContext, $this->config]
		);

		$this->assertTrue($value->isNull());
	}

	/**
	 * Test if the `isNull` method returns `false` if the value is not `null`.
	 */
	public function testIsNullReturnsFalseIfValueIsNotNull() {

		/* @var \com\mohiva\elixir\document\expression\values\AbstractValue $value */
		$value = $this->getMockForAbstractClass('\com\mohiva\elixir\document\expression\values\AbstractValue',
			['not null', $this->valueContext, $this->config]
		);

		$this->assertFalse($value->isNull());
	}

	/**
	 * Test if the `isEmpty` method returns `true` if the value is empty.
	 */
	public function testIsEmptyReturnsTrueIfValueIsNull() {

		/* @var \com\mohiva\elixir\document\expression\values\AbstractValue $value */
		$value = $this->getMockForAbstractClass('\com\mohiva\elixir\document\expression\values\AbstractValue',
			['', $this->valueContext, $this->config]
		);

		$this->assertTrue($value->isEmpty());
	}

	/**
	 * Test if the `isEmpty` method returns `false` if the value is not empty.
	 */
	public function testIsEmptyReturnsFalseIfValueIsNotNull() {

		/* @var \com\mohiva\elixir\document\expression\values\AbstractValue $value */
		$value = $this->getMockForAbstractClass('\com\mohiva\elixir\document\expression\values\AbstractValue',
			['not empty', $this->valueContext, $this->config]
		);

		$this->assertFalse($value->isEmpty());
	}

	/**
	 * Gets a mock of the encoder factory.
	 *
	 * @return EncoderFactory A mock of the encoder factory.
	 */
	protected function getEncoderFactory() {

		$rawEncoder = Phake::mock('\com\mohiva\elixir\document\expression\Encoder');
		$customEncoder = Phake::mock('\com\mohiva\elixir\document\expression\Encoder');
		$encoderFactory = Phake::mock('\com\mohiva\elixir\document\expression\EncoderFactory');

		/** @noinspection PhpUndefinedMethodInspection */
		/** @noinspection PhpParamsInspection */
		Phake::when($rawEncoder)->encode(Phake::anyParameters())->thenReturn(self::STRATEGY_RAW);

		/** @noinspection PhpUndefinedMethodInspection */
		/** @noinspection PhpParamsInspection */
		Phake::when($customEncoder)->encode(Phake::anyParameters())->thenReturn(self::STRATEGY_CUSTOM);

		/** @noinspection PhpUndefinedMethodInspection */
		/** @noinspection PhpParamsInspection */
		Phake::when($encoderFactory)->getEncoder(self::STRATEGY_RAW)->thenReturn($rawEncoder);

		/** @noinspection PhpUndefinedMethodInspection */
		/** @noinspection PhpParamsInspection */
		Phake::when($encoderFactory)->getEncoder(self::STRATEGY_CUSTOM)->thenReturn($customEncoder);

		return $encoderFactory;
	}
}
