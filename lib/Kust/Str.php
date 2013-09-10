<?php

# Auteur:	Benoît Zuckschwerdt
# Date:		30-05-2011
# Modif:	10-09-2013
# Desc:	Objets contenant toutes les méthodes
# 		concernant des chaînes de caractères.
#
#
# string :: str ;)

class Kust_str {
	private static $algo = ALGO;
	private static $salt = SALT;

	/*
	 + Nom: hashUserPassword
	 + In:	string
	 + Out:	string
	 + ----------------------
	 + Date de création: Benoît Zuckschwerdt @ 30.05.2010
	 + Buts: - Hachage du mot de passe utilisateur.
	 + ----------------------
	 + Modifications:
	 +	par:
	 +	date:
	 +	raison:
	*/
	public static function hashUserPassword($pass) {
		$hash = hash(self::$algo, $pass . self::$salt . $pass);
		$hash = hash(self::$algo, $pass . self::$salt);
		return hash(self::$algo, $hash);
	}

	/*
	 + Nom: clean
	 + In:	string
	 + Out:	string
	 + ----------------------
	 + Date de creation: Benoît Zuckschwerdt @ 17.11.2010
	 + Buts: - Convertit tous les caractères éligibles en entités HTML.
	 +	 - Ajoute des antislashs dans une chaîne.
	 + ----------------------
	 + Modifications:
	 +	par:
	 +	date:
	 +	raison:
	*/
	public static function clean($entry, $convertQuotes=false) {
		if($convertQuotes)
			return mysql_real_escape_string(htmlentities($entry, ENT_QUOTES));
		else
			return mysql_real_escape_string(htmlentities($entry));
	}


	/*
	 + Nom: filter
	 + In:	string
	 + Out:	string
	 + ----------------------
	 + Date de creation: Benoît Zuckschwerdt @ 20.12.2010
	 + Buts: - Convertit tous les caractères spéciaux en caractères classiques
	 + 		 - Met aussi en minuscule et supprime les espaces
	 + ----------------------
	 + Modifications:
	 +	par:
	 +	date:
	 +	raison:
	*/
	public function filter($in) {
		$in = strtolower($in);
		$search = array ('@[BUG]@i','@[BUG]@i','@[BUG]@i','@[BUG]@i','@[BUG]@i','@[BUG]@i','@[ ]@i','@[\']@i','@[^a-zA-Z0-9_]@');
		$replace = array ('e','a','i','u','o','c','_','_','');
		return preg_replace($search, $replace, $in);
	}


	/**
	 * Translate
	 * @param string $sText
	 * @return string
	 */
	public static function translate($string) {

		# NOT IMPLEMENTED YET !
		return $string;
	}


	/**
	 * Get random string
	 * @param int $chars
	 * @param string $letters
	 * @return string
	 */
	public static function getRandomString($chars = 8, $letters = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890') {
		return substr(str_shuffle($letters), 0, $chars);
	}


	/**
	 * Make hash
	 * @param string password
	 * @param string $algo
	 * @param string $salt
	 * @return string
	 */
	 public static function makeHash($password, $algo=SEC_PASSWORD_ALGO, $salt=SEC_PASSWORD_SALT) {
		 return hash($algo, $password.$salt);
	 }
}

?>
