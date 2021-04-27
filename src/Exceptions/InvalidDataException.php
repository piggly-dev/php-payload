<?php
namespace Piggly\Payload\Exceptions;

use InvalidArgumentException;
use Piggly\Payload\Interfaces\PayloadInterface;
use Throwable;

/**
 * An exception which indicates there is a invalid
 * data at payload.
 *
 * @since 1.0.0
 * @package Piggly\Payload
 * @subpackage Piggly\Payload\Exceptions
 * @author Caique Araujo <caique@piggly.com.br>
 */
class InvalidDataException extends InvalidArgumentException
{
	/**
	 * Argument key.
	 * 
	 * @var string
	 * @since 1.0.1
	 */
	protected $key;

	/**
	 * Argument value.
	 * 
	 * @var string
	 * @since 1.0.1
	 */
	protected $value;

	/**
	 * Hint.
	 * 
	 * @var string
	 * @since 1.0.1
	 */
	protected $hint;

	/**
	 * Get argument key.
	 * 
	 * @since 1.0.1
	 * @return string
	 */
	public function getKey () 
	{ return $this->key; }

	/**
	 * Get argument value.
	 * 
	 * @since 1.0.1
	 * @return string
	 */
	public function getValue ()
	{ return $this->value; }

	/**
	 * Get hint.
	 * 
	 * @since 1.0.1
	 * @return string
	 */
	public function getHint ()
	{ return $this->hint; }

	/**
	 * Constructor.
	 * 
	 * @param PayloadInterface $payload
	 * @param string $key
	 * @param mixed $value
	 * @param mixed $hint
	 * @param int $code
	 * @param Throwable $previous
	 * @since 1.0.1
	 * @return self
	 */
	public function __construct (
		PayloadInterface $payload,
		string $key,
		$value,
		$hint = 'Fix it',
		int $code = 0,
		Throwable $previous = null
	)
	{
		$this->key   = $key;
		$this->value = is_string($value) ? $value : \gettype($value);
		$this->hint  = $hint;
		
		parent::__construct(
			\sprintf(
				'Unexpected value to argument `%s` as `%s` in payload `%s`: %s.',
				$this->key,
				$this->value,
				\get_class($payload),
				$this->hint
			),
			$code,
			$previous
		);
	}

	/**
	 * Invalid data.
	 * 
	 * @param PayloadInterface $payload
	 * @param string $key
	 * @param mixed $value
	 * @param mixed $hint
	 * @param int $code
	 * @param Throwable $previous
	 * @since 1.0.0
	 * @return InvalidDataException
	 */
	public static function invalid (
		PayloadInterface $payload,
		string $key,
		$value,
		$hint = 'Fix it',
		int $code = 0,
		Throwable $previous = null
	)
	{ return new static ($payload, $key, $value, $hint, $code, $previous); }
}