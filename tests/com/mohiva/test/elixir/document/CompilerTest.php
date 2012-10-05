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
 * @package   Mohiva/Elixir/Test
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\test\elixir\document;

use com\mohiva\test\elixir\Bootstrap;
use com\mohiva\pyramid\Parser as ExpressionParser;
use com\mohiva\common\xml\XMLDocument;
use com\mohiva\common\lang\ReflectionClass;
use com\mohiva\common\io\TempResourceContainer;
use com\mohiva\common\io\TempFileResource;
use com\mohiva\common\crypto\Hash;
use com\mohiva\common\cache\HashKey;
use com\mohiva\common\cache\adapters\ResourceAdapter;
use com\mohiva\elixir\document\expression\Lexer as ExpressionLexer;
use com\mohiva\elixir\document\expression\Grammar as ExpressionGrammar;
use com\mohiva\elixir\document\Lexer as DocumentLexer;
use com\mohiva\elixir\document\Parser as DocumentParser;
use com\mohiva\elixir\document\Compiler;
use com\mohiva\elixir\document\Lexer;
use com\mohiva\elixir\io\StreamWrapper;
use com\mohiva\elixir\io\CacheContainer;

/**
 * Unit test case for the Mohiva Elixir project.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/Test
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class CompilerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Setup the test case.
	 */
	public static function setUpBeforeClass() {

		$key = new HashKey(Hash::ALGO_SHA1, 'php://temp/');
		$adapter = new ResourceAdapter(new TempResourceContainer(TempFileResource::TYPE));
		$container = new CacheContainer($adapter, $key);

		StreamWrapper::setCacheContainer($container);
		StreamWrapper::register();
	}

	/**
	 * Tear down the test case.
	 */
	public static function tearDownAfterClass() {

		StreamWrapper::unregister();
		StreamWrapper::unsetCacheContainers();
	}

	/**
	 * Test if the `getFile` method returns a valid `PHPFile` instance.
	 *
	 * @param Compiler $compiler The compiler class instance to test.
	 * @dataProvider classProvider
	 */
	public function testGetFile(Compiler $compiler) {

		$this->assertInstanceOf('\com\mohiva\manitou\generators\php\PHPFile', $compiler->getFile());
	}

	/**
	 * Test if the `getNamespace` method returns a valid `PHPNamespace` instance.
	 *
	 * @param Compiler $compiler The compiler class instance to test.
	 * @dataProvider classProvider
	 */
	public function testGetNamespace(Compiler $compiler) {

		$this->assertInstanceOf('\com\mohiva\manitou\generators\php\PHPNamespace', $compiler->getNamespace());
	}

	/**
	 * Test if the `getClass` method returns a valid `PHPClass` instance.
	 *
	 * @param Compiler $compiler The compiler class instance to test.
	 * @dataProvider classProvider
	 */
	public function testGetClass(Compiler $compiler) {

		$this->assertInstanceOf('\com\mohiva\manitou\generators\php\PHPClass', $compiler->getClass());
	}

	/**
	 * Test if the `registerBlock` method throws an `IDAlreadyRegisteredException`
	 * exception if the block is registered twice.
	 *
	 * @param Compiler $compiler The compiler class instance to test.
	 * @expectedException \com\mohiva\elixir\document\exceptions\IDAlreadyRegisteredException
	 * @dataProvider classProvider
	 */
	public function testRegisterBlockThrowsException(Compiler $compiler) {

		$compiler->registerBlock('content', 'nodeId');
		$compiler->registerBlock('content', 'nodeId');
	}

	/**
	 * Test if the compilation contains all use statements used by the compiled class.
	 */
	public function testCompilationContainsUseStatements() {

		$class = $this->createReflectionClassFixture();
		$namespace = $class->getNamespace();
		$useStatements = $namespace->getUseStatements();

		$this->assertArrayHasKey('Variables', $useStatements);
	}

	/**
	 * Test if the compiled class extends the `stdClass` class.
	 */
	public function testCompiledClassExtendsStdClass() {

		$class = $this->createReflectionClassFixture();

		$this->assertEquals('stdClass', $class->getParentClass()->getName());
	}

	/**
	 * Test if the compilation contains the `$helperStack` property.
	 */
	public function testCompilationContainsHelperStackProperty() {

		$class = $this->createReflectionClassFixture();

		$this->assertSame('helperStack', $class->getProperty('helperStack')->getName());
	}

	/**
	 * Test if the compilation contains the `$blockMap` property.
	 */
	public function testCompilationContainsBlockMapProperty() {

		$class = $this->createReflectionClassFixture();

		$this->assertSame('blockMap', $class->getProperty('blockMap')->getName());
	}

	/**
	 * Test if the compilation contains the `$encoding` property.
	 */
	public function testCompilationContainsEncodingProperty() {

		$class = $this->createReflectionClassFixture();

		$this->assertSame('encoding', $class->getProperty('encoding')->getName());
	}

	/**
	 * Test if the compiler replaces all child node placeholders with the helper stack calls.
	 */
	public function testCompileReplacesAllChildNodePlaceholders() {

		$class = $this->createReflectionClassFixture();
		$content = file_get_contents($class->getFileName());

		$this->assertNotContains(Lexer::NODE_PLACEHOLDER, $content);
	}

	/**
	 * Data provider which returns a Compiler instance.
	 *
	 * @return array An array containing a Compiler instance.
	 */
	public function classProvider() {

		return array(array(
			new Compiler(__NAMESPACE__, 'Test', '\stdClass', 'Test class')
		));
	}

	/**
	 * Creates a ReflectionClass instance and return it.
	 *
	 * @return ReflectionClass A ReflectionClass instance.
	 */
	private function createReflectionClassFixture() {

		$hash = sha1(uniqid() . microtime(true));
		$className = 'Test_' . $hash;
		$fileName = 'elixir://test' . $hash;

		$doc = new XMLDocument();
		$doc->load(Bootstrap::$resourceDir . '/elixir/document/compiler/default.xml');

		$lexer = new DocumentLexer();
		$stream = $lexer->scan($doc);

		$parser = new DocumentParser(new ExpressionLexer(), new ExpressionParser(new ExpressionGrammar));
		$tree = $parser->parse($stream);

		$compiler = new Compiler(__NAMESPACE__, $className, '\stdClass', 'Test class');
		$content = $compiler->compile($tree);
		file_put_contents($fileName, $content);

		/** @noinspection PhpIncludeInspection */
		require $fileName;

		return new ReflectionClass(__NAMESPACE__ . '\\' . $className);
	}
}
