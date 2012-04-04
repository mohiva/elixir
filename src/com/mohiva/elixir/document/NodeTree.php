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

/**
 * Contains the documents parser result.
 *
 * The root node of this class is the root of the complete document tree. It
 * consists of many `DocumentNode` objects which are all ancestors or children
 * of an other node in the tree.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class NodeTree {

	/**
	 * The XML version.
	 *
	 * @var string
	 */
	protected $version = null;

	/**
	 * The XML encoding.
	 *
	 * @var string
	 */
	protected $encoding = null;

	/**
	 * The root of the node tree.
	 *
	 * @var Node
	 */
	protected $root = null;

	/**
	 * Sets the documents XML version.
	 *
	 * @param string $version The XML version.
	 */
	public function setVersion($version) {

		$this->version = $version;
	}

	/**
	 * Gets the documents XML version.
	 *
	 * @return string The XML version.
	 */
	public function getVersion() {

		return $this->version;
	}

	/**
	 * Sets the documents XML encoding.
	 *
	 * @param string $encoding The XML encoding.
	 */
	public function setEncoding($encoding) {

		$this->encoding = $encoding;
	}

	/**
	 * Gets the documents XML encoding.
	 *
	 * @return string The XML encoding.
	 */
	public function getEncoding() {

		return $this->encoding;
	}

	/**
	 * Sets the root of the node tree.
	 *
	 * @param Node $root The root of the node tree.
	 */
	public function setRoot(Node $root) {

		$this->root = $root;
	}

	/**
	 * Return the root of the node tree.
	 *
	 * @return Node The root of the node tree.
	 */
	public function getRoot() {

		return $this->root;
	}
}
