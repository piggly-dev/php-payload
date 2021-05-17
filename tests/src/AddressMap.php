<?php
namespace Piggly\Dev\Payload;

use Piggly\Payload\Exceptions\InvalidDataException;
use Piggly\Payload\PayloadMap;
use Respect\Validation\Validator as v;

class AddressMap extends PayloadMap
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
			->add('address')
				->required()
				->back()
			->add('number')
				->required()
				->back()
			->add('complement')
				->back()
			->add('district')
				->required()
				->back()
			->add('city')
				->required()
				->back()
			->add('country')
				->exportKeyAs('country_id')
				->validator(v::countryCode())
				->required()
				->back()
			->add('postal_code')
				->required()
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
	 * Mutator district name.
	 *
	 * @param string $district District name.
	 * @since 1.0.0
	 * @return self
	 */ 
	public function setterDistrict ( string $district ) : string
	{ return \ucwords($district); }

	/**
	 * Mutator city name.
	 *
	 * @param string $city City name.
	 * @since 1.0.0
	 * @return self
	 */ 
	public function setterCity ( string $city ) : string
	{ return \ucwords($city); }

	/**
	 * Mutator country ID.
	 *
	 * @param string $country_id Country ID.
	 * @since 1.0.0
	 * @return self
	 * @throws InvalidDataException
	 */ 
	public function setterCountryId ( string $country_id ) : string
	{
		$country_id = \strtoupper($country_id);
		$this->getField('postal_code')->validator(v::postalCode($country_id));
		return $country_id;
	}

	/**
	 * Mutator postal Code.
	 *
	 * @param string $postal_code Postal Code.
	 * @since 1.0.0
	 * @return self
	 * @throws InvalidDataException
	 */ 
	public function setterPostalCode ( string $postal_code ) : string
	{ return \preg_replace('/[^\d]/', '', $postal_code); }
}