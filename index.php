<?php

	ob_start('compress_page');

	require_once 'includes/global.inc.php';
	require_once 'classes/Contact.class.php';

	if(isset($_SESSION['logged_in'])) require_once 'includes/indexphpwithuser.inc.php' ; else require_once 'includes/indexphpwithoutuser.inc.php';

	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);

	if(isset($_SESSION['logged_in'])) {
		$user = unserialize($_SESSION['user']);
	}

?>

<!DOCTYPE html>

<html>

<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">

	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/font-awesome.min.css" rel="stylesheet">
	<link href="/css/general.css" rel="stylesheet">

	<title>Телефонный справочник</title>
	
</head>

<body>

<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
	
	<a class="navbar-brand col-md-4 col-lg-2 me-0 px-3" href="https://skfmba.ru">ФГБУ СКФНКЦ ФМБА России</a>
	
	<button class="navbar-toggler position-absolute d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	
	<input class="form-control form-control-dark" type="text" id="keyword" placeholder="Введите запрос для поиска..." autocomplete="off">
	
</header>

<div class="container-fluid">
	<div class="row">
		<nav id="sidebarMenu" class="col-md-4 col-lg-2 d-md-block bg-light sidebar collapse px-3">
			<div class="position-sticky pt-3">

				<button class="nav-link sidebar-heading text-muted" data-bs-toggle="collapse" data-bs-target="#affiliates-collapse" >Филиалы</button>

				<div class="collapse show mb-2" id="affiliates-collapse" >
	
					<button class="nav-link button-select-affilate active">Все</button>
			
					<?php $affiliatesList = mysqli_query($GLOBALS['link'],"SELECT * FROM affiliates ORDER BY importance"); ?>

					<?php while ($row = mysqli_fetch_assoc($affiliatesList)): ?>			
					<button class="nav-link button-select-affilate" value="<?php echo $row["id"]; ?>"><?php echo $row["name"];?></button>
					<?php endwhile; ?>

					<?php mysqli_free_result($affiliatesList); ?>
	
				</div>
				
				<?php if(isset($user)) : ?>
				
				<button class="nav-link sidebar-heading text-muted" data-bs-toggle="collapse" data-bs-target="#actions-collapse" >Управление</button>
				
				<div class="collapse show" id="actions-collapse" >

					<button class="nav-link" data-toggle="modal" data-target="#newContact">Добавить контакт</button>
					<button class="nav-link button-users">Пользователи системы</button>
				
				</div>
				
				<?php endif; ?>
				
				<hr/>

				<div class="navbar-nav">

				<?php if(isset($user)) : ?>

					<span class="nav-link"><small>Вы вошли как: </small><strong><?php echo $user->username; ?></strong></span>
					<a class="nav-link" href="logout.php">Выйти</a>
					
				<?php else : ?>
				
					<button class="nav-link button-login" data-bs-toggle="modal" data-bs-target="#loginForm">Управление</button>
					
				<?php endif; ?>

				</div>

			</div>
		</nav>

		<main class="col-md-8 ms-sm-auto col-lg-10 px-md-4">
		
			<?php if(isset($error) && $error != "") : ?>
			<div class="alert alert-warning alert-dismissible fade show" role="alert">
				<?php echo $error; ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
			<?php endif; ?>
		
			<div class="pt-3 pb-2 mb-3 border-bottom">
				<h1 class="h2">Телефонный справочник cлужебных телефонов персонала</h1>
			</div>
			
			<div id="result"></div>
			
		</main>
		
	</div>
</div>

<?php if(isset($_SESSION['logged_in'])) : ?>

<div class="modal fade" id="newContact">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			<h2>Добавить контакт</h2>
			</div>
			<form action="index.php" method="post">
				<div class="modal-body">
					<div class="input-group">
						<span class="input-group-addon">ФИО:</span>
						<input class="form-control" type="text" name="name" maxlength="50" />
					</div>
					<div class="input-group">
						<span class="input-group-addon">Внутренний номер:</span>
						<input class="form-control" type="tel" name="phone1" maxlength="12" required />
					</div>
					<div class="input-group">
						<span class="input-group-addon">Городской номер:</span>
						<input class="form-control" type="tel" name="phone2" maxlength="12" />
					</div>
					<div class="input-group">
						<span class="input-group-addon">Факс:</span>
						<input class="form-control" type="tel" name="fax" maxlength="12" />
					</div>
					<div class="input-group">
						<span class="input-group-addon">E-mail:</span>
						<input class="form-control" type="email" name="email" maxlength="50" />
					</div>
					<div class="input-group">
						<span class="input-group-addon">Описание:</span>
						<input class="form-control" type="text" name="description" maxlength="255" />
					</div>
					<div class="input-group">
						<span class="input-group-addon">Должность:</span>
						<input class="form-control" type="text" name="position" maxlength="50" />
					</div>
					<div class="input-group">
						<span class="input-group-addon">Отдел:</span>
						<input class="form-control" type="text" name="department" maxlength="50" required />
					</div>
					<div class="input-group">
						<span class="input-group-addon">Кабинет:</span>
						<input class="form-control" type="text" name="room" maxlength="50" required />
					</div>
					<div class="input-group">
						<span class="input-group-addon">Корпус:</span>
						<input class="form-control" type="text" name="building" maxlength="50" required />
					</div>
					<div class="input-group">
						<span class="input-group-addon">Филиал:</span>
						<select class="form-control" name="affiliate_id" required>
