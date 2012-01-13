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
 * @package   Mohiva/Elixir/IO
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
namespace com\mohiva\elixir\io;

use com\mohiva\common\cache\Key;
use com\mohiva\common\cache\adapters\Adapter;

/**
 * Cache container class which handles the caching of elixir documents.
 * 
 * @category  Mohiva/Elixir
 * @package   Mohiva/Elixir/IO
 * @author    Christian Kaps <christian.kaps@mohiva.com>
 * @copyright Copyright (c) 2007-2012 Christian Kaps (http://www.mohiva.com)
 * @license   https://github.com/mohiva/elixir/blob/master/LICENSE.textile New BSD License
 * @link      https://github.com/mohiva/elixir
 */
class CacheContainer {
	
	/**
	 * The adapter to use as backend.
	 * 
	 * @var \com\mohiva\common\cache\adapters\Adapter
	 */
	private $adapter = null;
	
	/**
	 * The key to use for the adapter.
	 * 
	 * @var \com\mohiva\common\cache\Key
	 */
	private $key = null;
	
	/**
	 * Indicates if the container is enabled or disabled for caching.
	 * 
	 * @var bool
	 */
	private $enabled = null;
	
	/**
	 * The class constructor.
	 * 
	 * @param \com\mohiva\common\cache\adapters\Adapter $adapter The adapter to use as backend.
	 * @param \com\mohiva\common\cache\Key $key The key to use for the adapter.
	 * @param bool $enabled True if the cache is enabled, false otherwise.
	 */
	public function __construct(Adapter $adapter, Key $key, $enabled = true) {
		
		$this->adapter = $adapter;
		$this->key = $key;
		$this->enabled = $enabled;
	}
	
	/**
	 * Check if a cache entry for the given path exists.
	 * 
	 * @param string $path The path to check for.
	 * @return boolean True if an cache entry exists for the given path, false otherwise.
	 */
	public function exists($path) {
		
		if ($this->enabled === false) {
			return false;
		}
		
		$this->key->set($path);
		
		return $this->adapter->exists($this->key);
	}
	
	/**
	 * Fetch the PHP code for the given path.
	 * 
	 * @param string $path The path to check for.
	 * @return string A string containing PHP code or null if no cache entry exists for the given path.
	 */
	public function fetch($path) {
		
		if ($this->enabled === false) {
			return null;
		}
		
		$this->key->set($path);
		$value = $this->adapter->fetch($this->key);
		if (!$value) {
			return null;
		}
		
		return $value;
	}
	
	/**
	 * Store the PHP code for the given path in cache.
	 * 
	 * @param string $path The path to the document.
	 * @param string $code The generated PHP code for the MFX document.
	 */
	public function store($path, $code) {
		
		if ($this->enabled === false) {
			return;
		}
		
		$this->key->set($path);
		$this->adapter->store($this->key, $code);
	}
	
	/**
	 * Remove the cached PHP code for the given path from cache.
	 * 
	 * @param string $path The path to remove.
	 */
	public function remove($path) {
		
		if ($this->enabled === false) {
			return;
		}
		
		$this->key->set($path);
		$this->adapter->remove($this->key);
	}
}
