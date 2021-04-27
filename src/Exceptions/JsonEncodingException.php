<?php
namespace Piggly\Payload\Exceptions;

use Piggly\Payload\Interfaces\PayloadInterface;
use RuntimeException;

class JsonEncodingException extends RuntimeException
{
	/**
	 * Indicates exception while encoding to JSON
	 * at $payload class.
	 * 
	 * @param PayloadInterface $payload Payload that issued exception.
	 * @param string $message JSON error string message.
	 * @since 1.0.0
	 * @return JsonEncodingException
	 */
	public static function for ( PayloadInterface $payload, string $message )
	{
		return new static(
			\sprintf(
				'Error while encoding `%s` data to JSON: %s.', 
				\get_class($payload), 
				$message
			)
		); 
	} 
}