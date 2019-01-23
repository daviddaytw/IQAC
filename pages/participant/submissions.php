<?
show_header('Submissions' , 'Submissions');

// Get questions of judge
$question_list = getQuestionsOfContest($CONTEST_INFO['ID']);
$submissions = getSubmissionsOfParticipant($_SESSION['ID']);
?>

<div class="panel table-responsive">
	<table class="pure-table">
		<thead>
			<tr>
				<th>#</th>
				<th>Question</th>
				<th>Answer</th>
				<th>Status</th>
				<th>Comment</th>
			</tr>
		</thead>
		<tbody>
			<? foreach($submissions as $row ): ?>
			<tr>
			<td><?= $row['ID'] ?></td>
			<td><?= $question_list[$row['QUESTION']] ?></td>
			<td><?= $row['CONTENT'] ?></td>
			<td class="score"><?= is_null($row['SCORE']) ? 'Pending' : $row['SCORE'] ?></td>
			<td><?= empty($row['COMMENT']) ? '<i>(No comment)</i>' : $row['COMMENT'] ?></td>
			</tr>
			<? endforeach; ?>
		</tbody>
	</table>
</div>
<? show_footer(); ?>