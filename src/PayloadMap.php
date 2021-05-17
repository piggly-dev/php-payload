<?php
namespace Piggly\Payload;

use Exception;
use Piggly\Payload\Concerns\PayloadImportable;
use Piggly\Payload\Concerns\PayloadValidable;
use Piggly\Payload\Exceptions\InvalidDataException;
use Piggly\Payload\Exceptions\JsonEncodingException;
use Piggly\Payload\Interfaces\PayloadInterface;
use Piggly\Payload\Mapping\Field;
use RuntimeException;

/**
 * A payloader as an smart map. It retains the expected
 * fields mapping and mount payload from it.
 *
 * @since 1.1.0
 * @package Piggly\Payload
 * @subpackage Piggly\Payload
 * @author Caique Araujo <caique@piggly.com.br>
 */
abstract class PayloadMap implements PayloadInterface, PayloadImportable, PayloadValidable
{
	/**
	 * Payload data.
	 * @var array<Field>
	 * @since 1.1.0
	 */
	private $_fields = [];

	/**
	 * Create payload and add a map to it.
	 * 
	 * @since 1.1.0
	 * @return void
	 */
	public function __construct ()
	{ $this->_map(); }

	/**
	 * Add a new field $key to payload data.
	 * 
	 * @param string $key
	 * @since 1.1.0
	 * @return Field
	 */
	protected function add ( string $key ) : Field
	{ 
		$this->_fields[$key] = new Field($key, $this); 
		return $this->_fields[$key]; 
	}

	/**
	 * Add a new $key to payload data only when $condition
	 * is equal to TRUE.
	 * 
	 * @param bool $condition
	 * @param string $key
	 * @since 1.1.0
	 * @return Field
	 */
	protected function addWhen ( bool $condition, string $key ) : Field
	{ 
		if ( $condition )
		{ return $this->add($key); }

		return $this;
	}

	/**
	 * Set a value to a field $key.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @since 1.1.0
	 * @return self
	 * @throws InvalidDataException
	 */
	public function set ( string $key, $value )
	{
		if ( !$this->has($key) )
		{ throw InvalidDataException::invalid($this, $key, $value, 'Key does not exist on payload map'); }

		$mutator = 'setter'.str_replace('_', '', $key);

		if ( \method_exists($this, $mutator) )
		{ $value = $this->{$mutator}($value); }

		$this->_fields[$key]->value($value);
		return $this;
	}

	/**
	 * Set a value to a field $key.
	 *
	 * @param bool $condition
	 * @param string $key
	 * @param mixed $value
	 * @since 1.1.0
	 * @return self
	 */
	public function setWhen ( bool $condition, string $key, $value )
	{
		if ( !$condition )
		{ return $this; }

		return $this->set($key, $value);
	}

	/**
	 * Remove a $key from payload data.
	 * 
	 * @param string $key
	 * @since 1.1.0
	 * @since 1.0.4 Check if payload isset before remove
	 * @return self
	 */
	public function remove ( string $key )
	{ return $this->removeWhen( isset($this->_fields), $key ); }

	/**
	 * Remove a $key from payload data, only when $condition
	 * is equal to TRUE.
	 * 
	 * @param string $key
	 * @since 1.1.0
	 * @return self
	 */
	public function removeWhen ( bool $condition, string $key )
	{ 
		if ( !$condition || !$this->has($key) )
		{ return $this; }

		unset( $this->_fields[$key] );
		return $this; 
	}

	/**
	 * Get a $key from payload data.
	 * 
	 * @param string $key
	 * @param mixed $default Default value when $key value not set.
	 * @since 1.1.0
	 * @return mixed
	 */
	public function get ( string $key, $default = null )
	{ 
		if ( !$this->has($key) )
		{ return $default; }

		$value = $this->_fields[$key]->getValue($default);
		$mutator = 'getter'.str_replace('_', '', $key);

		if ( \method_exists($this, $mutator) )
		{ return $this->{$mutator}($value); }

		return $value;
	}

	/**
	 * Get a $key from payload data.
	 * 
	 * @param string $key
	 * @param mixed $default Default value when $key value not set.
	 * @since 1.1.0
	 * @return Field|mixed
	 */
	public function getField ( string $key, $default = null )
	{ 
		if ( !$this->has($key) )
		{ return $default; }

		return $this->_fields[$key];
	}

