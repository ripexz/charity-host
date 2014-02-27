<?php

/*
* Validation functions by Derek Bridge
*/

/**
 * Validates a user's input for a text field that is (a) required; (b) a string; (c) no greater than a certain length.
 * 
 * @param $user_data the associative array that contains the user's data (usually the actual parameter will be $_GET or $_POST)
 * @param $name the value of the HTML name attribute for this text field
 * @param $label the content of the HTML label element that accompanies this textfield (used in error messages)
 * @param $maxlength the value of the HTML maxlength attribute for this textfield
 * @param $errors an associative array to which error messages are appended. An element in the array has $name as its key, and an error message as its value, e.g. 'firstname' => 'Firstname is required'
 * @return if there are no errors, returns the user's input (trimmed of leading and trailing spaces); otherwise (errors), returns NULL
 */
function get_required_string( &$user_data, $name, $label, $maxlength, &$errors )
{
	if ( ! isset($user_data[$name]) )
	{
		$errors[$name] = "{$label} is required";
		return NULL;
	}
	$value = trim($user_data[$name]);
	if ( $value == '' )
	{
		$errors[$name] = "{$label} is required";
		return NULL;
	}
	if ( strlen($value) > $maxlength  )
	{
		$errors[$name] = "{$label} must be {$maxlength} characters or less";
		return NULL;
	}
	return $value;
}

/**
 * Validates a user's input for a text field that is (a) optional; (b) a string; (c) no greater than a certain length.
 * 
 * @param $user_data the associative array that contains the user's data (usually the actual parameter will be $_GET or $_POST) 
 * @param $name the value of the HTML name attribute for this text field
 * @param $label the content of the HTML label element that accompanies this textfield (used in error messages)
 * @param $maxlength the value of the HTML maxlength attribute for this textfield
 * @param $errors an associative array to which error messages are appended. An element in the array has $name as its key, and an error message as its value, e.g. 'firstname' => 'Firstname is required'
 * @return if the user supplied a non-empty value and there are no errors, returns the user's input (trimmed of leading and trailing spaces); otherwise (errors or user didn't supply a value), returns NULL
 */
function get_optional_string( &$user_data, $name, $label, $maxlength, &$errors )
{
	if ( ! isset($user_data[$name]) )
	{
		return NULL;
	}
	$value = trim($user_data[$name]); 
	if ( $value == '' )
	{
		return NULL;
	}
	if ( strlen($value) > $maxlength )
	{
		$errors[$name] = "{$label} must be {$maxlength} characters or less";
		return NULL;
	}
	return $value; 
}

/**
 * Validates a user's input for a text field that is (a) defaulting; (b) a string; (c) no greater than a certain length.
 * 
 * @param $user_data the associative array that contains the user's data (usually the actual parameter will be $_GET or $_POST)
 * @param $name the value of the HTML name attribute for this text field
 * @param $label the content of the HTML label element that accompanies this textfield (used in error messages)
 * @param $maxlength the value of the HTML maxlength attribute for this textfield
 * @param $errors an associative array to which error messages are appended. An element in the array has $name as its key, and an error message as its value, e.g. 'firstname' => 'Firstname is required'
 * @param $default the default value to be returned if the user doesn't supply a value
 * @return if the user supplied a value and there are no errors, returns the user's input (trimmed of leading and trailing spaces); if the user didn't supply a value, returns a default value; otherwise (errors), returns NULL
 */    
function get_defaulting_string( $user_data, $name, $label, $maxlength, &$errors, $default )
{
	if ( ! isset($user_data[$name]) )
	{
		return $default;
	}
	$value = trim($user_data[$name]);
	if ( $value == '' )
	{
		return $default;
	}
	if ( strlen($value) > $maxlength )
	{
		$errors[$name] = "{$label} must be {$maxlength} characters or less";
		return NULL;
	} 
	return $value;
}

/**
 * Validates a user's input for a text field that is (a) required; (b) an int; (c) no greater than a certain length; (d) in a certain range.
 *
 * @param $user_data the associative array that contains the user's data (usually the actual parameter will be $_GET or $_POST) 
 * @param $name the value of the HTML name attribute for this text field
 * @param $label the content of the HTML label element that accompanies this textfield (used in error messages)
 * @param $maxlength the value of the HTML maxlength attribute for this textfield
 * @param $errors an associative array to which error messages are appended. An element in the array has $name as its key, and an error message as its value, e.g. 'firstname' => 'Firstname is required'
 * @param $min the minimum legal value
 * @param $max the maximum legal value
 * @return if there are no errors, returns the user's input (as an int); otherwise, returns NULL
 */
