<?php
/**
 * ACL class
 *
 * Author:	Benoit Zuckschwerdt
 * Date:	9 August 2012
 *
 * Thank to Andrew Steenbuck for his great tutorial
 * 	-> http://net.tutsplus.com/tutorials/php/a-better-login-system/
 *
 * Version 1.0
 *
 * Requirements:
 * 	+ Kust_Mysql class		>=v1.4
 * 	+ Kust_Resource class	>=v1.4
 *
 * Changes:
 * 	+ {...}
 */


class Kust_ACL {

	private $perms	= array();
	private $userID	= 0;
	private $userRoles;


	/**
	 * Constructor
	 * @param int $userID
	 */
	public function __construct($userID='') {
		$this->userID = ($userID == '' ? (!empty($_SESSION['user']) ? $_SESSION['user'] : -1) : $userID);

		$this->userRoles = $this->getUserRoles();
		$this->buildACL();
	}


	/**
	 * Get user roles
	 * @return int
	 */
	public function getUserRoles() {
		$sql = "
		SELECT uro_role_id FROM ".T_USER_ROLE."
		WHERE uro_user_id = '".$this->userID."'
		ORDER BY uro_add_date ASC";
		$oResource = $GLOBALS['mysql']->query($sql);

		$resp=array();
		while($row = $oResource->fetch())
			$resp[]	= $row['uro_role_id'];

		return $resp;
	}


	/**
	 * Get all roles
	 * @param string $format
	 * @return array
	 */
	 public function getAllRoles($bWithRoleName=false) {

		$sql = "
		SELECT id_role, rol_name FROM ".T_ROLE."
		ORDER BY rol_name ASC";
		$oResource = $GLOBALS['mysql']->query($sql);

		$resp=array();
		while($row = $oResource->fetch()) {
			if($bWithRoleName)
				$resp[] = array("id" => $row['id_role'],"name" => $row['rol_name']);
			else
				$resp[] = $row['id_role'];
		}


		return $resp;
	 }


	 /**
	  * Build ACL
	  */
	 public function buildACL() {
	 	#
	 	if(count($this->userRoles) > 0)
			$this->perms = array_merge($this->perms, $this->getRolePerms($this->userRoles));

		$this->perms = array_merge($this->perms, $this->getUserPerms($this->userID));
	 }


	 /**
	  * Get perm from ID
	  * @param int $permID
	  * @return int
	  */
	public function getPermFromID($permID) {
		$sql = "
		SELECT per_key FROM ".T_PERM."
		WHERE id_per = '$permID'
		LIMIT 1";
		$oResource = $GLOBALS['mysql']->query($sql);
		$row = $oResource->fetch();

		return $row['per_key'];
	}


	 /**
	  * Get perm name from ID
	  * @param int $permID
	  * @return string
	  */
	public function getPermNameFromID($permID) {
		$sql = "
		SELECT per_name FROM ".T_PERM."
		WHERE id_per = '$permID'
		LIMIT 1";

		$oResource = $GLOBALS['mysql']->query($sql);
		$row = $oResource->fetch();

		return $row['per_name'];
	}


	/**
	 * Get role name from ID
	 * @param int $roleID
	 * @return string
	 */
	 public function getRoleNameFromID($roleID) {
	 	$sql = "
	 	SELECT rol_name FROM ".T_ROLE."
	 	WHERE id_rol = '$roleID'
	 	LIMIT 1";

		$oResource = $GLOBALS['mysql']->query($sql);
		$row = $oResource->fetch();

		return $row['rol_name'];
	 }


	 /**
	  * Get user login
	  * @param int $userID
	  * @return string
	  */
	 public function getUserLogin($userID) {
		$sql = "
		SELECT use_login FROM ".T_USER."
		WHERE id_use = '$userID'
		LIMIT 1";

		$oResource = $GLOBALS['mysql']->query($sql);
		$row = $oResource->fetch();

		return $row['use_login'];
	 }


	 /**
	  * Get role perms
	  * @param array|int $role
	  * @return array
	  */
	 public function getRolePerms($role) {

		# For array
	 	if(is_array($role))
			$sql = "
			SELECT rpe_perm_id, rpe_value FROM ".T_ROLE_PERM."
			WHERE rpe_role_id IN (".implode(',',$role).")
			ORDER BY id_rpe ASC";

		# For unique int
		else
			$sql = "
			SELECT rpe_perm_id, rpe_value FROM ".T_ROLE_PERM."
			WHERE rpe_role_id = '$role'
			ORDER BY id_rpe ASC";

		$oResource = $GLOBALS['mysql']->query($sql);
		$perms = array();
		while($row = $oResource->fetch()) {
			$permKey = strtolower($this->getPermFromID($row['rpe_perm_id']));
			if($permKey == '') continue;
			$hP = ($row['rpe_value'] === '1');

			$perms[$permKey] = array(
				'perm' 			=> $permKey,
				'inheritted'	=> true,
				'value'			=> $hP,
				'name'			=> $this->getPermNameFromID($row['rpe_perm_id']),
				'id'			=> $row['rpe_perm_id']);
		}

		return $perms;
	}


	/**
	 * Get user perms
	 * @param int $userID
	 * @return array
	 */
	public function getUserPerms($userID) {
		$sql = "
		SELECT upe_perm_id, upe_value FROM ".T_USER_PERM."
		WHERE upe_user_id = '$userID'
		ORDER BY `upe_add_date` ASC";
		$oResource = $GLOBALS['mysql']->query($sql);
		$perms = array();

		while($row = $oResource->fetch()) {
			$permKey = strtolower($this->getPermFromID($row['upe_perm_id']));
			if($permKey == '') continue;
			$hP = ($row['upe_value'] === '1');

			$perms[$permKey] = array(
				'perm' 			=> $permKey,
				'inheritted'	=> false,
				'value'			=> $hP,
				'name'			=> $this->getPermNameFromID($row['upe_perm_id']),
				'id'			=> $row['upe_perm_id']);
		}

		return $perms;
	}


	/**
	 * Get all perms
	 * @param boolean $bWithName
	 * @return array
	 */
	public function getAllPerms($bFull=false) {
		$sql = "
		SELECT id_per, per_key, per_name, per_row FROM ".T_PERM."
		ORDER BY per_name ASC";
		$oResource = $GLOBALS['mysql']->query($sql);
		$resp = array();

		while($row = $oResource->fetch()) {
			if($bFull)
				$resp[$row['per_key']] = array(
					'id'	=> $row['id_per'],
					'name'	=> $row['per_name'],
					'key'	=> $row['per_row']);
			else
				$resp[] = $row['id_per'];
		}

		return $resp;
	}


	/**
	 * User has role
	 * @param int $roleID
	 * @return boolean
	 */
	public function userHasRole() {
		foreach($this->userRoles as $userRoleID)
			if($userRoleID === $roleID)
				return true;
		return false;
	}


	/**
	 * Has permission
	 * @param string $permKey
	 * @return return false;
	 */
	 public function hasPermission($permKey) {
	 	$permKey = strtolower($permKey);

		if(array_key_exists($permKey, $this->perms))
			return ($this->perms[$permKey]['value'] === '1' or $this->perms[$permKey]['value'] === true);
		else
			return false;
	 }

}
