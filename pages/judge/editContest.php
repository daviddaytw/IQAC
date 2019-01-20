<?
if(isset($CONTEST_INFO)) show_header('Edit '.$CONTEST_INFO['NAME'] , 'Edit');
else show_header('New Conteset' , 'Create Contest');

if(isset($_POST['name'])){
	if(isset($CONTEST_INFO)){
		//Edit contest
		if ($stmt = $db->prepare("UPDATE `Contests` SET `NAME`=?,`BEGIN`=?,`FINISH`=? WHERE `ID`=?")) {
			$begin = $_POST['beginDate'] . ' ' . $_POST['beginTime'];
			$finish = $_POST['finishDate'] . ' ' . $_POST['finishTime'];
			$stmt->bind_param("sssi",$_POST['name'],$begin,$finish,$CONTEST_INFO['ID']);
			$stmt->execute();
			$stmt->close();
			$CONTEST_INFO['NAME'] = $_POST['name'];
			$CONTEST_INFO['BEGIN'] = $begin;
			$CONTEST_INFO['FINISH'] = $finish;
		} else die('Error while preparing SQL');
	} else {
		//Create contest
		if ($stmt = $db->prepare("INSERT INTO `Contests` (`NAME`,`BEGIN`,`FINISH`) VALUES (?,?,?);")) {
			$begin = $_POST['beginDate'] . ' ' . $_POST['beginTime'];
			$finish = $_POST['finishDate'] . ' ' . $_POST['finishTime'];
			$stmt->bind_param("sss",$_POST['name'],$begin,$finish);
			$stmt->execute();
			$stmt->close();
		} else die('Error while preparing SQL');
		$CONTEST_INFO_id = $db->insert_id;
		// Link judge to the contest
		if ($stmt = $db->prepare("INSERT INTO `Judges` (`JUDGE`,`CONTEST`) VALUES (?,?);")) {
			$stmt->bind_param("si",$_SESSION['ID'],$CONTEST_INFO_id);
			$stmt->execute();
			$stmt->close();
		} else die('Error while preparing SQL');
	}
}

// edit judge link
if(isset($_POST['judge'],$CONTEST_INFO)){
	if($_POST['action'] == 'add'){
		if ($stmt = $db->prepare("INSERT INTO `Judges` (`JUDGE`,`CONTEST`) VALUES (?,?);")) {
			$stmt->bind_param("si",$_POST['judge'],$CONTEST_INFO['ID']);
			$stmt->execute();
			$stmt->close();
		} else die('Error while preparing SQL');
	}
	if($_POST['action'] == 'remove'){
		if ($stmt = $db->prepare("DELETE FROM `Judges`  WHERE `JUDGE`=? AND `CONTEST`=?")) {
			$stmt->bind_param("si",$_POST['judge'],$CONTEST_INFO['ID']);
			$stmt->execute();
			$stmt->close();
		} else die('Error while preparing SQL');
	}
}
?>
<div class="pure-g">
	<div class="pure-u-1 pure-u-md-1-2">
		<form class="pure-form pure-form-aligned" method="POST">
			<fieldset>
				<div class="pure-control-group">
					<label for="name">Contest Name</label>
					<input id="name" name="name" type="text" value="<?= $CONTEST_INFO['NAME'] ?>" required>
				</div>
				<div class="pure-control-group">
					<label for="beginDate">Begin Date</label>
					<input id="beginDate" name="beginDate" type="date" value="<?= explode(' ',$CONTEST_INFO['BEGIN'])[0] ?>" required>
				</div>
				<div class="pure-control-group">
					<label for="beginTime">Begin Time</label>
					<input id="beginTime" name="beginTime" type="time" value="<?= explode(' ',$CONTEST_INFO['BEGIN'])[1] ?>" required>
				</div>
				<div class="pure-control-group">
					<label for="finishDate">Finish Date</label>
					<input id="finishDate" name="finishDate" type="date" value="<?= explode(' ',$CONTEST_INFO['FINISH'])[0] ?>" required>
				</div>
				<div class="pure-control-group">
					<label for="finishTime">Finish Time</label>
					<input id="finishTime" name="finishTime" type="time" value="<?= explode(' ',$CONTEST_INFO['FINISH'])[1] ?>" required>
				</div>
				<div class="pure-controls">
					<button type="submit" class="pure-button pure-button-primary">Submit</button>
				</div>
			</fieldset>
		</form>
	</div>
	<? if(isset($CONTEST_INFO)): ?>
		<div class="pure-u-1 pure-u-md-1-2">
			<table class="pure-table">
				<thead>
					<tr><th>Judge ID list</th></tr>
				</thead>
				<tbody>
					<?
					if ($stmt = $db->prepare("SELECT * FROM `Judges` WHERE CONTEST=?")) {
						$stmt->bind_param("i",$CONTEST_INFO['ID']);
						$stmt->execute();
						$result = $stmt->get_result();

						while( $row = $result->fetch_assoc() ){
							echo '<tr>';
							echo '<td>'.$row['JUDGE'].'</td>';
							echo '</tr>';
						}
						$result->free();
						$stmt->close();
					} else die('Error while preparing SQL');
					?>
				</tbody>
			</table>
			<form class="pure-form pure-form-aligned" method="POST">
				<fieldset>
					<div class="pure-control-group">
						<label for="judge">Judge ID</label>
						<input id="judge" name="judge" type="text" required>
					</div>
					<div class="pure-control-group">
						<label for="action">Action</label>
						<select id="action" name="action">
							<option value="add">Add</option>
							<option value="remove">Remove</option>
						</select>
					</div>
					<div class="pure-controls">
						<button type="submit" class="pure-button pure-button-primary">Submit</button>
					</div>
				</fieldset>
			</form>
		</div>
	<? endif; ?>
</div>
<? show_footer(); ?>