	/**
	 * Get $key from payload data and remove after.
	 * 
	 * @param string $key
	 * @param mixed $default Default value when $key not set.
	 * @since 1.1.0
	 * @return mixed
	 */
	public function getAndRemove ( string $key, $default = null )
	{
		$value = $this->get($key, $default);
		$this->remove($key);
		return $value;
	}

	/**
	 * Get $key from payload data and remove after.
	 * 
	 * @param string $key
	 * @param mixed $default Default value when $key not set.
	 * @since 1.1.0
	 * @return Field|mixed
	 */
	public function getFieldAndRemove ( string $key, $default = null )
	{
		$field = $this->getField($key, $default);
		$this->remove($key);
		return $field;
	}

	/**
	 * Check if has $key at payload data.
	 * 
	 * @param string $key
	 * @since 1.1.0
	 * @return bool
	 */
	public function has ( string $key ) : bool
	{ return isset( $this->_fields[$key] ); }

	/**
	 * This method is called at constructor.
	 * You should use it to setup fields map
	 * with add method.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	abstract protected function _map ();

	/**
	 * Validate all data from payload.
	 * But, instead throw an exception when cannot
	 * validate, return a boolean.
	 * 
	 * @since 1.1.0
	 * @return bool TRUE when valid, FALSE when invalid.
	 */
	public function isValid () : bool
	{
		try
		{ 
			foreach ( $this->_fields as $key => $field )
			{ $field->assert(); }
		}
		catch ( Exception $e )
		{ return false; }

		return true;
	}

	/**
	 * Validate all data from payload.
	 * Throw an exception when cannot validate.
	 * 
	 * @since 1.1.0
	 * @return void
	 * @throws InvalidDataException When some data is invalid.
	 */
	public function validate ()
	{
		foreach ( $this->_fields as $key => $field )
		{ $field->assert(); }
	}

	/**
	 * Import fields from $input. Will not add not allowed
	 * $keys.
	 * 
	 * @param array $input
	 * @param bool $ignoreInvalid Should ignore invalid data.
	 * @since 1.1.0
	 * @return self
	 * @throws InvalidDataException
	 */
	protected function _importArray ( array $input, bool $ignoreInvalid = true )
	{
		foreach ( $input as $key => $value )
		{ 
			try
			{ $this->set($key, $value); }
			catch ( Exception $e )
			{ 
				if ( $ignoreInvalid )
				{ continue; }

				throw $e;
			}
		}

		return $this;
	}

	/**
	 * Export all payload data to an array.
	 * 
	 * @since 1.1.0
	 * @return array
	 */
	public function toArray () : array
	{
		$_array = [];

		foreach ( $this->_fields as $key => $field )
		{ $_array[$field->getKeyToExport()] = $field->export(); }

		return $_array;
	}

	/**
	 * Export all payload data to a JSON string.
	 * 
	 * @since 1.1.0
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
	 * @since 1.1.0
	 * @return array
	 */
	public function jsonSerialize()
	{ return $this->toArray(); }

	/**
	 * Generate a storable representation of payload object.
	 * 
	 * @since 1.1.0
	 * @return string
	 */
	public function serialize ()
	{ return \serialize($this->_fields); }

	/**
	 * Create a Payload object from a stored representation.
	 * 
	 * @param string $data
	 * @since 1.1.0
	 * @return string
	 */
	public function unserialize ( $data )
	{ $this->_fields = \unserialize($data); } 

	/**
	 * Dynamically set attributes.
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @since 1.0.4
	 * @return mixed
	 */
	public function __set ( $key, $value )
	{ return $this->set($key, $value); }

	/**
	 * Dynamically retrieve attributes.
	 * 
	 * @param string $key
	 * @since 1.0.4
	 * @return mixed
	 */
	public function __get ( $key )
	{ return $this->get($key); }

	/**
	 * Determine if an attribute exists on the Payload.
	 *
	 * @param string $key
	 * @since 1.0.4
	 * @return void
	 */
	public function __isset ( $key )
	{ return $this->has($key); }

	/**
	 * Unset an attribute on the Payload.
	 *
	 * @param string $key
	 * @since 1.0.4
	 * @return void
	 */
	public function __unset ( $key )
	{ $this->remove($key); }

	/**
	 * Convert the Payload to its string representation.
	 *
	 * @since 1.0.4
	 * @return string
	 */
	public function __toString ()
	{ return $this->toJson(); }
}