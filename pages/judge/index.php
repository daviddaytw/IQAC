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
			$stmt->free_result();
			$stmt->close();

			// get contest name
			if ($stmt = $db->prepare("SELECT * FROM `Contests` WHERE ID=?")) {
				$stmt->bind_param("i",$_GET['contest']);
				$stmt->execute();
				$_SESSION['CONTEST'] = $stmt->get_result()->fetch_assoc();
				$_SESSION['MENU'] = 'JUDGE_CONTEST';
				$smtm->free_result();
				$stmt->close();
			} else die('Error while preparing SQL');
		} else die('Error while preparing SQL');
	}
}

if(isset($_SESSION['CONTEST'])) require('scoreboard.php');
else require('listContest.php');
?>