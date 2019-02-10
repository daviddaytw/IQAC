<?
//Define api URL
if(isset($_SESSION['ID'])){
	session_unset();
	header('Location: http://' . $_SERVER['HTTP_HOST']);
	exit;
}

if(isset($_GET['role'])) $_SESSION['gateway'] = $_GET['role'];

// Participant don't need to login, just register for each contest
if($_SESSION['gateway'] == 'participant'){
	if(isset($_POST['contest'],$_POST['name'])){
		unset($_SESSION['gateway']);

		$CONTEST_INFO = getContest($_POST['contest']);
		// Check contest existence
		if( is_null($CONTEST_INFO)) die("The contest you're trying to join do not exist.");
		if( strtotime($CONTEST_INFO['FINISH']) < time()) die("The contest you're trying to join had already finished.");

		if(existParticipantOfContest($_POST['contest'],$_POST['name'])) die("Your nickname have been used, please change a new one.");

		// Create participant
		createParticipant($_POST['name'],$CONTEST_INFO['ID']);
		
		$_SESSION['NAME'] = $_POST['name'];
		$_SESSION['ROLE'] = 'participant';
		$_SESSION['CONTEST'] = $CONTEST_INFO['ID'];
		$_SESSION['MENU'] = 'PARTICIPANT';
		$_SESSION['ID'] = $db->insert_id;

		header('Location: http://' . $_SERVER['HTTP_HOST']);
		exit;
	} else {
		require('participant/register.php');
	}
}

// Login is required for Judge
if($_SESSION['gateway'] == 'judge'){
	//Process returned code
	if(isset($_POST['name'],$_POST['password'],$_POST['action'])){
		unset($_SESSION['gateway']);

		switch($_POST['action']){
			case 'signup':
				if( !empty(existAccount($_POST['name'])) ) die("Account Already exist");
				createAccount($_POST['name'],$_POST['password']);
			case 'login':
				$userinfo = authAccount($_POST['name'],$_POST['password']);
				if( empty($userinfo) ) die("Username or Password is Wrong");
			break;
		}

		$_SESSION['ID'] = $userinfo['ID'];
		$_SESSION['NAME'] = $userinfo['NAME'];
		$_SESSION['ROLE'] = 'judge';
		$_SESSION['MENU'] = 'JUDGE_DEFAULT';

		header('Location: http://' . $_SERVER['HTTP_HOST']);
		exit;
	} else {
		require('judge/register.php');
	}
}
?>
