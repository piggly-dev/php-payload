<?php
namespace Piggly\Payload\Mapping;

use JsonSerializable;
use Piggly\Payload\Concerns\PayloadValidable;
use Piggly\Payload\Exceptions\InvalidDataException;
use Piggly\Payload\Interfaces\PayloadInterface;
use Piggly\Payload\PayloadMap;
use Respect\Validation\Validator;
use Serializable;
use Traversable;

/**
 * A field object with properties for mapping.
 *
 * @since 1.1.0
 * @package Piggly\Payload
 * @subpackage Piggly\Payload\Mapping
 * @author Caique Araujo <caique@piggly.com.br>
 */
class Field implements Serializable
{
	/**
	 * Properties.
	 * 
	 * @var array
	 * @since 1.1.0
	 */
	protected $_props;

	/**
	 * Field key.
	 *
	 * @var string
	 * @since 1.1.0
	 */
	protected $_key;

	/**
	 * Field value.
	 *
	 * @var mixed
	 * @since 1.1.0
	 */
	protected $_value;

	/**
	 * Parent payload.
	 *
	 * @var PayloadMap
	 * @since 1.1.0
	 */
	protected $_parent;

	/**
	 * Create a new field key with properties values.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param PayloadMap $parent
	 * @since 1.1.0
	 * @return self
	 */
	public function __construct( string $key, PayloadMap $parent )
	{ 
		$this->_key = $key;
		$this->_parent = $parent;

		$this->_props = [
			'export_as' => null,
			'nullable' => false,
			'accessible' => true,
			'required' => false,
			'default' => null,
			'label' => null,
			'validator' => null,
			'custom' => []
		];

		return $this;
	}

	/**
	 * Get field key.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function getKey ()
	{ return $this->_key; } 

	/**
	 * Set new field value.
	 *
	 * @param mixed $newValue
	 * @since 1.1.0
	 * @return self
	 */
	public function exportKeyAs ( $newValue )
	{ $this->_props['export_as'] = $newValue; return $this; }

	/**
	 * Get field value.
	 *
	 * @since 1.1.0
	 * @return mixed
	 */
	public function getKeyToExport ()
	{ return $this->_props['export_as'] ?? $this->getKey(); } 

	/**
	 * Set new field value.
	 *
	 * @param mixed $newValue
	 * @since 1.1.0
	 * @return self
	 */
	public function value ( $newValue )
	{ $this->_value = $newValue; return $this; }

	/**
	 * Get field value.
	 *
	 * @param mixed $default
	 * @since 1.1.0
	 * @return mixed
	 */
	public function getValue ( $default = null )
	{ return $this->_value ?? $this->getDefault() ?? $default; } 

	/**
	 * Export field value normalized.
	 * 
	 * @since 1.1.0
	 * @return mixed
	 */
	public function export ()
	{
		$value = $this->getValue();

		if ( $value instanceof PayloadInterface )
		{ return $value->toArray(); }

		if ( $value instanceof JsonSerializable )
		{ return $value->jsonSerialize(); }

		if ( $value instanceof Traversable )
		{ return iterator_to_array($value); }

		if ( method_exists($value, 'toArray') )
		{ return $value->toArray(); }

		return $value;
	}

	/**
	 * Set label property to field.
	 *
	 * @param string $label
	 * @since 1.1.0
	 * @return self
	 */
	public function label ( string $label )
	{ $this->_props['label'] = $label; return $this; }

	/**
	 * Get label property to field.
	 *
	 * @since 1.1.0
	 * @return string
	 */
	public function getLabel () : string
	{ return $this->_props['label']; }

	/**
	 * Set default property to field.
	 *
	 * @param mixed $default
	 * @since 1.1.0
	 * @return self
	 */
	public function defaults ( $default )
	{ $this->_props['default'] = $default; return $this; }

	/**
	 * Get default property to field.
	 *
	 * @since 1.1.0
	 * @return mixed
	 */
	public function getDefault ()
	{ return $this->_props['default'] ?? null; }

	/**
	 * Mark field as required.
	 *
	 * @since 1.1.0
	 * @return self
	 */
	public function required ()
	{ $this->_props['required'] = true; return $this; }

	/**
	 * Mark field as optional.
	 *
	 * @since 1.1.0
	 * @return self
	 */
	public function optional ()
	{ $this->_props['required'] = false; return $this; }

