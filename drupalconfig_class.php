<?php


class DrupalConfig {

    static $loaded = false;

    public static function getJSon() {
	if (!self::$loaded) {
	    self::initDrupal();
	}
	return json_encode(self::getDataArray());
    }

    public static function getSecuredKeys() {
	if (!self::$loaded) {
	    self::initDrupal();
	}

	return array(variable_get('drupal_diatem_getconfig_clepublique') => variable_get('drupal_diatem_getconfig_cleprivee'));
    }

    private static function initDrupal() {
	define('DRUPAL_ROOT', '../../../..');

	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);

	require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
	require_once DRUPAL_ROOT.'/includes/common.inc';
	require_once DRUPAL_ROOT.'/includes/module.inc';
	drupal_bootstrap(DRUPAL_BOOTSTRAP_CONFIGURATION);
	drupal_bootstrap(DRUPAL_BOOTSTRAP_PAGE_CACHE);
	drupal_bootstrap(DRUPAL_BOOTSTRAP_VARIABLES);
	drupal_bootstrap(DRUPAL_BOOTSTRAP_SESSION);
	drupal_bootstrap(DRUPAL_BOOTSTRAP_PAGE_HEADER);
	drupal_bootstrap(DRUPAL_BOOTSTRAP_LANGUAGE);
	drupal_bootstrap(DRUPAL_BOOTSTRAP_DATABASE);

	self::$loaded = true;
    }

    private static function getDataArray() {
	$output = array();

	$output['cms'] = self::getCms();
	$output['plugins'] = self::getPlugins();

	return $output;
    }

    private static function getCms() {
	$output = array();

	$output['name'] = 'drupal';
	$output['version'] = VERSION;
	return $output;
    }

    private static function getPlugins() {
	$output = array();
	
	$modules = module_list(TRUE);

	foreach ($modules AS $modulename => $module) {
	    $path = DRUPAL_ROOT.'/'.drupal_get_path('module', $module) . '/' . $module . '.info';
	    $info = drupal_parse_info_file($path);
	    
	    if($info['package'] != 'Core' && !strstr($path,'profiles/')){
	    
		$line = array();
		$line['type'] = $info['package'];
		$line['name'] = $modulename;
		$line['version'] = $info['version'];
		$line['editeur'] = '';
		$line['pluginUrl'] = '';
		$line['info'] = $info['description'];
		$line['enabled'] = (module_exists($modulename)) ? true : false;
		$output[] = $line;
	    
	    }
	}
	

	return $output;
    }

}
