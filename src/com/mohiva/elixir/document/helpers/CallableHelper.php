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

use com\mohiva\elixir\document\Processor;
use com\mohiva\elixir\document\Variables;

/**
 * Interface for a callable helper class.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Helpers
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
interface CallableHelper {

	/**
	 * Return the id of the helper.
	 *
	 * @return string The id of the helper.
	 */
	public function getId();

	/**
	 * Process the helper.
	 *
	 * @param Processor $processor The compiled Elixir document.
	 * @param string $nodeId The id of the node on which the helper is located.
	 * @param Variables $vars The runtime variables to parse in the node on which the helper is located.
	 */
	public function process(Processor $processor, $nodeId, Variables $vars);
}
