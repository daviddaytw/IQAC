<?
/** CONFIGURATION SETUP START **/
require('config.php');
require('function.php');

$MENUS = array(
	'JUDGE_DEFAULT' => array(
		'Contests' => '/',
		'Create Contest' => '/configContest',
		'Logout' => '/auth'
	),
	'JUDGE_CONTEST' => array(
		'ScoreBoard' => '/',
		'Submissions' => '/submissions',
		'Questions' => '/questions',
		'Configure' => '/configContest',
		'Exit' => '/?contest=exit'
	),
	'PARTICIPANT' => array(
		'ScoreBoard' => '/',
		'Questions' => '/questions',
		'Submissions' => '/submissions',
		'Logout' => '/auth'
	)
);

session_start();

/** CONFIGURATION SETUP END **/

/** LOCALIZATION SETUP START**/
if(isset($_GET['timezone'])) $_SESSION['TIMEZONE'] = $_GET['timezone'];
if(isset($_SESSION['TIMEZONE'])){
	date_default_timezone_set($_SESSION['TIMEZONE']);
} else {
	echo '<script>window.location = "?timezone="+encodeURI(Intl.DateTimeFormat().resolvedOptions().timeZone);</script>';
	exit;
}
/** LOCALIZATION SETUP END **/

/** Get Contest Information if user is in a contest**/
if(isset($_SESSION['CONTEST'])) $CONTEST_INFO = getContest($_SESSION['CONTEST']);

// Remove extension and set index if not set
$REQUEST_CODE = ( empty($_GET['page']) ? "index" : str_replace('/','',explode(".",$_GET['page'])[0]) );

// Direct access to login or logout
if($REQUEST_CODE == 'auth') $PAGE_PATH = 'pages/auth.php';
else $PAGE_PATH = 'pages/' . $_SESSION['ROLE'] . '/' . $REQUEST_CODE . '.php';

// open requested page if exists
if(file_exists($PAGE_PATH)) require($PAGE_PATH);
else http_response_code(410);
?>
