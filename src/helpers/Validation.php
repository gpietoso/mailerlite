<?php
/**
 * Mailerlite API
 *
 * @link      https://github.com/gpietoso/mailerlite
 * @copyright Copyright (c) 2018 Giuliano Pietoso
 */

namespace MailerLite;

/**
 * Validation
 *
 * This class provides validation methods for the API
 */
class Validation
{

	/**
	 * Validates if passed id is a valid numberss
	 *
	 * @param int $id The passed id
	 */
	public static function validateId($id){
		return (int)$id && $id > 0;
	}

	/**
	 * Validates if passed field type exists
	 *
	 * @param string $type The passed type
	 */
	public static function isValidType($type){
		if(!in_array($type, array("date", "number", "string", "boolean"))) {
			return false;
		}
		return true;
	}

	/**
	 * Validates if passed fields exist
	 *
	 * @param array $fields Passed fields
	 * @param array $valid_fields Valid fields
	 */
	public static function areValidFields($fields, $validFields){
		foreach($validFields as $validField){
			$validFieldArr[] = $validField['title'];
		}
		foreach($fields as $field){
			if(!in_array($field->title, $validFieldArr)) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Validates if payload contains the minimum fields for subscriber
	 *
	 * @param array $fields Passed fields from payload
	 */
	public static function meetsMinimumFieldRequirement($fields){
		$min = 0;
		foreach($fields as $field){
			if(in_array($field->title, array("email_address", "name", "state"))) {
				$min++;
			}
		}

		if($min == 3){
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Validates if the email is valid
	 *
	 * @param string $email The passed email
	 */
	public static function isValidEmail($fields){
		foreach($fields as $field){
			if($field->title == "email_address") {
				$validFormat =  filter_var($field->value, FILTER_VALIDATE_EMAIL);

				$domainArr  = explode('@', $field->value);
				$activeDomain = checkdnsrr($domainArr[1], 'ANY');
			}
		}
		return $validFormat && $activeDomain;
	}
}

?>
