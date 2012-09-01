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

use stdClass;
use com\mohiva\elixir\Config;
use com\mohiva\elixir\document\expression\DefaultValueFactory;
use com\mohiva\elixir\document\expression\ValueContext;
use com\mohiva\common\exceptions\UnexpectedValueException;

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
class DefaultValueFactoryTest extends \PHPUnit_Framework_TestCase {

	/**
	 * The config object.
	 *
	 * @var Config
	 */
	private $config = null;

	/**
	 * The value context object.
	 *
	 * @var ValueContext
	 */
	private $valueContext = null;

	/**
	 * The list of all value classes.
	 *
	 * @var array
	 */
	private $values = [
		'null' => '\com\mohiva\elixir\document\expression\values\NullValue',
		'bool' => '\com\mohiva\elixir\document\expression\values\BooleanValue',
		'object' => '\com\mohiva\elixir\document\expression\values\ObjectValue',
		'array' => '\com\mohiva\elixir\document\expression\values\ArrayValue',
		'number' => '\com\mohiva\elixir\document\expression\values\NumberValue',
		'string' => '\com\mohiva\elixir\document\expression\values\StringValue'
	];

	/**
	 * Setup the test case.
	 */
	public function setUp() {

		$this->config = new Config();
		$this->valueContext = new ValueContext();
	}

	/**
	 * Test if the `getByValue` method returns a new `NullValue` object for `null`.
	 */
	public function testGetByValueReturnsNullValueObjectForNull() {

		$factory = new DefaultValueFactory();
		$value = $factory->getByValue(null, $this->valueContext, $this->config);

		$this->assertInstanceOf($this->values['null'], $value);
	}

	/**
	 * Test if the `getByValue` method returns a new `NullValue` object for the string representation of `null`.
	 */
	public function testGetByValueReturnsNullValueObjectForStringRepresentationOfNull() {

		$factory = new DefaultValueFactory();
		$value = $factory->getByValue('null', $this->valueContext, $this->config);

		$this->assertInstanceOf($this->values['null'], $value);
	}

	/**
	 * Test if the `getByValue` method returns a new `BooleanValue` object for boolean `true`.
	 */
	public function testGetByValueReturnsBooleanValueObjectForBooleanTrue() {

		$factory = new DefaultValueFactory();
		$value = $factory->getByValue(true, $this->valueContext, $this->config);

		$this->assertInstanceOf($this->values['bool'], $value);
	}

	/**
	 * Test if the `getByValue` method returns a new `BooleanValue` object for boolean `false`.
	 */
	public function testGetByValueReturnsBooleanValueObjectForBooleanFalse() {

		$factory = new DefaultValueFactory();
		$value = $factory->getByValue(false, $this->valueContext, $this->config);

		$this->assertInstanceOf($this->values['bool'], $value);
	}

	/**
	 * Test if the `getByValue` method returns a new `BooleanValue` object for the string representation
	 * of a boolean `true`.
	 */
	public function testGetByValueReturnsBooleanValueObjectForStringRepresentationOfBooleanTrue() {

		$factory = new DefaultValueFactory();
		$value = $factory->getByValue('true', $this->valueContext, $this->config);

		$this->assertInstanceOf($this->values['bool'], $value);
	}

	/**
	 * Test if the `getByValue` method returns a new `BooleanValue` object for the string representation
	 * of a boolean `false`.
	 */
	public function testGetByValueReturnsBooleanValueObjectForStringRepresentationOfBooleanFalse() {

		$factory = new DefaultValueFactory();
		$value = $factory->getByValue('false', $this->valueContext, $this->config);

		$this->assertInstanceOf($this->values['bool'], $value);
	}

	/**
	 * Test if the `getByValue` method returns a new `ObjectValue` object for a object.
	 */
	public function testGetByValueReturnsObjectValueObjectForObject() {

		$object = new stdClass();

		$factory = new DefaultValueFactory();
		$value = $factory->getByValue($object, $this->valueContext, $this->config);

		$this->assertInstanceOf($this->values['object'], $value);
	}

	/**
	 * Test if the `getByValue` method returns a new `ArrayValue` object for a array.
	 */
	public function testGetByValueReturnsArrayValueObjectForArray() {

		$factory = new DefaultValueFactory();
		$value = $factory->getByValue([], $this->valueContext, $this->config);

		$this->assertInstanceOf($this->values['array'], $value);
	}

	/**
	 * Test if the `getByValue` method returns a new `NumberValue` object for an integer.
	 */
	public function testGetByValueReturnsNumberValueObjectForInteger() {

		$factory = new DefaultValueFactory();
		$value = $factory->getByValue(1, $this->valueContext, $this->config);

		$this->assertInstanceOf($this->values['number'], $value);
	}

	/**
	 * Test if the `getByValue` method returns a new `NumberValue` object for a floating point value.
	 */
	public function testGetByValueReturnsNumberValueObjectForFloat() {

		$factory = new DefaultValueFactory();
		$value = $factory->getByValue(1.1, $this->valueContext, $this->config);

		$this->assertInstanceOf($this->values['number'], $value);
	}

	/**
	 * Test if the `getByValue` method returns a new `NumberValue` object for a numeric string.
	 */
	public function testGetByValueReturnsNumberValueObjectForNumericString() {

		$factory = new DefaultValueFactory();
		$value = $factory->getByValue('1.1', $this->valueContext, $this->config);

		$this->assertInstanceOf($this->values['number'], $value);
	}

	/**
	 * Test if the `getByValue` method returns a new `StringValue` object for a string.
	 */
	public function testGetByValueReturnsStringValueObjectForString() {

		$factory = new DefaultValueFactory();
		$value = $factory->getByValue('a string', $this->valueContext, $this->config);

		$this->assertInstanceOf($this->values['string'], $value);
	}

	/**
	 * Test if the `getByValue` method throws `UnexpectedValueException` for an unexpected value.
	 *
	 * @expectedException \com\mohiva\common\exceptions\UnexpectedValueException
	 */
	public function testGetByValueThrowsUnexpectedValueException() {

		$resource = fopen(__FILE__, 'r');
		$factory = new DefaultValueFactory();
		try {
			$factory->getByValue($resource, $this->valueContext, $this->config);
		} catch (UnexpectedValueException $e) {
			fclose($resource);
			throw $e;
		}
	}
}
