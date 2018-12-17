<?	
// Get questions of contest
$questions = array();
if ($stmt = $db->prepare("SELECT * FROM `Questions` WHERE CONTEST=?")) {
	$stmt->bind_param("i",$_SESSION['CONTEST']['ID']);
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
if($_SESSION['ROLE'] == 'judge') echo '<h1>Contest&nbsp;ID:&nbsp;'.$_SESSION['CONTEST']['ID'].'</h1>';
if( strtotime($_SESSION['CONTEST']['FINISH']) < time() ) echo '<h2>This contest had finished!!</h2>';

echo '<div class="table-responsive"><table class="pure-table pure-table-horizontal"><thead><tr><th></th>';
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
echo '</tbody></table></div>';
?>
<script>
setTimeout(function(){ location = '' }, 20000);
</script>