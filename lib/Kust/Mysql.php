<?php
/**
* MYSQL class
* Kust/Mysql.php
*
* --------------------
* Author:	Benoit Zuckschwerdt, @ 28.10.2010
* --------------------
*
* Objet mysql.
 * 
 * Version 1.2
 * 
 * 
 * Changes:
 *  + v1.4
 *    - Adaptation for Kust project
 * 	+ v1.3 - Benoit Zuckschwerdt, 10.08.2012
 * 		- new method delete
 * 		- new method update
 * 		- new method insert
 * 	+ v1.2 - Benoit Zuckschwerdt, 07.08.2012
 * 		- add existsTable method
 * 	+ v1.1 - Benoit Zuckscherdt, 01.11.2010
 * 		- add translate method
**/

class Kust_Mysql {
	public $host;
	public $user;
	public $password;
	public $database;
	public $rid;
	public $res;
	
	
	function __construct($aConfiguration=null) {
		$this->setConfig($aConfiguration);	
	}
	
	
	public function setConfig($aConfiguration) {
		(isset($aConfiguration['host']) ?		$this->host		= $aConfiguration['host'] : null);
		(isset($aConfiguration['user']) ?		$this->user		= $aConfiguration['user'] : null);
		(isset($aConfiguration['password']) ?	$this->password		= $aConfiguration['password'] : null);
		(isset($aConfiguration['database']) ?	$this->database		= $aConfiguration['database'] : null);
	}
	
	
	public function connect() {
		$this->rid = mysql_connect($this->host, $this->user, $this->password) or die(mysql_error());
		mysql_select_db($this->database, $this->rid) or die(mysql_error());
		//$this->query("SET NAMES UTF8");
	}
	
	
	public function close() {
		mysql_close($this->rid) or die(mysql_error());
	}
	
	
	public function query_nobj($sSqlQuery) {
		$sSqlQuery = $this->translate($sSqlQuery);
		$this->res = mysql_query($sSqlQuery, $this->rid) or die(mysql_error());
		return $this->res;
	}
	
	
	public function count($res=null) {
		return mysql_num_rows(($res != null ? $res : $this->res)) or die(mysql_error());	
	}
	
	
	public function fetch($res=null) {
		return mysql_fetch_assoc(($res != null ? $res : $this->res)) or die(mysql_error());	
	}
	
	
	public function setVariable($sVariable, $sValue) {
		$this->$sVariable = $sValue;
	}
	
	
	public function query($sSqlQuery) {
		$sSqlQuery = $this->translate($sSqlQuery);
		$this->res = mysql_query($sSqlQuery, $this->rid) or die(mysql_error());
		return new Kust_resource($this->res);
	}
	
	
	public function secure($unescaped_string) {
		return mysql_real_escape_string(htmlentities($unescaped_string, $this->rid));
	}
	
	
	private function translate($sql) {
		if(LANG!='fr') {
			$b = stristr($sql, 'from');
			$a = str_replace($b, '', $sql);
			$a_new = str_replace('ing_name', 'ing_name_'.LANG.' as \'ing_name\'', $a);
			$a_new = str_replace('too_name', 'too_name_'.LANG.' as \'too_name\'', $a_new);
			$sql = str_replace($a, $a_new, $sql);
		}
		
		return $sql;
	}
	
	
	/**
	 * Exists table
	 * @return boolean
	 */
	public function existsTable($sTable) {
		$result = $this->query_nobj('SHOW TABLES FROM '.$this->database);
		
		if(!$result) return false;
		
		while($row = mysql_fetch_row($result))
			if($row == $sTable) { return 1; }
		
		return 0;
	}
	
	
	/**
	 * Delete records
	 * @param string the table to remove rows from
     * @param string the condition for which rows are to be removed
     * @param int the number of rows to be removed
     * @return void
	 */
	public function delete($table, $condition, $limit) {
		$limit = ( $limit == '' ) ? '' : ' LIMIT ' . $limit;  
        $delete = "DELETE FROM `$table` WHERE $condition $limit";  
        return $this->query_nobj($delete);
	}
	
	
	/**
     * Update records in the database
     * @param string the table
     * @param array of changes field => value
     * @param string the condition
     * @return boolean
     */
	public function update( $table, $changes, $condition ) {  
		$update = "UPDATE `" . $table . "` SET ";
		foreach( $changes as $field => $value )
		$update .= "`" . $field . "`='$value',";
		
		# Remove last comma
		$update = substr($update, 0, -1);
		
		if($condition != '')
			$update .= "WHERE " . $condition;
		
		return $this->query_nobj($update);
	}
	
	
	/**
	 * Insert records into the database 
	 * @param string $table
	 * @param array $data - data to insert field => value 
	 * @return bool 
	 */
	public function insert($table, $data) {
		// setup some variables for fields and values
		$fields = "";
		$values = "";
		
		// populate them
		foreach ($data as $f => $v) {
			$fields .= "`$f`,";
			$values .= ( is_numeric( $v ) && ( intval( $v ) == $v ) ) ? $v."," : "'$v',";
		}
		
		// remove our trailing ,
		$fields = substr($fields, 0, -1);
		// remove our trailing ,
		$values = substr($values, 0, -1);
		  
		$insert = "INSERT INTO `$table` ($fields) VALUES($values)";
		return $this->query_nobj($insert);
	}
	
	
}
?>
