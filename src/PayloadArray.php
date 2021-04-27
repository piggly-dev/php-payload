<?php
namespace Piggly\Payload;

use Exception;
use Piggly\Payload\Concerns\PayloadImportable;
use Piggly\Payload\Concerns\PayloadValidable;
use Piggly\Payload\Exceptions\InvalidDataException;
use Piggly\Payload\Exceptions\JsonEncodingException;
use Piggly\Payload\Interfaces\PayloadInterface;

/**
 * A payloader as an smart array. $_payload is an array
 * value which receipts all payload data. Supports the following
 * api methods to perform actions to $_payload:
 * 
 * add(), addWhen(), remove(), removeWhen(), get(), 
 * getAndRemove() and has().
 *
 * @since 1.0.0
 * @since 1.0.2 New interfaces matching.
 * @package Piggly\Payload
 * @subpackage Piggly\Payload
 * @author Caique Araujo <caique@piggly.com.br>
 */
abstract class PayloadArray implements PayloadInterface, PayloadImportable, PayloadValidable
{
	/**
	 * Payload data.
	 * @var array
	 * @since 1.0.0
	 */
	private $_payload = [];

	/**
	 * Add a new $key to payload data.
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @since 1.0.0
	 * @return self
	 */
	protected function add ( string $key, $value )
	{ $this->_payload[$key] = $value; return $this; }

	/**
	 * Add a new $key to payload data only when $condition
	 * is equal to TRUE.
	 * 
	 * @param bool $condition
	 * @param string $key
	 * @param mixed $value
	 * @since 1.0.0
	 * @return self
	 */
	protected function addWhen ( bool $condition, string $key, $value )
	{ 
		if ( $condition )
		{ $this->_payload[$key] = $value; }

		return $this;
	}

	/**
	 * Remove a $key from payload data.
	 * 
	 * @param string $key
	 * @since 1.0.0
	 * @return self
	 */
	protected function remove ( string $key )
	{ unset($this->_payload[$key]); return $this; }

	/**
	 * Remove a $key from payload data, only when $condition
	 * is equal to TRUE.
	 * 
	 * @param string $key
	 * @since 1.0.0
	 * @return self
	 */
	protected function removeWhen ( bool $condition, string $key )
	{ 
		if ( $condition )
		{ unset($this->_payload[$key]); }

		return $this; 
	}

	/**
	 * Get a $key from payload data.
	 * 
	 * @param string $key
	 * @param mixed $default Default value when $key not set.
	 * @since 1.0.0
	 * @return mixed
	 */
	protected function get ( string $key, $default = null )
	{ return $this->_payload[$key] ?? $default; }

	/**
	 * Get $key from payload data and remove after.
	 * 
	 * @param string $key
	 * @param mixed $default Default value when $key not set.
	 * @since 1.0.0
	 * @return mixed
	 */
	protected function getAndRemove ( string $key, $default = null )
	{
		$value = $this->get($key, $default);
		$this->remove($key);
		return $value;
	}

	/**
	 * Check if has $key at payload data.
	 * 
	 * @param string $key
	 * @since 1.0.0
	 * @return bool
	 */
	protected function has ( string $key ) : bool
	{ return isset($this->_payload[$key]); }

	/**
	 * Validate if all required $keys were set.
	 * 
	 * @param array $keys
	 * @since 1.0.0
	 * @return void
	 * @throws InvalidDataException
	 */
	protected function _validateRequired ( array $keys )
	{
		foreach ( $keys as $key )
		{ 
			$value = $this->get($key);

			if ( empty($value) )
			{ throw InvalidDataException::invalid($this, $key, $value, 'Cannot be empty value.'); }
		}
	}

	/**
	 * Validate in depth. Looking for any PayloadInterface object
	 * and running validate() method.
	 * 
	 * @since 1.0.0
	 * @return void
	 * @throws InvalidDataException
	 */
	protected function _validateDepth ()
	{
		foreach ( $this->_payload as $key => $value )
		{ 
			if ( $value instanceof PayloadInterface )
			{ $value->validate(); }
		}
	}

	/**
	 * Import $keys from $array. Lookup all $keys and if $key
	 * is set in $array, add it to payload.
	 * 
	 * If $key => $method is set, call $method instead simple 
	 * add(). E.g.:
	 * 
	 * $keys = [ 'name', 'age' => 'setAge' ];
	 * 
	 * 'name' will be added with method add();
	 * 'age' will be added with method setAge();
	 * 
	 * The $array parameters need to has $key => $value format.
	 * 
	 * @param array $keys
	 * @param array $array
	 * @since 1.0.0
	 * @return self
	 * @throws InvalidDataException
	 */
	protected function _importArray ( array $keys, array $array )
	{
		foreach ( $keys as $key => $method )
		{
			if ( !isset($array[is_numeric($key) ? $method : $key]) )
			{ continue; }

			if ( is_numeric( $key ) )
			{ 
				$this->add($method, $array[$method]); 
				continue;
			}
				
			$this->{$method}($array[$key]);
		}

		return $this;
	}

	/**
	 * Validate all data from payload.
	 * But, instead throw an exception when cannot
	 * validate, return a boolean.
	 * 
	 * @since 1.0.0
	 * @return bool TRUE when valid, FALSE when invalid.
	 */
	public function isValid () : bool
	{
		try
		{ $this->validate(); }
		catch ( Exception $e )
		{ return false; }

		return true;
	}

	/**
	 * Export all payload data to an array.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function toArray () : array
	{
		$_array = [];

		foreach ( $this->_payload as $key => $value )
		{
			if ( $value instanceof PayloadInterface )
			{ 
				$_array[$key] = $value->toArray();
				continue;
			}

			$_array[$key] = $value;
		}

		return $_array;
	}

	/**
	 * Export all payload data to a JSON string.
	 * 
	 * @since 1.0.0
	 * @return string
	 * @throws JsonEncodingException If can't parse JSON.
	 */
	public function toJson ( int $option = \JSON_ERROR_NONE, int $depth = 512 ) : string
	{
		$json = json_encode( $this->jsonSerialize(), $option, $depth );

		if ( JSON_ERROR_NONE !== json_last_error() ) 
		{ throw JsonEncodingException::for($this, \json_last_error_msg()); }

		return $json;
	}
  
	/**
	 * Prepare the resource for JSON serialization.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function jsonSerialize()
	{ return $this->toArray(); }

	/**
	 * Generate a storable representation of payload object.
	 * 
	 * @since 1.0.0
	 * @return string
	 */
	public function serialize ()
	{ return \serialize($this->_payload); }

	/**
	 * Create a Payload object from a stored representation.
	 * 
	 * @param string $data
	 * @since 1.0.0
	 * @return string
	 */
	public function unserialize ( $data )
	{ $this->_payload = \unserialize($data); } 
}