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

use com\mohiva\common\parser\TokenStream;
use com\mohiva\elixir\document\tokens\ExpressionToken;

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
class ExpressionTokenTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test all getters for the values set with the constructor.
	 */
	public function testConstructorAccessors() {

		$code = mt_rand(1, 30);
		$id = sha1(microtime(true));
		$path = sha1(microtime(true));
		$line = mt_rand(1, 100);
		$attribute = sha1(microtime(true));
		$stream = new TokenStream();
		$token = new ExpressionToken(
			$code,
			$id,
			$path,
			$line,
			$attribute,
			$stream
		);

		$this->assertSame($code, $token->getCode());
		$this->assertSame($id, $token->getId());
		$this->assertSame($path, $token->getPath());
		$this->assertSame($line, $token->getLine());
		$this->assertSame($attribute, $token->getAttribute());
		$this->assertSame($stream, $token->getStream());
	}
}
