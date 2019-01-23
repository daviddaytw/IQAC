<?
// user select contest
if(isset($_GET['contest'])){
	if($_GET['contest'] == 'exit'){
		unset($_SESSION['CONTEST']);
		$_SESSION['MENU'] = 'JUDGE_DEFAULT';
	} else {
		// check if user is the judge of contest
		$_GET['contest'] = intval($_GET['contest']);
		if(isJudgeOfContest($_SESSION['ID'],$_GET['contest'])){
			$_SESSION['CONTEST'] = $_GET['contest'];
			$_SESSION['MENU'] = 'JUDGE_CONTEST';
		} else die("You're not the judge of the contest");
	}
	header('Location: /');
	exit;
}

if(isset($CONTEST_INFO)){
	show_header($CONTEST_INFO['NAME'] , 'ScoreBoard');
	echo '<div class="panel">';
	echo '<p>This contest start at '.$CONTEST_INFO['BEGIN'].', finish at '.$CONTEST_INFO['FINISH'].'</p>';
	
	if( strtotime($CONTEST_INFO['BEGIN']) > time() ) require('./pages/general/count_down.php');
	else require('./pages/general/scoreboard.php');
	
	echo '</div>';
	show_footer();
} else require('listContest.php');
?>