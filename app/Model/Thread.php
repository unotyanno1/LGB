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

	public function getThreadCnt( $game_id, $age, $area, $sex, $photo_flag )
	{
		$sql = 'SELECT count(*) as cnt FROM %s ';

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

	public function selectAllPhoto( $device_token )
	{
		$sql = sprintf('SELECT photo FROM %s WHERE device_token = ? AND photo_flag = 1',
				$this->useTable
			);

		return $this->query( $sql, array($device_token) );
	}

	public function deleteAllThread( $device_token )
	{
		$sql = sprintf('DELETE FROM %s WHERE '
			      	.'device_token = ? ',
				$this->useTable
		);
		$ret = $this->query( $sql, array($device_token) );
		if( empty($ret) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function insertThread( $user_name, $device_token, $device, $line_id, $sex, $age, $area, $body, $game_id, $photo, $now_date )
	{
		if($photo == null)
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
				.'device, '
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
				.'?, '
				.'?, '
				.'? '
				.') ',
				$this->useTable
		);

		$this->query( $sql, array(
				$user_name,
				$device_token,
				$device,
				$line_id,
				$sex,
				$age,
				$area,
				$body,
				$game_id,
				$photo_flag,
				$photo,
				$now_date,
				$now_date,
		) );

		return $this->query( 'SELECT LAST_INSERT_ID() as last_insert_id' );
	}

	public function updateImageName( $id, $line_id, $image_date )
	{
		$sql = sprintf('UPDATE %s SET photo = ? WHERE id = ? AND line_id = ?',
				$this->useTable
		);
		
		$image_name = $image_date . "_" . $id . "_" . $line_id . ".png";

		$this->query( $sql, array(
					$image_name,
					$id,
					$line_id,
				) );
	}
}
?>
