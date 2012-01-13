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
use com\mohiva\elixir\document\expression\nodes\UnaryPosNode;
use com\mohiva\elixir\document\expression\nodes\UnaryNegNode;
use com\mohiva\elixir\document\expression\nodes\BinaryAddNode;
use com\mohiva\elixir\document\expression\nodes\BinarySubNode;
use com\mohiva\elixir\document\expression\nodes\BinaryMulNode;
use com\mohiva\elixir\document\expression\nodes\BinaryDivNode;
use com\mohiva\elixir\document\expression\nodes\BinaryModNode;
use com\mohiva\elixir\document\expression\nodes\BinaryPowerNode;
use com\mohiva\elixir\document\expression\operands\ParenthesesOperand;
use com\mohiva\elixir\document\expression\operands\ScalarValueOperand;

/**
 * The parser grammar for the Elixir expressions.
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
		
		// Note: unary +/- operators must have higher precedence as all binary operators
		// http://www.antlr.org/pipermail/antlr-dev/2009-April/002255.html
		$this->addOperator(new UnaryOperator(Lexer::T_PLUS, 3, function($node) {
			return new UnaryPosNode($node);
		}));
		$this->addOperator(new UnaryOperator(Lexer::T_MINUS, 3, function($node) {
			return new UnaryNegNode($node);
		}));
		$this->addOperator(new BinaryOperator(Lexer::T_PLUS, 0, BinaryOperator::LEFT, function($left, $right) {
			return new BinaryAddNode($left, $right);
		}));
		$this->addOperator(new BinaryOperator(Lexer::T_MINUS, 0, BinaryOperator::LEFT, function($left, $right) {
			return new BinarySubNode($left, $right);
		}));
		$this->addOperator(new BinaryOperator(Lexer::T_MUL, 1, BinaryOperator::LEFT, function($left, $right) {
			return new BinaryMulNode($left, $right);
		}));
		$this->addOperator(new BinaryOperator(Lexer::T_DIV, 1, BinaryOperator::LEFT, function($left, $right) {
			return new BinaryDivNode($left, $right);
		}));
		$this->addOperator(new BinaryOperator(Lexer::T_MOD, 1, BinaryOperator::LEFT, function($left, $right) {
			return new BinaryModNode($left, $right);
		}));
		$this->addOperator(new BinaryOperator(Lexer::T_POWER, 2, BinaryOperator::RIGHT, function($left, $right) {
			return new BinaryPowerNode($left, $right);
		}));
		
		$this->addOperand(new ParenthesesOperand());
		$this->addOperand(new ScalarValueOperand());
		
		
		/*
		$table = new OperatorTable();
		$table->addOperator(new UnaryOperator(Lexer::O_NOT, 60, function($left, $right) {
			return new UnaryNotNode($left, $right);
		}));
		$table->addOperator(new UnaryOperator(Lexer::O_PLUS,        70));
		$table->addOperator(new UnaryOperator(Lexer::O_MINUS,       70));
		$table->addOperator(new UnaryOperator(Lexer::O_STRING_CAST, 70));
		$table->addOperator(new UnaryOperator(Lexer::O_INT_CAST,    70));
		$table->addOperator(new UnaryOperator(Lexer::O_FLOAT_CAST,  70));
		$table->addOperator(new UnaryOperator(Lexer::O_BOOL_CAST,   70));
		$table->addOperator(new UnaryOperator(Lexer::O_XML_CAST,    70));
		
		$table->addOperator(new BinaryOperator(Lexer::O_OR,            10, BinaryOperator::LEFT));
		$table->addOperator(new BinaryOperator(Lexer::O_AND,           20, BinaryOperator::LEFT));
		$table->addOperator(new BinaryOperator(Lexer::O_ASSIGN,        30, BinaryOperator::LEFT));
		$table->addOperator(new BinaryOperator(Lexer::O_EQUAL,         40, BinaryOperator::LEFT));
		$table->addOperator(new BinaryOperator(Lexer::O_NOT_EQUAL,     40, BinaryOperator::LEFT));
		$table->addOperator(new BinaryOperator(Lexer::O_LESS,          40, BinaryOperator::LEFT));
		$table->addOperator(new BinaryOperator(Lexer::O_LESS_EQUAL,    40, BinaryOperator::LEFT));
		$table->addOperator(new BinaryOperator(Lexer::O_GREATER,       40, BinaryOperator::LEFT));
		$table->addOperator(new BinaryOperator(Lexer::O_GREATER_EQUAL, 40, BinaryOperator::LEFT));
		$table->addOperator(new BinaryOperator(Lexer::O_PLUS,          50, BinaryOperator::LEFT));
		$table->addOperator(new BinaryOperator(Lexer::O_MINUS,         50, BinaryOperator::LEFT));
		$table->addOperator(new BinaryOperator(Lexer::O_MUL,           80, BinaryOperator::LEFT));
		$table->addOperator(new BinaryOperator(Lexer::O_DIV,           80, BinaryOperator::LEFT));
		$table->addOperator(new BinaryOperator(Lexer::O_MOD,           80, BinaryOperator::LEFT));
		$table->addOperator(new BinaryOperator(Lexer::O_POWER,         90, BinaryOperator::RIGHT));
		
		return $table;*/
	}
}
