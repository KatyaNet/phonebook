<?php
//User.class.php
require_once 'DB.class.php';

class User {
	public $id;
	public $username;
	public $hashedPassword;
	public $fired;
	public $affiliateId;

	//Конструктор вызывается при создании нового объекта
	//Takes an associative array with the DB row as an argument.
	function __construct($data) {
		$this->id				= (isset($data['id'])) ? $data['id'] : "";
		$this->username			= (isset($data['username'])) ? $data['username'] : "";
		$this->hashedPassword	= (isset($data['password'])) ? $data['password'] : "";
		$this->fired			= (isset($data['fired'])) ? $data['fired'] : "";
		$this->affiliateId		= (isset($data['affiliate_id'])) ? $data['affiliate_id'] : "";
	}

	public function new_user() {
		//Создать нового пользователя.
		$db = new DB();

		//if the user is being registered for the first time.
		$data = array(
		"username"		=> "'$this->username'",
		"password"		=> "'$this->hashedPassword'",
		"fired"			=> "'$this->fired'",
		);
		$this->id = $db->insert($data, 't_users');
		return true;
	}

	public function edit_user() {
		//Редактировать пользователя.
		$db = new DB();

		$data = array(
		"username"		=> "'$this->username'",
		"fired"			=> "'$this->fired'",
		);

		//update the row in the database
		$db->update($data, 't_users', 'id = '.$this->id);
		return true;
	}
	
	public function change_password() {
		//Сменить пароль пользователю.
		$db = new DB();

		$data = array(
		"password"		=> "'$this->hashedPassword'",
		);

		//update the row in the database
		$db->update($data, 't_users', 'id = '.$this->id);
		return true;
	}
}
?>