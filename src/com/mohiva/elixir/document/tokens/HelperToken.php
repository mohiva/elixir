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
class HelperToken implements Token {

	/**
	 * The token code.
	 *
	 * @var int
	 */
	private $code = null;

	/**
	 * The id of the helper.
	 *
	 * @var int
	 */
	private $id = null;

	/**
	 * The path to this helper in the source file.
	 *
	 * @var string
	 */
	private $path = null;

	/**
	 * The line number of the helper in the source file.
	 *
	 * @var int
	 */
	private $line = null;

	/**
	 * The name of the helper.
	 *
	 * @var string
	 */
	private $name = null;

	/**
	 * The helper namespace.
	 *
	 * @var string
	 */
	private $namespace = null;

	/**
	 * The attributes for a element helper.
	 *
	 * @var array
	 */
	private $attributes = array();

	/**
	 * The value for the attribute helper.
	 *
	 * @var string
	 */
	private $value = null;

	/**
	 * The class constructor.
	 *
	 * @param int $code The token code.
	 * @param string $id The id of the node.
	 * @param string $path The path to this node in the source file.
	 * @param int $line The line number of the node in the source file.
	 * @param string $name The name of the helper.
	 * @param string $namespace The helper namespace.
	 */
	public function __construct(
		$code,
		$id,
		$path,
		$line,
		$name,
		$namespace) {

		$this->code = $code;
		$this->id = $id;
		$this->path = $path;
		$this->line = $line;
		$this->name = $name;
		$this->namespace = $namespace;
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
	 * Returns the name of the helper.
	 *
	 * @return string The name of the helper.
	 */
	public function getName() {

		return $this->name;
	}

	/**
	 * Returns the helper namespace.
	 *
	 * @return string The helper namespace.
	 */
	public function getNamespace() {

		return $this->namespace;
	}

	/**
	 * Sets the attributes for a element helper.
	 *
	 * @param array $attributes The attributes for a element helper.
	 */
	public function setAttributes(array $attributes) {

		$this->attributes = $attributes;
	}

	/**
	 * Returns the attributes for a element helper.
	 *
	 * @return array The attributes for a element helper.
	 */
	public function getAttributes() {

		return $this->attributes;
	}

	/**
	 * Sets the value for the attribute helper.
	 *
	 * @param string $value The value for the attribute helper.
	 */
	public function setValue($value) {

		$this->value = $value;
	}

	/**
	 * Returns the value for the attribute helper.
	 *
	 * @return string The value for the attribute helper.
	 */
	public function getValue() {

		return $this->value;
	}
}
