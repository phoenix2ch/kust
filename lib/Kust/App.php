<?php
/**
 * Application MVC system class
 *
 * Author:	Benoit Zuckschwerdt
 * Date:	7 August 2012
 *
 *
 */


# Security access
if( !defined( "IN_SITE" ) ){
	header("HTTP/1.0 403 Forbidden");
	die();
}


class Kust_App
{
	private static $frameworkName = 'Sample Framework';
	private static $frameworkVersion = '0.0.0';

	public $uri;


	/**
	 * Constructor
	 * @param array $uri
	 */
	public function __construct( $uri = null ) {
		$this->uri = $uri;

		$this->loadController( $uri['controller'] );
	}


	/**
	 * Load controller
	 * @param string $class
	 */
	public function loadController( $class )
	{
		$file = "app/controllers/".$this->uri['controller'].".controller.php";

		if(!file_exists($file)) die( "controller not found at $file" );

		require_once($file);

		$controller = new $class();

		if( method_exists( $controller, $this->uri['method'] ) ){
			$controller->{$this->uri['method']}( $this->uri['var'] );
		}
		else {
			$controller->index();
		}
	}


	/**
	 * Get Framework name
	 * @return string
	 */
	public function getFrameworkName() {
		return self::$frameworkName;
	}
	
	
	/**
	 * Get Framework version
	 * @return string
	 */
	public function getFrameworkVersion() {
		return self::$frameworkVersion;
	}
}
?>
