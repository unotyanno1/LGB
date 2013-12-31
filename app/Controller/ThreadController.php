<?php

class ThreadController extends AppController
{
	public $uses = array( 'Thread' );

	public function get_thread()
	{
		if( !isset($_GET['game_id']) 
		||  !isset($_GET['age']) 
		||  !isset($_GET['area'])
		||  !isset($_GET['sex'])
		||  !isset($_GET['photo_flag']) 
		||  !isset($_GET['num']) 
		) return;
		
		$game_id = $_GET['game_id'];
		$age = $_GET['age'];
		$area = $_GET['area'];
		$sex = $_GET['sex'];
		$photo_flag = $_GET['photo_flag'];
		$num = $_GET['num'];

		$results = $this->Thread->getThread( $game_id, $age, $area, $sex, $photo_flag, $num );
	
		$this->set( compact('results') );
		$this->viewClass = 'Json';
		$this->set( '_serialize', array( 'results' ) );
	}

	public function delete_thread()
	{
		if( !isset($_GET['id']) 
		||  !isset($_GET['device_token'])
		||  !isset($_GET['img_path'])
		) return;

		$id = $_GET['id'];
		$device_token = $_GET['device_token'];
		$img_path = $_GET['img_path'];
		$ret = $this->Thread->deleteThread( $id, $device_token, $img_path );
		
		//スレッド削除処理が成功した場合は写真の削除を行う
		if($ret == true)
		{

		}
		//スレッド削除処理失敗の場合は写真の削除を行わない
		else
		{
			//エラーログに出力する
		}	
	}

	public function insert_thread()
	{
		if( !isset($_GET['user_name'])
		||  !isset($_GET['device_token']) 
		||  !isset($_GET['line_id']) 
		||  !isset($_GET['sex']) 
		||  !isset($_GET['age']) 
		||  !isset($_GET['area']) 
		||  !isset($_GET['body']) 
		||  !isset($_GET['game_id']) 
		||  !isset($_GET['img_path']) 
		) return;

		$user_name = $_GET['user_name'];
		$device_token = $_GET['device_token'];
		$line_id = $_GET['line_id'];
		$sex = $_GET['sex'];
		$age = $_GET['age'];
		$area = $_GET['area'];
		$body = $_GET['body'];
		$game_id = $_GET['game_id'];
		$img_path = $_GET['img_path'];

		$this->Thread->insertThread( 
					$user_name,
					$device_token,
					$line_id,
					$sex,
					$age,
					$area,
					$body,
					$game_id,
					$img_path
		 );
	}
}
?>
