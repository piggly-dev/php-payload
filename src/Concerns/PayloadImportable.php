<?php
namespace Piggly\Payload\Concerns;

use Piggly\Payload\Exceptions\InvalidDataException;

/**
 * A interface to create a payload with import method,
 * which convert some $input data to current payload.
 *
 * @since 1.0.2
 * @package Piggly\Payload
 * @subpackage Piggly\Payload\Interfaces
 * @author Caique Araujo <caique@piggly.com.br>
 */
interface PayloadImportable
{
	/**
	 * Import $input data to payload.
	 * 
	 * @param mixed $input
	 * @since 1.0.2
	 * @return self
	 * @throws InvalidDataException When some imported data is invalid.
	 */
	public function import ( $input );
}