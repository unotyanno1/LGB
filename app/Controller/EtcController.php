<?php

class EtcController extends AppController
{
	public function check_app_start()
	{
		if( !$_GET['app_version'] ) return;
		
		$app_version = $_GET['app_version'];
	
		$result = $app_version;

		$this->set( compact('result') );
		$this->viewClass = 'Json';
		$this->set( '_serialize', array( 'result' ) );
	}
}
?>
