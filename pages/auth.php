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

		// Check contest existence and get contest name
		if ($stmt = $db->prepare("SELECT * FROM `Contests` WHERE ID=?")) {
			$stmt->bind_param("i",$_POST['contest']);
			$stmt->execute();
			$contest_info = $stmt->get_result()->fetch_assoc();
			$stmt->free_result();
			$stmt->close();
		} else die('Error while preparing SQL');
		if($contest_info == NULL) die("The contest you're trying to join do not exist.");

		// Check participant name conflict
		if ($stmt = $db->prepare("SELECT * FROM `Participants` WHERE CONTEST=? AND NAME=?")) {
			$stmt->bind_param("is",$_POST['contest'],$_POST['name']);
			$stmt->execute();
			if($stmt->fetch()) die("Your nickname have been used by other participant, please change a new one.");
			$stmt->close();
		} else die('Error while preparing SQL');

		// Create participant
		if ($stmt = $db->prepare("INSERT INTO `Participants` (`NAME`,`CONTEST`) VALUES (?,?);")) {
			$stmt->bind_param("si",$_POST['name'],$_POST['contest']);
			$stmt->execute();
			$stmt->close();
		} else die('Error while preparing SQL');
		
		$_SESSION['NAME'] = $_POST['name'];
		$_SESSION['ROLE'] = 'participant';
		$_SESSION['CONTEST'] = $contest_info;
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

function apiRequest($url, $post=FALSE) {
	global $access_token;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	if($post) curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
	$headers[] = 'Accept: application/json';
	if($access_token) $headers[] = 'Authorization: Bearer ' . $access_token;
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = curl_exec($ch);
	curl_close($ch);
	return json_decode($response);
}
?>
