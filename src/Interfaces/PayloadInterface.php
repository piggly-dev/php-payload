<?php
namespace Piggly\Payload\Interfaces;

use ArrayAccess;
use JsonSerializable;
use Piggly\Payload\Exceptions\JsonEncodingException;
use Serializable;

/**
 * A payload interface.
 *
 * @since 1.0.0
 * @package Piggly\Payload
 * @subpackage Piggly\Payload\Interfaces
 * @author Caique Araujo <caique@piggly.com.br>
 */
interface PayloadInterface extends JsonSerializable, Serializable
{
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