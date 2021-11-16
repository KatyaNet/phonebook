<?php

	ob_start('compress_page');
	
	require_once 'includes/global.inc.php';

	if(!isset($_SESSION['logged_in'])) die;

	$user = unserialize($_SESSION['user']);
	if($user->affiliateId != 1) die;

	$userList = mysqli_query($GLOBALS['link'],
	"SELECT
		u.id, 
		u.username,
		u.fired,
		u.affiliate_id,
		a.name AS a_name
	FROM users u
		LEFT JOIN affiliates a ON a.id = u.affiliate_id");

?>

<h2>Список пользователей</h2>

<button type="button" class="btn btn-success btn-sm mb-2" id="buttonNewUser">Создать пользователя</button>

<table class="table table-striped table-borderless table-sm">
	<thead class="table-dark">
		<tr>
			<th scope="col">Пользователь</th>
			<th scope="col">Филиал</th>
			<th scope="col">Статус</th>
			<th scope="col">Пароль</th>
		</tr>
	</thead>
	<tbody>
<?php while ($row = mysqli_fetch_assoc($userList)): ?>
		<tr>
			<td><button class="nav-link p-0 button-edit-user" value="<?php echo $row["id"]; ?>"><?php echo $row["username"]; ?></button></td>
			<td><?php echo $row["a_name"]; ?></td>
			<td><?php if($row["fired"] == 1) {echo "Уволен";} else {echo "Работает";} ?></td>
			<td><a href="password.php?user=<?php echo $row["id"];?>">Изменить</a></td>
		</tr>
<?php endwhile; ?>
	</tbody>
</table>
<?php mysqli_free_result($userList); ?>

<?php
	ob_end_flush();

	function compress_page($buffer)
	{
		return preg_replace("/\t|^\s*[\n\r]/m", "", $buffer);
	}
?>