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
namespace com\mohiva\test\elixir\io;

use com\mohiva\common\io\TempResourceContainer;
use com\mohiva\common\io\TempFileResource;
use com\mohiva\common\crypto\Hash;
use com\mohiva\common\cache\HashKey;
use com\mohiva\common\cache\adapters\ResourceAdapter;
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
class CacheContainerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Test if can store PHP code.
	 */
	public function testStorePHPCode() {

		$key = new HashKey(Hash::ALGO_SHA1, 'php://temp');
		$adapter = new ResourceAdapter(new TempResourceContainer(TempFileResource::TYPE));
		$container = new CacheContainer($adapter, $key);
		$container->store('/path/to/doc.xml', '<?php class Test {}');

		$this->assertTrue($adapter->exists($key));
	}

	/**
	 * Test if previous stored PHP code exists.
	 */
	public function testPHPCodeExists() {

		$key = new HashKey(Hash::ALGO_SHA1, 'php://temp');
		$adapter = new ResourceAdapter(new TempResourceContainer(TempFileResource::TYPE));
		$container = new CacheContainer($adapter, $key);
		$container->store('/path/to/doc.xml', '<?php class Test {}');

		$this->assertTrue($container->exists('/path/to/doc.xml'));
	}

	/**
	 * Test if can fetch previous stored PHP code.
	 */
	public function testFetchAnnotationList() {

		$key = new HashKey(Hash::ALGO_SHA1, 'php://temp');
		$adapter = new ResourceAdapter(new TempResourceContainer(TempFileResource::TYPE));
		$container = new CacheContainer($adapter, $key);
		$container->store('/path/to/doc.xml', '<?php class Test {}');

		$this->assertEquals($container->fetch('/path/to/doc.xml'), '<?php class Test {}');
	}

	/**
	 * Test if can remove previous stored PHP code.
	 */
	public function testRemoveAnnotationList() {

		$key = new HashKey(Hash::ALGO_SHA1, 'php://temp');
		$adapter = new ResourceAdapter(new TempResourceContainer(TempFileResource::TYPE));
		$container = new CacheContainer($adapter, $key);
		$container->store('/path/to/doc.xml', '<?php class Test {}');
		$container->remove('/path/to/doc.xml');

		$this->assertFalse($container->exists('/path/to/doc.xml'));
	}
}
