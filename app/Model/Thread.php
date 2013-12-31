<?php
class Thread extends AppModel
{
	public $useTable = 'thread';

	public function getThread( $game_id, $age, $area, $sex, $photo_flag, $num )
	{
		$sql = 'SELECT * FROM %s ';

		if( $game_id != 0 
		||  $age != 0
		||  $area != 0
		||  $sex != 0
		||  $photo_flag != 0
		)
		{
			$sql .= 'WHERE ';
		}

		if( $game_id != 0 )
		{
			$sql .= "game_id = $game_id AND ";
		}
		if( $age != 0 )
		{
			$sql .= "age = $age AND ";
		}
		if( $area != 0 )
		{
			$sql .= "area = $area AND ";
		}
		if( $sex != 0 )
		{
			$sql .= "sex = $sex AND ";
		}
		if( $photo_flag != 0 )
		{
			$sql .= "photo_flag = $photo_flag AND ";
		}
		
		if( $game_id != 0 
		||  $age != 0
		||  $area != 0
		||  $sex != 0
		||  $photo_flag != 0
		)
		{
			$sql = substr( $sql, 0, -4);
		}

		$sql .= 'ORDER BY created DESC LIMIT 0,' . $num;
		
		$result_sql = sprintf( $sql, 
				$this->useTable
		 );

		return $this->query( $result_sql );
	}

	public function deleteThread( $id, $device_token, $photo )
	{
		$sql = sprintf('DELETE FROM %s WHERE '
			      	.'id = ? AND '
			      	.'device_token = ? ',
				$this->useTable
		);
		$ret = $this->query( $sql, array($id, $device_token) );
		if( empty($ret) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function insertThread( $user_name, $device_token, $line_id, $sex, $age, $area, $body, $game_id, $photo )
	{
		if($photo === 'no_photo')
		{
			$photo_flag = 2;
		}
		else
		{
			$photo_flag = 1;
		}

		$sql = sprintf('INSERT INTO %s ( '
				.'user_name, '
				.'device_token, '
				.'line_id, '
				.'sex, '
				.'age, '
				.'area, '
				.'body, '
				.'game_id, '
				.'photo_flag, '
				.'photo, '
				.'created, '
				.'updated '
				.') VALUES ( '
				.'?, '
				.'?, '
				.'?, '
				.'?, '
				.'?, '
				.'?, '
				.'?, '
				.'?, '
				.'?, '
				.'?, '
				.'now(), '
				.'now() '
				.') ',
				$this->useTable
		);

		$this->query( $sql, array(
				$user_name,
				$device_token,
				$line_id,
				$sex,
				$age,
				$area,
				$body,
				$game_id,
				$photo_flag,
				$photo,
		) );
	}
}
?>
