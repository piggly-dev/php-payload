<?php

use Piggly\Dev\Payload\Person;

require_once ('../vendor/autoload.php');

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