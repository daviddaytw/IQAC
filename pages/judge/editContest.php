<?
if(isset($_SESSION['CONTEST'])) show_header('Edit '.$_SESSION['CONTEST']['NAME'] , 'Edit');
else show_header('New Conteset' , 'Create Contest');

if(isset($_POST['name'])){
	if(isset($_SESSION['CONTEST'])){
		//Edit contest
		if ($stmt = $db->prepare("UPDATE `Contests` SET `NAME`=?,`BEGIN`=?,`FINISH`=? WHERE `ID`=?")) {
			$begin = $_POST['beginDate'] . ' ' . $_POST['beginTime'];
			$finish = $_POST['finishDate'] . ' ' . $_POST['finishTime'];
			$stmt->bind_param("sssi",$_POST['name'],$begin,$finish,$_SESSION['CONTEST']);
			$stmt->execute();
			$stmt->close();
			$_SESSION['CONTEST']['NAME'] = $_POST['name'];
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
		$contest_id = $db->insert_id;
		// Link judge to the contest
		if ($stmt = $db->prepare("INSERT INTO `Judges` (`JUDGE`,`CONTEST`) VALUES (?,?);")) {
			$stmt->bind_param("si",$_SESSION['ID'],$contest_id);
			$stmt->execute();
			$stmt->close();
		} else die('Error while preparing SQL');
	}
	
}
?>
<div style="text-align: center;">
	<form class="pure-form pure-form-aligned" method="POST">
		<fieldset>
			<div class="pure-control-group">
				<label for="name">Contest Name</label>
				<input id="name" name="name" type="text" value="<?= $_SESSION['CONTEST']['NAME'] ?>" required>
			</div>
			<div class="pure-control-group">
				<label for="beginDate">Begin Date</label>
				<input id="beginDate" name="beginDate" type="date" value="<?= explode(' ',$_SESSION['CONTEST']['BEGIN'])[0] ?>" required>
			</div>
			<div class="pure-control-group">
				<label for="beginTime">Begin Time</label>
				<input id="beginTime" name="beginTime" type="time" value="<?= explode(' ',$_SESSION['CONTEST']['BEGIN'])[1] ?>" required>
			</div>
			<div class="pure-control-group">
				<label for="finishDate">Finish Date</label>
				<input id="finishDate" name="finishDate" type="date" value="<?= explode(' ',$_SESSION['CONTEST']['FINISH'])[0] ?>" required>
			</div>
			<div class="pure-control-group">
				<label for="finishTime">Finish Time</label>
				<input id="finishTime" name="finishTime" type="time" value="<?= explode(' ',$_SESSION['CONTEST']['FINISH'])[1] ?>" required>
			</div>
			<div class="pure-controls">
				<button type="submit" class="pure-button pure-button-primary">Submit</button>
			</div>
		</fieldset>
	</form>
</div>
<? show_footer(); ?>