<?php

require('drupalconfig_class.php');
include 'restservice_class.php';

class DiatemDrupalGetConfig extends RestService{
    public function __construct() {
	parent::__construct();
    }
    
    public function _get(){
	if($this->get_request_method() != 'GET'){
	    $this->response('', 405);
	}
	
	$retStr = DrupalConfig::getJSon();
	$this->response($retStr, 200);
    }
}

$api = new DiatemDrupalGetConfig();
$api->setSecured(DrupalConfig::getSecuredKeys());
$api->processApi();