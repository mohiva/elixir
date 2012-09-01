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
 * @package   Mohiva/Elixir/IO
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\io;

use com\mohiva\elixir\io\exceptions\StreamWrapperRegisterException;
use com\mohiva\elixir\io\exceptions\UnsupportedAccessModeException;
use com\mohiva\elixir\io\exceptions\MissingCacheContainerException;

/**
 * Stream wrapper class to load Elixir documents from cache.
 *
 * This class acts as a bridge for the cache container. So it's possible to include the
 * data from cache directly with the include/require construct.
 *
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/IO
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 * @link      http://pecl.php.net/bugs/bug.php?id=23823
 */
class StreamWrapper {

	/**
	 * The protocol to register.
	 *
	 * @var string
	 */
	const PROTOCOL = 'elixir';

	/**
	 * Open for reading only; place the file pointer at the beginning of the file.
	 *
	 * @var string
	 */
	const MODE_READONLY = 'r';

	/**
	 * Open for writing only; place the file pointer at the beginning of the file and truncate the file
	 * to zero length. If the file does not exist, attempt to create it.
	 *
	 * @var string
	 */
	const MODE_WRITEONLY = 'w';

	/**
	 * A list with cache containers.
	 *
	 * @var array
	 */
	private static $containers = array();

	/**
	 * The cache container used for the current path.
	 *
	 * @var CacheContainer
	 */
	private $container = null;

	/**
	 * The path to the file.
	 *
	 * @var string
	 */
	private $path = null;

	/**
	 * The stream data.
	 *
	 * @var string
	 */
	private $data = null;

	/**
	 * The access mode.
	 *
	 * @var string
	 */
	private $mode = null;

	/**
	 * The current position of the stream.
	 *
	 * @var int
	 */
	private $position = 0;

	/**
	 * Register the stream wrapper.
	 *
	 * @throws \com\mohiva\elixir\io\exceptions\StreamWrapperRegisterException if the stream wrapper is
	 * already registered.
	 */
	public static function register() {

		if (self::isRegistered() === true) {
			$message = 'The stream wrapper for protocol ' . self::PROTOCOL . ' is already registered';
			throw new StreamWrapperRegisterException($message);
		}

		stream_wrapper_register(self::PROTOCOL, __CLASS__);
	}

	/**
	 * Unregister the stream wrapper.
	 *
	 * @throws \com\mohiva\elixir\io\exceptions\StreamWrapperRegisterException if the stream wrapper isn't registered.
	 */
	public static function unregister() {

		if (self::isRegistered() === false) {
			$message = 'No stream wrapper for protocol ' . self::PROTOCOL . ' registered';
			throw new StreamWrapperRegisterException($message);
		}

		stream_wrapper_unregister(self::PROTOCOL);
	}

	/**
	 * Indicates if the stream wrapper is already registered or not.
	 *
	 * @return bool True if the stream wrapper is already registered, false otherwise.
	 */
	public static function isRegistered() {

		return in_array(self::PROTOCOL, stream_get_wrappers());
	}

	/**
	 * Set the cache container.
	 *
	 * The container can be set global or for a specific path. These path containers will be
	 * treated preferentially.
	 *
	 * @param CacheContainer $container The cache container object.
	 * @param string $path The path for the container or null for the global container.
	 */
	public static function setCacheContainer(CacheContainer $container, $path = null) {

		if ($path === null) {
			self::$containers['global'] = $container;
		} else {
			self::$containers[self::getPath($path)] = $container;
		}
	}

	/**
	 * Gets the cache container for the given path.
	 *
	 * @param string $path The path for the container to check for or null to check for the global container.
	 * @return CacheContainer The cache container for the given path,
	 * the global container if no path was given or null if no cache container exists.
	 */
	public static function getCacheContainer($path = null) {

		$path = self::getPath($path);
		if (isset(self::$containers[$path])) {
			return self::$containers[$path];
		} else if (isset(self::$containers['global'])) {
			return self::$containers['global'];
		}

		return null;
	}

	/**
	 * Unset the cache container for the given path.
	 *
	 * @param string $path The path for the container to unset or null to unset the global container.
	 */
	public static function unsetCacheContainer($path = null) {

		$path = self::getPath($path);
		if ($path === null && isset(self::$containers['global'])) {
			unset(self::$containers['global']);
		} else if (isset(self::$containers[$path])) {
			unset(self::$containers[$path]);
		}
	}

	/**
	 * Unset all cache containers.
	 */
	public static function unsetCacheContainers() {

		self::$containers = array();
	}

