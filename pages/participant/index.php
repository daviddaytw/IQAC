<?
show_header($_SESSION['CONTEST']['NAME'] , 'ScoreBoard');

// Get participants of contest
$participants = array();
if ($stmt = $db->prepare("SELECT * FROM `Participants` WHERE CONTEST=?")) {
	$stmt->bind_param("i",$_SESSION['CONTEST']['ID']);
	$stmt->execute();
	$result = $stmt->get_result();
	while( ($row=$result->fetch_assoc()) != NULL ) $participants[$row['ID']] = $row['NAME'];
	$result->free();
	$stmt->close();
} else die('Error while preparing SQL');

echo '<div class="panel">';
echo '<p>This contest start at '.$_SESSION['CONTEST']['BEGIN'].', finish at '.$_SESSION['CONTEST']['FINISH'].'</p>';

if( strtotime($_SESSION['CONTEST']['BEGIN']) > time() ) require('count_down.php');
else require('scoreboard.php');

echo '</div>';
?>
<? show_footer(); ?>