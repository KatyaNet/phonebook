<?php
//password.php

require_once 'includes/global.inc.php';

if(!isset($_SESSION['logged_in'])) header("Location: index.php");

//Получить текущего пользователя
$id = $_GET['user'];

//Игнорируем пользователя и если не указан id меняем пароль себе
if($id != null){
	if ($user->specialist != 1){
		header("Location: password.php");
	} 
} else {
	$id = $user->id;
}

//Текущий пользователь
$otherUser	= $db->select('users',"id = $id");

//инициализируем php переменные, которые используются в форме
$username			= $otherUser['username'];
$error				= "";

//проверить отправлена ли форма
if(isset($_POST['submit-user'])) { 

	//получить переменные $_POST
	$password			= $_POST['password'];
	$password_confirm	= $_POST['password-confirm'];

	//инициализировать переменные для проверки формы
	$success = true;

	//проверить совпадение паролей
	if($password != $password_confirm) {
		$error .= "Пароль и подтверждение не совпадают. \n\r";
		$success = false;
	}

	if($success)
	{
		//подготовить информацию для сохранения объекта нового пользователя
		$data['password'] = md5($password); //зашифровать пароль для хранения

		//создать новый объект пользователя
		$editUser = new User($data);

		//сохранить нового пользователя в БД
		$editUser->id = $id;
		$editUser->change_password();

		//редирект на страницу приветствия
		header("Location: users.php");
	}
}

//Если форма не отправлена или не прошла проверку, тогда показать форму снова

?>
<html>
<head>
<?php require_once 'includes/header.inc.php'; ?>
<title>Изменить пароль пользователю <?php echo $username; ?></title>
</head>
<body>
<div id="loginBlock">
	<h1>Изменить пароль</h1>
<?php echo ($error != "") ? $error : ""; ?>
	<form action="password.php?user=<?php echo $id; ?>" method="post">
		<div class="formGroup">
			<label>Пароль:</label><input type="password" name="password" /><br/>
		</div>
		<div class="formGroup">
			<label>Еще раз:</label><input type="password" name="password-confirm" /><br/>
		</div>
		<div class="formButtons">
		<input type="submit" value="Сохранить" name="submit-user" />
		<input type="button" value="Отмена" onClick="window.location.href='users.php'" />
		</div>
	</form>
</div
</body>
</html>