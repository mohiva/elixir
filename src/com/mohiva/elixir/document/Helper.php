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

use com\mohiva\elixir\document\Compiler;
use com\mohiva\elixir\document\Node;
use com\mohiva\elixir\document\expression\Container;
use com\mohiva\manitou\generators\php\PHPRawCode;

/**
 * Document helper interface.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
interface Helper extends Container {

	/**
	 * Gets the id of the helper.
	 *
	 * @return string The id of the helper.
	 */
	public function getId();

	/**
	 * Gets the line number of the source file in which the helper is located.
	 *
	 * @return int The line number of the source file in which the helper is located.
	 */
	public function getLine();

	/**
	 * Gets the path to this helper in the source file.
	 *
	 * @return string The path to this helper in the source file.
	 */
	public function getPath();

	/**
	 * This method must generate the body of the helper method which will be
	 * automatically generated for each helper in the compiled Document
	 * class.
	 *
	 * @param Compiler $compiler The compiler which compiles the document in which the helper is located.
	 * @param Node $node The node on which the helper is located.
	 * @return PHPRawCode The body of the helper method.
	 */
	public function compile(Compiler $compiler, Node $node);
}
