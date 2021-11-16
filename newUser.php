<?php
//newUser.php
require_once 'includes/global.inc.php';

if(!isset($_SESSION['logged_in'])) die;

$user = unserialize($_SESSION['user']);
if($user->affiliateId ! = 1) die;

//инициализируем php переменные, которые используются в форме
$username			= "";
$password			= "";
$passwordConfirm	= "";
$affiliateId		= 0;
$error				= "";

//проверить отправлена ли форма
if(isset($_POST['submit-user'])) { 

	//получить переменные $_POST
	$username			= trim($_POST['username']);
	$password			= $_POST['password'];
	$passwordConfirm	= $_POST['password_confirm'];
	if(isset($_POST['affiliateId'])) {
		$affiliateId 	= $_POST['affiliate_id'];
	}

	//инициализировать переменные для проверки формы
	$success = true;
	$userTools = new UserTools();

	//проверить правильность заполнения формы
	//проверить не занят ли этот логин
	if($affiliateId == 1) {
		$error .= "Возможен только один повелитель!";
		$success = false;
	}

	//проверить правильность заполнения формы
	//проверить не занят ли этот логин
	if($userTools->check_username_exists($username)) {
		$error .= "Этот логин уже забит!";
		$success = false;
	}

	//проверить совпадение паролей
	if($password != $passwordConfirm) {
		$error .= "Пароли не совпадают!";
		$success = false;
	}

	if($success)
	{
		//подготовить информацию для сохранения объекта нового пользователя
		$data['username']		= $username;
		$data['password']		= md5($password); //зашифровать пароль для хранения
		$data['affiliateId']	= $affiliateId;

		//создать новый объект пользователя
		$newUser = new User($data);

		//сохранить нового пользователя в БД
		$newUser->new_user();

		//редирект на страницу приветствия
		// header("Location: users.php");
	}
}

//Если форма не отправлена или не прошла проверку, тогда показать форму снова

?>


<h2>Создать пользователя</h2>

<?php echo ($error != "") ? $error : ""; ?>

<form action="newUser.php" method="post">
	<div class="form-group">
		<label>Логин:</label>
		<input type="text" class="form-control" value="<?php echo $username; ?>" name="username" required />
	</div>
	<div class="form-group">
		<label>Пароль:</label>
		<input type="password" class="form-control" value="<?php echo $password; ?>" name="password" required />
	</div>
	<div class="form-group">
		<label>Еще раз:</label>
		<input type="password" class="form-control" value="<?php echo $password_confirm; ?>" name="password-confirm" required />
	</div>
	<div class="form-group">
		<label>Филиал:</label>
		<select class="form-control" name="affiliate_id" required>

		
	<?php $affiliatesList = mysql_query("SELECT * FROM affiliates WHERE id!=1 ORDER BY importance"); ?>
	<?php while ($row = mysql_fetch_assoc($affiliatesList)): ?>
		<option value="<?php echo $row["id"]; ?>" ><?php echo $row["name"]; ?></option>
	<?php endwhile; ?>
	<?php mysql_free_result($affiliatesList); ?>
		</select>
	</div>
	<button type="submit" class="btn btn-success buttonUsers" name="submit-user">Создать</button>
	<button type="button" class="btn btn-danger buttonUsers" onClick="showUsers()">Отмена</button>
</form>

<script type="text/javascript" src="/js/showusers.js"></script>



