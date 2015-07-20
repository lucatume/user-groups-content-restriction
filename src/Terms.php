<?php


class ugcr_Terms {

	public static function instance() {
		return new self;
	}

	public function get_default_role_slug() {
		return apply_filters( 'ugcr_default_role_slug', 'visitor' );
	}
}