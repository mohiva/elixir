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
 * @package   Mohiva/Elixir/Document/Expression
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\document\expression;

use com\mohiva\pyramid\Grammar as ParserGrammar;
use com\mohiva\pyramid\operators\BinaryOperator;
use com\mohiva\pyramid\operators\UnaryOperator;
use com\mohiva\pyramid\operators\TernaryOperator;
use com\mohiva\elixir\document\expression\nodes\UnaryPosNode;
use com\mohiva\elixir\document\expression\nodes\UnaryNegNode;
use com\mohiva\elixir\document\expression\nodes\UnaryNotNode;
use com\mohiva\elixir\document\expression\nodes\BinaryEscapeNode;
use com\mohiva\elixir\document\expression\nodes\BinaryOrNode;
use com\mohiva\elixir\document\expression\nodes\BinaryAndNode;
use com\mohiva\elixir\document\expression\nodes\BinaryEqualNode;
use com\mohiva\elixir\document\expression\nodes\BinaryNotEqualNode;
use com\mohiva\elixir\document\expression\nodes\BinaryLessNode;
use com\mohiva\elixir\document\expression\nodes\BinaryLessEqualNode;
use com\mohiva\elixir\document\expression\nodes\BinaryGreaterNode;
use com\mohiva\elixir\document\expression\nodes\BinaryGreaterEqualNode;
use com\mohiva\elixir\document\expression\nodes\BinaryAddNode;
use com\mohiva\elixir\document\expression\nodes\BinarySubNode;
use com\mohiva\elixir\document\expression\nodes\BinaryConcatNode;
use com\mohiva\elixir\document\expression\nodes\BinaryMulNode;
use com\mohiva\elixir\document\expression\nodes\BinaryDivNode;
use com\mohiva\elixir\document\expression\nodes\BinaryModNode;
use com\mohiva\elixir\document\expression\nodes\BinaryPowerNode;
use com\mohiva\elixir\document\expression\nodes\TernaryIfNode;
use com\mohiva\elixir\document\expression\operands\ParenthesesOperand;
use com\mohiva\elixir\document\expression\operands\ScalarValueOperand;
use com\mohiva\elixir\document\expression\operands\ArrayValueOperand;
use com\mohiva\elixir\document\expression\operands\NamedValueOperand;
use com\mohiva\elixir\document\expression\operands\EscapeOperand;

