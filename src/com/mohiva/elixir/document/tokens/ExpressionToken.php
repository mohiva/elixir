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
 * @package   Mohiva/Elixir/Document/Tokens
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\document\tokens;

use com\mohiva\common\parser\Token;
use com\mohiva\common\parser\TokenStream;

/**
 * Class which represents a document expression token.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Tokens
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class ExpressionToken implements Token {

	/**
	 * The token code.
	 *
	 * @var int
	 */
	private $code = null;

	/**
	 * The id of the node.
	 *
	 * @var int
	 */
	private $id = null;

	/**
	 * The path to this node in the source file.
	 *
	 * @var string
	 */
	private $path = null;

	/**
	 * The line number of the node in the source file.
	 *
	 * @var int
	 */
	private $line = null;

	/**
	 * The attribute name or null if the expression wasn't found inside an attribute value.
	 *
	 * @var array
	 */
	private $attribute = null;

	/**
	 * The token stream for the found expression[s].
	 *
	 * @var TokenStream
	 */
	private $stream = null;

	/**
	 * The class constructor.
	 *
	 * @param int $code The token code.
	 * @param string $id The id of the node.
	 * @param string $path The path to this node in the source file.
	 * @param int $line The line number of the node in the source file.
	 * @param string $attribute The attribute name or null if the expression wasn't found inside an attribute value.
	 * @param TokenStream $stream The token stream for the found expression[s].
	 */
	public function __construct(
		$code,
		$id,
		$path,
		$line,
		$attribute,
		TokenStream $stream) {

		$this->code = $code;
		$this->id = $id;
		$this->path = $path;
		$this->line = $line;
		$this->attribute = $attribute;
		$this->stream = $stream;
	}

	/**
	 * Returns the token code.
	 *
	 * @return int The token code.
	 */
	public function getCode() {

		return $this->code;
	}

	/**
	 * Returns the id of the node.
	 *
	 * @return string The id of the node.
	 */
	public function getId() {

		return $this->id;
	}

	/**
	 * Returns the path to this node in the source file.
	 *
	 * @return string The path to this node in the source file.
	 */
	public function getPath() {

		return $this->path;
	}

	/**
	 * Returns the line number of the node in the source file.
	 *
	 * @return int The line number of the node in the source file.
	 */
	public function getLine() {

		return $this->line;
	}

	/**
	 * Returns the attribute name or null if the expression wasn't found inside an attribute value.
	 *
	 * @return array The attribute name or null if the expression wasn't found inside an attribute value.
	 */
	public function getAttribute() {

		return $this->attribute;
	}

	/**
	 * Returns the token stream for the expression.
	 *
	 * @return TokenStream The token stream for the expression.
	 */
	public function getStream() {

		return $this->stream;
	}
}