	/**
	 * Open the stream.
	 *
	 * @param string $path Specifies the URL that was passed to the original function.
	 * @param string $mode The mode used to open the file, as detailed for fopen(). For this implementation only the
	 * modes `r` and `w` are supported.
	 *
	 * @return bool True on success or false on failure.
	 * @throws \com\mohiva\elixir\io\exceptions\UnsupportedAccessModeException if the given mode isn't supported.
	 * @throws \com\mohiva\elixir\io\exceptions\MissingCacheContainerException if no cache container is set for
	 * the given path.
	 */
	public function stream_open($path, $mode) {

		$this->mode = rtrim($mode, 'bt');
		if (!$this->isSupportedMode($this->mode)) {
			throw new UnsupportedAccessModeException(
				'Unsupported mode `' . $this->mode .  '`; ' .
				'Only the modes `' .
				self::MODE_READONLY . '` and `' .
				self::MODE_WRITEONLY . '` are supported for this stream wrapper implementation'
			);
		}

		$this->path = self::getPath($path);
		$this->container = self::getCacheContainer($this->path);
		if (!$this->container) {
			throw new MissingCacheContainerException("No cache container for path `{$this->path}` exists");
		} else if ($this->mode === self::MODE_READONLY && !$this->container->exists($this->path)) {
			return false;
		} else if ($this->mode === self::MODE_READONLY) {
			$this->data = (string) $this->container->fetch($this->path);
		}

		return true;
	}

	/**
	 * Write data to the stream.
	 *
	 * In read-only mode this method will always return 0.
	 *
	 * @param string $data The data to write.
	 * @return int The number of bytes that were successfully stored, or 0 if none could be stored.
	 */
	public function stream_write($data) {

		if ($this->mode === self::MODE_READONLY) return 0;

		$length = strlen($data);
		$this->data = (string) $data;
		$this->container->store($this->path, $this->data);
		$this->position += $length;

		return $length;
	}

	/**
	 * Read data from stream.
	 *
	 * In write-only mode this method will always return an empty string.
	 *
	 * @param int $count The number of bytes to read from the stream.
	 * @return string The read data.
	 */
	public function stream_read($count) {

		if ($this->mode === self::MODE_WRITEONLY) return '';

		$chunk = substr($this->data, $this->position, $count);
		$this->position += strlen($chunk);

		return $chunk;
	}

	/**
	 * Retrieve the current position of a stream.
	 *
	 * @return int The current position of the stream.
	 */
	public function stream_tell() {

		return $this->position;
	}

	/**
	 * Tests for end-of-file on a file pointer.
	 *
	 * @return bool True if the read/write position is at the end of the stream and if no more data is available
	 * to be read, or false otherwise.
	 */
	public function stream_eof() {

		return $this->position >= strlen($this->data);
	}

	/**
	 * Seeks to specific location in a stream.
	 *
	 * @param int $offset The stream offset to seek to.
	 * @param int $whence Possible values:
	 * SEEK_SET - Set position equal to offset bytes.
	 * SEEK_END - Set position to end-of-file plus offset.
	 *
	 * @return bool True if the position was updated, false otherwise.
	 */
	public function stream_seek($offset, $whence = SEEK_SET) {

		// SEEK_CUR will be automatically converted to SEEK_SET
		// see http://svn.php.net/viewvc/php/php-src/trunk/main/streams.c?annotate=96547&pathrev=96547#l582
		// see http://news.php.net/php.internals/54999
		if ($whence === SEEK_SET && $offset < strlen($this->data) && $offset >= 0) {
			$this->position = $offset;

			return true;
		} else if ($whence === SEEK_END && strlen($this->data) + $offset >= 0) {
			$this->position = strlen($this->data) + $offset;

			return true;
		}

		return false;
	}

	/**
	 * Retrieve information about a file resource.
	 *
	 * @return array Should return as many elements as stat() does. Unknown or unavailable values should be set
	 * to a rational value (usually 0).
	 *
	 * @see http://www.php.net/manual/en/streamwrapper.url-stat.php
	 */
	public function stream_stat() {

		$stat = array(
			'dev' => 1,
			'ino' => 1,
			'mode' => 1,
			'nlink' => 1,
			'uid' => 1,
			'gid' => 1,
			'rdev' => 1,
			'size' => 1,
			'atime' => 1,
			'mtime' => 1,
			'ctime' => 1,
			'blksize' => 1,
			'blocks' => 1
		);

		return $stat;
	}

	/**
	 * Retrieve information about a file.
	 *
	 * @param string $path The file path or URL to stat.
	 * @param int $flags Holds additional flags set by the streams API.
	 * @return array Should return as many elements as stat() does. Unknown or unavailable values should be set
	 * to a rational value (usually 0).
	 *
	 * @see http://php.net/manual/de/streamwrapper.url-stat.php
	 */
	public function url_stat($path, $flags) {

		$stat = array(
			'dev' => 1,
			'ino' => 1,
			'mode' => 1,
			'nlink' => 1,
			'uid' => 1,
			'gid' => 1,
			'rdev' => 1,
			'size' => 1,
			'atime' => 1,
			'mtime' => 1,
			'ctime' => 1,
			'blksize' => 1,
			'blocks' => 1
		);

		return $stat;
	}

	/**
	 * Get the path to the resource without the protocol.
	 *
	 * @param string $path The path to the resource with or without the protocol.
	 * @return string The path to the resource without the protocol.
	 */
	private static function getPath($path) {

		$path = parse_url($path, PHP_URL_HOST);

		return $path;
	}

	/**
	 * Check if the given mode is supported by the wrapper.
	 *
	 * @param string $mode The mode to check for.
	 * @return bool True if the mode is supported, false otherwise.
	 */
	private function isSupportedMode($mode) {

		return $mode === self::MODE_READONLY || $mode === self::MODE_WRITEONLY;
	}
}