	/**
	 * Get required property to field.
	 *
	 * @since 1.1.0
	 * @return bool
	 */
	public function isRequired () : bool
	{ return $this->_props['required'] === true; }

	/**
	 * Mark field as acessible.
	 *
	 * @since 1.1.0
	 * @return self
	 */
	public function acessible ()
	{ $this->_props['acessible'] = true; return $this; }

	/**
	 * Mark field as hidden.
	 *
	 * @since 1.1.0
	 * @return self
	 */
	public function hidden ()
	{ $this->_props['acessible'] = false; return $this; }

	/**
	 * Get acessible property to field.
	 *
	 * @since 1.1.0
	 * @return bool
	 */
	public function isAcessible () : bool
	{ return $this->_props['acessible'] === true; }

	/**
	 * Allow null as value.
	 *
	 * @since 1.1.0
	 * @return self
	 */
	public function allowsNull ()
	{ $this->_props['nullable'] = true; return $this; }

	/**
	 * Not allow null as value.
	 *
	 * @since 1.1.0
	 * @return self
	 */
	public function notAllowsNull ()
	{ $this->_props['nullable'] = false; return $this; }

	/**
	 * Get nullable property to field.
	 *
	 * @since 1.1.0
	 * @return bool
	 */
	public function isAllowingNull () : bool
	{ return $this->_props['nullable'] === true; }

	/**
	 * Set validator property to field.
	 *
	 * @param Validator $validator
	 * @since 1.1.0
	 * @return self
	 */
	public function validator ( Validator $validator )
	{ $this->_props['validator'] = $validator; return $this; }

	/**
	 * Validate _value with validator.
	 *
	 * @since 1.1.0
	 * @return bool
	 */
	public function validate () : bool
	{ 
		$value = $this->getValue();

		if ( $this->isRequired() && !$this->isAllowingNull() )
		{
			if ( is_null($value) )
			{ return false; }
		}
		else if ( $this->isAllowingNull() && is_null($value) )
		{ return true; }

		if ( $value instanceof PayloadValidable )
		{ return $value->isValid(); }

		if ( !($this->_props['validator'] instanceof Validator) )
		{ return true; }

		return $this->_props['validator']->validate( $value ); 
	}

	/**
	 * Set custom property to field.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @since 1.1.0
	 * @return self
	 */
	public function custom ( string $key, $value )
	{ $this->_props['custom'][$key] = $value; return $this; }

	/**
	 * Get custom property to field.
	 *
	 * @param string $key
	 * @param mixed $default
	 * @since 1.1.0
	 * @return string
	 */
	public function getCustom ( string $key, $default = null ) : string
	{ return $this->_props['custom'][$key] ?? $default; }

	/**
	 * Set up all $props to field.
	 *
	 * @param array $props
	 * @since 1.1.0
	 * @return self
	 */
	public function props ( array $props )
	{
		$this->_props = [
			'export_as' => $props['export_as'] ?? null,
			'nullable' => $props['nullable'] ?? false,
			'accessible' => $props['accessible'] ?? true,
			'required' => $props['required'] ?? false,
			'default' => $props['default'] ?? null,
			'label' => $props['label'] ?? null,
			'validator' => $props['validator'] ?? null,
			'custom' => $props['custom'] ?? []
		];

		return $this;
	}

	/**
	 * Goes back to parent payload.
	 *
	 * @since 1.1.0
	 * @return PayloadMap
	 */
	public function back () : PayloadMap
	{ return $this->_parent; }

	/**
	 * Set validator property to field.
	 *
	 * @since 1.1.0
	 * @return self
	 * @throws InvalidDataException
	 */
	public function assert ()
	{ 
		if ( !$this->validate() )
		{ throw InvalidDataException::invalid($this->_parent, $this->_key, $this->_value, 'You must to fix it'); }
	}

	/**
	 * Generate a storable representation of payload object.
	 * 
	 * @since 1.1.0
	 * @return string
	 */
	public function serialize ()
	{ 
		return \serialize(
			\array_merge(
				$this->_props,
				['_key' => $this->_key, '_value' => $this->_value ?? null]
			)
		); 
	}

	/**
	 * Create a Payload object from a stored representation.
	 * 
	 * @param string $data
	 * @since 1.1.0
	 * @return string
	 */
	public function unserialize ( $data )
	{ 
		$data = \unserialize($data); 

		$this->_key = $data['_key'];
		$this->_value = $data['_value'];

		$this->props($data);
	} 
}