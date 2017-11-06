<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Admin AJAX Functionality
 *
 *
 * @package    TrestianWPManagers
 * @subpackage TrestianWPManagers/managers
 * @author     Yaron Guez <yaron@trestian.com>
 */
class Trestian_Ajax_Manager{

	/**
	 * Return an AJAX response
	 *
	 * @param $message - Message to include in payload
	 * @param $success - Whether to return a success or error response
	 * @param array $data - Optional data to include in payload
	 */
    public function return_response($message, $success, $data=array()){
        $response = array(
            'success' => $success,
            message => $message
        );
        if(!empty($data)){
        	$response = array_merge($data, $response);
        }
        echo json_encode($response);
        wp_die();
    }

	/**
	 * Returns an AJAX error response
	 *
	 * @param $message - Error message to include in payload
	 * @param array $data - Optional data to include in payload
	 */
    public function return_error($message, $data=array()){
        $this->return_response($message, false, $data);
    }

	/**
	 * Returns an AJAX success response
	 *
	 * @param $message - Success message to include in payload
	 * @param array $data - Optional data to include in payload
	 */
    public function return_success($message, $data=array()){
        $this->return_response($message, true, $data);
    }

	/**
	 * Fetches and optionally sanitizes data while triggering error if missing
	 *
	 * @param $field - field name to check
	 * @param $message - error message to return on failure
	 * @param bool $sanitize_text - whether or not to sanitize the data property as well
	 * @param null $data - data source. defaults to $_REQUEST
	 *
	 * @return string
	 */
    public function check_missing_data($field, $message, $sanitize_text = true, $data = null)
    {
    	if($data == null){
    		$data = $_REQUEST;
	    }

        if (
        	!isset($data[$field]) ||
            (is_array($data[$field]) && empty($data[$field])) ||
            (!is_array($data[$field]) && !strlen($data[$field]))
        ) {
            $this->return_error($message);
        } else if($sanitize_text){
        	return $this->sanitize_text($data[$field]);
        }
        else {
            return $data[$field];
        }
    }

    private function sanitize_text($field){
    	if(is_array($field)){
    		foreach($field as $i => $value){
    			$field[$i] = $this->sanitize_text($value);
		    }
		    return $field;
	    }

	    return sanitize_text_field($field);
    }

	/**
	 * Fetches and optionally sanitizes data from POST while triggering error if missing
	 *
	 * @param $field - Which POST field to check
	 * @param $message - Error message when field is missing
	 * @param string $format - Date format expected
	 * @param string $invalid_date_message - Error to return on invalid date

	 *
	 * @return DateTime
	 */
	public function check_missing_date($field, $message, $format = 'Y-m-d', $invalid_date_message = 'Invalid date specified', $data = null, $returnDateTime = true)
	{
		if($data == null){
			$data = $_REQUEST;
		}
		if (!isset($data[$field]) || !strlen($data[$field])) {
			$this->return_error($message);
		}
		$val = sanitize_text_field($data[$field]);

		$date = DateTime::createFromFormat($format, $val);
		if(!$date || $date->format($format) !== $val){
			$this->return_error($invalid_date_message);
		}

		if($returnDateTime) {
			return $date;
		} else {
			return $val;
		}
	}


	/**
	 * Helper function to validate nonce in form submission
	 *
	 * @param string $nonce_field
	 * @param string $action_field
	 * @param string $message
	 * @return string - action of form
	 */
    public function validate_nonce($nonce_field = 'nonce', $action_field = 'action', $message='The form has expired. Please refresh the page and try again'){
    	if(!isset($_REQUEST[$nonce_field]) || !isset($_REQUEST[$action_field]) || !wp_verify_nonce($_REQUEST[$nonce_field], $_REQUEST[$action_field])){
    		$this->return_error($message);
	    }

	    return $_REQUEST[$action_field];
    }
}
