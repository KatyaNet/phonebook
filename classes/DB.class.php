<?php
//DB.class.php
class DB {
	protected $db_name = 'skfnkc_phonebook';
	protected $db_user = 'skfnkc_phpgod';
	protected $db_pass = '46RjA1tcBPPVWoIno525';
	protected $db_host = 'localhost';

	// Открываем соединение к БД.
	public function connect() {
		// $connection = mysql_connect($this->db_host, $this->db_user, $this->db_pass);
		// mysql_select_db($this->db_name);
		$GLOBALS['link'] = mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
	return true;
	}

	// Берет ряд mysql и возвращает ассоциативный массив, в котором
	// названия колонок являются ключами массива. Если singleRow - true,
	// тогда выводится только один ряд
	public function process_row_set($rowSet, $singleRow=false)
	{
		$resultArray = array();
		while($row = mysqli_fetch_assoc($rowSet))
			{
			array_push($resultArray, $row);
			}
		if($singleRow === true)
		return $resultArray[0];
		return $resultArray;
	}

	//Выбирает ряды из БД
	//Выводит полный ряд или ряды из $table используя $where 
	public function select($table, $where) {
		if (!empty($where)) {$where = 'WHERE ' . $where;}
		$sql = "SELECT * FROM $table $where";
		$result = mysqli_query($GLOBALS['link'],$sql);
		if(mysqli_num_rows($result) == 1)
		return $this->process_row_set($result, true);
		return $this->process_row_set($result);
	}

	//Вносит изменения в БД
	public function update($data, $table, $where) {
		foreach ($data as $column => $value) {
			$sql = "UPDATE $table SET $column = $value WHERE $where";
			mysqli_query($GLOBALS['link'],$sql) or die(mysql_error());
		}
		return true;
	}

	//Вставляет новый ряд в таблицу
	public function insert($data, $table) {
		$columns = "";
		$values = "";
		foreach ($data as $column => $value) {
			$columns .= ($columns == "") ? "" : ", ";
			$columns .= $column;
			$values .= ($values == "") ? "" : ", ";
			$values .= $value;
		}

		$sql = "insert into $table ($columns) values ($values)";
		mysqli_query($GLOBALS['link'],$sql) or die(mysql_error());

		//Выводит ID записи в БД.
		return mysqli_insert_id($GLOBALS['link']);
	}

	//Удаляет строку
	public function del($table, $where) {
		$sql = "DELETE FROM $table WHERE $where";
		mysqli_query($GLOBALS['link'],$sql) or die(mysql_error());
		return true;
	}	
}
?>