function get_required_int( &$user_data, $name, $label, $maxlength, &$errors, $min, $max )
{
	if ( ! isset($user_data[$name]) )
	{
		$errors[$name] = "{$label} is required";
		return NULL;
	}
	$value = trim($user_data[$name]);
	if ( $value == '' )
	{
		$errors[$name] = "{$label} is required";
		return NULL;
	}
	if ( strlen($value) > $maxlength  )
	{
		$errors[$name] = "{$label} must be {$maxlength} characters or less";
		return NULL;
	}
	if ( $value != (string) (int) $value ) // does $value contain an int?
	{
		$errors[$name] = "{$label} must be a whole number";
		return NULL;
	}
	$value = (int) $value;
	if ( $value < $min || $value > $max )
	{
		$errors[$name] = "{$label} must be between {$min} and {$max} inclusive";
		return NULL;
	}
	return $value;
}

/**
 * Validates a user's input for a text field that is (a) optional; (b) an int; (c) no greater than a certain length; (d) in a certain range.
 *
 * @param $user_data the associative array that contains the user's data (usually the actual parameter will be $_GET or $_POST)
 * @param $name the value of the HTML name attribute for this text field
 * @param $label the content of the HTML label element that accompanies this textfield (used in error messages)
 * @param $maxlength the value of the HTML maxlength attribute for this textfield
 * @param $errors an associative array to which error messages are appended. An element in the array has $name as its key, and an error message as its value, e.g. 'firstname' => 'Firstname is required'
 * @param $min the minimum legal value
 * @param $max the maximum legal value
 * @return if the user supplied a non-empty value and there are no errors, returns the user's input (as an int); otherwise (errors or user didn't supply a value), returns NULL
 */    
function get_optional_int( &$user_data, $name, $label, $maxlength, &$errors, $min, $max )
{
	if ( ! isset($user_data[$name]) )
	{
		return NULL;
	}
	$value = trim($user_data[$name]); 
	if ( $value == '' )
	{
		return NULL;
	}
	if ( strlen($value) > $maxlength )
	{
		$errors[$name] = "{$label} must be {$maxlength} characters or less";
		return NULL;
	}
	if ( $value != (string) (int) $value ) // does $value contain an int?
	{
		$errors[$name] = "{$label} must be a whole number";
		return NULL;
	}
	$value = (int) $value;
	if ( $value < $min || $value > $max )
	{
		$errors[$name] = "{$label} must be between {$min} and {$max} inclusive";
		return NULL;
	}
	return $value;
}

/**
 * Validates a user's input for a text field that is (a) defaulting; (b) an int; (c) no greater than a certain length; (d) in a certain range.
 *
 * @param $user_data the associative array that contains the user's data (usually the actual parameter will be $_GET or $_POST)
 * @param $name the value of the HTML name attribute for this text field
 * @param $label the content of the HTML label element that accompanies this textfield (used in error messages)
 * @param $maxlength the value of the HTML maxlength attribute for this textfield
 * @param $errors an associative array to which error messages are appended. An element in the array has $name as its key, and an error message as its value, e.g. 'firstname' => 'Firstname is required'
 * @param $min the minimum legal value
 * @param $max the maximum legal value
 * @param $default the default value to be returned if the user doesn't supply a value
 * @return if the user supplied a value and there are no errors, returns the user's input (as an int); if the user didn't supply a value, returns a default value; otherwise (errors), returns NULL
 */    
function get_defaulting_int( $user_data, $name, $label, $maxlength, &$errors, $min, $max, $default )
{
	if ( ! isset($user_data[$name]) )
	{
		return $default;
	}
	$value = trim($user_data[$name]);
	if ( $value == '' )
	{
		return $default;
	}
	if ( strlen($value) > $maxlength )
	{
		$errors[$name] = "{$label} must be {$maxlength} characters or less";
		return NULL;
	} 
	if ( $value != (string) (int) $value ) // does $value contain an int?
	{
		$errors[$name] = "{$label} must be a whole number";
		return NULL;
	}
	$value = (int) $value;
	if ( $value < $min || $value > $max )
	{
		$errors[$name] = "{$label} must be between {$min} and {$max} inclusive";
		return NULL;
	}
	return $value;
}

