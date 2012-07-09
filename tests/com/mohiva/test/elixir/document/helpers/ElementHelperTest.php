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
namespace com\mohiva\test\elixir\document\helpers;

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
class ElementHelperTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test all getters for the values set with the constructor.
	 */
	public function testConstructorAccessors() {

		$id = sha1(microtime(true));
		$attributes = array(sha1(microtime(true)));
		$line = mt_rand(1, 100);
		$path = sha1(microtime(true));

		/* @var \com\mohiva\elixir\document\helpers\ElementHelper $helper */
		$helper = $this->getMockForAbstractClass('\com\mohiva\elixir\document\helpers\ElementHelper', array(
			$id,
			$attributes,
			$line,
			$path
		));

		$this->assertSame($id, $helper->getId());
		$this->assertSame($attributes, $helper->getAttributes());
		$this->assertSame($line, $helper->getLine());
		$this->assertSame($path, $helper->getPath());
	}

	/**
	 * Test the `addExpression` and `setExpression` ancestors.
	 */
	public function testExpressionAncestors() {

		/* @var \com\mohiva\elixir\document\helpers\ElementHelper $helper */
		$helper = $this->getMockForAbstractClass(
			'\com\mohiva\elixir\document\helpers\ElementHelper', array(), '', false
		);

		/* @var \com\mohiva\elixir\document\Expression $expression */
		$expression = $this->getMock('\com\mohiva\elixir\document\Expression', array(), array(), '', false);

		$helper->addExpressions(array($expression));

		$this->assertSame(array($expression), $helper->getExpressions());
	}
}
