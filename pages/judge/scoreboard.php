<?php
if(!isset($_SESSION['CONTEST'])) die('No contest selected');

show_header($_SESSION['CONTEST_NAME'] , 'ScoreBoard');

if ($stmt = $db->prepare("SELECT * FROM `Contests` WHERE ID=?")) {
	$stmt->bind_param("i",$_SESSION['CONTEST']);
	$stmt->execute();
	$contest_data = $stmt->get_result()->fetch_assoc();
	$stmt->free_result();
	$stmt->close();
} else die('Error while preparing SQL');

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

echo '<div class="panel">';
echo '<h1>Contest&nbsp;ID:&nbsp;'.$_SESSION['CONTEST'].'</h1>';
echo '<p>This contest start at '.$contest_data['BEGIN'].', finish at '.$contest_data['FINISH'].'</p>';
if( strtotime($contest_data['BEGIN']) > time() ){
	echo '<h2>Contest has not start yet!!</h2>';
	//TODO: echo '<h3 id="count_down">'.$contest_data['BEGIN'].'</h3>';
	echo '<div class="pure-g" style="text-align: center;">';
	foreach($participants as $name){
		echo '<div class="pure-u-1-3 pure-u-md-1-6"><p>'.htmlentities($name).'</p></div>';
	}
	echo '</div>';
} else {
	// Get questions of contest
	$questions = array();
	if ($stmt = $db->prepare("SELECT * FROM `Questions` WHERE CONTEST=?")) {
		$stmt->bind_param("i",$_SESSION['CONTEST']);
		$stmt->execute();
		$result = $stmt->get_result();
		while( ($row=$result->fetch_assoc()) != NULL ) $questions[$row['ID']] = $row['TITLE'];
		$result->free();
		$stmt->close();
	} else die('Error while preparing SQL');

	// Get scoreboard from submissions
	$scores = array();
	foreach($participants as $pid => $name ){
		$scores[$pid]['SUM'] = 0;
		foreach($questions as $qid => $title){
			if ($stmt = $db->prepare("SELECT * FROM `Submissions` WHERE QUESTION=? AND PARTICIPANT=? ORDER BY SCORE DESC LIMIT 1")) {
				$stmt->bind_param("ii",$qid,$pid);
				$stmt->execute();
				$scores[$pid][$qid] = $stmt->get_result()->fetch_assoc()['SCORE'];
				$stmt->free_result();
				$stmt->close();
			} else die('Error while preparing SQL');
			if( !empty($scores[$pid][$qid]) ) $scores[$pid]['SUM'] += $scores[$pid][$qid];
		}
	}

	// Sort the Rank
	$rank  = array();
	foreach( $scores as $pid => $value ) $rank[$pid] = $value['SUM'];
	arsort($rank);

	// Render the scoreboard
	echo '<table class="pure-table pure-table-horizontal"><thead><tr><th></th>';
	foreach( $questions as $title ) echo '<th>'.htmlentities($title).'</th>';
	echo '</tr></thead><tbody>';
	foreach( $rank as $pid => $value ){
		echo '<tr>';
		echo '<td>'.htmlentities($participants[$pid]).'</td>';
		foreach( $questions as $qid => $title ){
			echo '<td class="score">'.htmlentities($scores[$pid][$qid]).'</td>';
		}
		echo '</tr>';
	}
	echo '</tbody></table>';
}
echo '</div>';
?>
<script>
setTimeout(function(){ location = '' }, 20000);
</script>
<?php show_footer(); ?>