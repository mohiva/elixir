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
namespace com\mohiva\test\elixir\document\expression\nodes;

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
		$this->addTestSuite(__NAMESPACE__ . '\OperandNodeTest');
		$this->addTestSuite(__NAMESPACE__ . '\BinaryAddNodeTest');
		$this->addTestSuite(__NAMESPACE__ . '\BinaryAndNodeTest');
		$this->addTestSuite(__NAMESPACE__ . '\BinaryDivNodeTest');
		$this->addTestSuite(__NAMESPACE__ . '\BinaryEqualNodeTest');
		$this->addTestSuite(__NAMESPACE__ . '\BinaryGreaterEqualNodeTest');
		$this->addTestSuite(__NAMESPACE__ . '\BinaryGreaterNodeTest');
		$this->addTestSuite(__NAMESPACE__ . '\BinaryLessEqualNodeTest');
		$this->addTestSuite(__NAMESPACE__ . '\BinaryLessNodeTest');
		$this->addTestSuite(__NAMESPACE__ . '\BinaryModNodeTest');
		$this->addTestSuite(__NAMESPACE__ . '\BinaryMulNodeTest');
		$this->addTestSuite(__NAMESPACE__ . '\BinaryNotEqualNodeTest');
		$this->addTestSuite(__NAMESPACE__ . '\BinaryOrNodeTest');
		$this->addTestSuite(__NAMESPACE__ . '\BinaryPowerNodeTest');
		$this->addTestSuite(__NAMESPACE__ . '\BinarySubNodeTest');
		$this->addTestSuite(__NAMESPACE__ . '\UnaryNegNodeTest');
		$this->addTestSuite(__NAMESPACE__ . '\UnaryNotNodeTest');
		$this->addTestSuite(__NAMESPACE__ . '\UnaryPosNodeTest');
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
