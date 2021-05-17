<?php
namespace Piggly\Tests\Payload;

use PHPUnit\Framework\TestCase;
use Piggly\Dev\Payload\Person;
use Piggly\Payload\Exceptions\InvalidDataException;

class PayloadArrayTest extends TestCase
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
				'country_id' => 'US',
				'postal_code' => '55372'
			]
		];
	}

	/** @test Convert payload object to array. */
	public function toArray ()
	{
		$person = (new Person())->import($this->_person);
		$this->assertSame( $this->_person, $person->toArray() );
	}

	/** @test Convert payload object to json. */
	public function toJson ()
	{
		$_json  = json_encode($this->_person);
		$person = (new Person())->import($this->_person);
		$this->assertSame( $_json, $person->toJson() );
	}

	/** @test Validate all data imported. */
	public function isValid ()
	{
		$person = (new Person())->import($this->_person);
		$this->assertTrue($person->isValid());
	}

	/** @test Remove required index and validate data imported. */
	public function isInvalid ()
	{
		$_person = $this->_person;
		unset( $_person['name'] );

		$person = (new Person())->import($_person);
		$this->assertFalse($person->isValid());
	}

	/** @test Set invalid value and expect exception. */
	public function wrongValue ()
	{
		$this->expectException(InvalidDataException::class);

		$_person = $this->_person;
		$_person['phone'] = 'unknow';

		$person = (new Person())->import($_person);
		$person->validate();
	}
}