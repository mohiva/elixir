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
namespace com\mohiva\test\elixir\document\expression\operands;

use com\mohiva\pyramid\Token;
use com\mohiva\elixir\document\expression\Lexer;
use com\mohiva\elixir\document\expression\Grammar;
use com\mohiva\elixir\document\expression\operands\ArrayValueOperand;
use com\mohiva\common\parser\TokenStream;

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
class ArrayValueOperandTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test if the parse method can parse a simple indexed array(without key definition) with one value.
	 */
	public function testParseSimpleIndexedArrayWithOneValue() {

		$lexer = new Lexer();
		$tokenStream = $lexer->scan('[1]');

		$operand = new ArrayValueOperand();
		$node = $operand->parse(new Grammar, $tokenStream);

		$this->assertSame('[1]', $node->evaluate());
	}

	/**
	 * Test if the parse method can parse a simple indexed array(without key definition) with multiple values.
	 */
	public function testParseSimpleIndexedArrayWithMultipleValues() {

		$lexer = new Lexer();
		$tokenStream = $lexer->scan('[1, 2, 3, 4, 5]');

		$operand = new ArrayValueOperand();
		$node = $operand->parse(new Grammar, $tokenStream);

		$this->assertSame('[1, 2, 3, 4, 5]', $node->evaluate());
	}

	/**
	 * Test if the parse method can parse indexed array(with key definition) with one value.
	 */
	public function testParseIndexedArrayWithOneValue() {

		$lexer = new Lexer();
		$tokenStream = $lexer->scan('[0 => 1]');

		$operand = new ArrayValueOperand();
		$node = $operand->parse(new Grammar, $tokenStream);

		$this->assertSame('[0 => 1]', $node->evaluate());
	}

	/**
	 * Test if the parse method can parse indexed array(with key definition) with multiple values.
	 */
	public function testParseIndexedArrayWithMultipleValues() {

		$lexer = new Lexer();
		$tokenStream = $lexer->scan('[1 => 10, 2 => 20, 3 => 30, 4 => 40]');

		$operand = new ArrayValueOperand();
		$node = $operand->parse(new Grammar, $tokenStream);

		$this->assertSame('[1 => 10, 2 => 20, 3 => 30, 4 => 40]', $node->evaluate());
	}

	/**
	 * Test if the parse method can parse a nested array.
	 */
	public function testParseNestedArray() {

		$lexer = new Lexer();
		$tokenStream = $lexer->scan('[1 => 2, 3, [1, 2 => 3, [1]], 1]');

		$operand = new ArrayValueOperand();
		$node = $operand->parse(new Grammar, $tokenStream);

		$this->assertSame('[1 => 2, 3, [1, 2 => 3, [1]], 1]', $node->evaluate());
	}

	/**
	 * Test if the parse method can parse a expression without parenthesis inside a array.
	 */
	public function testParseExpressionWithoutParenthesisInsideArray() {

		$lexer = new Lexer();
		$tokenStream = $lexer->scan('[1 + 1 => 5 _ 5]');

		$operand = new ArrayValueOperand();
		$node = $operand->parse(new Grammar, $tokenStream);

		$this->assertSame('[1 + 1 => 5 . 5]', $node->evaluate());
	}

	/**
	 * Test if the parse method can parse a expression with parenthesis inside a array.
	 */
	public function testParseExpressionWithParenthesisInsideArray() {

		$lexer = new Lexer();
		$tokenStream = $lexer->scan('[(1 + 1 * (2 + 4)) => (5 _ 5)]');

		$operand = new ArrayValueOperand();
		$node = $operand->parse(new Grammar, $tokenStream);

		$this->assertSame('[(1 + 1 * (2 + 4)) => (5 . 5)]', $node->evaluate());
	}

	/**
	 * Test if the parse method throws an `SyntaxErrorException` if the value before a comma is missing.
	 *
	 * @expectedException \com\mohiva\common\exceptions\SyntaxErrorException
	 */
	public function testParseThrowExceptionOnMissingValueBeforeComma() {

		$lexer = new Lexer();
		$tokenStream = $lexer->scan('[,1]');

		$operand = new ArrayValueOperand();
		$operand->parse(new Grammar, $tokenStream);
	}

	/**
	 * Test if the parse method throws an `SyntaxErrorException` if the value after a comma is missing.
	 *
	 * @expectedException \com\mohiva\common\exceptions\SyntaxErrorException
	 */
	public function testParseThrowExceptionOnMissingValueAfterComma() {

		$lexer = new Lexer();
		$tokenStream = $lexer->scan('[1,]');

		$operand = new ArrayValueOperand();
		$operand->parse(new Grammar, $tokenStream);
	}

	/**
	 * Test if the parse method throws an `SyntaxErrorException` if the value before a double arrow is missing.
	 *
	 * @expectedException \com\mohiva\common\exceptions\SyntaxErrorException
	 */
	public function testParseThrowExceptionOnMissingValueBeforeDoubleArrow() {

		$lexer = new Lexer();
		$tokenStream = $lexer->scan('[=>1]');

		$operand = new ArrayValueOperand();
		$operand->parse(new Grammar, $tokenStream);
	}

	/**
	 * Test if the parse method throws an `SyntaxErrorException` if the value after a double arrow is missing.
	 *
	 * @expectedException \com\mohiva\common\exceptions\SyntaxErrorException
	 */
	public function testParseThrowExceptionOnMissingValueAfterDoubleArrow() {

		$lexer = new Lexer();
		$tokenStream = $lexer->scan('[1=>]');

		$operand = new ArrayValueOperand();
		$operand->parse(new Grammar, $tokenStream);
	}

	/**
	 * Test if the `parse` method throws an exception if the closing square bracket is missing
	 * and the end of the stream isn't reached.
	 *
	 * @expectedException \com\mohiva\common\exceptions\SyntaxErrorException
	 */
	public function testParseThrowExceptionOnMissingCloserIfEndOfStreamIsNotReached() {

		$lexer = new Lexer();
		$tokenStream = $lexer->scan('[1=>1 1');

		$operand = new ArrayValueOperand();
		$operand->parse(new Grammar, $tokenStream);
	}

	/**
	 * Test if the `parse` method throws an exception if the closing square bracket is missing
	 * and the end of the stream is reached.
	 *
	 * @expectedException \com\mohiva\common\exceptions\SyntaxErrorException
	 */
	public function testParseThrowExceptionOnMissingCloserIfEndOfStreamIsReached() {

		$lexer = new Lexer();
		$tokenStream = $lexer->scan('[1=>1');

		$operand = new ArrayValueOperand();
		$operand->parse(new Grammar, $tokenStream);
	}
}
