<?php
namespace Piggly\Payload\Exceptions;

use InvalidArgumentException;
use Piggly\Payload\Interfaces\PayloadInterface;
use Throwable;

class InvalidDataException extends InvalidArgumentException
{
	/**
	 * Invalid data.
	 * 
	 * @param PayloadInterface $payload
	 * @param string $key
	 * @param mixed $value
	 * @param int $code
	 * @param Throwable $previous
	 * @since 1.0.0
	 * @return InvalidDataException
	 */
	public static function invalid (
		PayloadInterface $payload,
		string $key,
		$value,
		$message,
		int $code = 0,
		Throwable $previous = null
	)
	{
		$value = is_string($value) ? $value : \gettype($value);
		$message = !empty($message) ? $message : 'Fix it before continue.';

		return new static (
			\sprintf(
				'Unexpected value to argument `%s` as `%s` in payload `%s`: %s.',
				$key,
				$value,
				\get_class($payload),
				$message
			),
			$code,
			$previous
		);
	}
}