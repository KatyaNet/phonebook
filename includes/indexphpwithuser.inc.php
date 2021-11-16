<?php
$error 			= "";
$username		= "";
$password		= "";
$contactWhere	= "";

$name			= "";
$phone1			= "";
$phone2			= "";
$fax			= "";
$email			= "";
$description	= "";
$position		= "";
$department		= "";
$departmentId	= "";
$room			= "";
$building		= "";
$buildingId		= "";
$affiliateId	= "";
$boss			= 0;
$importance		= 0;

//проверить отправлена ли форма создания нового контакта
if(isset($_POST['submit-newcontact'])) {

	//получить переменные $_POST
	$name			= trim($_POST['name']);
	$phone1			= trim($_POST['phone1']);
	$phone2			= trim($_POST['phone2']);
	$fax			= trim($_POST['fax']);
	$email			= trim($_POST['email']);
	$description	= trim($_POST['description']);
	$position		= trim($_POST['position']);
	$department		= trim($_POST['department']);
	$departmentId	= trim($_POST['department_id']);
	$room			= trim($_POST['room']);
	$building		= trim($_POST['building']);
	$buildingId		= trim($_POST['building_id']);
	$affiliateId	= trim($_POST['affiliate_id']);
	isset($_POST['boss']) ? $boss = 1 : $boss = 0;
	$importance		= trim($_POST['importance']);
	
	// Кастыль по очистке текстовых полей при выборе из справочника
	if(!empty($departmentId)){
		$department	= "";
	}
	
	if(!empty($buildingId)){
		$building	= "";
	}
	
	//подготовить информацию для сохранения объекта нового контакта
	$data['name']			= $name;
	$data['phone1']			= $phone1;
	$data['phone2']			= $phone2;
	$data['fax']			= $fax;
	$data['email']			= $email;
	$data['description']	= $description;
	$data['position']		= $position;
	$data['department']		= $department;
	$data['department_id']	= $departmentId;
	$data['room']			= $room;
	$data['building']		= $building;
	$data['building_id']	= $buildingId;
	$data['affiliate_id']	= $affiliateId;
	$data['boss']			= $boss;
	$data['importance']		= $importance;

	//создать новый объект контакта
	$newContact = new Contact($data);

	//сохранить новый контакт в БД
	$newContact->new_contact();
	
	//редирект на главную
	header("Location: index.php");
}

//проверить отправлена ли форма редактирования контакта
if(isset($_POST['submit-editcontact'])) {

	//получить переменные $_POST	
	$name			= trim($_POST['name']);
	$phone1			= trim($_POST['phone1']);
	$phone2			= trim($_POST['phone2']);
	$fax			= trim($_POST['fax']);
	$email			= trim($_POST['email']);
	$description	= trim($_POST['description']);
	$position		= trim($_POST['position']);
	$department		= trim($_POST['department']);
	$departmentId	= trim($_POST['department_id']);
	$room			= trim($_POST['room']);
	$building		= trim($_POST['building']);
	$buildingId		= trim($_POST['building_id']);
	$affiliateId	= trim($_POST['affiliate_id']);
	isset($_POST['boss']) ? $boss = 1 : $boss = 0;
	$importance		= trim($_POST['importance']);

	// Кастыль по очистке текстовых полей при выборе из справочника
	if(!empty($departmentId)){
		$department	= "";
	}
	
	if(!empty($buildingId)){
		$building	= "";
	}
	
	//подготовить информацию для сохранения объекта контакта
	$data['name']			= $name;
	$data['phone1']			= $phone1;
	$data['phone2']			= $phone2;
	$data['fax']			= $fax;
	$data['email']			= $email;
	$data['description']	= $description;
	$data['position']		= $position;
	$data['department']		= $department;
	$data['department_id']	= $departmentId;
	$data['room']			= $room;
	$data['building']		= $building;
	$data['building_id']	= $buildingId;
	$data['affiliate_id']	= $affiliateId;
	$data['boss']			= $boss;
	$data['importance']		= $importance;

	//создать новый объект контакта
	$editContact = new Contact($data);

	//обновить контакт в БД
	$editContact->id = $_POST['id'];
	$editContact->edit_contact();
	
}

//проверить отправлена ли форма удаления контакта
if(isset($_POST['submit-deletecontact'])) {

	//получить переменные $_POST
	$id = $_POST['id'];
	
	//подготовить информацию для сохранения объекта нового контакта
	$data['id'] = $id;

	//создать новый объект контакта
	$deleteContact = new Contact($data);

	//сохранить новую заявку в БД
	$deleteContact->delete_contact();

}

//получить выбранный филиал
if($user->affiliateId > 1) {
	$_SESSION['affiliate'] = $user->affiliateId;
} else {
	if(isset($_GET['affiliate'])) {
		$_SESSION['affiliate'] = $_GET['affiliate'];
	}
}
?>