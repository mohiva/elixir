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
 * @package   Mohiva/Elixir/Document/Expression/Operands
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\document\expression\operands;

use com\mohiva\elixir\document\expression\Lexer;
use com\mohiva\elixir\document\expression\nodes\OperandNode;
use com\mohiva\common\exceptions\SyntaxErrorException;
use com\mohiva\common\parser\TokenStream;
use com\mohiva\pyramid\Token;
use com\mohiva\pyramid\Parser;
use com\mohiva\pyramid\Grammar;
use com\mohiva\pyramid\Operand;
use com\mohiva\pyramid\Node;

/**
 * Operand which parses array values.
 *
 * Parses arrays in the form:
 * [1, 2 => 3]
 * [1, 2 => 3, [1, 2]]
 * [1, 2 => 3, {'test': 'test'}]
 * [1 + 1 => 1 + 1]
 * [(1 + 1) => (1 + 1)]
 * [var.key => var.value]
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression/Operands
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class ArrayValueOperand implements Operand {

	/**
	 * Returns the identifiers for this operand.
	 *
	 * @return array The identifiers for this operand.
	 */
	public function getIdentifiers() {

		return array(Lexer::T_OPEN_ARRAY);
	}

	/**
	 * Parse the operand.
	 *
	 * @param Grammar $grammar The grammar of the parser.
	 * @param TokenStream $stream The token stream to parse.
	 * @return Node The node between the parentheses.
	 * @throws SyntaxErrorException if an unexpected token will be found.
	 */
	public function parse(Grammar $grammar, TokenStream $stream) {

		// Opening square bracket
		/* @var \com\mohiva\pyramid\Token $current */
		$current = $stream->current();
		$operand = $current->getValue();

		// Array content
		$operand .= $this->parseArrayContent($grammar, $stream);

		// Closing square bracket
		$stream->expect(array(Lexer::T_CLOSE_ARRAY), function(Token $current = null) use ($stream) {
			/* @var TokenStream $stream */
			if ($current) {
				$near = substr($stream->getSource(), 0, $current->getOffset());
				$message = "Expected `]`; got `{$current->getValue()}`; near: " . $near;
			} else {
				$near = substr($stream->getSource(), 0, strlen($stream->getSource()));
				$message = "Expected `]` but end of stream reached; near: " . $near;
			}

			throw new SyntaxErrorException($message);
		});

		$current = $stream->current();
		$operand .= $current->getValue();

		return new OperandNode($operand);
	}

	/**
	 * Parse all the content between the opening and the closing square brackets.
	 *
	 * @param Grammar $grammar The grammar of the parser.
	 * @param TokenStream $stream The token stream to parse.
	 * @return string The parsed content.
	 * @throws SyntaxErrorException if the content cannot be parsed.
	 */
	private function parseArrayContent(Grammar $grammar, TokenStream $stream) {

		/* @var \com\mohiva\pyramid\Token $current */
		$content = '';
		while (true) {
			try {
				$content .= $this->parseSubExpression($grammar, $stream);
			} catch (SyntaxErrorException $e) {
				$current = $stream->current();
				$offset = $current ? $current->getOffset() : strlen($stream->getSource());
				$near = substr($stream->getSource(), 0, $offset);
				throw new SyntaxErrorException('Syntax error in array definition near: ' . $near, 0, $e);
			}

			$current = $stream->current();
			$currentCode = $current ? $current->getCode() : null;
			if ($currentCode === Lexer::T_COMMA) {
				$content .= $current->getValue() . ' ';
			} else if ($currentCode === Lexer::T_DOUBLE_ARROW) {
				$content .= ' ' . $current->getValue() . ' ';
			} else {
				break;
			}
		}

		return $content;
	}

	/**
	 * Parse the array keys or values.
	 *
	 * @param Grammar $grammar The grammar of the parser.
	 * @param TokenStream $stream The token stream to parse.
	 * @return string The evaluated expression.
	 */
	private function parseSubExpression(Grammar $grammar, TokenStream $stream) {

		$stream->next();
		$parser = new Parser($grammar);
		$node = $parser->parse($stream);

		return $node->evaluate();
	}
}
