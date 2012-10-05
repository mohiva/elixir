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
 * Operand which parses expressions between parentheses.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression/Operands
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class ParenthesesOperand implements Operand {

	/**
	 * Returns the identifiers for this operand.
	 *
	 * @return array The identifiers for this operand.
	 */
	public function getIdentifiers() {

		return array(Lexer::T_OPEN_PARENTHESIS);
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

		$stream->next();

		$parser = new Parser($grammar);
		$node = $parser->parse($stream);

		$stream->expect(array(Lexer::T_CLOSE_PARENTHESIS), function(Token $current = null) use ($stream) {
			/* @var TokenStream $stream */
			if ($current) {
				$near = substr($stream->getSource(), 0, $current->getOffset());
				$message = "Expected `)`; got `{$current->getValue()}`; near: " . $near;
			} else {
				$near = substr($stream->getSource(), 0, strlen($stream->getSource()));
				$message = "Expected `)` but end of stream reached; near: " . $near;
			}

			throw new SyntaxErrorException($message);
		});

		return new OperandNode('(' . $node->evaluate() . ')');
	}
}
