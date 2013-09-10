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
		require_once(dirname(__FILE__).'/models/'. $model .'.model.php' );
		return new $model;
	}


	public function loadView($sView, $aData, $vars="", $bAjax=false) {
		//if(is_array($vars) && count($vars) > 0) extract($vars, EXTR_PREFIX_SAME, "wddx");
		//require_once(__DIR__.'/app/views/'.$view.'.html' );


        $oView = new Smarty;
		if($bAjax === false) {

			# Set templates dir
			$oView->setTemplateDir(SMARTY_TEMPLATE_DIR);

			# Add view choice by controller
			$oView->assign('contentView', $sView);

			$oView->assign('langage', LANG);
			$oView->assign('meta', $GLOBALS['meta']);
			$oView->assign('styles', $GLOBALS['styles']);
			$oView->assign('less', $GLOBALS['less']);
			$oView->assign('scripts', $GLOBALS['scripts']);
			$oView->assign('navs', $GLOBALS['navs'][(!empty($_SESSION['user']) ? 'connected' : 'public')]);
			$oView->assign('alpha_list', $GLOBALS['alpha_list']);

			# Breadcrump
		    $oView->assign('trail', $GLOBALS['trail']->path);


			# Open Graph Protocol
			$oView->assign('og', $GLOBALS['og']);

			# Data not in content (for zero notice)
			if(empty($aData['errorMessages'])) $aData['errorMessages'] = array();
			if(empty($aData['successMessages'])) $aData['successMessages'] = array();
			if(empty($aData['infoMessages'])) $aData['infoMessages'] = array();

			$oView->assign('pre', 'on');
			$oView->assign('header', 'on');
			$oView->assign('js_end', 'on');
			$oView->assign('nav', 'on');
			$oView->assign('footer', 'on');
			$oView->assign('post', 'on');

			# Assign data send by controller
			foreach($aData as $key => $value)
				$oView->assign($key, $value);

            # Is logged?
			$oView->assign('logged', !empty($_SESSION['user']));
			$oView->display('index.html');

		} else {
			$oView->display("$sView.html");
		}
	}


	public function redirect( $uri ) {
		header( "Location: ".URL."$uri" );

		die();
	}
}
?>