/**
 * The parser grammar for the Elixir expressions.
 *
 * Precedence Table
 *
 * Unary
 * ====================================
 * T_NOT,                    80
 * T_PLUS,                  150
 * T_MINUS,                 150
 *
 * Binary
 * ====================================
 * T_ASSIGN,                 10,  RIGHT
 * T_OR,                     30,  LEFT
 * T_AND,                    40,  LEFT
 * T_EQUAL,                  50,  LEFT
 * T_NOT_EQUAL,              50,  LEFT
 * T_LESS,                   50,  LEFT
 * T_LESS_EQUAL,             50,  LEFT
 * T_GREATER,                50,  LEFT
 * T_GREATER_EQUAL,          50,  LEFT
 * T_PLUS,                   60,  LEFT
 * T_MINUS,                  60,  LEFT
 * T_CONCAT                  70,  LEFT
 * T_MUL,                    90,  LEFT
 * T_DIV,                    90,  LEFT
 * T_MOD,                    90,  LEFT
 * T_POWER,                 100,  RIGHT
 * T_PIPE                   110,  LEFT
 *
 * Ternary
 * ====================================
 * T_QUESTION_MARK, T_COLON, 20,  RIGHT
 *
 * Note: unary +/- operators must have higher precedence as all binary operators
 * http://www.antlr.org/pipermail/antlr-dev/2009-April/002255.html
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class Grammar extends ParserGrammar {

	/**
	 * Creates the grammar.
	 */
	public function __construct() {

		parent::__construct();

		$this->addOperator(new UnaryOperator(Lexer::T_NOT, 80,
			function($node) { return new UnaryNotNode($node); }
		));
		$this->addOperator(new UnaryOperator(Lexer::T_PLUS, 150,
			function($node) { return new UnaryPosNode($node); }
		));
		$this->addOperator(new UnaryOperator(Lexer::T_MINUS, 150,
			function($node) { return new UnaryNegNode($node); }
		));
		$this->addOperator(new BinaryOperator(Lexer::T_OR, 30, BinaryOperator::LEFT,
			function($left, $right) { return new BinaryOrNode($left, $right); }
		));
		$this->addOperator(new BinaryOperator(Lexer::T_AND, 40, BinaryOperator::LEFT,
			function($left, $right) { return new BinaryAndNode($left, $right); }
		));
		$this->addOperator(new BinaryOperator(Lexer::T_EQUAL, 50, BinaryOperator::LEFT,
			function($left, $right) { return new BinaryEqualNode($left, $right); }
		));
		$this->addOperator(new BinaryOperator(Lexer::T_NOT_EQUAL, 50, BinaryOperator::LEFT,
			function($left, $right) { return new BinaryNotEqualNode($left, $right); }
		));
		$this->addOperator(new BinaryOperator(Lexer::T_LESS, 50, BinaryOperator::LEFT,
			function($left, $right) { return new BinaryLessNode($left, $right); }
		));
		$this->addOperator(new BinaryOperator(Lexer::T_LESS_EQUAL, 50, BinaryOperator::LEFT,
			function($left, $right) { return new BinaryLessEqualNode($left, $right); }
		));
		$this->addOperator(new BinaryOperator(Lexer::T_GREATER, 50, BinaryOperator::LEFT,
			function($left, $right) { return new BinaryGreaterNode($left, $right); }
		));
		$this->addOperator(new BinaryOperator(Lexer::T_GREATER_EQUAL, 50, BinaryOperator::LEFT,
			function($left, $right) { return new BinaryGreaterEqualNode($left, $right); }
		));
		$this->addOperator(new BinaryOperator(Lexer::T_PLUS, 60, BinaryOperator::LEFT,
			function($left, $right) { return new BinaryAddNode($left, $right); }
		));
		$this->addOperator(new BinaryOperator(Lexer::T_MINUS, 60, BinaryOperator::LEFT,
			function($left, $right) { return new BinarySubNode($left, $right); }
		));
		$this->addOperator(new BinaryOperator(Lexer::T_CONCAT, 70, BinaryOperator::LEFT,
			function($left, $right) { return new BinaryConcatNode($left, $right); }
		));
		$this->addOperator(new BinaryOperator(Lexer::T_MUL, 90, BinaryOperator::LEFT,
			function($left, $right) { return new BinaryMulNode($left, $right); }
		));
		$this->addOperator(new BinaryOperator(Lexer::T_DIV, 90, BinaryOperator::LEFT,
			function($left, $right) { return new BinaryDivNode($left, $right); }
		));
		$this->addOperator(new BinaryOperator(Lexer::T_MOD, 90, BinaryOperator::LEFT,
			function($left, $right) { return new BinaryModNode($left, $right); }
		));
		$this->addOperator(new BinaryOperator(Lexer::T_POWER, 100, BinaryOperator::RIGHT,
			function($left, $right) { return new BinaryPowerNode($left, $right); }
		));
		$this->addOperator(new BinaryOperator(Lexer::T_PIPE, 110, BinaryOperator::LEFT,
			function($left, $right) { return new BinaryEscapeNode($left, $right); }
		));
		$this->addOperator(new TernaryOperator(Lexer::T_QUESTION_MARK, Lexer::T_COLON, 20, TernaryOperator::RIGHT, true,
			function($condition, $if, $else) { return new TernaryIfNode($condition, $if, $else); }
		));

		$this->addOperand(new ParenthesesOperand());
		$this->addOperand(new ScalarValueOperand());
		$this->addOperand(new ArrayValueOperand());
		$this->addOperand(new NamedValueOperand());
		$this->addOperand(new EscapeOperand());
	}
}
