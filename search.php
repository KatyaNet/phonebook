<?php

	ob_start('compress_page');

	require_once 'includes/global.inc.php';

	//Создать условие в запрос

	$keyword = $_POST['keyword'];
	$affiliate = $_POST['affiliate'];
		
	switch ($affiliate) {
		case 0:
			break;
		case 1:
			$conditions[affiliate]  = '(a.id = 1 OR pb.boss = 1)';
			break;
		default:
			$conditions[affiliate]  = 'a.id = ' . $affiliate;
	}
		
	if ($keyword) {
		$conditions[keyword]  = '(pb.name LIKE "%' . $keyword . '%" OR pb.phone1 LIKE "%' . $keyword . '%" OR pb.phone2 LIKE "%' . $keyword . '%" OR pb.fax LIKE "%' . $keyword . '%" OR pb.email LIKE "%' . $keyword . '%" OR pb.description LIKE "%' . $keyword . '%" OR pb.position LIKE "%' . $keyword . '%" OR pb.department LIKE "%' . $keyword . '%" OR d.name LIKE "%' . $keyword . '%" OR pb.room LIKE "%' . $keyword . '%" OR pb.building LIKE "%' . $keyword . '%" OR b.name LIKE "%' . $keyword . '%" OR a.name LIKE "%' . $keyword . '%")';
	}

	$contactSearch = implode(' AND ', $conditions);

	if ($contactSearch) {
		$contactSearch = 'WHERE ' . $contactSearch;
	}

	$contactList = mysqli_query($GLOBALS['link'],
	"SELECT
		pb.*,
		b.id AS b_id,
		COALESCE(b.name,building) AS building_full,
		d.id AS d_id,
		COALESCE(d.name,department) AS department_full,
		d.importance AS d_importance,
		a.id AS a_id,
		a.name AS a_name,
		a.importance AS a_importance,	
		CASE
			WHEN pb.boss = 1 THEN 1  
			ELSE a.id  
		END AS affiliate_full
	FROM phonebook pb
		INNER JOIN affiliates a ON a.id = pb.affiliate_id
		LEFT JOIN buildings b ON b.id = pb.building_id
		LEFT JOIN departments d ON d.id = pb.department_id	
	$contactSearch
	ORDER BY affiliate_full, d_importance DESC, department_full, importance DESC, name");

	function mask($inPhone) {
		if (iconv_strlen($inPhone) == 11) {
			if (substr($inPhone,1,3) == 862) {
				$outPhone = substr($inPhone,0,1) . ' (' . substr($inPhone,1,3) . ') ' . substr($inPhone,4,3) . '-' . substr($inPhone,7,2) . '-' . substr($inPhone,9,2);			
			} elseif (substr($inPhone,1,5) == 87933) {
				$outPhone = substr($inPhone,0,1) . ' (' . substr($inPhone,1,4) . ') ' . substr($inPhone,5,2) . '-' . substr($inPhone,7,2) . '-' . substr($inPhone,9,2);
			} else {
				$outPhone = substr($inPhone,0,1) . ' (' . substr($inPhone,1,5) . ') ' . substr($inPhone,6,1) . '-' . substr($inPhone,7,2) . '-' . substr($inPhone,9,2);			
			}		
		} else {
			$outPhone = $inPhone;
		}
		echo $outPhone;
	};
?>



<?php if (mysqli_num_rows($contactList) == 0): ?>
	<div class="alert alert-danger" role="alert">Контакты не найдены. Быть может, Вы плохо ищете?</div>
<?php else: ?>

	<?php /*if(isset($_SESSION['logged_in'])) : ?>

	<div class="row">

		<?php $num = 1; ?>

		<?php while ($row = mysqli_fetch_assoc($contactList)): ?>
		<?php $curaff = $row["affiliate_id"]; ?>
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="card<?php if($row['department_id'] == 0 || $row['building_id'] == 0) echo ' bg-warning';?>">
					<div class="card-body">
					<form action="" method="post">
						<input type="hidden" value="<?php echo $row["id"]; ?>" name="id">
						<div class="input-group">
							<label class="input-group-addon">ФИО:</label>
							<input class="form-control" type="text" value="<?php echo $row["name"]; ?>" name="name" maxlength="50" />
						</div>
						<div class="input-group">
							<label class="input-group-addon">Внутренний номер:</label>
							<input class="form-control" type="tel" value="<?php echo $row["phone1"]; ?>" name="phone1" maxlength="12" />
						</div>
						<div class="input-group">
							<label class="input-group-addon">Городской номер:</label>
							<input class="form-control" type="tel" value="<?php echo $row["phone2"]; ?>" name="phone2" maxlength="12" />
						</div>
						<div class="input-group">
							<label class="input-group-addon">Факс:</label>
							<input class="form-control" type="tel" value="<?php echo $row["fax"]; ?>" name="fax" maxlength="12" />
						</div>
						<div class="input-group">
							<label class="input-group-addon">E-mail:</label>
							<input class="form-control" type="email" value="<?php echo $row["email"]; ?>" name="email" maxlength="50" />
						</div>
						<div class="input-group">
							<label class="input-group-addon">Описание:</label>
							<input class="form-control" type="text" value="<?php echo $row["description"]; ?>" name="description" maxlength="255" />
						</div>
						<div class="input-group">
							<label class="input-group-addon">Должность:</label>
							<input class="form-control" type="text" value="<?php echo $row["position"]; ?>" name="position" maxlength="150" />
						</div>

						<div class="input-group">
							<label class="input-group-addon">Отдел из спр.:</label>
							<select class="form-control" name="department_id">
								<option value=""></option>
		<?php $affiliatesList = mysqli_query($GLOBALS['link'],"SELECT * FROM departments WHERE affiliate_id = $curaff ORDER BY name"); ?>
		<?php while ($row2 = mysqli_fetch_assoc($affiliatesList)): ?>
								<option value="<?php echo $row2["id"]; ?>" <?php if ($row2["id"] == $row["d_id"]) echo 'selected'; ?>><?php echo $row2["name"]; ?></option>
		<?php endwhile; ?>
		<?php mysqli_free_result($affiliatesList); ?>
							</select>
						</div>
		<?php if($row['department_id'] == 0) : ?>
						<div class="input-group">
							<label class="input-group-addon">Отдел:</label>
							<input class="form-control" type="text" value="<?php echo $row["department"]; ?>" name="department" maxlength="100" />
						</div>
		<?php endif; ?>				
						<div class="input-group">
							<label class="input-group-addon">Кабинет:</label>
							<input class="form-control" type="text" value="<?php echo $row["room"]; ?>" name="room" maxlength="40" />
						</div>
						<div class="input-group">
							<label class="input-group-addon">Корпус из спр.:</label>
							<select class="form-control" name="building_id">
								<option value=""></option>
		<?php $affiliatesList = mysqli_query($GLOBALS['link'],"SELECT * FROM buildings WHERE affiliate_id = $curaff ORDER BY name"); ?>
		<?php while ($row2 = mysqli_fetch_assoc($affiliatesList)): ?>
								<option value="<?php echo $row2["id"]; ?>" <?php if ($row2["id"] == $row["b_id"]) echo 'selected'; ?>><?php echo $row2["name"]; ?></option>
		<?php endwhile; ?>
		<?php mysqli_free_result($affiliatesList); ?>
							</select>
						</div>
		<?php if($row['building_id'] == 0) : ?>
						<div class="input-group">
							<label class="input-group-addon">Корпус:</label>
							<input class="form-control" type="text" value="<?php echo $row["building"]; ?>" name="building" maxlength="60" />
						</div>
		<?php endif; ?>	
						<div class="input-group">
							<label class="input-group-addon">Филиал:</label>
							<select class="form-control" name="affiliate_id" required>
		<?php $affiliatesList = mysqli_query($GLOBALS['link'],"SELECT * FROM affiliates ORDER BY importance"); ?>
		<?php while ($row2 = mysqli_fetch_assoc($affiliatesList)): ?>
								<option value="<?php echo $row2["id"]; ?>" <?php if ($row2["id"] == $row["a_id"]) echo 'selected'; ?>><?php echo $row2["name"]; ?></option>
		<?php endwhile; ?>
		<?php mysqli_free_result($affiliatesList); ?>
							</select>			
						</div>
		<?php if($row['a_id'] != 1) : ?>				
						<div class="form-check">
							<label class="input-group-addon">Отображать в СКФНКЦ:</label>
							<input class="form-check-input" type="checkbox" value="<?php echo $row["boss"]; ?>" name="boss" <?php if( $row['boss'] == 1) echo ' checked';?>/>
						</div>
		<?php endif; ?>
						<div class="input-group">
							<label class="input-group-addon">Приоритет:</label>
							<input class="form-control" type="number" min="0" max="255"  value="<?php echo $row["importance"]; ?>" name="importance" required />
						</div>
						<input type="submit" value="Применить" class="btn btn-success" name="submit-editcontact">
					</form>
					<form action="index.php" method="post" onsubmit="return confirm('Ты хорошо подумал?')">
						<input type="hidden" value="<?php echo $row["id"]; ?>" name="id">
						<input type="submit" value="Удалить контакт" class="btn btn-danger" name="submit-deletecontact">			
					</form>
					</div>
				</div>
			</div>
			<?php $num++; ?>
		<?php endwhile; ?>
		
	</div>

	<?php else:*/ ?>	

		
			<?php $curdep = "null"; ?>
		
			<table class="table table-striped table-borderless table-sm">
				<thead class="table-dark">
					<tr>
						<th scope="col">Кабинет</th>
						<th scope="col">Сотрудник</th>
						<th scope="col">Телефон</th>
						<?php if(isset($_SESSION['logged_in'])) : ?>
						<th scope="col">Действия</th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>	

			<?php while ($row = mysqli_fetch_assoc($contactList)): ?>
				
				<?php if($curdep != $row["department_full"]): ?>
					<tr class="table-primary">
						<th colspan="4">
							<?php echo $row["department_full"]; ?>
							<?php /*if($affiliate<2): ?>
							<?php echo $row["a_name"]; ?>
							<?php endif;*/ ?>
						</th>
					</tr>
					<?php $curdep = $row["department_full"];?>
				<?php endif; ?>
					<tr>
						<td>
							<?php if(!empty($row["room"])): ?>
							<b><?php echo $row["room"];?></b>
							<?php endif; ?>
							<?php if(!empty($row["room"]) && !empty($row["building_full"])) echo '<br/>'; ?>
							<?php echo $row["building_full"];?>
						</td>
						<td>
							<?php if(!empty($row["name"])): ?>
							<b><?php echo $row["name"];?></b>
							<?php endif; ?>
							<?php if(!empty($row["name"]) && !empty($row["position"])) echo '<br/>'; ?>
							<?php echo $row["position"];?>
						</td>
						<td>
							<?php if(!empty($row["phone1"])): ?>
							<b><span class="nowrap"><?php mask($row["phone1"]);?></span></b>
							<?php endif; ?>
							<?php if(!empty($row["phone1"]) && !empty($row["phone2"])) echo '<br/>'; ?>
							<?php if(!empty($row["phone2"])): ?>
							<span class="nowrap"><?php mask($row["phone2"]);?></span>
							<?php endif; ?>
						</td>
						<?php if(isset($_SESSION['logged_in'])) : ?>
						<td>
							<i class="fa fa-pencil contact-action contact-action-edit"></i>
							<i class="fa fa-trash contact-action contact-action-delete"></i>
						</td>
						<?php endif; ?>
						
					</tr>
			<?php endwhile; ?>
			
				</tbody>
			</table>
				
		
		
	<?php /*endif;*/ ?>

<?php endif; ?>


<?php mysqli_free_result($contactList); ?>

<?php
	ob_end_flush();

	function compress_page($buffer)
	{
		return preg_replace("/\t|^\s*[\n\r]/m", "", $buffer);
	}
?>