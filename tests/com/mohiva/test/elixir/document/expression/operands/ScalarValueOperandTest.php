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
namespace com\mohiva\test\elixir\document\expression\operands;

use com\mohiva\pyramid\Grammar;
use com\mohiva\pyramid\Token;
use com\mohiva\common\parser\TokenStream;
use com\mohiva\elixir\document\expression\Lexer;
use com\mohiva\elixir\document\expression\operands\ScalarValueOperand;

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
class ScalarValueOperandTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * Test if the parse method returns the `OperandNode` with the correct number.
	 */
	public function testParseReturnsNodeWithCorrectNumber() {
		
		$number = (string) mt_rand(1, 100);
		
		$tokenStream = new TokenStream();
		$tokenStream->push(new Token(Lexer::T_VALUE, $number, 1));
		$tokenStream->rewind();
		
		$operand = new ScalarValueOperand();
		$node = $operand->parse(new Grammar, $tokenStream);
		
		$this->assertSame($number, $node->evaluate());
	}
}