<?php $affiliatesList = mysqli_query($GLOBALS['link'],"SELECT * FROM affiliates ORDER BY importance"); ?>
<?php while ($row = mysqli_fetch_assoc($affiliatesList)): ?>
							<option value="<?php echo $row["id"]; ?>" ><?php echo $row["name"]; ?></option>
<?php endwhile; ?>
<?php mysqli_free_result($affiliatesList); ?>
						</select>
					</div>
					<div class="input-group">
						<span class="input-group-addon">Приоритет:</span>
						<input class="form-control" type="number" min="0" max="255" value="0" name="importance" required />
					</div>
				</div>
				
				<div class="modal-footer">
					<input type="submit" value="Добавить" class="btn btn-primary" name="submit-newcontact">
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="editContact">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			<h2>Исправить контакт</h2>
			</div>
			<form action="index.php" method="post">
				<div class="modal-body">
					<input class="cart-id" type="hidden" name="id" />
					<div class="input-group">
						<span class="input-group-addon">ФИО:</span>
						<input class="form-control" type="text" name="name" maxlength="50" />
					</div>
					<div class="input-group">
						<span class="input-group-addon">Внутренний номер:</span>
						<input class="form-control" type="tel" name="phone1" maxlength="12" required />
					</div>
					<div class="input-group">
						<span class="input-group-addon">Городской номер:</span>
						<input class="form-control" type="tel" name="phone2" maxlength="12" />
					</div>
					<div class="input-group">
						<span class="input-group-addon">Факс:</span>
						<input class="form-control" type="tel" name="fax" maxlength="12" />
					</div>
					<div class="input-group">
						<span class="input-group-addon">E-mail:</span>
						<input class="form-control" type="email" name="email" maxlength="50" />
					</div>
					<div class="input-group">
						<span class="input-group-addon">Описание:</span>
						<input class="form-control" type="text" name="description" maxlength="255" />
					</div>
					<div class="input-group">
						<span class="input-group-addon">Должность:</span>
						<input class="form-control" type="text" name="position" maxlength="50" />
					</div>
					<div class="input-group">
						<span class="input-group-addon">Отдел:</span>
						<input class="form-control" type="text" name="department" maxlength="50" required />
					</div>
					<div class="input-group">
						<span class="input-group-addon">Кабинет:</span>
						<input class="form-control" type="text" name="room" maxlength="50" required />
					</div>
					<div class="input-group">
						<span class="input-group-addon">Корпус:</span>
						<input class="form-control" type="text" name="building" maxlength="50" required />
					</div>
					<div class="input-group">
						<span class="input-group-addon">Филиал:</span>
						<select class="form-control" name="affiliate_id" required>
<?php $affiliatesList = mysqli_query($GLOBALS['link'],"SELECT * FROM affiliates ORDER BY importance"); ?>
<?php while ($row = mysqli_fetch_assoc($affiliatesList)): ?>
							<option value="<?php echo $row["id"]; ?>" ><?php echo $row["name"]; ?></option>
<?php endwhile; ?>
<?php mysqli_free_result($affiliatesList); ?>
						</select>
					</div>
					<div class="input-group">
						<span class="input-group-addon">Приоритет:</span>
						<input class="form-control" type="number" min="0" max="255" value="0" name="importance" required />
					</div>
				</div>
				
				<div class="modal-footer">
					<input type="submit" value="Добавить" class="btn btn-primary" name="submit-editcontact">
				</div>
			</form>
		</div>
	</div>
</div>

<?php else: ?>

<div class="modal fade" id="loginForm" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			<h2>Введите логин и пароль</h2>
			</div>
			<form action="/" method="post">
				<div class="modal-body">
					<div class="input-group mb-3">
						<span class="input-group-text col-4 col-sm-3">Логин:</span>
						<input class="form-control" type="text" name="username" maxlength="50" required />
					</div>
					<div class="input-group mb-3">
						<span class="input-group-text col-4 col-sm-3">Пароль:</span>
						<input class="form-control" type="password" name="password" maxlength="50" required />
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" value="Войти" class="btn btn-primary" name="submit-login">
				</div>
			</form>
		</div>
	</div>
</div>

<?php endif; ?>

<script type="text/javascript" src="/js/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="/js/scripts.js"></script>

</body>

</html>

<?php
	ob_end_flush();

	function compress_page($buffer)
	{
		$buffer = str_replace("\t", '  ', $buffer);
		$buffer = preg_replace("/^\s*[\n\r]/m", "", $buffer);
		return $buffer;
	}
?>