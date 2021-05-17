<?php
namespace Piggly\Dev\Payload;

use Piggly\Payload\Exceptions\InvalidDataException;
use Piggly\Payload\PayloadMap;
use Respect\Validation\Validator as v;

class PersonMap extends PayloadMap
{
	/**
	 * This method is called at constructor.
	 * You should use it to setup fields map
	 * with add method.
	 *
	 * @since 1.0.5
	 * @return void
	 */
	protected function _map ()
	{
		$this
			->add('name')
				->required()
				->back()
			->add('email')
				->validator(v::email())
				->required()
				->back()
			->add('phone')
				->validator(v::phone())
				->back()
			->add('address')
				->back();
	}

	/**
	 * Import $input data to payload.
	 * 
	 * @param array $input
	 * @param bool $ignoreInvalid Should ignore invalid data.
	 * @since 1.0.0
	 * @return self
	 * @throws InvalidDataException
	 */
	public function import ( $input, $ignoreInvalid = true )
	{
		$input = is_array( $input ) ? $input : json_decode($input, true);
		return $this->_importArray($input, $ignoreInvalid);
	}

	/**
	 * Mutator for address.
	 *
	 * @param AddressMap|array $address Address.
	 * @since 1.0.0
	 * @return AddressMap
	 */ 
	protected function setterAddress ( $address ) : AddressMap
	{
		if ( !($address instanceof AddressMap) )
		{ $address = (new AddressMap())->import($address); }

		return $address;
	}
}