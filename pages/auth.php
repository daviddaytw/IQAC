<?
//Define api URL
$authorizeURL = 'https://accounts.google.com/o/oauth2/auth';
$tokenURL = 'https://oauth2.googleapis.com/token';
$access_token = false;


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
	if (isset($_GET['code'])) {
		$token = apiRequest($tokenURL, array(
			'client_id' => $OAUTH2_CLIENT_ID,
			'client_secret' => $OAUTH2_CLIENT_SECRET,
			'redirect_uri' => "http://$_SERVER[SERVER_NAME]/auth",
			'grant_type' => 'authorization_code',
			'code' => $_GET['code']
		));
		$access_token = $token->access_token;
		$userinfo = apiRequest('https://www.googleapis.com/oauth2/v1/userinfo?alt=json');

		// Got user information, login
		if(empty($userinfo)) die("Error while requesting api");

		// Update account if exist, otherwise create
		if(empty(getAccount($userinfo->id))) createAccount($userinfo->id,$userinfo->name,$userinfo->picture);
		else updateAccount($userinfo->id,$userinfo->name,$userinfo->picture);
		
		$_SESSION['ID'] = $userinfo->id;
		$_SESSION['NAME'] = $userinfo->name;
		$_SESSION['ROLE'] = 'judge';
		$_SESSION['MENU'] = 'JUDGE_DEFAULT';
		unset($_SESSION['gateway']);

		header('Location: http://' . $_SERVER['HTTP_HOST']);
		exit; 
	} else{
		//Redirect User to Google login page
		$params = array(
			'client_id' => $OAUTH2_CLIENT_ID,
			'redirect_uri' => "http://$_SERVER[SERVER_NAME]/auth",
			'response_type' => 'code',
			'scope' => 'https://www.googleapis.com/auth/userinfo.profile'
		);
		header('Location: ' . $authorizeURL . '?' . http_build_query($params));
		exit;
	}
}
?>
