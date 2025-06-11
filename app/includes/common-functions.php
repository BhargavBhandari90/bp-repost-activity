<?php
/**
 * Common functions.
 *
 * @package Bp_Repost_Activity
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Format Large number.
 *
 * @param integer $num Number to be formatted.
 * @return string      Formatted number.
 */
function bprpa_format_number_short( $num ) {
	if ( $num >= 1000000000 ) {
		return number_format( $num / 1000000000, 1 ) . 'B';
	} elseif ( $num >= 1000000 ) {
		return number_format( $num / 1000000, 1 ) . 'M';
	} elseif ( $num >= 1000 ) {
		return number_format( $num / 1000, 1 ) . 'K';
	} else {
		return $num;
	}
}
