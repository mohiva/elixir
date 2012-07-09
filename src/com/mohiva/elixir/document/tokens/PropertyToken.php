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
 * Class which represents a document property token.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document/Tokens
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class PropertyToken implements Token {

	/**
	 * The token code.
	 *
	 * @var int
	 */
	private $code = null;

	/**
	 * The token value.
	 *
	 * @var string
	 */
	private $value = null;

	/**
	 * The class constructor.
	 *
	 * @param int $code The token code.
	 * @param string $value The token offset.
	 */
	public function __construct($code, $value) {

		$this->code = $code;
		$this->value = $value;
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
	 * Returns the token value.
	 *
	 * @return string The token value.
	 */
	public function getValue() {

		return $this->value;
	}
}
