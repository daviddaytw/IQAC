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

		// Auth participant if exist
		$profile = getParticipant($CONTEST_INFO['ID'],$_POST['name']);
		
		// Create participant if not used
		if( is_null($profile) ){
            createParticipant($_POST['name'],$_POST['password'],$CONTEST_INFO['ID']);
		}
		else if( $profile['PASSWORD'] !== sha1($_POST['password'])){
            die('Password incorrect');
		}

		
		$profile = getParticipant($CONTEST_INFO['ID'],$_POST['name']);
		
		$_SESSION['NAME'] = $profile['NAME'];
		$_SESSION['CONTEST'] = $profile['CONTEST'];
		$_SESSION['ID'] = $profile['ID'];
		$_SESSION['ROLE'] = 'participant';
		$_SESSION['MENU'] = 'PARTICIPANT';

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
