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

use com\mohiva\elixir\document\expression\encoders\XMLEncoder;

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
class XMLEncoderTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test if the `encode` method doesn't double encode a value.
	 */
	public function testEncoderDoesNotDoubleEncode() {

		$encoder = new XMLEncoder();

		$this->assertSame('&amp;', $encoder->encode('&amp;', 'UTF-8'));
	}

	/**
	 * Test if the `encode` method encodes the ampersand into &amp;.
	 */
	public function testEncodeAmpersand() {

		$encoder = new XMLEncoder();

		$this->assertSame('&amp;', $encoder->encode('&', 'UTF-8'));
	}
}
