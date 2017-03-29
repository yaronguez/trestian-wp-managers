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
	 * Fetches and optionally sanitizes data from POST while triggering error if missing
	 * @param $field
	 * @param $message
	 * @param bool $sanitize_text
	 *
	 * @return string
	 */
    public function check_missing_data($field, $message, $sanitize_text = true)
    {
        if (!isset($_POST[$field])) {
            $this->return_error($message);
        } else if($sanitize_text){
        	return sanitize_text_field($_POST[$field]);
        }
        else {
            return $_POST[$field];
        }
    }
}
