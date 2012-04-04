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
namespace com\mohiva\test\elixir\document\expression;

use com\mohiva\elixir\document\expression\Lexer;
use com\mohiva\common\parser\TokenStream;

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
class LexerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test the syntax of a mathematical calculation.
	 */
	public function testCalculationSyntax() {

		$lexer = new Lexer(new TokenStream());
		$lexer->scan(' 12+4-1/3 * +0.4 + (-12 + 5^3) ');

		$actual = $this->buildActualTokens($lexer->getStream());
		$expected = array(
			array(Lexer::T_VALUE => '12'),
			array(Lexer::T_PLUS => '+'),
			array(Lexer::T_VALUE => '4'),
			array(Lexer::T_MINUS => '-'),
			array(Lexer::T_VALUE => '1'),
			array(Lexer::T_DIV => '/'),
			array(Lexer::T_VALUE => '3'),
			array(Lexer::T_MUL => '*'),
			array(Lexer::T_PLUS => '+'),
			array(Lexer::T_VALUE => '0.4'),
			array(Lexer::T_PLUS => '+'),
			array(Lexer::T_OPEN_PARENTHESIS => '('),
			array(Lexer::T_MINUS => '-'),
			array(Lexer::T_VALUE => '12'),
			array(Lexer::T_PLUS => '+'),
			array(Lexer::T_VALUE => '5'),
			array(Lexer::T_POWER => '^'),
			array(Lexer::T_VALUE => '3'),
			array(Lexer::T_CLOSE_PARENTHESIS => ')')
		);

		$this->assertSame($expected, $actual);
	}

	/**
	 * Test the syntax of a logical expression.
	 */
	public function testLogicalSyntax() {

		$lexer = new Lexer(new TokenStream());
		$lexer->scan(' 12 > 4 && 0 >= 5 || 34 < 5 && 56 <= 5 || 4 % 2 == 0 && 5 != 4');

		$actual = $this->buildActualTokens($lexer->getStream());
		$expected = array(
			array(Lexer::T_VALUE => '12'),
			array(Lexer::T_GREATER => '>'),
			array(Lexer::T_VALUE => '4'),
			array(Lexer::T_AND => '&&'),
			array(Lexer::T_VALUE => '0'),
			array(Lexer::T_GREATER_EQUAL => '>='),
			array(Lexer::T_VALUE => '5'),
			array(Lexer::T_OR => '||'),
			array(Lexer::T_VALUE => '34'),
			array(Lexer::T_LESS => '<'),
			array(Lexer::T_VALUE => '5'),
			array(Lexer::T_AND => '&&'),
			array(Lexer::T_VALUE => '56'),
			array(Lexer::T_LESS_EQUAL => '<='),
			array(Lexer::T_VALUE => '5'),
			array(Lexer::T_OR => '||'),
			array(Lexer::T_VALUE => '4'),
			array(Lexer::T_MOD => '%'),
			array(Lexer::T_VALUE => '2'),
			array(Lexer::T_EQUAL => '=='),
			array(Lexer::T_VALUE => '0'),
			array(Lexer::T_AND => '&&'),
			array(Lexer::T_VALUE => '5'),
			array(Lexer::T_NOT_EQUAL => '!='),
			array(Lexer::T_VALUE => '4')
		);
		$this->assertSame($expected, $actual);
	}

	/**
	 * Test the syntax for an variable assignment.
	 */
	public function testAssignmentSyntax() {

		$lexer = new Lexer(new TokenStream());
		$lexer->scan(" var = (value == 1) ? 1 : 2 ");

		$actual = $this->buildActualTokens($lexer->getStream());
		$expected = array(
			array(Lexer::T_NAME => 'var'),
			array(Lexer::T_ASSIGN => '='),
			array(Lexer::T_OPEN_PARENTHESIS => '('),
			array(Lexer::T_NAME => 'value'),
			array(Lexer::T_EQUAL => '=='),
			array(Lexer::T_VALUE => '1'),
			array(Lexer::T_CLOSE_PARENTHESIS => ')'),
			array(Lexer::T_QUESTION_MARK => '?'),
			array(Lexer::T_VALUE => '1'),
			array(Lexer::T_COLON => ':'),
			array(Lexer::T_VALUE => '2')
		);
		$this->assertSame($expected, $actual);
	}

	/**
	 * Test the syntax for single quoted strings.
	 */
	public function testSingleQuotedStringSyntax() {

		$lexer = new Lexer(new TokenStream());
		$lexer->scan(" '\\'key\\\\':va\\'l\\'ue\\'' ");

		$actual = $this->buildActualTokens($lexer->getStream());
		$expected = array(
			array(Lexer::T_VALUE => "'\\'key\\\\':va\\'l\\'ue\\''")
		);
		$this->assertSame($expected, $actual);
	}

	/**
	 * Test the syntax for double quoted strings.
	 */
	public function testDoubleQuotedStringSyntax() {

		$lexer = new Lexer(new TokenStream());
		$lexer->scan(' "\"key\":va\"l\"ue\"" ');

		$actual = $this->buildActualTokens($lexer->getStream());
		$expected = array(
			array(Lexer::T_VALUE => '"\"key\":va\"l\"ue\""')
		);
		$this->assertSame($expected, $actual);
	}

	/**
	 * Test the syntax for an array definition.
	 */
	public function testArraySyntax() {

		$lexer = new Lexer(new TokenStream());
		$lexer->scan(' [key1:"val\"ue", 0 : 1, 1, [1,2]] ');

		$actual = $this->buildActualTokens($lexer->getStream());
		$expected = array(
			array(Lexer::T_OPEN_ARRAY => '['),
			array(Lexer::T_NAME => 'key1'),
			array(Lexer::T_COLON => ':'),
			array(Lexer::T_VALUE => '"val\"ue"'),
			array(Lexer::T_COMMA => ','),
			array(Lexer::T_VALUE => '0'),
			array(Lexer::T_COLON => ':'),
			array(Lexer::T_VALUE => '1'),
			array(Lexer::T_COMMA => ','),
			array(Lexer::T_VALUE => '1'),
			array(Lexer::T_COMMA => ','),
			array(Lexer::T_OPEN_ARRAY => '['),
			array(Lexer::T_VALUE => '1'),
			array(Lexer::T_COMMA => ','),
			array(Lexer::T_VALUE => '2'),
			array(Lexer::T_CLOSE_ARRAY => ']'),
			array(Lexer::T_CLOSE_ARRAY => ']'),
		);
		$this->assertSame($expected, $actual);
	}

	/**
	 * Test the syntax of a variable.
	 */
	public function testVariableSyntax() {

		$lexer = new Lexer(new TokenStream());
		$lexer->scan(' my.var.methodCall(1, 2) ');

		$actual = $this->buildActualTokens($lexer->getStream());
		$expected = array(
			array(Lexer::T_NAME => 'my'),
			array(Lexer::T_POINT => '.'),
			array(Lexer::T_NAME => 'var'),
			array(Lexer::T_POINT => '.'),
			array(Lexer::T_NAME => 'methodCall'),
			array(Lexer::T_OPEN_PARENTHESIS => '('),
			array(Lexer::T_VALUE => '1'),
			array(Lexer::T_COMMA => ','),
			array(Lexer::T_VALUE => '2'),
			array(Lexer::T_CLOSE_PARENTHESIS => ')'),
		);
		$this->assertSame($expected, $actual);
	}

	/**
	 * Test the syntax of a class constant.
	 */
	public function testClassConstantSyntax() {

		$lexer = new Lexer(new TokenStream());
		$lexer->scan(' \my\namespace\Class::CONSTANT.methodCall(1, 2) ');

		$actual = $this->buildActualTokens($lexer->getStream());
		$expected = array(
			array(Lexer::T_NS_SEPARATOR => '\\'),
			array(Lexer::T_NAME => 'my'),
			array(Lexer::T_NS_SEPARATOR => '\\'),
			array(Lexer::T_NAME => 'namespace'),
			array(Lexer::T_NS_SEPARATOR => '\\'),
			array(Lexer::T_NAME => 'Class'),
			array(Lexer::T_DOUBLE_COLON=> '::'),
			array(Lexer::T_NAME => 'CONSTANT'),
			array(Lexer::T_POINT => '.'),
			array(Lexer::T_NAME => 'methodCall'),
			array(Lexer::T_OPEN_PARENTHESIS => '('),
			array(Lexer::T_VALUE => '1'),
			array(Lexer::T_COMMA => ','),
			array(Lexer::T_VALUE => '2'),
			array(Lexer::T_CLOSE_PARENTHESIS => ')'),
		);
		$this->assertSame($expected, $actual);
	}

	/**
	 * Test the syntax for string type cast.
	 */
	public function testStringCastSyntax() {

		$lexer = new Lexer(new TokenStream());
		$lexer->scan(' (string) my.var ');

		$actual = $this->buildActualTokens($lexer->getStream());
		$expected = array(
			array(Lexer::T_STRING_CAST => '(string)'),
			array(Lexer::T_NAME => 'my'),
			array(Lexer::T_POINT => '.'),
			array(Lexer::T_NAME => 'var'),
		);
		$this->assertSame($expected, $actual);
	}

	/**
	 * Test the syntax for int type cast.
	 */
	public function testIntCastSyntax() {

		$lexer = new Lexer(new TokenStream());
		$lexer->scan(' (int) my.var ');

		$actual = $this->buildActualTokens($lexer->getStream());
		$expected = array(
			array(Lexer::T_INT_CAST => '(int)'),
			array(Lexer::T_NAME => 'my'),
			array(Lexer::T_POINT => '.'),
			array(Lexer::T_NAME => 'var'),
		);
		$this->assertSame($expected, $actual);
	}

	/**
	 * Test the syntax for float type cast.
	 */
	public function testFloatCastSyntax() {

		$lexer = new Lexer(new TokenStream());
		$lexer->scan(' (float) my.var ');

		$actual = $this->buildActualTokens($lexer->getStream());
		$expected = array(
			array(Lexer::T_FLOAT_CAST => '(float)'),
			array(Lexer::T_NAME => 'my'),
			array(Lexer::T_POINT => '.'),
			array(Lexer::T_NAME => 'var'),
		);
		$this->assertSame($expected, $actual);
	}

	/**
	 * Test the syntax for bool type cast.
	 */
	public function testBoolCastSyntax() {

		$lexer = new Lexer(new TokenStream());
		$lexer->scan(' (bool) my.var ');

		$actual = $this->buildActualTokens($lexer->getStream());
		$expected = array(
			array(Lexer::T_BOOL_CAST => '(bool)'),
			array(Lexer::T_NAME => 'my'),
			array(Lexer::T_POINT => '.'),
			array(Lexer::T_NAME => 'var'),
		);
		$this->assertSame($expected, $actual);
	}

	/**
	 * Test the syntax for xml type cast.
	 */
	public function testXmlCastSyntax() {

		$lexer = new Lexer(new TokenStream());
		$lexer->scan(' (xml) my.var ');

		$actual = $this->buildActualTokens($lexer->getStream());
		$expected = array(
			array(Lexer::T_XML_CAST => '(xml)'),
			array(Lexer::T_NAME => 'my'),
			array(Lexer::T_POINT => '.'),
			array(Lexer::T_NAME => 'var'),
		);
		$this->assertSame($expected, $actual);
	}

	/**
	 * Test the syntax for the ternary operator.
	 */
	public function testTernarySyntax() {

		$lexer = new Lexer(new TokenStream());
		$lexer->scan(' my.var ? "1" : "2" ');

		$actual = $this->buildActualTokens($lexer->getStream());
		$expected = array(
			array(Lexer::T_NAME => 'my'),
			array(Lexer::T_POINT => '.'),
			array(Lexer::T_NAME => 'var'),
			array(Lexer::T_QUESTION_MARK => '?'),
			array(Lexer::T_VALUE => '"1"'),
			array(Lexer::T_COLON => ':'),
			array(Lexer::T_VALUE => '"2"'),
		);
		$this->assertSame($expected, $actual);
	}

	/**
	 * Test the concat token.
	 */
	public function testConcatToken() {

		$lexer = new Lexer(new TokenStream());
		$lexer->scan(' "string" _ 1 ');

		$actual = $this->buildActualTokens($lexer->getStream());
		$expected = array(
			array(Lexer::T_VALUE => '"string"'),
			array(Lexer::T_CONCAT => '_'),
			array(Lexer::T_VALUE => '1')
		);
		$this->assertSame($expected, $actual);
	}

	/**
	 * Test the none token.
	 */
	public function testNoneToken() {

		$lexer = new Lexer(new TokenStream());
		$lexer->scan(' # ');

		$actual = $this->buildActualTokens($lexer->getStream());
		$expected = array(
			array(Lexer::T_NONE => '#'),
		);
		$this->assertSame($expected, $actual);
	}

	/**
	 * Create an array from the token stream which contains only the tokens and the operators/values.
	 *
	 * @param \com\mohiva\common\parser\TokenStream $stream The stream containing the lexer tokens.
	 * @return array The actual list with tokens and operators/values.
	 */
	private function buildActualTokens(TokenStream $stream) {

		$actual = array();
		while ($stream->valid()) {
			/* @var \com\mohiva\pyramid\Token $current */
			$current = $stream->current();
			$stream->next();
			$actual[] = array($current->getCode() => $current->getValue());
		}

		return $actual;
	}
}
