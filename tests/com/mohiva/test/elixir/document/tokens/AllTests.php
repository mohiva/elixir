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

/**
 * Test suite for the Mohiva Elixir project.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Test
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class AllTests extends \PHPUnit_Framework_TestSuite {

	/**
	 * Constructs the test suite handler.
	 */
	public function __construct() {

		$this->setName(__CLASS__);
		$this->addTestSuite(__NAMESPACE__ . '\NodeTokenTest');
		$this->addTestSuite(__NAMESPACE__ . '\PropertyTokenTest');
		$this->addTestSuite(__NAMESPACE__ . '\HelperTokenTest');
		$this->addTestSuite(__NAMESPACE__ . '\ExpressionTokenTest');
	}

	/**
	 * Creates the suite.
	 *
	 * @return AllTests The test suite.
	 */
	public static function suite() {

		return new self();
	}
}
