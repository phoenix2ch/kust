<?php
/**
 * Model MVC system class
 *
 * Author:	Benoit Zuckschwerdt
 * Date:	8 August 2012
 *
 */

# Security access
if( !defined( "IN_SITE" ) ){
	header("HTTP/1.0 403 Forbidden");
	die();
}


class Kust_Model {
	function __construct(){}
}
