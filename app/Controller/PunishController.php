<?php

class PunishController extends AppController
{
	public $uses = array( 'Punish' );

	public function insert_punish()
	{
		if( !isset($_POST['punish_thread_id']) 
		||  !isset($_POST['punish_user_name'])
		||  !isset($_POST['send_user_token'])
		) return;

		$punish_thread_id = $_POST['punish_thread_id'];
		$punish_user_name = $_POST['punish_user_name'];
		$send_user_token = $_POST['send_user_token'];
		$this->Punish->insertPunish( $punish_thread_id, $punish_user_name, $send_user_token );
	}
}
?>
