<?php
/**
 * Mohiva Elixir
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.textile.
 * It is also available through the world-wide-web at this URL:
 * https://github.com/mohiva/pyramid/blob/master/LICENSE.textile
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
use com\mohiva\elixir\io\StreamWrapper;

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
class StreamWrapperTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * Setup the test case.
	 */
	public function setUp() {
		
		$this->unregisterStreamWrapper();
	}
	
	/**
	 * Tear down the test case.
	 */
	public function tearDown() {
		
		$this->unregisterStreamWrapper();
	}
	
	/**
	 * Test if can register the stream wrapper.
	 */
	public function testRegister() {
		
		StreamWrapper::register();
		
		$this->assertTrue(StreamWrapper::isRegistered());
	}
	
	/**
	 * Test if the `register` method throws an exception if the stream wrapper is already registered.
	 * 
	 * @expectedException \com\mohiva\elixir\io\exceptions\StreamWrapperRegisterException
	 */
	public function testRegisterThrowsException() {
		
		StreamWrapper::register();
		StreamWrapper::register();
	}
	
	/**
	 * Test if can unregister the stream wrapper.
	 */
	public function testUnregister() {
		
		StreamWrapper::register();
		StreamWrapper::unregister();
		
		$this->assertFalse(StreamWrapper::isRegistered());
	}
	
	/**
	 * Test if the `unregister` method throws an exception if the stream wrapper is not registered.
	 * 
	 * @expectedException \com\mohiva\elixir\io\exceptions\StreamWrapperRegisterException
	 */
	public function testUnregisterThrowsException() {
		
		$this->assertFalse(StreamWrapper::isRegistered());
		
		StreamWrapper::unregister();
	}
	
	/**
	 * Test if can set or get a global cache container with the `setCacheContainer` and 
	 * the `getCacheContainer` methods.
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @dataProvider cacheContainerProvider
	 */
	public function testGlobalCacheContainerAccessors(CacheContainer $container) {
		
		StreamWrapper::setCacheContainer($container);
		
		$this->assertSame($container, StreamWrapper::getCacheContainer());
	}
	
	/**
	 * Test if can set or get a path cache container with the `setCacheContainer` and 
	 * the `getCacheContainer` methods.
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @dataProvider cacheContainerProvider
	 */
	public function testPathCacheContainerAccessors(CacheContainer $container) {
		
		$path = 'elixir://' . sha1(uniqid() . microtime(true));
		StreamWrapper::setCacheContainer($container, $path);
		
		$this->assertSame($container, StreamWrapper::getCacheContainer($path));
	}
	
	/**
	 * Test if can unset a global cache container with the `unsetCacheContainer` method.
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @dataProvider cacheContainerProvider
	 */
	public function testUnsetGlobalCacheContainer(CacheContainer $container) {
		
		StreamWrapper::setCacheContainer($container);
		StreamWrapper::unsetCacheContainer();
		
		$this->assertNull(StreamWrapper::getCacheContainer());
	}
	
	/**
	 * Test if can unset a path cache container with the `unsetCacheContainer` method.
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @dataProvider cacheContainerProvider
	 */
	public function testUnsetPathCacheContainer(CacheContainer $container) {
		
		$path = 'elixir://' . sha1(uniqid() . microtime(true));
		StreamWrapper::setCacheContainer($container, $path);
		StreamWrapper::unsetCacheContainer($path);
		
		$this->assertNull(StreamWrapper::getCacheContainer($path));
	}
	
	/**
	 * Test if can unset all previous set cache containers.
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @dataProvider cacheContainerProvider
	 */
	public function testUnsetCacheContainers(CacheContainer $container) {
		
		$path = 'elixir://' . sha1(uniqid() . microtime(true));
		StreamWrapper::setCacheContainer($container, $path);
		StreamWrapper::setCacheContainer($container);
		
		StreamWrapper::unsetCacheContainers();
		
		$this->assertNull(StreamWrapper::getCacheContainer($path));
		$this->assertNull(StreamWrapper::getCacheContainer());
	}
	
	/**
	 * Test all supported access modes.
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @param string $mode The access mode to test.
	 * @dataProvider supportedModesProvider
	 */
	public function testSupportedAccessModes(CacheContainer $container, $mode) {
		
		StreamWrapper::register();
		StreamWrapper::setCacheContainer($container);
		
		$path = 'elixir://' . sha1(uniqid() . microtime(true));
		
		// The resource must be created here to avoid the "failed to open stream" error for the `r` mode
		if ($mode == 'r') file_put_contents($path, 'content');
		
		$fp = fopen($path, $mode);
		fclose($fp);
	}
	
	/**
	 * Test all unsupported access modes.
	 * 
	 * @param string $mode The access mode to test.
	 * @dataProvider unsupportedModesProvider
	 * @expectedException \com\mohiva\elixir\io\exceptions\UnsupportedAccessModeException
	 */
	public function testUnsupportedAccessModes($mode) {
		
		StreamWrapper::register();
		
		$path = 'elixir://' . sha1(uniqid() . microtime(true));
		fopen($path, $mode);
	}
	
	/**
	 * Test if the `stream_open` method throws an `MissingCacheContainerException` if
	 * no cache container is set for the given path.
	 * 
	 * @expectedException \com\mohiva\elixir\io\exceptions\MissingCacheContainerException
	 */
	public function testStreamOpenThrowsMissingCacheContainerException() {
		
		StreamWrapper::register();
		StreamWrapper::unsetCacheContainers();
		
		$path = 'elixir://' . sha1(uniqid() . microtime(true));
		fopen($path, 'w');
	}
	
	/**
	 * Test if the `stream_open` method throws the "failed to open stream" error if the resource 
	 * was accessed with the `r` mode and if the resource doesn't exists.
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @expectedException PHPUnit_Framework_Error_Warning
	 * @dataProvider cacheContainerProvider
	 */
	public function testStreamOpenThrowsFailedToOpenStreamError(CacheContainer $container) {
		
		StreamWrapper::register();
		StreamWrapper::setCacheContainer($container);
		
		$path = 'elixir://' . sha1(uniqid() . microtime(true));
		$fp = fopen($path, 'r');
		fclose($fp);
	}
	
	/**
	 * Test if the `stream_open` method fetches the cache entry if the resource 
	 * was accessed with the `r` mode.
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @dataProvider cacheContainerProvider
	 */
	public function testStreamOpenFetchesCacheEntryInReadonlyMode(CacheContainer $container) {
		
		StreamWrapper::register();
		StreamWrapper::setCacheContainer($container);
		
		$id = sha1(uniqid() . microtime(true));
		$path = 'elixir://' . $id;
		
		$container->store($id, 'TEST');
		
		$fp = fopen($path, 'r');
		$result = fread($fp, 4);
		fclose($fp);
		
		$this->assertSame('TEST', $result);
	}
	
	/**
	 * Test if the `stream_write` method returns the correct number of bytes that were 
	 * successfully stored.
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @dataProvider cacheContainerProvider
	 */
	public function testStreamWriteReturnCorrectNumberOfBytes(CacheContainer $container) {
		
		StreamWrapper::register();
		StreamWrapper::setCacheContainer($container);
		
		$path = 'elixir://' . sha1(uniqid() . microtime(true));
		$fp = fopen($path, 'w');
		$bytes = fwrite($fp, 'A_Ä_U_Ü');
		fclose($fp);
		
		$this->assertSame(9, $bytes);
	}
	
	/**
	 * Test if the `stream_write` method stores data in cache.
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @dataProvider cacheContainerProvider
	 */
	public function testStreamWriteDoesStoreCacheEntryInWriteMode(CacheContainer $container) {
		
		StreamWrapper::register();
		StreamWrapper::setCacheContainer($container);
		
		$id = sha1(uniqid() . microtime(true));
		$path = 'elixir://' . $id;
		$fp = fopen($path, 'w');
		fwrite($fp, 'TEST');
		fclose($fp);
		
		$this->assertTrue($container->exists($id));
	}
	
	/**
	 * Test if the `stream_write` method returns 0 if the file was opened in read mode.
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @dataProvider cacheContainerProvider
	 */
	public function testStreamWriteDoesNotStoreCacheEntryInReadMode(CacheContainer $container) {
		
		StreamWrapper::register();
		StreamWrapper::setCacheContainer($container);
		
		$id = sha1(uniqid() . microtime(true));
		$path = 'elixir://' . $id;
		
		// Cache entry must exists in read mode
		$container->store($id, 'TEST');
		
		$fp = fopen($path, 'r');
		$result = fwrite($fp, 'TEST');
		fclose($fp);
		
		$this->assertSame(0, $result);
	}
	
	/**
	 * Test if the `stream_write` method appends the written bytes to the position.
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @dataProvider cacheContainerProvider
	 */
	public function testStreamWriteAppendsWrittenBytesToPosition(CacheContainer $container) {
		
		StreamWrapper::register();
		StreamWrapper::setCacheContainer($container);
		
		$path = 'elixir://' . sha1(uniqid() . microtime(true));
		$fp = fopen($path, 'w');
		fwrite($fp, '1234');
		$firstPosition = ftell($fp);
		fwrite($fp, 5678);
		$secondPosition = ftell($fp);
		fclose($fp);
		
		$this->assertSame(4, $firstPosition);
		$this->assertSame(8, $secondPosition);
	}
	
	/**
	 * Test if the `stream_seek` method sets the position of the stream. 
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @dataProvider cacheContainerProvider
	 */
	public function testStreamSeekSetPosition(CacheContainer $container) {
		
		StreamWrapper::register();
		StreamWrapper::setCacheContainer($container);
		
		$id = sha1(uniqid() . microtime(true));
		$path = 'elixir://' . $id;
		
		$fp = fopen($path, 'w');
		fwrite($fp, '123456');
		fseek($fp, 4, SEEK_SET);
		$position = ftell($fp);
		fclose($fp);
		
		$this->assertSame(4, $position);
	}
	
	/**
	 * Test if the `stream_seek` method appends a offset the the current position.
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @dataProvider cacheContainerProvider
	 */
	public function testStreamSeekAppendsOffsetToCurrentPosition(CacheContainer $container) {
		
		StreamWrapper::register();
		StreamWrapper::setCacheContainer($container);
		
		$path = 'elixir://' . sha1(uniqid() . microtime(true));
		$fp = fopen($path, 'w');
		fwrite($fp, '12345678');
		fseek($fp, 3); // Sets to 2
		fseek($fp, 3, SEEK_CUR); // Sets to 6
		$position = ftell($fp);
		fclose($fp);
		$this->assertSame(6, $position);
	}
	
	/**
	 * Test if the `stream_seek` method appends a offset to the end of the stream.
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @dataProvider cacheContainerProvider
	 */
	public function testStreamSeekAppendsOffsetToEnd(CacheContainer $container) {
		
		StreamWrapper::register();
		StreamWrapper::setCacheContainer($container);
		
		$path = 'elixir://' . sha1(uniqid() . microtime(true));
		$fp = fopen($path, 'w');
		fwrite($fp, '123456');
		fseek($fp, 1, SEEK_END); // Sets to 7
		$content = fread($fp, 1); // Read next
		fclose($fp);
		
		$this->assertSame('', $content);
	}
	
	/**
	 * Test if the `stream_seek` method return false, if the offset isn't supported.
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @param int $whence Possible values:
	 * SEEK_SET - Set position equal to offset bytes.
	 * SEEK_CUR - Set position to current location plus offset.
	 * SEEK_END - Set position to end-of-file plus offset.
	 * 
	 * @param int $offset The stream offset to seek to.
	 * @dataProvider unsupportedSeekPositionsProvider
	 */
	public function testStreamSeekFails(CacheContainer $container, $whence, $offset) {
		
		StreamWrapper::register();
		StreamWrapper::setCacheContainer($container);
		
		$path = 'elixir://' . sha1(uniqid() . microtime(true));
		$fp = fopen($path, 'w');
		fwrite($fp, '12345678');
		$result = fseek($fp, $offset, $whence);
		fclose($fp);
		
		$this->assertSame(-1, $result);
	}
	
	/**
	 * Test if the `stream_read` method fetches the cache entry.
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @dataProvider cacheContainerProvider
	 */
	public function testStreamReadReturnEmptyStringInWriteonlyMode(CacheContainer $container) {
		
		StreamWrapper::register();
		StreamWrapper::setCacheContainer($container);
		
		$path = 'elixir://' . sha1(uniqid() . microtime(true));
		$fp = fopen($path, 'w');
		fwrite($fp, 'TEST');
		$content = fread($fp, 4);
		fclose($fp);
		
		$this->assertSame('', $content);
	}
	
	/**
	 * Test if the `stream_read` method returns a chunk.
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @dataProvider cacheContainerProvider
	 */
	public function testStreamReadReturnsChunk(CacheContainer $container) {
		
		StreamWrapper::register();
		StreamWrapper::setCacheContainer($container);
		
		$id = sha1(uniqid() . microtime(true));
		$path = 'elixir://' . $id;
		
		$container->store($id, '12345678');
		
		$fp = fopen($path, 'r');
		fseek($fp, 2, SEEK_SET);
		$content1 = fread($fp, 3);
		$content2 = fread($fp, 3);
		fclose($fp);
		
		$this->assertSame('345', $content1);
		$this->assertSame('678', $content2);
	}
	
	/**
	 * Test if the `stream_eof` method returns true if the position is at the end of the stream.
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @dataProvider cacheContainerProvider
	 */
	public function testStreamEOFReturnsTrue(CacheContainer $container) {
		
		StreamWrapper::register();
		StreamWrapper::setCacheContainer($container);
		
		$path = 'elixir://' . sha1(uniqid() . microtime(true));
		$fp = fopen($path, 'w');
		fwrite($fp, '12345678');
		fread($fp, 8);
		$result = feof($fp);
		fclose($fp);
		
		$this->assertTrue($result);
	}
	
	/**
	 * Test if the `stream_eof` method returns false if the position isn't at the end of the stream.
	 * 
	 * @param \com\mohiva\elixir\io\CacheContainer $container The cache container instance.
	 * @dataProvider cacheContainerProvider
	 */
	public function testStreamEOFReturnsFalse(CacheContainer $container) {
		
		StreamWrapper::register();
		StreamWrapper::setCacheContainer($container);
		
		$path = 'elixir://' . sha1(uniqid() . microtime(true));
		$fp = fopen($path, 'w');
		fwrite($fp, '12345678');
		fseek($fp, 3);
		$result = feof($fp);
		fclose($fp);
		
		$this->assertFalse($result);
	}
	
	/**
	 * Data provider which returns a ReflectionClass instance.
	 * 
	 * @return array An array containing a cache container instance.
	 */
	public function cacheContainerProvider() {
		
		$key = new HashKey(Hash::ALGO_SHA1, 'php://temp/');
		$adapter = new ResourceAdapter(new TempResourceContainer(TempFileResource::TYPE));
		$container = new CacheContainer($adapter, $key);
		
		return array(array($container));
	}
	
	/**
	 * Data provider which returns all supported access modes.
	 * 
	 * @return array An array containing all supported access modes.
	 */
	public function supportedModesProvider() {
		
		$adapter = new ResourceAdapter(new TempResourceContainer(TempFileResource::TYPE));
		$container = new CacheContainer($adapter, new HashKey(Hash::ALGO_SHA1, 'php://temp/'));
		
		return array(
			array($container, 'r'),
			array($container, 'w')
		);
	}
	
	/**
	 * Data provider which returns all unsupported access modes.
	 * 
	 * @return array An array containing all unsupported access modes.
	 */
	public function unsupportedModesProvider() {
		
		return array(
			array('r+'),
			array('w+'),
			array('a'),
			array('a+'),
			array('x'),
			array('x+'),
			array('c'),
			array('c+')
		);
	}
	
	/**
	 * Data provider which returns all unsupported seek positions for the string "12345678".
	 * 
	 * @return array An array containing all supported access modes.
	 */
	public function unsupportedSeekPositionsProvider() {
		
		$adapter = new ResourceAdapter(new TempResourceContainer(TempFileResource::TYPE));
		$container = new CacheContainer($adapter, new HashKey(Hash::ALGO_SHA1, 'php://temp/'));
		
		return array(
			array($container, SEEK_SET, -1),
			array($container, SEEK_SET, 9),
			array($container, SEEK_CUR, -9),
			array($container, SEEK_CUR, 9),
			array($container, SEEK_END, -9),
		);
	}
	
	/**
	 * Helper method to unregister the stream wrapper if is registered.
	 */
	private function unregisterStreamWrapper() {
		
		if (!in_array(StreamWrapper::PROTOCOL, stream_get_wrappers())) {
			return;
		}
		
		stream_wrapper_unregister(StreamWrapper::PROTOCOL);
	}
}
