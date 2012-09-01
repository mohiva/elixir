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

use com\mohiva\elixir\document\expression\ValueContext;

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
class ValueContextTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test the `setContext` and `getContext` accessors.
	 */
	public function testContextAccessors() {

		$context = new ValueContext();
		$context->setContext(ValueContext::DOC);

		$this->assertSame(ValueContext::DOC, $context->getContext());
	}

	/**
	 * Test the `setEscapingStrategy` and `getEscapingStrategy` accessors.
	 */
	public function testEscapingStrategyAccessors() {

		$strategy = sha1(microtime(true));

		$context = new ValueContext();
		$context->setEscapingStrategy($strategy);

		$this->assertSame($strategy, $context->getEscapingStrategy());
	}
}
