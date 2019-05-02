<?	
// Get questions of contest
$questions = getQuestionsOfContest($CONTEST_INFO['ID']);
$participants = getPartcipantsOfContest($CONTEST_INFO['ID']);

// Get scoreboard data
$scores = array();
foreach($participants as $pid => $name ){
	$scores[$pid]['SUM'] = 0;
	foreach($questions as $qid => $title){
		$scores[$pid][$qid] = getBestSubmissionScoreOfParticipant($qid,$pid);
		if( !empty($scores[$pid][$qid]) ) $scores[$pid]['SUM'] += $scores[$pid][$qid];
	}
}

// Sort the Rank
$rank  = array();
foreach( $scores as $pid => $value ) $rank[$pid] = $value['SUM'];
arsort($rank);
?>

<? if( strtotime($CONTEST_INFO['FINISH']) < time() ): ?>
<h1>This contest had finished!!</h1>
<? endif; ?>

<? if($_SESSION['ROLE'] == 'judge'): ?>
<h2>Contest ID: <?= $CONTEST_INFO['ID'] ?></h2>
<? endif; ?>

<div class="table-responsive">
	<table class="pure-table pure-table-horizontal">
		<thead>
			<tr>
				<th></th>
				<? foreach( $questions as $title ): ?>
				<th><?= htmlentities($title) ?></th>
				<? endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<? foreach( $rank as $pid => $value ): ?>
		<tr>
			<td><?= htmlentities($participants[$pid]) ?></td>
			<? foreach( $questions as $qid => $title ): ?>
			<td class="score"><?= htmlentities($scores[$pid][$qid]) ?></td>
			<? endforeach; ?>
		</tr>
		<? endforeach; ?>
	</tbody>
</table>
</div>
<script>
	setTimeout(function(){ location = '' }, 20000);
</script>
