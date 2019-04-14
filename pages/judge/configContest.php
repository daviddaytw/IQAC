<?
if(isset($CONTEST_INFO)) show_header('Edit '.$CONTEST_INFO['NAME'] , 'Edit');
else show_header('New Conteset' , 'Create Contest');

if(isset($_POST['name'])){
	$begin = $_POST['beginDate'] . ' ' . $_POST['beginTime'];
	$finish = $_POST['finishDate'] . ' ' . $_POST['finishTime'];
	if(isset($CONTEST_INFO)){
		//Edit contest
		updateContest($_POST['name'],$begin,$finish,$CONTEST_INFO['ID']);
		$CONTEST_INFO['NAME'] = $_POST['name'];
		$CONTEST_INFO['BEGIN'] = $begin;
		$CONTEST_INFO['FINISH'] = $finish;
	} else {
		//Create contest
		createContest($_POST['name'],$begin,$finish);
		$CONTEST_INFO_id = $db->insert_id;
		linkJudgeToContest($_SESSION['ID'],$CONTEST_INFO_id);
	}
}

// edit judge link
if(isset($_POST['judge'],$CONTEST_INFO)){
	if($_POST['action'] == 'add') linkJudgeToContest($_POST['judge'],$CONTEST_INFO['ID']);
	if($_POST['action'] == 'remove') delinkJudgeToContest($_POST['judge'],$CONTEST_INFO['ID']);
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
					<tr><th>Judge ID</th><th>Judge Name</th></tr>
				</thead>
				<tbody>
					<? foreach(getJudgesOfContest($CONTEST_INFO['ID']) as $row): ?>
					<tr>
						<td><?= $row['JUDGE'] ?></td>
						<? $account = getAccount($row['JUDGE']) ?>
						<td><?= $account['NAME'] ?></td>
					</tr>
					<? endforeach; ?>
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