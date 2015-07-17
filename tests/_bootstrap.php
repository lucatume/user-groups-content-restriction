<?php
// This is global bootstrap for autoloading
function tests_plug_functions() {
	global $_user_logged_in;

	function is_user_logged_in() {
		global $_user_logged_in;

		return $_user_logged_in;
	}
}

tests_plug_functions();

function tests_set_current_user( $user ) {
	global $current_user;
	$current_user = $user;
}
