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

/**
 * Class which represents a document node token.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Tokens
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class NodeToken implements Token {

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
	 * The id of the ancestor node.
	 *
	 * @var string
	 */
	private $ancestor = null;

	/**
	 * The ID of the node immediately preceding this node.
	 *
	 * @var string
	 */
	private $previousSibling = null;

	/**
	 * The ID of the node immediately following this node.
	 *
	 * @var string
	 */
	private $nextSibling = null;

	/**
	 * The content of the node.
	 *
	 * @var string
	 */
	private $content = null;

	/**
	 * A list with child nodes.
	 *
	 * @var array
	 */
	private $children = array();

	/**
	 * The class constructor.
	 *
	 * @param int $code The token code.
	 * @param string $id The id of the node.
	 * @param string $path The path to this node in the source file.
	 * @param int $line The line number of the node in the source file.
	 * @param string $ancestor The id of the ancestor node.
	 * @param string $previousSibling The ID of the node immediately preceding this node.
	 * @param string $nextSibling The ID of the node immediately following this node.
	 * @param string $content The content of the node.
	 * @param array $children The node children.
	 */
	public function __construct(
		$code,
		$id,
		$path,
		$line,
		$ancestor,
		$previousSibling,
		$nextSibling,
		$content,
		array $children) {

		$this->code = $code;
		$this->id = $id;
		$this->path = $path;
		$this->line = $line;
		$this->ancestor = $ancestor;
		$this->previousSibling = $previousSibling;
		$this->nextSibling = $nextSibling;
		$this->content = $content;
		$this->children = $children;
	}

	/**
	 * Gets the token code.
	 *
	 * @return int The token code.
	 */
	public function getCode() {

		return $this->code;
	}

	/**
	 * Gets the id of the node.
	 *
	 * @return string The id of the node.
	 */
	public function getId() {

		return $this->id;
	}

	/**
	 * Gets the path to this node in the source file.
	 *
	 * @return string The path to this node in the source file.
	 */
	public function getPath() {

		return $this->path;
	}

	/**
	 * Gets the line number of the node in the source file.
	 *
	 * @return int The line number of the node in the source file.
	 */
	public function getLine() {

		return $this->line;
	}

	/**
	 * Gets the id of the ancestor node.
	 *
	 * @return string The id of the ancestor node.
	 */
	public function getAncestor() {

		return $this->ancestor;
	}

	/**
	 * Gets the ID of the node immediately preceding this node.
	 *
	 * @return string The Id of the node immediately preceding this node, or null if there is no such node.
	 */
	public function getPreviousSibling() {

		return $this->previousSibling;
	}

	/**
	 * Gets the ID of the node immediately following this node.
	 *
	 * @return string The Id of the node immediately following this node, or null if there is no such node.
	 */
	public function getNextSibling() {

		return $this->nextSibling;
	}

	/**
	 * Gets the content of the node.
	 *
	 * @return string The content of the node.
	 */
	public function getContent() {

		return $this->content;
	}

	/**
	 * Gets a list with child nodes.
	 *
	 * @return array A list with child nodes.
	 */
	public function getChildren() {

		return $this->children;
	}
}
