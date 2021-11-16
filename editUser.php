<?php

require_once 'includes/global.inc.php';

if(!isset($_SESSION['logged_in'])) die;

//Получить текущего пользователя
$id = $_POST['id'];

//Отклоняем запросы без id
if ($id == null) {
	header("Location: error.php");
}

//Текущий пользователь
$otherUser	= $db->select('users',"id = $id");

//инициализируем php переменные, которые используются в форме
$username	= $previoususername		= $otherUser['username'];
$lastname	= $previousLastname		= $otherUser['lastname'];
$firstname	= $previousFirstname	= $otherUser['firstname'];
$secondname	= $previousSecondname	= $otherUser['secondname'];
$specialist	= $previousSpecialist	= $otherUser['specialist'];
$fired		= $previousFired		= $otherUser['fired'];
$error		= "";

//проверить отправлена ли форма
if(isset($_POST['submit-user'])) { 

	//получить переменные $_POST
	$username	= trim($_POST['username']);
	$lastname	= trim($_POST['lastname']);
	$firstname	= trim($_POST['firstname']);
	$secondname	= trim($_POST['secondname']);
	if(isset($_POST['specialist']) && $_POST['specialist'] == 'yes') {
		$specialist = 1;
	} else {
		$specialist = 0;
	}
	if(isset($_POST['fired']) && $_POST['fired'] == 'yes') {
		$fired = 1;
	} else {
		$fired = 0;
	}

	if (($username != $previoususername) || ($lastname != $previousLastname) || ($firstname != $previousFirstname) || ($secondname != $previousSecondname) || ($specialist != $previousSpecialist) || ($fired != $previousFired)) {
	
		//инициализировать переменные для проверки формы
		$success = true;
		$userTools = new UserTools();

		//проверить правильность заполнения формы
		//проверить не занят ли этот логин
		if($username != $previoususername) {
			if($userTools->check_username_exists($username)) {
				$error .= "Этот логин уже забит.<br/> \n\r";
				$success = false;
			}
		}

		if($success)
		{
			//подготовить информацию для сохранения объекта нового пользователя
			$data['username']	= $username;
			$data['lastname']	= $lastname;
			$data['firstname']	= $firstname;
			$data['secondname']	= $secondname;
			$data['specialist']	= $specialist;
			$data['fired']		= $fired;

			//создать новый объект пользователя
			$editUser = new User($data);

			//сохранить нового пользователя в БД
			$editUser->id = $id;
			$editUser->edit_user();
		}
	}
	// Редирект на менеджер пользователей
	header("Location: users.php");
}

//Если форма не отправлена или не прошла проверку, тогда показать форму снова

?>


<div class="col-md-8 offset-md-2">
	<h1>Редактировать пользователя</h1>
	<?php echo ($error != "") ? $error : ""; ?>
	<form action="editUser.php?user=<?php echo $id; ?>" method="post">
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Логин:</label>
			<div class="col-sm-10">
				<input class="form-control" type="text" value="<?php echo $username; ?>" name="username" required />
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Филиал:</label>
			<div class="col-sm-10">
				<select class="form-control" name="affiliate_id" required>
					<?php $affiliatesList = mysqli_query($GLOBALS['link'],"SELECT * FROM affiliates ORDER BY importance"); ?>
					<?php while ($row2 = mysqli_fetch_assoc($affiliatesList)): ?>
					<option value="<?php echo $row2["id"]; ?>" <?php if ($row2["id"] == $row["a_id"]) echo 'selected'; ?>><?php echo $row2["name"]; ?></option>
					<?php endwhile; ?>
					<?php mysqli_free_result($affiliatesList); ?>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-2">Уволен:</label>
			<div class="col-10">
				<div class="form-check">
					<input class="form-check-input" type="checkbox" value="yes" name="fired" <?php if($fired == 1) echo 'checked'; ?>>
				</div>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-sm-12">
				<input class="btn btn-primary" type="submit" value="Сохранить" name="submit-user" />
				<input class="btn btn-secondary" type="button" value="Отмена" onClick="window.location.href='users.php'" />
			</div>
		</div>
	</form>
</div>
