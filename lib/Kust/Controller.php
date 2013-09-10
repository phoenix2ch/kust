<?php
/**
 * Controller MVC system class
 *
 * Author:	Benoit Zuckschwerdt
 * Date:	7 August 2012
 *
 */

# Security access
if( !defined( "IN_SITE" ) ){
	header("HTTP/1.0 403 Forbidden");
	die();
}

class Kust_Controller {


	public function loadModel($model) {
		require_once(__DIR__.'/app/models/'. $model .'.model.php' );
		return new $model;
	}


	public function loadView($sView, $aData, $vars="", $bAjax=false) {
		//if(is_array($vars) && count($vars) > 0) extract($vars, EXTR_PREFIX_SAME, "wddx");
		//require_once(__DIR__.'/app/views/'.$view.'.html' );

		if($bAjax === false) {

			$oView = new Smarty;

			# Add view choice by controller
			$oView->assign('contentView', $sView);

			$oView->assign('langage', LANG);
			$oView->assign('meta', $GLOBALS['meta']);
			$oView->assign('styles', $GLOBALS['styles']);
			$oView->assign('less', $GLOBALS['less']);
			$oView->assign('scripts', $GLOBALS['scripts']);
			$oView->assign('navs', $GLOBALS['navs']);

			# Open Graph Protocol
			$oView->assign('og', $GLOBALS['og']);

			# Data not in content (for zero notice)
			if(empty($aData['errorMessages'])) $aData['errorMessages'] = array();
			if(empty($aData['successMessages'])) $aData['successMessages'] = array();

			# Assign data send by controller
			foreach($aData as $key => $value)
				$oView->assign($key, $value);

			$oView->display('index.html');

		} else {
			$oView->display("$sView.html");
		}
	}


	public function redirect( $uri ) {
		header( "Location: ?r=$uri" );

		die();
	}
}
?>
