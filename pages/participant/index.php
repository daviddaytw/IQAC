<?
show_header($CONTEST_INFO['NAME'] , 'ScoreBoard');

// Get participants of contest
$participants = array();
if ($stmt = $db->prepare("SELECT * FROM `Participants` WHERE CONTEST=?")) {
	$stmt->bind_param("i",$CONTEST_INFO['ID']);
	$stmt->execute();
	$result = $stmt->get_result();
	while( ($row=$result->fetch_assoc()) != NULL ) $participants[$row['ID']] = $row['NAME'];
	$result->free();
	$stmt->close();
} else die('Error while preparing SQL');

echo '<div class="panel">';
echo '<p>This contest start at '.$CONTEST_INFO['BEGIN'].', finish at '.$CONTEST_INFO['FINISH'].'</p>';

if( strtotime($CONTEST_INFO['BEGIN']) > time() ) require('./pages/general/count_down.php');
else require('./pages/general/scoreboard.php');

echo '</div>';
?>
<? show_footer(); ?>