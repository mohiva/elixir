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
use com\mohiva\manitou\generators\php\PHPRawCode;
use com\mohiva\elixir\document\expression\nodes\OperandNode;
use com\mohiva\common\exceptions\SyntaxErrorException;
use com\mohiva\common\parser\TokenStream;
use com\mohiva\pyramid\Token;
use com\mohiva\pyramid\Parser;
use com\mohiva\pyramid\Grammar;
use com\mohiva\pyramid\Operand;
use com\mohiva\pyramid\Node;

/**
 * Operand which parses the escaping strategy for an expression.
 *
 * This operand parses the escaping strategy for the complete expression. It is only allowed
 * at the beginning of an expression. If it's located at another position, then the operand
 * throws an expression.
 *
 * For the following example, this operand parses only the html strategy. The other escaping
 * strategies are handled by the escape operator.
 *
 * Example:
 * {%|html 'Lucky'|js _ ' ' _ 'Luke'|js _ ' is crazy' %}
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Expression/Operands
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class EscapeOperand implements Operand {

	/**
	 * Returns the identifiers for this operand.
	 *
	 * @return array The identifiers for this operand.
	 */
	public function getIdentifiers() {

		return array(Lexer::T_PIPE);
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

		$this->checkIfAllowed($stream);

		$code = new PHPRawCode();
		$code->openScope('$this->evaluateClosure(function() use ($valueContext, $vars) {');
		$code->addLine('$valueContext = clone $valueContext;');
		$code->addLine('$valueContext->setEscapingStrategy(\'' . $this->parseEscapingStrategy($stream) . '\');');

		$stream->next();
		$parser = new Parser($grammar);
		$node = $parser->parse($stream);

		$code->addLine('return ' . $node->evaluate() . ';');
		$code->closeScope('})');

		return new OperandNode($code->generate());
	}

	/**
	 * Check if the expression escaping strategy is allowed at the current position.
	 *
	 * The escaping strategy for an expression is only allowed at the beginning of an expression.
	 *
	 * @param TokenStream $stream The token stream to parse.
	 * @throws SyntaxErrorException if the strategy is not allowed at the current position.
	 */
	private function checkIfAllowed(TokenStream $stream) {

		if ($stream->getLookahead(-1) == null) {
			return;
		}

		/* @var Token $current */
		$current = $stream->current();
		$near = substr($stream->getSource(), 0, $current->getOffset());
		$message  = "An expression escaping strategy is only allowed at the beginning of an expression; ";
		$message .= "found declaration near: " . $near;

		throw new SyntaxErrorException($message);
	}

	/**
	 * Parse the escaping strategy.
	 *
	 * @param TokenStream $stream The token stream to parse.
	 * @return string The escaping strategy.
	 * @throws SyntaxErrorException if an unexpected token will be found.
	 */
	private function parseEscapingStrategy(TokenStream $stream) {

		$stream->next();
		$stream->expect(array(Lexer::T_NAME), function(Token $current = null) use ($stream) {
			/* @var TokenStream $stream */
			if ($current) {
				$near = substr($stream->getSource(), 0, $current->getOffset());
				$message = "Escaping strategy expected; got `{$current->getValue()}`; near: " . $near;
			} else {
				$near = substr($stream->getSource(), 0, strlen($stream->getSource()));
				$message = "Escaping strategy expected but end of stream reached; near: " . $near;
			}

			throw new SyntaxErrorException($message);
		});

		/* @var \com\mohiva\pyramid\Token $current */
		$current = $stream->current();
		$strategy = $current->getValue();

		return $strategy;
	}
}
