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
		
		foreach( $results as $i => $data )
		{
			$before_time = $this->convert_before_strtime( $data['thread']['created'] );
			$results[$i]['thread']['created'] = $before_time;
		}
		
		$thread_all_cnt = $this->Thread->getThreadCnt( $game_id, $age, $area, $sex, $photo_flag );
		$results[0]['thread']['thread_all_cnt'] = $thread_all_cnt[0][0]['cnt'];

		$this->set( compact('results') );
		$this->viewClass = 'Json';
		$this->set( '_serialize', array( 'results' ) );
	}

	public function delete_thread()
	{
		if( !isset($_POST['id']) 
		||  !isset($_POST['device_token'])
		||  !isset($_POST['img_path'])
		) return;

		$id = $_POST['id'];
		$device_token = $_POST['device_token'];
		$img_path = $_POST['img_path'];
		$ret = $this->Thread->deleteThread( $id, $device_token, $img_path );
		
		if($ret == true)
		{
			$command = 'rm /var/www/html/img_folder_line_game_bbs/' . $img_path;
			exec( $command );
		}
		else
		{
			error_log("deleteThread error, thread_id:" . $id);
		}	
	}
	
	public function delete_all_thread()
	{
		if( !isset($_POST['device_token']) ) return;

		$device_token = $_POST['device_token'];
		
		$photo_list = $this->Thread->selectAllPhoto( $device_token );
		
		$ret = $this->Thread->deleteAllThread( $device_token );
		
		if($ret == true)
		{
			foreach( $photo_list as $val )
			{
				$photo = $val['thread']['photo'];
				$command = 'rm /var/www/html/img_folder_line_game_bbs/' . $photo;
				exec( $command );
			}
		}
		else
		{
			error_log("deleteAllThread error, device_token:" . $device_token);
		}	
	}

	public function insert_thread()
	{
		if( !isset($_POST['user_name'])
		||  !isset($_POST['device_token']) 
		||  !isset($_POST['device']) 
		||  !isset($_POST['line_id']) 
		||  !isset($_POST['sex']) 
		||  !isset($_POST['age']) 
		||  !isset($_POST['area']) 
		||  !isset($_POST['body']) 
		||  !isset($_POST['game_id']) 
		||  !isset($_POST['img_path']) 
		) return;

		$user_name = $_POST['user_name'];
		$device_token = $_POST['device_token'];
		$device = $_POST['device'];
		$line_id = $_POST['line_id'];
		$sex = $_POST['sex'];
		$age = $_POST['age'];
		$area = $_POST['area'];
		$body = $_POST['body'];
		$game_id = $_POST['game_id'];
		$img_path = $_POST['img_path'] === 'no_photo' ? null : $_POST['img_path'];
		
		$now_date = date("Y-m-d H:i:s");
		$image_date = date("YmdHis");

		$ret = $this->Thread->insertThread( 
					$user_name,
					$device_token,
					$device,
					$line_id,
					$sex,
					$age,
					$area,
					$body,
					$game_id,
					$img_path,
					$now_date
		 );
		
		$last_insert_id = $ret[0][0]['last_insert_id'];
		
		//画像のポストされ、スレッドデータをインサートしたIDがとれた場合、画像の名前の更新と画像の保存を行う
		if( $img_path != null && $last_insert_id != 0 )
		{
			$this->Thread->updateImageName( $last_insert_id, $line_id, $image_date );
			$this->saveImage( $last_insert_id, $line_id, $image_date);
		}
	}

	public function convert_before_strtime( $str_time )
	{
		$timestamp = strtotime( $str_time );
		$now = time();
		$diff_time = $now - $timestamp;

		if( $diff_time < 60 )
		{
			$time = $diff_time;
			$unit = '秒前';
		}
		elseif( $diff_time < 3600 )
		{
			$time = $diff_time / 60;
			$unit = '分前';
		}
		elseif( $diff_time < 86400 )
		{
			$time = $diff_time / 3600;
			$unit = '時間前';
		}
		elseif( $diff_time < 2764800 )
		{
			$time = $diff_time / 86400;
			$unit = '日前';
		}

		return (int)$time . $unit;
	}

	public function saveImage( $id, $line_id, $image_date )
	{
		$dir_name = "/var/www/html/img_folder_line_game_bbs";
		
		if(is_uploaded_file($_FILES["profileImg"]["tmp_name"]))
		{
			$extension = pathinfo($_FILES["profileImg"]["name"], PATHINFO_EXTENSION);
			if($extension == "png")
			{
				$image_name = $image_date . "_" . $id . "_" . $line_id . ".png";
				$upload_file = "{$dir_name}/{$image_name}";
				move_uploaded_file($_FILES["profileImg"]["tmp_name"], $upload_file);
			}
		}
	}
}
?>
