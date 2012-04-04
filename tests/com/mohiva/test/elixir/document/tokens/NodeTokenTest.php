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

use com\mohiva\elixir\document\tokens\NodeToken;

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
class NodeTokenTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test all getters for the values set with the constructor.
	 */
	public function testConstructorAccessors() {

		$code = mt_rand(1, 30);
		$id = sha1(microtime(true));
		$path = sha1(microtime(true));
		$line = mt_rand(1, 100);
		$ancestor = sha1(microtime(true));
		$content = sha1(microtime(true));
		$children = array(sha1(microtime(true)));
		$token = new NodeToken(
			$code,
			$id,
			$path,
			$line,
			$ancestor,
			$content,
			$children
		);

		$this->assertSame($code, $token->getCode());
		$this->assertSame($id, $token->getId());
		$this->assertSame($path, $token->getPath());
		$this->assertSame($line, $token->getLine());
		$this->assertSame($ancestor, $token->getAncestor());
		$this->assertSame($content, $token->getContent());
		$this->assertSame($children, $token->getChildren());
	}
}
