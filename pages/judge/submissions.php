<?
if(!isset($CONTEST_INFO)) die('No contest selected');

show_header('Submissions' , 'Submissions');

// Judge mark a submission
if(isset($_GET['id'],$_POST['score'],$_POST['comment'])){
	gradeSubmission($_GET['id'],$_POST['score'],$_POST['comment']);
	header('Location: /submissions');
	exit;
}

$questions = getQuestionsOfJudgeInContest($_SESSION['ID'],$CONTEST_INFO['ID']);
$participants = getPartcipantsOfContest($CONTEST_INFO['ID']);
$submissions = (empty($questions)? array() :  getSubmissionsOfQuestions(array_keys($questions)));

if(isset($_GET['id'])){
	$current_submission = getSubmission($_GET['id']);
	$current_question = getQuestion($current_submission['QUESTION']);
}
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
				<? foreach($submissions as $row ): ?>
					<tr>
						<td><?= $row['ID'] ?></td>
						<td class="score"><?= is_null($row['SCORE']) ? '<a href="?id='.$row['ID'].'">Pending</a>': $row['SCORE'] ?></td>
						<td><?= empty($row['COMMENT']) ? '<i>(No comment)</i>' : $row['COMMENT'] ?></td>
						<td><?= $questions[$row['QUESTION']] ?></td>
						<td><?= $participants[$row['PARTICIPANT']] ?></td>
					</tr>
				<? endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class="pure-u-1-2">
		<div class="panel">
			<? if(isset($current_submission,$current_question)): ?>
				<h1><?= $current_question['TITLE'] ?></h1>
				<div><?= $current_question['CONTENT'] ?></div>
				<h1>Participant's Answer</h1>
				<?= $current_submission['CONTENT'] ?>

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
			<? else: ?>
				<script>
					setTimeout(function(){ location = '' }, 5000);
				</script>
			<? endif; ?>
		</div>
	</div>
</div>
<? show_footer(); ?>