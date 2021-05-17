<?php
namespace Piggly\Tests\Payload;

use PHPUnit\Framework\TestCase;
use Piggly\Dev\Payload\PersonMap;
use Piggly\Payload\Exceptions\InvalidDataException;

class PayloadMapTest extends TestCase
{
	/**
	 * Person array data.
	 * @var array
	 * @since 1.0.0
	 */
	protected $_person;

	/**
	 * Setup base array for testing
	 * 
	 * @since 1.0.0
	 * @return void
	 */
	protected function setUp () : void
	{
		$this->_person = [
			'name' => 'John Connor',
			'email' => 'john@skynet.com',
			'phone' => '+1-202-555-0172',
			'address' => [
				'address' => 'Future Avenue',
				'number' => '2047',
				'complement' => 'High Tech World',
				'district' => 'Nobody\'s Alive',
				'city' => 'Unknown',
				'country' => 'US',
				'postal_code' => '55372'
			]
		];
	}

	/** @test Convert payload object to array. */
	public function toArray ()
	{
		$person = (new PersonMap())->import($this->_person);
		$this->assertSame( 
			[
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
			]
			, $person->toArray() 
		);
	}

	/** @test Convert payload object to json. */
	public function toJson ()
	{
		$person = (new PersonMap())->import($this->_person);
		$this->assertSame( 
			json_encode([
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
			]), 
			$person->toJson() 
	);
	}

	/** @test Validate all data imported. */
	public function isValid ()
	{
		$person = (new PersonMap())->import($this->_person);
		$this->assertTrue($person->isValid());
	}

	/** @test Remove required index and validate data imported. */
	public function isInvalid ()
	{
		$_person = $this->_person;
		unset( $_person['name'] );

		$person = (new PersonMap())->import($_person);
		$this->assertFalse($person->isValid());
	}

	/** @test Remove required index and validate data imported. */
	public function isIgnoringUnknownField ()
	{
		$_person = $this->_person;
		$_person['unknown'] = 'unknown';

		$person = (new PersonMap())->import($_person);
		$this->assertNull($person->get('unknown'));
	}

	/** @test Remove required index and validate data imported. */
	public function throwAnExceptionIfCannotIgnoreUnknownField ()
	{
		$this->expectException(InvalidDataException::class);

		$_person = $this->_person;
		$_person['unknown'] = 'unknown';

		$person = (new PersonMap())->import($_person, false);
	}

	/** @test Set invalid value and expect exception. */
	public function wrongValue ()
	{
		$this->expectException(InvalidDataException::class);

		$_person = $this->_person;
		$_person['phone'] = 'unknow';

		$person = (new PersonMap())->import($_person);
		$person->validate();
	}
}