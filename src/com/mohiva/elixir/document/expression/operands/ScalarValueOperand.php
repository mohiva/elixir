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
 * @package   Mohiva/Elixir/Document/Expression/Operands
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\document\expression\operands;

use com\mohiva\elixir\document\expression\Lexer;
use com\mohiva\elixir\document\expression\nodes\OperandNode;
use com\mohiva\common\parser\TokenStream;
use com\mohiva\pyramid\Grammar;
use com\mohiva\pyramid\Operand;
use com\mohiva\pyramid\Node;

/**
 * Operand which parses scalar values.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression/Operands
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class ScalarValueOperand implements Operand {

	/**
	 * Returns the identifiers for this operand.
	 *
	 * @return array The identifiers for this operand.
	 */
	public function getIdentifiers() {

		return array(Lexer::T_VALUE);
	}

	/**
	 * Parse the operand.
	 *
	 * This example shows how you should parse sub expressions. You must only create a
	 * new parser with the passed grammar and token stream.
	 *
	 * @param Grammar $grammar The grammar of the parser.
	 * @param TokenStream $stream The token stream to parse.
	 * @return Node The node between the parentheses.
	 */
	public function parse(Grammar $grammar, TokenStream $stream) {

		/* @var \com\mohiva\pyramid\Token $current */
		$current = $stream->current();
		$node = new OperandNode($current->getValue());

		return $node;
	}
}
