<?php
// получить формат вывода результата
if(isset($_GET['view'])) {
	$_SESSION['view'] = $_GET['view'];
}

//проверить отправлена ли форма логина
if(isset($_POST['submit-login'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];

	$userTools = new UserTools();
	if(!$userTools->login($username, $password)){
		$error = "Неверный логин или пароль!";
	}
}
?>