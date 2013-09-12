<?php
/**
 * MYSQL Resource class
 * Kust/Resource.php
 *
 * --------------------
 * Author:		Benoît Zuckschwerdt @ 09.11.2010
 * --------------------
 *
 * Resource mysql.
 *
 **/

class Kust_Resource{
	private $res;
	
	function __construct($res) {
		$this->res	= $res;
	}
	
	function fetchAll() {
		while($aLineData = mysql_fetch_assoc($this->res))
			$aData[] = $aLineData;
			
		return $aData;
	}
	
	function fetch() {
		return mysql_fetch_assoc($this->res);
	}
	
	function count() {
		return mysql_num_rows($this->res);
	}
}

?>
