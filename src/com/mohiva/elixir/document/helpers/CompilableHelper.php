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
 * @package   Mohiva/Elixir/Document/Helpers
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\document\helpers;

use com\mohiva\elixir\document\Compiler;
use com\mohiva\elixir\document\Node;
use com\mohiva\manitou\generators\php\PHPRawCode;

/**
 * Interface for a compilable helper class.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Helpers
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
interface CompilableHelper {

	/**
	 * Return the id of the helper.
	 *
	 * @return string The id of the helper.
	 */
	public function getId();

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
