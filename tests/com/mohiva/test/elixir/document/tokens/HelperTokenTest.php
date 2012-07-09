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
namespace com\mohiva\test\elixir\document\tokens;

use com\mohiva\elixir\document\tokens\HelperToken;

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
class HelperTokenTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test all getters for the values set with the constructor.
	 */
	public function testConstructorAccessors() {

		$code = mt_rand(1, 30);
		$id = sha1(microtime(true));
		$path = sha1(microtime(true));
		$line = mt_rand(1, 100);
		$name = sha1(microtime(true));
		$namespace = sha1(microtime(true));
		$token = new HelperToken(
			$code,
			$id,
			$path,
			$line,
			$name,
			$namespace
		);

		$this->assertSame($code, $token->getCode());
		$this->assertSame($id, $token->getId());
		$this->assertSame($path, $token->getPath());
		$this->assertSame($line, $token->getLine());
		$this->assertSame($name, $token->getName());
		$this->assertSame($namespace, $token->getNamespace());
	}

	/**
	 * Test the `setAttributes` and `getAttributes` accessors.
	 */
	public function testAttributeAccessors() {

		$attributes = array(sha1(microtime(true)));

		$token = new HelperToken(0, null, null, 0, null, null, array());
		$token->setAttributes($attributes);

		$this->assertSame($attributes, $token->getAttributes());
	}

	/**
	 * Test the `setValue` and `getValue` accessors.
	 */
	public function testValueAccessors() {

		$value = sha1(microtime(true));

		$token = new HelperToken(0, null, null, 0, null, null, array());
		$token->setValue($value);

		$this->assertSame($value, $token->getValue());
	}
}
