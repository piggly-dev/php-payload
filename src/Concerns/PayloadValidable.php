<?php
namespace Piggly\Payload\Concerns;

use Piggly\Payload\Exceptions\InvalidDataException;

/**
 * A interface to determine that a payload can be validated
 * by validate() method.
 *
 * @since 1.0.2
 * @package Piggly\Payload
 * @subpackage Piggly\Payload\Interfaces
 * @author Caique Araujo <caique@piggly.com.br>
 */
interface PayloadValidable
{
	/**
	 * Validate all data from payload.
	 * Throw an exception when cannot validate.
	 * 
	 * @since 1.0.2
	 * @return void
	 * @throws InvalidDataException When some data is invalid.
	 */
	public function validate ();

	/**
	 * Validate all data from payload.
	 * But, instead throw an exception when cannot
	 * validate, return a boolean.
	 * 
	 * @since 1.0.2
	 * @return bool TRUE when valid, FALSE when invalid.
	 */
	public function isValid () : bool;
}