/**
 * Validates a user's input for a text field that is (a) required; (b) a float; (c) no greater than a certain length; (d) in a certain range.
 *
 * @param $user_data the associative array that contains the user's data (usually the actual parameter will be $_GET or $_POST)
 * @param $name the value of the HTML name attribute for this text field
 * @param $label the content of the HTML label element that accompanies this textfield (used in error messages)
 * @param $maxlength the value of the HTML maxlength attribute for this textfield
 * @param $errors an associative array to which error messages are appended. An element in the array has $name as its key, and an error message as its value, e.g. 'firstname' => 'Firstname is required'
 * @param $min the minimum legal value
 * @param $max the maximum legal value
 * @return if there are no errors, returns the user's input (as a float); otherwise, returns NULL
 */
function get_required_float( &$user_data, $name, $label, $maxlength, &$errors, $min, $max )
{
	if ( ! isset($user_data[$name]) )
	{
		$errors[$name] = "{$label} is required";
		return NULL;
	}
	$value = trim($user_data[$name]);
	if ( $value == '' )
	{
		$errors[$name] = "{$label} is required";
		return NULL;
	}
	if ( strlen($value) > $maxlength  )
	{
		$errors[$name] = "{$label} must be {$maxlength} characters or less";
		return NULL;
	}
	if ( ! is_numeric($value) ) // does $value contain a float?
	{
		$errors[$name] = "{$label} must be a number";
		return NULL;
	}
	$value = (float) $value;
	if ( $value < $min || $value > $max )
	{
		$errors[$name] = "{$label} must be between {$min} and {$max} inclusive";
		return NULL;
	}
	return $value;
}

/**
 * Validates a user's input for a text field that is (a) optional; (b) a float; (c) no greater than a certain length; (d) in a certain range.
 *
 * @param $user_data the associative array that contains the user's data (usually the actual parameter will be $_GET or $_POST)
 * @param $name the value of the HTML name attribute for this text field
 * @param $label the content of the HTML label element that accompanies this textfield (used in error messages)
 * @param $maxlength the value of the HTML maxlength attribute for this textfield
 * @param $errors an associative array to which error messages are appended. An element in the array has $name as its key, and an error message as its value, e.g. 'firstname' => 'Firstname is required'
 * @param $min the minimum legal value
 * @param $max the maximum legal value
 * @return if the user supplied a non-empty value and there are no errors, returns the user's input (as a float); otherwise (errors or user didn't supply a value), returns NULL
 */    
function get_optional_float( &$user_data, $name, $label, $maxlength, &$errors, $min, $max )
{
	if ( ! isset($user_data[$name]) )
	{
		return NULL;
	}
	$value = trim($user_data[$name]); 
	if ( $value == '' )
	{
		return NULL;
	}
	if ( strlen($value) > $maxlength )
	{
		$errors[$name] = "{$label} must be {$maxlength} characters or less";
		return NULL;
	}
	if ( ! is_numeric($value) ) // does $value contain a float?
	{
		$errors[$name] = "{$label} must be a number";
		return NULL;
	}
	$value = (float) $value;
	if ( $value < $min || $value > $max )
	{
		$errors[$name] = "{$label} must be between {$min} and {$max} inclusive";
		return NULL;
	}
	return $value;
}

/**
 * Validates a user's input for a text field that is (a) defaulting; (b) a float; (c) no greater than a certain length; (d) in a certain range.
 *
 * @param $user_data the associative array that contains the user's data (usually the actual parameter will be $_GET or $_POST)
 * @param $name the value of the HTML name attribute for this text field
 * @param $label the content of the HTML label element that accompanies this textfield (used in error messages)
 * @param $maxlength the value of the HTML maxlength attribute for this textfield
 * @param $errors an associative array to which error messages are appended. An element in the array has $name as its key, and an error message as its value, e.g. 'firstname' => 'Firstname is required'
 * @param $min the minimum legal value
 * @param $max the maximum legal value
 * @param $default the default value to be returned if the user doesn't supply a value
 * @return if the user supplied a value and there are no errors, returns the user's input (as a float); if the user didn't supply a value, returns a default value; otherwise (errors), returns NULL
 */    
function get_defaulting_float( $user_data, $name, $label, $maxlength, &$errors, $min, $max, $default )
{
	if ( ! isset($user_data[$name]) )
	{
		return $default;
	}
	$value = trim($user_data[$name]);
	if ( $value == '' )
	{
		return $default;
	}
	if ( strlen($value) > $maxlength )
	{
		$errors[$name] = "{$label} must be {$maxlength} characters or less";
		return NULL;
	} 
	if ( ! is_numeric($value) ) // does $value contain a float?
	{
		$errors[$name] = "{$label} must be a number";
		return NULL;
	}
	$value = (float) $value;
	if ( $value < $min || $value > $max )
	{
		$errors[$name] = "{$label} must be between {$min} and {$max} inclusive";
		return NULL;
	}
	return $value;
}

