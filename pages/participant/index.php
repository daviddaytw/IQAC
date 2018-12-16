<?
show_header($_SESSION['CONTEST_NAME'] , 'ScoreBoard');

// Get contest infomation
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
echo '<p>This contest start at '.$contest_data['BEGIN'].', finish at '.$contest_data['FINISH'].'</p>';

if( strtotime($contest_data['BEGIN']) > time() ) require('count_down.php');
else require('scoreboard.php');

echo '</div>';
?>
<? show_footer(); ?>