<?
if(!isset($_SESSION['CONTEST'])) die('No contest selected');

show_header('Submissions' , 'Submissions');

// Judge mark a submission
if(isset($_GET['id'],$_POST['score'],$_POST['comment'])){
	if ($stmt = $db->prepare("UPDATE `Submissions` SET `SCORE`=?,`COMMENT`=? WHERE `ID`=?")) {
		$stmt->bind_param("isi",$_POST['score'],$_POST['comment'],$_GET['id']);
		$stmt->execute();
		$stmt->close();
	} else die('Error while preparing SQL');
}

// Get participants of contest
$participants = array();
if ($stmt = $db->prepare("SELECT * FROM `Participants` WHERE CONTEST=?")) {
	$stmt->bind_param("i",$_SESSION['CONTEST']);
	$stmt->execute();
	$result = $stmt->get_result();
	while( ($row=$result->fetch_assoc()) != NULL ) $participants[$row['ID']] = $row['NAME'];
	$result->free();
	$stmt->close();
} else die('Error while preparing SQL');

// Get questions of judge
$questions = array();
if ($stmt = $db->prepare("SELECT * FROM `Questions` WHERE JUDGE=?")) {
	$stmt->bind_param("s",$_SESSION['ID']);
	$stmt->execute();
	$result = $stmt->get_result();
	while( ($row=$result->fetch_assoc()) != NULL ) $questions[$row['ID']] = $row['TITLE'];
	$result->free();
	$stmt->close();
} else die('Error while preparing SQL');
?>

<div class="pure-g panel">
	<div class="pure-u-1-2 table-responsive">
		<table class="pure-table">
			<thead>
				<tr>
					<th>#</th>
					<th>Status</th>
					<th>Comment</th>
					<th>Question</th>
					<th>Participant</th>
				</tr>
			</thead>
			<tbody>
<?
// Get submissions of contest
if ($result = $db->query("SELECT * FROM `Submissions` WHERE QUESTION IN (".implode(',',array_keys($questions)).") ORDER BY ID DESC LIMIT 20")) {
	while( ($row=$result->fetch_assoc()) != NULL ){
		echo '<tr>';
		echo '<td>'.$row['ID'].'</td>';
		echo '<td class="score">'.( is_null($row['SCORE']) ? '<a href="?id='.$row['ID'].'">Pending</a>' : $row['SCORE']).'</td>';
		echo '<td>'.( empty($row['COMMENT']) ? '(No comment)' : $row['COMMENT']).'</td>';
		echo '<td>'.$questions[$row['QUESTION']].'</td>';
		echo '<td>'.$participants[$row['PARTICIPANT']].'</td>';
		echo '</tr>';
		if($_GET['id'] == $row['ID']){
			$qid = $row['QUESTION'];
			$answer = $row['CONTENT'];
		}
	}
	$result->free();
} else die('Error while query SQL');
?>
			</tbody>
		</table>
	</div>
	<div class="pure-u-1-2">
		<div class="panel">
<?
if(isset($_GET['id'])){
	// Get questions of submission
	if ($stmt = $db->prepare("SELECT * FROM `Questions` WHERE ID=?")) {
		$stmt->bind_param("i",$qid);
		$stmt->execute();
		$content = $stmt->get_result()->fetch_assoc()['CONTENT'];
		$stmt->free_result();
		$stmt->close();
	} else die('Error while preparing SQL');
?>
			<h1><?= $questions[$qid] ?></h1>
			<div><?= $content ?></div>
			<h1>Participant's Answer</h1>
			<?= $answer ?>

			<form class="pure-form pure-form-stacked" method="POST">
				<fieldset>
					<legend>Judge Area</legend>
					<label for="score">Score</label>
					<input id="score" name="score" type="number" min="0" max="100">
					<label for="comment">Comment</label>
					<textarea id="comment" name="comment"></textarea>
					<button type="submit" class="pure-button pure-button-primary">Submit</button>
				</fieldset>
			</form>
<?
}
?>
		</div>
	</div>
</div>
<? show_footer(); ?>