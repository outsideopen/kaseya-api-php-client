<?php

/**
 * Copyright 2010 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Kaseya;

/**
 * Extension to the regular Model that automatically exposes the items array for iteration, so you can just
 * iterate over the object rather than a reference inside.
 */
class Collection
	extends Model
	implements \Iterator, \Countable
{
	protected $collection_key = 'items';

	public function __construct()
	{
		if (func_num_args() == 1 && is_array(func_get_arg(0))) {
			// Initialize the model with the array's contents.
			$array = func_get_arg(0);
			$this->mapTypes($array);
		}
		$this->kapiInit();
	}

	public function rewind()
	{
		if (isset($this->{$this->collection_key})
			&& is_array($this->{$this->collection_key})) {
			reset($this->{$this->collection_key});
		}
	}

	public function current()
	{
		$this->coerceType($this->key());
		if (is_array($this->{$this->collection_key})) {
			return current($this->{$this->collection_key});
		}
	}

	public function key()
	{
		if (isset($this->{$this->collection_key})
			&& is_array($this->{$this->collection_key})) {
			return key($this->{$this->collection_key});
		}
	}

	public function next()
	{
		return next($this->{$this->collection_key});
	}

	public function valid()
	{
		$key = $this->key();

		return $key !== null && $key !== false;
	}

	public function count()
	{
		if (!isset($this->{$this->collection_key})) {
			return 0;
		}

		return count($this->{$this->collection_key});
	}

	public function offsetExists($offset)
	{
		if (!is_numeric($offset)) {
			return parent::offsetExists($offset);
		}

		return isset($this->{$this->collection_key}[$offset]);
	}

	public function offsetGet($offset)
	{
		if (!is_numeric($offset)) {
			return parent::offsetGet($offset);
		}
		$this->coerceType($offset);

		return $this->{$this->collection_key}[$offset];
	}

	public function offsetSet($offset, $value)
	{
		if (!is_numeric($offset)) {
			return parent::offsetSet($offset, $value);
		}
		$this->{$this->collection_key}[$offset] = $value;
	}

	public function offsetUnset($offset)
	{
		if (!is_numeric($offset)) {
			return parent::offsetUnset($offset);
		}
		unset($this->{$this->collection_key}[$offset]);
	}

	private function coerceType($offset)
	{
		$keyType = $this->keyType($this->collection_key);
		if ($keyType && !is_object($this->{$this->collection_key}[$offset])) {
			$this->{$this->collection_key}[$offset] =
				new $keyType($this->{$this->collection_key}[$offset]);
		}
	}
}