/**
 * Validates a user's input for a group of radio buttons for which a selection is required.
 *
 * @param $request_data the associative array that contains the user's data (usually the actual parameter will be $_GET or $_POST)
 * @param $name the value of the HTML name attribute for this group of radio buttons
 * @param $label a piece of text that describes this group of buttons (used in error messages)
 * @param $legal_values an array that contains the value of the HTML value attribute for each button in this group
 * @param $errors an associative array to which error messages are appended. An element in the array has $name as its key, and an error message as its value, e.g. 'firstname' => 'Firstname is required'
 * @return if no errors, returns the value associated with the button the user checks; otherwise, returns NULL
 */   
function get_required_radio( &$user_data, $name, $label, $legal_values, &$errors )
{
	if ( ! isset($user_data[$name]) )
	{
		$errors[$name] = "You didn't select one of the {$label} buttons";
		return NULL;
	}
	$value = $user_data[$name];
	if ( ! in_array( $value, $legal_values) )
	{
		$errors[$name] = "You provided an illegal value for the {$label} buttons";
		return NULL;
	}
	return $value;
}

/**
 * Validates a user's input for a group of radio buttons for which a selection is optional
 *
 * @param $request_data the associative array that contains the user's data (usually the actual parameter will be $_GET or $_POST) 
 * @param $name the value of the HTML name attribute for this group of radio buttons
 * @param $label a piece of text that describes this group of buttons (used in error messages)
 * @param $legal_values an array that contains the value of the HTML value attribute for each button in this group
 * @param $errors an associative array to which error messages are appended. An element in the array has $name as its key, and an error message as its value, e.g. 'firstname' => 'Firstname is required'
 * @return if no errors and one of the buttons was checked, returns the value associated with that button; otherwise (errors or no button checked), returns NULL
 */ 
function get_optional_radio( &$user_data, $name, $label, $legal_values, &$errors )
{
	if ( ! isset($user_data[$name]) )
	{
		return NULL;
	}
	$value = $user_data[$name];
	if ( ! in_array( $value, $legal_values) )
	{
		$errors[$name] = "You provided an illegal value for the {$label} buttons";
		return NULL;
	}
	return $value;
}    

/**
 * Validates a user's input for a single checkbox (hence selection is optional)
 *
 * @param $request_data the associative array that contains the user's data (usually the actual parameter will be $_GET or $_POST) 
 * @param $name the value of the HTML name attribute for this checkbox
 * @param $label a piece of text that describes this checkbox (used in error messages)
 * @param $legal_value the value of the HTML value attribute for the checkbox
 * @param $errors an associative array to which error messages are appended. An element in the array has $name as its key, and an error message as its value, e.g. 'firstname' => 'Firstname is required'
 * @return if no errors and one of the buttons was checked, returns the value associated with that button; otherwise (errors or no button checked), returns NULL
 */
function get_optional_checkbox( &$user_data, $name, $label, $legal_value, &$errors )
{
	if ( ! isset($user_data[$name]) )
	{
		return NULL;
	}
	$value = $user_data[$name];
	if ( $value != $legal_value )
	{
		$errors[$name] = "You provided an illegal value for the {$label} checkbox";
		return NULL;
	}
	return $value;
}   

/**
 * Validates a user's input for a group of  checkboxes (hence, zero, one or more selections)
 * 
 * @param $request_data the associative array that contains the user's data (usually the actual parameter will be $_GET or $_POST)
 * @param $name the value of the HTML name attribute for this group of checkboxes
 * @param $label a piece of text that describes this group of checkboxes (used in error messages)
 * @param $legal_values an array that contains the value of the HTML value attribute for each checkbox in this group
 * @param $errors an associative array to which error messages are appended. An element in the array has $name as its key, and an error message as its value, e.g. 'firstname' => 'Firstname is required'
 * @return if no errors, returns an array (possibly empty) containing the value associated with each checkbox the user checked; otherwise (errors), returns NULL
 */
function get_multiple_checkboxes( &$user_data, $name, $label, $legal_values, &$errors )
{
	if ( ! isset($user_data[$name]) )
	{
		return array();
	}
	$values = $user_data[$name];
	if ( ! is_array($values) )
	{
		$errors[$name] = "You provided an illegal value for the {$label} checkbox";
		return NULL;
	}
	foreach ($values as $value)
	{
		if ( ! in_array($value, $legal_values) )
		{
			$errors[$name] = "You provided an illegal value for the {$label} checkbox";
			return NULL;
		}
	}
	return $values;
}   
 
?>