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

use RuntimeException;
use com\mohiva\elixir\Document;
use com\mohiva\common\exceptions\BadMethodCallException;

/**
 * The base class for all compiled Elixir documents.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Document
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
abstract class Processor {

	/**
	 * The helper result commands.
	 *
	 * @var int
	 */
	const CMD_NEXT   = 1;
	const CMD_RETURN = 2;

	/**
	 * The Elixir document implementation.
	 *
	 * @var Document
	 */
	protected $document = null;

	/**
	 * The parent processor instance.
	 *
	 * @var Processor
	 */
	protected $parent = null;

	/**
	 * Map block IDs with their node IDs.
	 *
	 * @var array
	 */
	protected $blockMap = array();

	/**
	 * A list with dummy blocks. Dummy blocks are blocks which are not exists
	 * in this document but which are passed from a child. So that these
	 * blocks can be passed to the parent of this document we must create
	 * a dummy for these.
	 *
	 * @var array
	 */
	protected $dummyBlocks = array();

	/**
	 * The helper stack for every node.
	 *
	 * @var array
	 */
	protected $helperStack = array();

	/**
	 * Indicates if the processing of the blocks is enabled or disabled.
	 *
	 * @var boolean
	 */
	protected $blockProcessing = true;

	/**
	 * The encoding of the content.
	 *
	 * @var string
	 */
	protected $encoding = null;

	/**
	 * The class constructor.
	 *
	 * @param Document $document The Elixir document implementation.
	 */
	public function __construct(Document $document) {

		$helperStack = array();
		foreach ($this->helperStack as $nodeId => $helper) {
			$helperStack[$nodeId] = array(array());
			foreach ($helper as $helperId) {
				$helperStack[$nodeId][0][] = array($this, $helperId);
			}
		}

		$this->helperStack = $helperStack;
		$this->document = $document;
	}

	/**
	 * Process the document and return the content.
	 *
	 * @param Variables $vars The vars to parse in the document.
	 * @return string The content of the parsed document.
	 */
	public function process(Variables $vars) {

		if (empty($this->helperStack)) {
			return '';
		}

		// Get the first node from helper stack
		$item = each($this->helperStack);
		$nodeId = $item['key'];

		// Process the first helper
		$current = $this->processHelperStack($nodeId, $vars);
		if (!$this->parent) {
			return $current;
		}

		// Process the parent document
		$this->blockProcessing = true;
		$parent = $this->parent->process($vars);

		return $parent;
	}

	/**
	 * Add a new block to this document.
	 *
	 * This method adds the given stack on top of the existing stack for this block.
	 * If no block with the given id exists in this document then we create a dummy
	 * block for this id. So this block can be registered later, by the extends helper,
	 * on the parent document of this document.
	 *
	 * @param string $blockId The id of the block to add.
	 * @param array $stack The helper stack for the block.
	 */
	public function addBlock($blockId, array $stack) {

		if (!isset($this->blockMap[$blockId])) {

			// Create a dummy id for the not existing block in this document
			$nodeId = sha1($blockId . microtime(true));

			// Register the block for the dummy id
			$this->blockMap[$blockId] = $nodeId;

			// Register the stack for the dummy id
			$this->helperStack[$nodeId] = $stack;

			// Register block as dummy
			$this->dummyBlocks[] = $blockId;

			return;
		}

		$nodeId = $this->blockMap[$blockId];
		$this->helperStack[$nodeId] = array_merge($this->helperStack[$nodeId], $stack);
	}

	/**
	 * Process a block and return its content. If no block exists or the block
	 * is marked as dummy then process the block on the parent document if exists,
	 * and so on.
	 *
	 * @param string $blockId The block id of the block to process.
	 * @param Variables $vars The vars to parse into the block.
	 * @return string The content of the parsed block or null if no block with the given id exists.
	 */
	public function processBlock($blockId, Variables $vars) {

		if (isset($this->blockMap[$blockId]) && !in_array($blockId, $this->dummyBlocks)) {
			$nodeId = $this->blockMap[$blockId];
			$helperStack = $this->helperStack[$nodeId];

			return $this->iterateStack($helperStack, $nodeId, $vars, 0);
		} else if ($this->parent) {
			return $this->parent->processBlock($blockId, $vars);
		}

		return null;
	}

	/**
	 * Parse the helper stack for the given id.
	 *
	 * @param string $nodeId The id of the node to process.
	 * @param Variables $vars The vars to parse in the document.
	 * @return string The content of the parsed node or an empty string if the block processing is deactivated.
	 */
	protected function processHelperStack($nodeId, Variables $vars) {

		if (!$this->blockProcessing && in_array($nodeId, $this->blockMap)) {
			return ''; // Block processing is disabled
		} else if (empty($this->helperStack[$nodeId][0])) {
			return $this->parseNode($this, $nodeId, $vars);
		}

		$helperStack = $this->helperStack[$nodeId];
		$level = count($helperStack) - 1;

		return $this->iterateStack($helperStack, $nodeId, $vars, $level);
	}

	/**
	 * Iterator over the given stack and process all helpers contained in it.
	 *
	 * @param array $stack The stack to process.
	 * @param string $nodeId The node ID of the given stack.
	 * @param Variables $vars The vars to parse into the helpers.
	 * @param int $level The inheritance level.
	 * @return string The content of the node.
	 * @throws RuntimeException if a helper return the wrong command.
	 */
	protected function iterateStack(array $stack, $nodeId, Variables $vars, $level) {

		$helpers = $stack[$level];
		foreach ($helpers as $helper) {
			$document = $helper[0];
			$helperId = $helper[1];
			$result = $this->processHelper($document, $helperId, $vars);

			// Execute the next helper
			if ($result['command'] == self::CMD_NEXT) {
				continue;
			}

			// If a block helper cannot be executed due to the fact that an attribute helper
			// sends the return command then execute the parent helper if one exists, and so on ...
			//
			// As example: <ex:Block id="content" ex:Locale="de_DE">
			// This block should overwrite the parent "content" block. But if this block cannot be
			// executed due to the fact that the "Locale" helper sends the return command then the
			// parent "content" block will be executed.
			if ($result['command'] == self::CMD_RETURN && $result['content'] == null && $level > 0) {
				return $this->iterateStack($stack, $nodeId, $vars, --$level);
			}

			// Return the content of the node
			if ($result['command'] == self::CMD_RETURN) {
				return $result['content'];
			}

			// Throws an exception if a wrong command was send by a helper
			throw new RuntimeException("Undefined command `{$result['command']}`");
		}

		// Parse the node if a helper sends the next command and no other helper is in the stack.
		// As example: <div ex:Locale="de_DE">
		// The locale helper isn't a element helper(which returns his node), so we
		// must return the node, on which the helper is located, here.
		return $this->parseNode($this, $nodeId, $vars);
	}

	/**
	 * Process the helper with the given id.
	 *
	 * @param Processor $processor The processor object in which the helper is located.
	 * @param string $helperId The id of the helper to process.
	 * @param Variables $vars The vars to parse into the helper.
	 * @return array The result from the helper method.
	 * @throws BadMethodCallException if the method for a helper doesn't exists.
	 */
	protected function processHelper(Processor $processor, $helperId, Variables $vars) {

		if (!method_exists($processor, "helper_{$helperId}")) {
			$class = get_class($processor);
			throw new BadMethodCallException("The method `helper_{$helperId}` doesn't exists in the class `{$class}`");
		}

		$result = $processor->{"helper_{$helperId}"}($vars);
		if (!is_array($result)) $result = array();
		if (!isset($result['command'])) $result['command'] = self::CMD_NEXT;
		if (!isset($result['content'])) $result['content'] = null;

		return $result;
	}

	/**
	 * Parse the node for the given id.
	 *
	 * @param Processor $processor The processor object in which the helper is located.
	 * @param string $nodeId The id of the node to parse.
	 * @param Variables $vars The vars to parse in the document.
	 * @return string The content of the node.
	 * @throws BadMethodCallException if the method for a node doesn't exists.
	 */
	protected function parseNode(Processor $processor, $nodeId, Variables $vars) {

		if (!method_exists($processor, "node_{$nodeId}")) {
			$class = get_class($processor);
			throw new BadMethodCallException("The method `node_{$nodeId}` doesn't exists in the class `{$class}`");
		}

		return $processor->{"node_{$nodeId}"}($vars);
	}
}
