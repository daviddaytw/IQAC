<?
// user select contest
if(isset($_GET['contest'])){
	if($_GET['contest'] == 'exit'){
		unset($_SESSION['CONTEST']);
		$_SESSION['MENU'] = 'JUDGE_DEFAULT';
	} else {
		// check if user is the judge of contest
		if ($stmt = $db->prepare("SELECT * FROM `Judges` WHERE JUDGE=? AND CONTEST=?")) {
			$stmt->bind_param("si",$_SESSION['ID'],$_GET['contest']);
			$stmt->execute();
			if( !$stmt->fetch() ) die("You're not the judge of the contest");
			$stmt->close();

			// get contest name
			if ($stmt = $db->prepare("SELECT * FROM `Contests` WHERE ID=?")) {
				$stmt->bind_param("i",$_GET['contest']);
				$stmt->execute();
				$_SESSION['CONTEST'] = $stmt->get_result()->fetch_assoc();
				$_SESSION['MENU'] = 'JUDGE_CONTEST';
				$stmt->free_result();
				$stmt->close();
			} else die('Error while preparing SQL');
		} else die('Error while preparing SQL');
	}
}

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

if(isset($_SESSION['CONTEST'])){
	show_header($_SESSION['CONTEST']['NAME'] , 'ScoreBoard');
	echo '<div class="panel">';
	echo '<p>This contest start at '.$_SESSION['CONTEST']['BEGIN'].', finish at '.$_SESSION['CONTEST']['FINISH'].'</p>';
	
	if( strtotime($_SESSION['CONTEST']['BEGIN']) > time() ) require('./pages/general/count_down.php');
	else require('./pages/general/scoreboard.php');
	
	echo '</div>';
	show_footer();
} else require('listContest.php');
?>