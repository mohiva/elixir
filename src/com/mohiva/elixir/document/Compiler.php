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

use com\mohiva\manitou\generators\php\PHPUse;
use com\mohiva\manitou\generators\php\PHPParameter;
use com\mohiva\manitou\generators\php\PHPValue;
use com\mohiva\manitou\generators\php\PHPRawCode;
use com\mohiva\manitou\generators\php\PHPDocBlock;
use com\mohiva\manitou\generators\php\PHPProperty;
use com\mohiva\manitou\generators\php\PHPMember;
use com\mohiva\manitou\generators\php\PHPMethod;
use com\mohiva\manitou\generators\php\PHPClass;
use com\mohiva\manitou\generators\php\PHPFile;
use com\mohiva\manitou\generators\php\PHPNamespace;
use com\mohiva\elixir\document\helpers\AttributeHelper;
use com\mohiva\elixir\document\helpers\ElementHelper;
use com\mohiva\elixir\document\exceptions\UnexpectedHelperTypeException;
use com\mohiva\elixir\document\exceptions\IDAlreadyRegisteredException;

/**
 * Compiles the node three into plain PHP code.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class Compiler {

	/**
	 * The instance of the file generator class.
	 *
	 * @var PHPFile
	 */
	private $file = null;

	/**
	 * The instance of the namespace generator class.
	 *
	 * @var PHPNamespace
	 */
	private $namespace = null;

	/**
	 * The instance of the class generator class.
	 *
	 * @var PHPClass
	 */
	private $class = null;

	/**
	 * Map block IDs with their node IDs.
	 *
	 * @var array
	 */
	private $blockMap = array();

	/**
	 * The helper stack for every node.
	 *
	 * @var array
	 */
	private $helperStack = array();

	/**
	 * The class constructor.
	 *
	 * @param string $namespace The namespace for the compiled class.
	 * @param string $className The name of the compiled class.
	 * @param string $baseClass The base class for the compiled class.
	 * @param string $comment A comment for the compiled class. This is normally the file name to identify the class.
	 */
	public function __construct($namespace, $className, $baseClass, $comment) {

		$this->class = new PHPClass($className, $baseClass);
		$this->class->setDocBlock((new PHPDocBlock)->addSection($comment));

		$this->namespace = new PHPNamespace($namespace);
		$this->namespace->addClass($this->class);
		$this->namespace->addUseStatement(new PHPUse(__NAMESPACE__ . '\Variables'));

		$this->file = new PHPFile();
		$this->file->addNamespace($this->namespace);
	}

	/**
	 * Gets the instance of the file generator class.
	 *
	 * @return PHPFile The instance of the file generator class.
	 */
	public function getFile() {

		return $this->file;
	}

	/**
	 * Gets the instance of the namespace generator class.
	 *
	 * @return PHPNamespace The instance of the namespace generator class.
	 */
	public function getNamespace() {

		return $this->namespace;
	}

	/**
	 * Gets the instance of the class generator class.
	 *
	 * @return PHPClass The instance of the class generator class.
	 */
	public function getClass() {

		return $this->class;
	}

	/**
	 * Register a block ID for the given node.
	 *
	 * @param string $blockId The ID of the block to register.
	 * @param string $nodeId The node on which the block is located.
	 * @throws IDAlreadyRegisteredException if the given ID is already registered for a block.
	 */
	public function registerBlock($blockId, $nodeId) {

		if (isset($this->blockMap[$blockId])) {
			throw new IDAlreadyRegisteredException("A block with the ID `{$blockId}` is already registered");
		}

		$this->blockMap[$blockId] = $nodeId;
	}

	/**
	 * Compile the node tree into plain PHP code.
	 *
	 * @param NodeTree $tree The node tree to compile.
	 * @return string The content of the compiled PHP file.
	 */
	public function compile(NodeTree $tree) {

		// Compile the node tree
		$this->compileNode($tree->getRoot());

		// Create the helperStack property
		$property = new PHPProperty('helperStack', new PHPValue($this->helperStack, PHPValue::TYPE_ARRAY));
		$this->class->addProperty($property);

		// Create the blockMap property
		$property = new PHPProperty('blockMap', new PHPValue($this->blockMap, PHPValue::TYPE_ARRAY));
		$this->class->addProperty($property);

		// Create the encoding property
		$property = new PHPProperty('encoding', new PHPValue($tree->getEncoding(), PHPValue::TYPE_STRING));
		$this->class->addProperty($property);

		// Generate the file
		$content = $this->file->generate();

		return $content;
	}

	/**
	 * Compile the given node.
	 *
	 * @param Node $node The node to compile.
	 */
	private function compileNode(Node $node) {

		// Add node to the stack
		$this->helperStack[$node->getId()] = array();

		// Escape all ' characters, because we use it for string concatenation
		$content = addcslashes($node->getContent(), "'");

		// Create the node method
		$docBlock = new PHPDocBlock;
		$docBlock->addAnnotation('@line ' . $node->getLine());
		$docBlock->addAnnotation('@path ' . str_replace('/', '\\', $node->getPath()));

		$param = new PHPParameter('vars', 'Variables');

		$method = new PHPMethod('node_' . $node->getId());
		$method->setDocBlock($docBlock);
		$method->addParameter($param);
		$this->class->addMethod($method);

		// Compile the helpers
		$this->compileHelpers($node);

		// Compile the expressions
		$content = $this->compileNodeExpressions($node->getExpressions(), $content);

		// Compile the children
		$content = $this->compileChildren($node, $content);

		// Add the content to the method body
		$body = new PHPRawCode();
		if ($node->getChildren()) {
			$body->addLine('$vars = clone $vars;');
			$body->addLine();
		}
		$body->addLine("\$content = '{$content}';");
		$body->addLine();
		$body->addLine('return $content;');
		$method->setBody($body);
	}

	/**
	 * Replace all child placeholders in the content of the node with method calls
	 * and compile all child nodes.
	 *
	 * @param Node $node The node for which the children should be compiled.
	 * @param string $content The content of the node.
	 * @return string The content of the node.
	 */
	private function compileChildren(Node $node, $content) {

		foreach ($node->getChildren() as $child) {
			/* @var Node $child */
			$id = $child->getId();
			$idValue = new PHPValue($id, PHPValue::TYPE_STRING);
			$pattern = '@<' . Lexer::NODE_PLACEHOLDER . ' id="' . $id . '"\s*/>@';
			$replace = "' . \$this->processHelperStack({$idValue}, \$vars) . '";
			$content = preg_replace($pattern, $replace, $content, 1);
			$this->compileNode($child);
		}

		return $content;
	}

	/**
	 * Compile all helpers located in the given node.
	 *
	 * @param Node $node The node for which the helpers should be compiled.
	 * @throws UnexpectedHelperTypeException if the helper isn't a callable or a compilable helper.
	 */
	private function compileHelpers(Node $node) {

		foreach ($node->getHelpers() as $helper) {
			/* @var Helper $helper */
			$helper = $this->compileHelperExpressions($helper);

			// Add helper to the stack
			$this->helperStack[$node->getId()][] = $helper->getId();

			// Compile the helper
			$docBlock = new PHPDocBlock;
			$docBlock->addAnnotation('@line ' . $helper->getLine());
			$docBlock->addAnnotation('@path ' . str_replace('/', '\\', $helper->getPath()));

			$param = new PHPParameter('vars', 'Variables');

			$method = new PHPMethod('helper_' . $helper->getId());
			$method->setVisibility(PHPMember::VISIBILITY_PUBLIC);
			$method->setDocBlock($docBlock);
			$method->addParameter($param);
			$method->setBody($helper->compile($this, $node));

			$this->class->addMethod($method);
		}
	}

	/**
	 * Compile the given node expressions.
	 *
	 * @param array $expressions The expressions to compile.
	 * @param string $string The string in which the expressions should be replaced.
	 * @return string The string with all replaced expressions.
	 */
	private function compileNodeExpressions(array $expressions, $string) {

		foreach ($expressions as $expression) {
			/* @var \com\mohiva\elixir\document\Expression $expression */
			$string = $this->compileExpression($expression, $string);
		}

		return $string;
	}

	/**
	 * Compile the helper expressions.
	 *
	 * @param Helper $helper The helper for which the expressions should be compiled.
	 * @return Helper The helper with the compiled expressions.
	 */
	private function compileHelperExpressions(Helper $helper) {

		if ($helper instanceof ElementHelper) {
			$helper->setAttributes($this->compileElementHelperExpressions(
				$helper->getExpressions(),
				$helper->getAttributes()
			));
		} else if ($helper instanceof AttributeHelper) {
			$helper->setValue($this->compileAttributeHelperExpressions(
				$helper->getExpressions(),
				$helper->getValue()
			));
		}

		return $helper;
	}

	/**
	 * Compile the given element helper expressions.
	 *
	 * @param array $expressions The expressions to compile.
	 * @param array $attributes The attributes in which the expressions should be replaced.
	 * @return array The attributes with all replaced expressions.
	 */
	private function compileElementHelperExpressions(array $expressions, array $attributes) {

		foreach ($expressions as $expression) {
			/* @var \com\mohiva\elixir\document\Expression $expression */
			$value = $attributes[$expression->getAttribute()];
			$value = $this->compileExpression($expression, $value);
			$attributes[$expression->getAttribute()] = $value;
		}

		return $attributes;
	}

	/**
	 * Compile the given attribute helper expressions.
	 *
	 * @param array $expressions The expressions to compile.
	 * @param string $value The attribute value in which the expressions should be replaced.
	 * @return string The attribute value with all replaced expressions.
	 */
	private function compileAttributeHelperExpressions(array $expressions, $value) {

		foreach ($expressions as $expression) {
			/* @var \com\mohiva\elixir\document\Expression $expression */
			$value = $this->compileExpression($expression, $value);
		}

		return $value;
	}

	/**
	 * Create a method for the given expression and replace the expression in the given string with the method
	 * call to this created expression method.
	 *
	 * @param Expression $expression The expression to compile.
	 * @param string $string The string in which the expression should be replaced.
	 * @return string The string with the replaced expression.
	 */
	private function compileExpression(Expression $expression, $string) {

		// Replace the first occurrence of the expression content with the associated method call
		// Escape all ' characters, because we use it for string concatenation
		$content = addcslashes($expression->getContent(), "'");
		$idValue = new PHPValue($expression->getId(), PHPValue::TYPE_STRING);
		$search = '{%' . $content . '%}';
		$replace = "' . \$this->processExpression({$idValue}, \$vars) . '";
		$string = substr_replace($string, $replace, strpos($string, $search), strlen($search));

		// Compile the expression
		$docBlock = new PHPDocBlock;
		$docBlock->addAnnotation('@line ' . $expression->getLine());
		$docBlock->addAnnotation('@path ' . str_replace('/', '\\', $expression->getPath()));

		$body = new PHPRawCode();
		$body->addLine('$vars = clone $vars;');
		$body->addLine();
		$body->addLine("\$exp = {$expression->getNode()->evaluate()};");
		$body->addLine();
		$body->addLine('return $exp;');

		$param = new PHPParameter('vars', 'Variables');

		$method = new PHPMethod('expression_' . $expression->getId());
		$method->setVisibility(PHPMember::VISIBILITY_PUBLIC);
		$method->setDocBlock($docBlock);
		$method->addParameter($param);
		$method->setBody($body);

		$this->class->addMethod($method);

		return $string;
	}
}
