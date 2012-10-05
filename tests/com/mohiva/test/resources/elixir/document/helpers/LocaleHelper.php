<?php

namespace com\mohiva\test\resources\elixir\document\helpers;

use com\mohiva\elixir\document\Node;
use com\mohiva\elixir\document\Compiler;
use com\mohiva\elixir\document\helpers\AttributeHelper;
use com\mohiva\manitou\generators\php\PHPRawCode;

class LocaleHelper extends AttributeHelper {

	/**
	 * This method must generate the body of the helper method which will be
	 * automatically generated for each helper in the compiled Document
	 * class.
	 *
	 * @param Compiler $compiler The compiler which compiles the document in which the helper is located.
	 * @param Node $node The node on which the helper is located.
	 * @return PHPRawCode The body of the helper method.
	 */
	public function compile(Compiler $compiler, Node $node) {

		return new PHPRawCode();
	}
}
