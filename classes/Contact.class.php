<?php
//Contact.class.php
require_once 'DB.class.php';

class Contact {
	public $id;
	public $userId;
	public $name;
	public $phone1;
	public $phone2;
	public $fax;
	public $email;
	public $description;
	public $position;
	public $department;
	public $departmentId;
	public $room;
	public $building;
	public $buildingId;
	public $affiliateId;
	public $boss;
	public $importance;

	//Конструктор вызывается при создании нового объекта.
	function __construct($data) {
		$this->id			= (isset($data['id'])) ? $data['id'] : "";
		$this->name			= (isset($data['name'])) ? $data['name'] : "";
		$this->phone1		= (isset($data['phone1'])) ? $data['phone1'] : "";
		$this->phone2		= (isset($data['phone2'])) ? $data['phone2'] : "";
		$this->fax			= (isset($data['fax'])) ? $data['fax'] : "";
		$this->email		= (isset($data['email'])) ? $data['email'] : "";
		$this->description	= (isset($data['description'])) ? $data['description'] : "";
		$this->position		= (isset($data['position'])) ? $data['position'] : "";
		$this->department	= (isset($data['department'])) ? $data['department'] : "";
		$this->departmentId	= (isset($data['department_id'])) ? $data['department_id'] : "";
		$this->room			= (isset($data['room'])) ? $data['room'] : "";
		$this->building		= (isset($data['building'])) ? $data['building'] : "";
		$this->buildingId	= (isset($data['building_id'])) ? $data['building_id'] : "";
		$this->affiliateId	= (isset($data['affiliate_id'])) ? $data['affiliate_id'] : "";
		$this->boss			= (isset($data['boss'])) ? $data['boss'] : "";
		$this->importance	= (isset($data['importance'])) ? $data['importance'] : "";
	}

	public function new_contact() {
		//Создать новый объект базы данных
		$db = new DB();
		
		//Добавить новый контакт
		$dataContact = array(
		"name"				=> "'$this->name'",
		"phone1"			=> "'$this->phone1'",
		"phone2"			=> "'$this->phone2'",
		"fax"				=> "'$this->fax'",
		"email"				=> "'$this->email'",
		"description"		=> "'$this->description'",
		"position"			=> "'$this->position'",
		"department"		=> "'$this->department'",
		"department_id"		=> "'$this->departmentId'",
		"room"				=> "'$this->room'",
		"building"			=> "'$this->building'",
		"building_id"		=> "'$this->buildingId'",
		"affiliate_id"		=> "'$this->affiliateId'",
		"boss"				=> "'$this->boss'",
		"importance"		=> "'$this->importance'"		
		);
		$this->id = $db->insert($dataContact, 'phonebook');	
		return true;
	}
	
	public function edit_contact() {
		//Создать новый объект базы данных
		$db = new DB();

		//Редактировать контакт
		$dataContact = array(
		"name"				=> "'$this->name'",
		"phone1"			=> "'$this->phone1'",
		"phone2"			=> "'$this->phone2'",
		"fax"				=> "'$this->fax'",
		"email"				=> "'$this->email'",
		"description"		=> "'$this->description'",
		"position"			=> "'$this->position'",
		"department"		=> "'$this->department'",
		"department_id"		=> "'$this->departmentId'",
		"room"				=> "'$this->room'",
		"building"			=> "'$this->building'",
		"building_id"		=> "'$this->buildingId'",
		"affiliate_id"		=> "'$this->affiliateId'",
		"boss"				=> "'$this->boss'",
		"importance"		=> "'$this->importance'"
		);
		$db->update($dataContact, 'phonebook', 'id = '.$this->id);
		return true;
	}	
	
	public function delete_contact() {
		//Создать новый объект базы данных
		$db = new DB();

		//Удалить существующую заявку
		$db->del('phonebook', 'id = '.$this->id);
		return true;
	}	
}
?>