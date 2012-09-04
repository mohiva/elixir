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
use com\mohiva\elixir\document\helpers\CallableHelper;
use com\mohiva\elixir\document\helpers\CompilableHelper;
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
	 * Register a block id for the given node.
	 *
	 * @param string $blockId The id of the block to register.
	 * @param string $nodeId The node on which the block is located.
	 * @throws IDAlreadyRegisteredException if the given id is already registered for a block.
	 */
	public function registerBlock($blockId, $nodeId) {

		if (isset($this->blockMap[$blockId])) {
			throw new IDAlreadyRegisteredException("A block with the id `{$blockId}` is already registered");
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
		$content = $this->compileExpressions($node->getExpressions(), $content);

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
			$placeholder = "<__node__>{$id}</__node__>";
			$idValue = new PHPValue($id, PHPValue::TYPE_STRING);
			$content = str_replace($placeholder, "' . \$this->processHelperStack({$idValue}, \$vars) . '", $content);

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

			if ($helper instanceof CompilableHelper) {
				/* @var helpers\CompilableHelper $helper */
				$this->compileCompilableHelper($method, $node, $helper);
			} else if ($helper instanceof CallableHelper) {
				/* @var helpers\CallableHelper $helper */
				$this->compileCallableHelper($method, $node, $helper);
			} else {
				$class = get_class($helper);
				throw new UnexpectedHelperTypeException(
					"The helper `{$class}` must implement the CompilableHelper or the CallableHelper interface"
				);
			}

			$this->class->addMethod($method);
		}
	}

	/**
	 * Creates the body of the helper method.
	 *
	 * @param PHPMethod $method The method for which the body should be created.
	 * @param Node $node The node on which the helper is located.
	 * @param helpers\CompilableHelper $helper The helper to compile.
	 */
	private function compileCompilableHelper(PHPMethod $method, Node $node, CompilableHelper $helper) {

		$method->setBody($helper->compile($this, $node));
	}

	/**
	 * Create the body of the helper method.
	 *
	 * @param PHPMethod $method The method for which the body should be created.
	 * @param Node $node The node on which the helper is located.
	 * @param helpers\CallableHelper $helper The helper to compile.
	 * @throws UnexpectedHelperTypeException if the helper isn't a attribute or a element helper.
	 */
	private function compileCallableHelper(PHPMethod $method, Node $node, CallableHelper $helper) {

		/* @var Helper $helper */
		$class = get_class($helper);
		$body = new PHPRawCode();
		$nodeIdVal = new PHPValue($node->getId(), PHPValue::TYPE_STRING);
		$helpIdVal = new PHPValue($helper->getId(), PHPValue::TYPE_STRING);
		$helpLineVal = new PHPValue($helper->getLine(), PHPValue::TYPE_INTEGER);
		$helpPathVal = new PHPValue($helper->getPath(), PHPValue::TYPE_STRING);
		$helpVarsVal = new PHPValue(array(), PHPValue::TYPE_ARRAY);
		$body->addLine("\$nodeId = {$nodeIdVal};");
		$body->addLine("\$helpId = {$helpIdVal};");
		$body->addLine("\$helpLine = {$helpLineVal};");
		$body->addLine("\$helpPath = {$helpPathVal};");
		$body->addLine("\$helpVars = {$helpVarsVal};");
		if ($helper instanceof AttributeHelper) {
			/* @var helpers\AttributeHelper $helper */
			$helpValueVal = new PHPValue($helper->getValue(), PHPValue::TYPE_STRING);
			$helpValueVal = $this->compileExpressions($helper->getExpressions(), $helpValueVal);
			$body->addLine("\$helpValue = {$helpValueVal};");
			$body->addLine();
			$body->addLine("\$helper = new {$class}(\$helpId, \$helpValue, \$helpVars, \$helpLine, \$helpPath);");
		} else if ($helper instanceof ElementHelper) {
			/* @var helpers\ElementHelper $helper */
			$helpAttrVal = new PHPValue($helper->getAttributes(), PHPValue::TYPE_ARRAY);
			$helpAttrVal = $this->compileExpressions($helper->getExpressions(), $helpAttrVal);
			$body->addLine("\$helpAttr = {$helpAttrVal};");
			$body->addLine();
			$body->addLine("\$helper = new {$class}(\$helpId, \$helpAttr, \$helpVars, \$helpLine, \$helpPath);");
		} else {
			throw new UnexpectedHelperTypeException(
				"The helper `{$class}` must be of type AttributeHelper or a ElementHelper"
			);
		}

		$body->addLine("\$content = \$helper->process(\$this, {$nodeIdVal}, \$vars);");
		$body->addLine();
		$body->addLine('return $content;');
		$method->setBody($body);
	}

	/**
	 * Replace all placeholders in the given string with the result of the expression compiler.
	 *
	 * @param array $expressions The expressions to compile.
	 * @param string $string The string in which the placeholders should be replaced.
	 * @return string The string with all replaced placeholders.
	 */
	private function compileExpressions(array $expressions, $string) {

		foreach ($expressions as $expression) {
			$compilation = null;
			$string = str_replace($expression['placeholder'], "' .  {$compilation}  . '", $string);
		}

		return $string;
	}
}
