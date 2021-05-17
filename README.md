# Payloads

Payloads, how name says, are about data. That tradicional model with getters and setters. But, we don't need models anymore. Thanks to **(Eloquent Model)[https://laravel.com/docs/8.x/eloquent]** which solved that. 

But, there are still Request and Response data, they are not any kind of storage data, they are just data. Adopt some kind of **ORM Model** to them... it's kind of overthinking, making these datas too complex.

This simples library cames to solve it. Payloads are tradicional `arrays` with more flexibily. Same flexibility we can see at Eloquent Models, but not complicated or overpowerfull at all. It's just a way to standatize dataa across Requests and Responses.

See below how it works.

## PayloadArray

### The `Person` object:

```php
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
```

### The `Address` object

```php
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
```

### Usage

```php
use Piggly\Dev\Payload\Person;

$person = [
	'name' => 'John Connor',
	'email' => 'john@skynet.com',
	'phone' => '+1-202-555-0172',
	'address' => [
		'address' => 'Future Avenue',
		'number' => '2047',
		'complement' => 'High Tech World',
		'district' => 'Nobody\'s Alive',
		'city' => 'Unknown',
		'country_id' => 'US',
		'postal_code' => '55372'
	]
];

// Import from an array
$person = (new Person())->import($person);
// Validate (throw an exception if can't)
$person->validate();

// Payload object to array
$_array = $person->toArray();
// Payload object to json
$_json = $person->toJson();
// Alternative way to converto to json
$_json = json_encode($person);

// Serialize
$_serialized = serialize($person);
// Unserialize
$_unserialized = unserialize($_serialized);
```

## PayloadMap

The `PayloadMap` class force to payload use a map. This mapping will strict define all fields allowed to payload and, even, fields validations. Each field inside a `PayloadMap` will be a `Field` object. The `Field` object will have following properties:

Method | Description
--- | ---
`getKey()` | Field key name.
`exportKeyAs()` and `getKeyToExport()` | Alias to field key name while exporting. (e.g. A field `country` may be exported as `country_id`).
`value()` and `getValue()` | Field value.
`label()` and `getLabel()` | Field label.
`defaults()` and `getDefault()` | Field default value.
`required()`, `optional()` and `isRequired()` | If field is required or optional.
`acessible()`, `hidden()` and `isAcessible()` | If field is accessible or hidden.
`allowsNull()`, `notAllowsNull()` and `isAllowingNull()` | If `NULL` is a valid value for field and it should accept it for exporting. (e.g. if `country` does not allows `NULL` and `country` is `NULL`, then it won't be exported).
`validator()` | A `Respect\Validation\Validator` object to validate field value.
`custom()` and `getCustom()` | To manager custom properties.

There also methods:

* `export()` will export field value;
* `validate()` will return a `boolean` which validates field value;
* `assert()` will throw an `InvalidDataException` if cannot validate field value;
* `props()` set all props once; 
* `back()` to navigation purpose it will goes back to `PayloadMap` object, since `Field` is inside one.

Creating a `PayloadMap` works the same way as creating a `PayloadArray`, but it's required the `_map()` method, which will contains the mapping to fields:

```php
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
```

You may use `setter{key}()` and `getter{key}()` methods to mutate field value before set and after get. Mutators, be them setters or getters, should always return the value mutated.

## Changelog

See the [CHANGELOG](CHANGELOG.md) file for information about all code changes.

## Testing the code

This library uses the PHPUnit. We carry out tests of all the main classes of this application.

```bash
vendor/bin/phpunit
```

## Contributions

See the [CONTRIBUTING](CONTRIBUTING.md) file for information before submitting your contribution.

## Credits

- [Caique Araujo](https://github.com/caiquearaujo)
- [All contributors](../../contributors)

## Support the project

Piggly Studio is an agency located in Rio de Janeiro, Brazil. If you like this library and want to support this job, be free to donate any value to BTC wallet `3DNssbspq7dURaVQH6yBoYwW3PhsNs8dnK` ❤.

## License

MIT License (MIT). See [LICENSE](LICENSE).