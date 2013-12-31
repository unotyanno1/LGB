<?php
class Punish extends AppModel
{
	public $useTable = 'punish';

	public function insertPunish( $punish_thread_id, $punish_user_name, $send_user_token )
	{
		$sql = sprintf( 'INSERT INTO %s ( '
				.'punish_thread_id, ' 
				.'punish_user_name, ' 
				.'send_user_token, ' 
				.'created ' 
				.') VALUES ( ' 
				.'?, ' 
				.'?, ' 
				.'?, ' 
				.'now() ' 
				.') ', 
				$this->useTable
		 );

		return $this->query( $sql, array(
				$punish_thread_id,
				$punish_user_name,
				$send_user_token,
			) );
	}
}
?>
