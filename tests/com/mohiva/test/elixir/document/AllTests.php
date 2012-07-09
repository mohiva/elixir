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
		$this->addTest(expression\AllTests::suite());
		$this->addTest(helpers\AllTests::suite());
		$this->addTest(tokens\AllTests::suite());
		$this->addTestSuite(__NAMESPACE__ . '\LexerTest');
		$this->addTestSuite(__NAMESPACE__ . '\NodeTest');
		$this->addTestSuite(__NAMESPACE__ . '\NodeTreeTest');
		$this->addTestSuite(__NAMESPACE__ . '\ParserTest');
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
