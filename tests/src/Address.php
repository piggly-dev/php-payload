<?php
namespace Piggly\Dev\Payload;

use Piggly\Payload\Exceptions\InvalidDataException;
use Piggly\Payload\PayloadArray;
use Respect\Validation\Validator as v;

class Address extends PayloadArray
{
	/**
	 * Import $input data to payload.
	 * 
	 * @param array $input
	 * @since 1.0.0
	 * @return self
	 * @throws InvalidDataException
	 */
	public function import ( $input )
	{
		$input = is_array( $input ) ? $input : json_decode($input, true);

		$_map = [
			'address' => 'setAddress',
			'number' => 'setNumber',
			'complement' => 'setComplement',
			'district' => 'setDistrict',
			'city' => 'setCity',
			'country_id' => 'setCountry',
			'postal_code' => 'setPostalCode'
		];

		return $this->_importArray($_map, $input);
	}

	/**
	 * Validate all data from payload.
	 * Throw an exception when cannot validate.
	 * 
	 * @since 1.0.0
	 * @return void
	 * @throws InvalidDataException
	 */
	public function validate ()
	{
		$required = [
			'address',
			'number',
			'district',
			'city',
			'country_id',
			'postal_code'
		];

		$this->_validateRequired($required);
	}

	/**
	 * Get address.
	 *
	 * @since 1.0.0
	 * @return string
	 */ 
	public function getAddress ()
	{ return $this->get('address'); }

	/**
	 * Set address.
	 *
	 * @param string $address Address.
	 * @since 1.0.0
	 * @return self
	 */ 
	public function setAddress ( string $address )
	{
		$this->add('address', $address);
		return $this;
	}

	/**
	 * Get complement.
	 * 
	 * @since 1.0.0
	 * @return string
	 */ 
	public function getComplement ()
	{ return $this->get('complement'); }

	/**
	 * Set complement.
	 *
	 * @param string $complement Complement.
	 * @since 1.0.0
	 * @return self
	 */ 
	public function setComplement ( string $complement )
	{
		$this->add('complement', $complement);
		return $this;
	}

	/**
	 * Get number.
	 *
	 * @since 1.0.0
	 * @return string
	 */ 
	public function getNumber ()
	{ return $this->get('number'); }

	/**
	 * Set number.
	 *
	 * @param string $number Number.
	 * @since 1.0.0
	 * @return self
	 */ 
	public function setNumber ( string $number )
	{
		$this->add('number', $number);
		return $this;
	}

	/**
	 * Get district name.
	 * @since 1.0.0
	 * @return string
	 */ 
	public function getDistrict ()
	{ return $this->get('district'); }

	/**
	 * Set district name.
	 *
	 * @param string $district District name.
	 * @since 1.0.0
	 * @return self
	 */ 
	public function setDistrict ( string $district )
	{
		$this->add('district', \ucwords($district));
		return $this;
	}

	/**
	 * Get city name.
	 *
	 * @since 1.0.0
	 * @return string
	 */ 
	public function getCity ()
	{ return $this->get('city'); }

	/**
	 * Set city name.
	 *
	 * @param string $city City name.
	 * @since 1.0.0
	 * @return self
	 */ 
	public function setCity ( string $city )
	{
		$this->add('city', \ucwords($city));
		return $this;
	}

	/**
	 * Get country ID.
	 *
	 * @since 1.0.0
	 * @return string
	 */ 
	public function getCountry ()
	{ return $this->get('country_id'); }

	/**
	 * Set country ID.
	 *
	 * @param string $country_id Country ID.
	 * @since 1.0.0
	 * @return self
	 * @throws InvalidDataException
	 */ 
	public function setCountry ( string $country_id )
	{
		if ( v::countryCode()->validate($country_id) === false )
		{ throw InvalidDataException::invalid($this, 'country_id', $country_id, 'Invalid country code.'); }
 
		$this->add('country_id', \strtoupper($country_id));
		return $this;
	}

	/**
	 * Get postal Code.
	 *
	 * @since 1.0.0
	 * @return string
	 */ 
	public function getPostalCode ()
	{ return $this->get('postal_code'); }

	/**
	 * Set postal Code.
	 *
	 * @param string $postal_code Postal Code.
	 * @since 1.0.0
	 * @return self
	 * @throws InvalidDataException
	 */ 
	public function setPostalCode ( string $postal_code )
	{
		if ( v::postalCode( $this->get('country_id', 'US') )->validate($postal_code) === false )
		{ throw InvalidDataException::invalid($this, 'postal_code', $postal_code, 'Invalid postal code.'); }
		
		$this->add('postal_code', \preg_replace('/[^\d]/', '', $postal_code));
		return $this;
	}
}