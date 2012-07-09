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
 * @package   Mohiva/Elixir/Document
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\document;

use com\mohiva\elixir\document\expression\Container as ExpressionContainer;

/**
 * Represents a document node. A node can be the root node of a document
 * or a node which contains at least one element helper or at least one
 * attribute helpers.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class Node implements ExpressionContainer {

	/**
	 * The id of this node.
	 *
	 * @var string
	 */
	private $id = null;

	/**
	 * The code of the node token.
	 *
	 * @var int
	 */
	private $code = null;

	/**
	 * The content of this node. Typically this contains all XML tags with
	 * the placeholders for the variables or constants and the child nodes.
	 *
	 * @var string
	 */
	private $content = null;

	/**
	 * Contains the line number of the source file in which the node is located.
	 *
	 * @var int
	 */
	private $line = null;

	/**
	 * The path to this node in the source file.
	 *
	 * @var string
	 */
	private $path = null;

	/**
	 * Contains the ancestor node of this node.
	 *
	 * @var Node
	 */
	private $ancestor = null;

	/**
	 * The node immediately preceding this node.
	 *
	 * @var Node
	 */
	private $previousSibling = null;

	/**
	 * The node immediately following this node.
	 *
	 * @var Node
	 */
	private $nextSibling = null;

	/**
	 * Contains all child nodes of this node.
	 *
	 * @var Node[]
	 */
	private $children = array();

	/**
	 * Contains all helper objects associated with this node.
	 *
	 * @var Helper[]
	 */
	private $helpers = array();

	/**
	 * Contains all expressions found in the content of this node.
	 *
	 * @var Expression[]
	 */
	private $expressions = array();

	/**
	 * The class constructor.
	 *
	 * @param string $id The id of the node.
	 * @param int $code The code of the node token.
	 * @param string $content The content of this node.
	 * @param int $line The line of the source file in which the node is located.
	 * @param string $path The path to this node in the source file.
	 */
	public function __construct($id, $code, $content, $line, $path) {

		$this->id = $id;
		$this->code = $code;
		$this->content = $content;
		$this->line = $line;
		$this->path = $path;
	}

	/**
	 * Gets the id of this node.
	 *
	 * @return string The id of this node.
	 */
	public function getId() {

		return $this->id;
	}

	/**
	 * Gets the code of the node token.
	 *
	 * @return int The code of the node token.
	 */
	public function getCode() {

		return $this->code;
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
	 * Gets the line number of the source file in which the node is located.
	 *
	 * @return int The line number of the source file in which the node is located.
	 */
	public function getLine() {

		return $this->line;
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
	 * Sets the ancestor node of this node.
	 *
	 * @param Node $node The ancestor node of this node.
	 */
	public function setAncestor(Node $node) {

		$this->ancestor = $node;
	}

	/**
	 * Gets the ancestor node of this node.
	 *
	 * @return Node The ancestor node of this node.
	 */
	public function getAncestor() {

		return $this->ancestor;
	}

	/**
	 * Sets the node immediately preceding this node.
	 *
	 * @param Node $previousSibling The node immediately preceding this node.
	 */
	public function setPreviousSibling(Node $previousSibling) {

		$this->previousSibling = $previousSibling;
	}

	/**
	 * Gets the node immediately preceding this node.
	 *
	 * @return Node The node immediately preceding this node, or null if there is no such node.
	 */
	public function getPreviousSibling() {

		return $this->previousSibling;
	}

	/**
	 * Sets the node immediately following this node.
	 *
	 * @param Node $nextSibling The node immediately following this node.
	 */
	public function setNextSibling(Node $nextSibling) {

		$this->nextSibling = $nextSibling;
	}

	/**
	 * Gets the node immediately following this node.
	 *
	 * @return Node The node immediately following this node, or null if there is no such node.
	 */
	public function getNextSibling() {

		return $this->nextSibling;
	}

	/**
	 * Adds a child node to this node.
	 *
	 * @param Node $node A child node of this node.
	 */
	public function addChild(Node $node) {

		$this->children[] = $node;
	}

	/**
	 * Gets all child nodes of this node.
	 *
	 * @return Node[] All child nodes of this node.
	 */
	public function getChildren() {

		return $this->children;
	}

	/**
	 * Adds a associated helper.
	 *
	 * @param Helper $helper A associated helper.
	 */
	public function addHelper(Helper $helper) {

		$this->helpers[] = $helper;
	}

	/**
	 * Gets all helpers associated with this node.
	 *
	 * @return Helper[] All helpers associated with this node.
	 */
	public function getHelpers() {

		return $this->helpers;
	}

	/**
	 * Adds a list with found expressions to the existing expression list.
	 *
	 * @param Expression[] $expressions The list with found expressions.
	 */
	public function addExpressions(array $expressions) {

		$this->expressions = array_merge($this->expressions, $expressions);
	}

	/**
	 * Gets the list with found expressions.
	 *
	 * @return Expression[] The list with found expressions.
	 */
	public function getExpressions() {

		return $this->expressions;
	}
}
