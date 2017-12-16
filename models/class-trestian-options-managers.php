<?php
class Trestian_Options_Managers {
	const ACF = 'ACF';

	const CMB2 = 'CMB2';

	public static $mapping = array(
		self::ACF => 'Trestian_Acf_Manager',
		self::CMB2 => 'Trestian_Cmb2_Manager'
	);

	public static function get_class($option){
		if(isset(self::$mapping[$option])){
			return self::$mapping[$option];
		}

		return false;
	}


}