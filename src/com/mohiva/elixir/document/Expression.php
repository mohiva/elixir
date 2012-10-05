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

use com\mohiva\pyramid\Node as ExpressionNode;

/**
 * Represents an expression.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class Expression {

	/**
	 * The id of this expression.
	 *
	 * @var string
	 */
	private $id = null;

	/**
	 * The code of the expression token.
	 *
	 * @var int
	 */
	private $code = null;

	/**
	 * Contains the line number of the source file in which the expression is located.
	 *
	 * @var int
	 */
	private $line = null;

	/**
	 * The path to this expression in the source file.
	 *
	 * @var string
	 */
	private $path = null;

	/**
	 * The expression content without the expression opener and closer.
	 *
	 * @var string
	 */
	private $content = null;

	/**
	 * The attribute name or null if the expression wasn't found inside an attribute value.
	 *
	 * @var array
	 */
	private $attribute = null;

	/**
	 * The expression node.
	 *
	 * @var ExpressionNode
	 */
	private $node = null;

	/**
	 * The class constructor.
	 *
	 * @param string $id The id of the expression.
	 * @param int $code The code of the expression token.
	 * @param int $line The line of the source file in which the expression is located.
	 * @param string $path The path to this expression in the source file.
	 * @param string $content The expression content without the expression opener and closer.
	 * @param string $attribute The attribute name or null if the expression wasn't found inside an attribute value.
	 * @param ExpressionNode $node The expression node.
	 */
	public function __construct($id, $code, $line, $path, $content, $attribute, ExpressionNode $node) {

		$this->id = $id;
		$this->code = $code;
		$this->line = $line;
		$this->path = $path;
		$this->content = $content;
		$this->attribute = $attribute;
		$this->node = $node;
	}

	/**
	 * Gets the id of this expression.
	 *
	 * @return string The id of this expression.
	 */
	public function getId() {

		return $this->id;
	}

	/**
	 * Gets the code of the expression token.
	 *
	 * @return int The code of the expression token.
	 */
	public function getCode() {

		return $this->code;
	}

	/**
	 * Gets the line number of the source file in which the expression is located.
	 *
	 * @return int The line number of the source file in which the expression is located.
	 */
	public function getLine() {

		return $this->line;
	}

	/**
	 * Gets the path to this expression in the source file.
	 *
	 * @return string The path to this expression in the source file.
	 */
	public function getPath() {

		return $this->path;
	}

	/**
	 * Gets the expression content without the expression opener and closer.
	 *
	 * @return string The expression content without the expression opener and closer.
	 */
	public function getContent() {

		return $this->content;
	}

	/**
	 * Returns the attribute name or null if the expression wasn't found inside an attribute value.
	 *
	 * @return string The attribute name or null if the expression wasn't found inside an attribute value.
	 */
	public function getAttribute() {

		return $this->attribute;
	}

	/**
	 * Gets the expression node.
	 *
	 * @return ExpressionNode The expression node.
	 */
	public function getNode() {

		return $this->node;
	}
}
