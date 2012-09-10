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
namespace com\mohiva\test\elixir\document;

use com\mohiva\elixir\document\Expression;

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
class ExpressionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test all getters for the values set with the constructor.
	 */
	public function testConstructorAccessors() {

		/* @var \com\mohiva\pyramid\Node $node */
		$id = sha1(microtime(true));
		$code = mt_rand(1, 30);
		$line = mt_rand(1, 100);
		$path = sha1(microtime(true));
		$content = sha1(microtime(true));
		$attribute = sha1(microtime(true));
		$node = $this->getMock('\com\mohiva\pyramid\Node');
		$expression = new Expression($id, $code, $line, $path, $content, $attribute, $node);

		$this->assertSame($id, $expression->getId());
		$this->assertSame($code, $expression->getCode());
		$this->assertSame($line, $expression->getLine());
		$this->assertSame($path, $expression->getPath());
		$this->assertSame($content, $expression->getContent());
		$this->assertSame($attribute, $expression->getAttribute());
		$this->assertSame($node, $expression->getNode());
	}
}
