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
 * @package   Mohiva/Elixir/Document
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\document;

use com\mohiva\pyramid\Node as ExpressionNode;
use com\mohiva\elixir\document\expression\Container as ExpressionContainer;

/**
 * Represents a document node. A node can be the root node of a document
 * or a node which contains at least one element helper or many attribute
 * helpers.
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
	 * @var MFXNode
	 */
	private $ancestor = null;
	
	/**
	 * Contains all child nodes of this node.
	 * 
	 * @var array
	 */
	private $children = array();
	
	/**
	 * Contains all helper objects associated with this node.
	 * 
	 * @var array
	 */
	private $helpers = array();
	
	/**
	 * Contains all expressions found in the content of this node.
	 *
	 * @var array
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
	 * @return MFXNode The ancestor node of this node.
	 */
	public function getAncestor() {
		
		return $this->ancestor;
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
	 * @return array All child nodes of this node.
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
	 * @return array All helpers associated with this node.
	 */
	public function getHelpers() {
		
		return $this->helpers;
	}
	
	/**
	 * Adds a expression.
	 * 
	 * @param \com\mohiva\pyramid\Node $expression The expression to add.
	 */
	public function addExpression(ExpressionNode $expression) {
		
		$this->expressions[] = $expression;
	}
	
	/**
	 * Gets the expressions contained in the content of this node.
	 * 
	 * @return array The expressions contained in the content of this node.
	 */
	public function getExpressions() {
		
		return $this->expressions;
	}
}
