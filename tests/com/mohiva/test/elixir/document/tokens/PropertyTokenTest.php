<?php
/**
 * Mohiva Elixir
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.textile.
 * It is also available through the world-wide-web at this URL:
 * https://github.com/mohiva/pyramid/blob/master/LICENSE.textile
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Test
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\test\elixir\document\tokens;

use com\mohiva\elixir\document\tokens\PropertyToken;

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
class PropertyTokenTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * Test all getters for the values set with the constructor.
	 */
	public function testConstructorAccessors() {
		
		$code = mt_rand(1, 30);
		$value = sha1(microtime(true));
		$token = new PropertyToken(
			$code,
			$value
		);
		
		$this->assertSame($code, $token->getCode());
		$this->assertSame($value, $token->getValue());
	}
}
