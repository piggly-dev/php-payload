<?php
namespace Piggly\Dev\Payload;

use Piggly\Payload\Exceptions\InvalidDataException;
use Piggly\Payload\PayloadArray;
use Respect\Validation\Validator as v;

class Person extends PayloadArray
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
			'name' => 'setName',
			'email' => 'setEmail',
			'phone' => 'setPhone',
			'address' => 'setAddress'
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
			'name',
			'email',
			'phone'
		];

		$this->_validateRequired($required);
		$this->_validateDepth();
	}

	/**
	 * Get name.
	 *
	 * @since 1.0.0
	 * @return string
	 */ 
	public function getName ()
	{ return $this->get('name'); }

	/**
	 * Set name.
	 *
	 * @param string $name Name.
	 * @since 1.0.0
	 * @return self
	 */ 
	public function setName ( string $name )
	{
		$this->add('name', \ucwords($name));
		return $this;
	}

	/**
	 * Get phone.
	 *
	 * @since 1.0.0
	 * @return string
	 */ 
	public function getPhone ()
	{ return $this->get('phone'); }

	/**
	 * Set phone.
	 *
	 * @param string $phone Phone.
	 * @since 1.0.0
	 * @return self
	 * @throws InvalidDataException
	 */ 
	public function setPhone ( string $phone )
	{
		if ( v::phone()->validate($phone) === false )
		{ throw InvalidDataException::invalid($this, 'phone', $phone, 'Invalid phone.'); }
 
		$this->add('phone', $phone);
		return $this;
	}

	/**
	 * Get e-mail.
	 *
	 * @since 1.0.0
	 * @return string
	 */ 
	public function getEmail ()
	{ return $this->get('email'); }

	/**
	 * Set e-mail.
	 *
	 * @param string $email E-mail.
	 * @since 1.0.0
	 * @return self
	 * @throws InvalidDataException
	 */ 
	public function setEmail ( string $email )
	{
		if ( v::email()->validate($email) === false )
		{ throw InvalidDataException::invalid($this, 'email', $email, 'Invalid e-mail.'); }
 
		$this->add('email', $email);
		return $this;
	}

	/**
	 * Get address.
	 *
	 * @since 1.0.0
	 * @return Address
	 */ 
	public function getAddress ()
	{ return $this->get('address'); }

	/**
	 * Set address.
	 *
	 * @param Address|array $address Address.
	 * @since 1.0.0
	 * @return self
	 */ 
	public function setAddress ( $address )
	{
		if ( !($address instanceof Address) )
		{ $address = (new Address())->import($address); }

		$this->add('address', $address);
		return $this;
	}
}