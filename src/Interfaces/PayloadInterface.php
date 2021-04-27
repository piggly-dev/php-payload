<?php
namespace Piggly\Payload\Interfaces;

use JsonSerializable;
use Piggly\Payload\Exceptions\JsonEncodingException;
use Piggly\Payload\Exceptions\InvalidDataException;
use Serializable;

/**
 * A payload interface.
 *
 * @since 1.0.0
 * @package Piggly\Payload
 * @subpackage Piggly\Payload\Exceptions
 * @author Caique Araujo <caique@piggly.com.br>
 */
interface PayloadInterface extends JsonSerializable, Serializable
{
	/**
	 * Validate all data from payload.
	 * Throw an exception when cannot validate.
	 * 
	 * @since 1.0.0
	 * @return void
	 * @throws InvalidDataException When some data is invalid.
	 */
	public function validate ();

	/**
	 * Validate all data from payload.
	 * But, instead throw an exception when cannot
	 * validate, return a boolean.
	 * 
	 * @since 1.0.0
	 * @return bool TRUE when valid, FALSE when invalid.
	 */
	public function isValid () : bool;

	/**
	 * Import $input data to payload.
	 * 
	 * @param mixed $input
	 * @since 1.0.0
	 * @since 1.0.1 Throw InvalidDataException when some data is invalid.
	 * @return self
	 * @throws InvalidDataException When some imported data is invalid.
	 */
	public function import ( $input );

	/**
	 * Export all payload data to an array.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function toArray () : array;

	/**
	 * Export all payload data to a JSON string.
	 * 
	 * @since 1.0.0
	 * @return string
	 * @throws JsonEncodingException If can't parse JSON.
	 */
	public function toJson ( int $option = \JSON_ERROR_NONE, int $depth = 512 ) : string;
}