<?
/* Account */
function authAccount($name,$password){
	global $db;
	$password = sha1($password);
	if ($stmt = $db->prepare("SELECT ID,NAME FROM `Accounts` WHERE NAME=? AND PASSWORD=?")) {
		$stmt->bind_param("ss",$name,$password);
		$stmt->execute();
		$result = $stmt->get_result()->fetch_assoc();
		$stmt->free_result();
		$stmt->close();
	} else die('Error while preparing SQL');
	return $result;
}

function createAccount($name,$password){
	global $db;
	$password = sha1($password);
	if ($stmt = $db->prepare("INSERT INTO `Accounts` (`NAME`,`PASSWORD`) VALUES (?,?);")) {
		$stmt->bind_param("ss",$name,$password);
		$stmt->execute();
		$stmt->close();
	} else die('Error while preparing SQL');
}


function existAccount($name){
	global $db;
	if ($stmt = $db->prepare("SELECT ID,NAME FROM `Accounts` WHERE NAME=?")) {
		$stmt->bind_param("s",$name);
		$stmt->execute();
		$result = $stmt->fetch();
		$stmt->free_result();
		$stmt->close();
	} else die('Error while preparing SQL');
	return $result;
}

function getAccount($id){
	global $db;
	if ($stmt = $db->prepare("SELECT ID,NAME FROM `Accounts` WHERE ID=?")) {
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result = $stmt->get_result()->fetch_assoc();
		$stmt->free_result();
		$stmt->close();
	} else die('Error while preparing SQL');
	return $result;
}

/* Participant */
function createParticipant($name,$password,$id){
	global $db;
	$password = sha1($password);
	if ($stmt = $db->prepare("INSERT INTO `Participants` (`CONTEST`,`NAME`,`PASSWORD`) VALUES (?,?,?);")) {
		$stmt->bind_param("iss",$id,$name,$password);
		$stmt->execute();
		$stmt->close();
	} else die('Error while preparing SQL');
}

function getParticipant($id,$name){
	global $db;
	if ($stmt = $db->prepare("SELECT * FROM `Participants` WHERE CONTEST=? AND NAME=?")) {
		$stmt->bind_param("is",$id,$name);
		$stmt->execute();
		$result = $stmt->get_result()->fetch_assoc();
		$stmt->free_result();
		$stmt->close();
	} else die('Error while preparing SQL');
	return $result;
}

function getPartcipantsOfContest($id){
	global $db;
	$result = array();
	if ($stmt = $db->prepare("SELECT ID,NAME FROM `Participants` WHERE CONTEST=?")) {
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$handle = $stmt->get_result();
		while( ($row=$handle->fetch_assoc()) != NULL ) $result[$row['ID']] = $row['NAME'];
		$handle->free();
		$stmt->close();
	} else die('Error while preparing SQL');
	return $result;
}



/* Judge */
function delinkJudgeToContest($judge,$contest){
	global $db;
	if ($stmt = $db->prepare("DELETE FROM `Judges`  WHERE `JUDGE`=? AND `CONTEST`=?")) {
		$stmt->bind_param("ii",$judge,$contest);
		$stmt->execute();
		$stmt->close();
	} else die('Error while preparing SQL');
}

function linkJudgeToContest($judge,$contest){
	global $db;
	if ($stmt = $db->prepare("INSERT INTO `Judges` (`JUDGE`,`CONTEST`) VALUES (?,?);")) {
		$stmt->bind_param("ii",$judge,$contest);
		$stmt->execute();
		$stmt->close();
	} else die('Error while preparing SQL');
}

function isJudgeOfContest($judge,$contest){
	global $db;
	if ($stmt = $db->prepare("SELECT * FROM `Judges` WHERE JUDGE=? AND CONTEST=?")) {
		$stmt->bind_param("ii",$judge,$contest);
		$stmt->execute();
		$result = $stmt->fetch();
		$stmt->free_result();
		$stmt->close();
	} else die('Error while preparing SQL');
	return $result;
}

function getContestsOfJudge($id){
	global $db;
	if ($stmt = $db->prepare("SELECT CONTEST FROM `Judges` WHERE JUDGE=? ORDER BY CONTEST DESC")) {
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
		$stmt->free_result();
		$stmt->close();
	} else die('Error while preparing SQL');
	return $result;
}

function getJudgesOfContest($id){
	global $db;
	if ($stmt = $db->prepare("SELECT JUDGE FROM `Judges` WHERE CONTEST=?")) {
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
		$stmt->free_result();
		$stmt->close();
	} else die('Error while preparing SQL');
	return $result;
}

/* Contest */
function createContest($name,$begin,$finish){
	global $db;
	if ($stmt = $db->prepare("INSERT INTO `Contests` (`NAME`,`BEGIN`,`FINISH`) VALUES (?,?,?);")) {
		$stmt->bind_param("sss",$name,$begin,$finish);
		$stmt->execute();
		$stmt->close();
	} else die('Error while preparing SQL');
}

function updateContest($name,$begin,$finish,$id){
	global $db;
	if ($stmt = $db->prepare("UPDATE `Contests` SET `NAME`=?,`BEGIN`=?,`FINISH`=? WHERE `ID`=?")) {
		$stmt->bind_param("sssi",$name,$begin,$finish,$id);
		$stmt->execute();
		$stmt->close();
	} else die('Error while preparing SQL');
}

function getContest($id){
	global $db;
	if ($stmt = $db->prepare("SELECT * FROM `Contests` WHERE ID=?")) {
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result = $stmt->get_result()->fetch_assoc();
		$stmt->free_result();
		$stmt->close();
	} else die('Error while preparing SQL');
	return $result;
}



/* Question */
function createQuestion($title,$content,$contest,$judge){
	global $db;
	if ($stmt = $db->prepare("INSERT INTO `Questions` (`TITLE`,`CONTENT`,`CONTEST`,`JUDGE`) VALUES (?,?,?,?);")) {
		$stmt->bind_param("ssii",$title,$content,$contest,$judge);
		$stmt->execute();
		$stmt->close();
	} else die('Error while preparing SQL');
}

function updateQuestion($title,$content,$judge,$id){
	global $db;
	if ($stmt = $db->prepare("UPDATE `Questions` SET `TITLE`=?,`CONTENT`=?,`JUDGE`=? WHERE `ID`=?")) {
		$stmt->bind_param("ssii",$title,$content,$judge,$id);
		$stmt->execute();
		$stmt->close();
	} else die('Error while preparing SQL');
}
function getQuestion($id){
	global $db;
	if ($stmt = $db->prepare("SELECT * FROM `Questions` WHERE ID=?")) {
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result = $stmt->get_result()->fetch_assoc();
		$stmt->free_result();
		$stmt->close();
	} else die('Error while preparing SQL');
	return $result;
}

function getQuestionsOfContest($id){
	global $db;
	$result = array();
	if ($stmt = $db->prepare("SELECT ID,TITLE FROM `Questions` WHERE CONTEST=?")) {
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$handle = $stmt->get_result();
		while( ($row=$handle->fetch_assoc()) != NULL ) $result[$row['ID']] = $row['TITLE'];
		$handle->free();
		$stmt->close();
	} else die('Error while preparing SQL');
	return $result;
}

function getQuestionsOfJudgeInContest($judge,$contest){
	global $db;
	$result = array();
	if ($stmt = $db->prepare("SELECT * FROM `Questions` WHERE JUDGE=? AND CONTEST=?")) {
		$stmt->bind_param("ii",$judge,$contest);
		$stmt->execute();
		$handle = $stmt->get_result();
		while( ($row=$handle->fetch_assoc()) != NULL ) $result[$row['ID']] = $row['TITLE'];
		$handle->free();
		$stmt->close();
	} else die('Error while preparing SQL');
	return $result;
}



/* Submission */
function createSubmission($question,$participant,$content){
	global $db;
	if ($stmt = $db->prepare("INSERT INTO `Submissions` (`QUESTION`,`PARTICIPANT`,`CONTENT`) VALUES (?,?,?);")) {
		$stmt->bind_param("iis",$question,$participant,$content);
		$stmt->execute();
		$stmt->close();
	} else die('Error while preparing SQL');
}

function getSubmission($id){
	global $db;
	if ($stmt = $db->prepare("SELECT * FROM `Submissions` WHERE ID=?")) {
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result = $stmt->get_result()->fetch_assoc();
		$stmt->free_result();
		$stmt->close();
	} else die('Error while preparing SQL');
	return $result;
}

function getSubmissionsOfQuestions($questions){
	global $db;
	if ($handle = $db->query("SELECT * FROM `Submissions` WHERE QUESTION IN (".implode(',',$questions).") ORDER BY ID DESC")) {
		$result = $handle->fetch_all(MYSQLI_ASSOC);
		$handle->free();
	} else die('Error while query SQL');
	return $result;
}

function getSubmissionsOfParticipant($id){
	global $db;
	if ($stmt = $db->prepare("SELECT * FROM `Submissions` WHERE PARTICIPANT=? ORDER BY ID DESC")) {
		$stmt->bind_param("i",$_SESSION['ID']);
		$stmt->execute();
		$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
		$stmt->free_result();
		$stmt->close();
	} else die('Error while preparing SQL');
	return $result;
}

function getBestSubmissionScoreOfParticipant($question,$participant){
	global $db;
	if ($stmt = $db->prepare("SELECT SCORE FROM `Submissions` WHERE QUESTION=? AND PARTICIPANT=? ORDER BY SCORE DESC LIMIT 1")) {
		$stmt->bind_param("ii",$question,$participant);
		$stmt->execute();
		$result = $stmt->get_result()->fetch_assoc()['SCORE'];
		$stmt->free_result();
		$stmt->close();
	} else die('Error while preparing SQL');
	return $result;
}

function gradeSubmission($id,$score,$comment){
	global $db;
	if ($stmt = $db->prepare("UPDATE `Submissions` SET `SCORE`=?,`COMMENT`=? WHERE `ID`=?")) {
		$stmt->bind_param("isi",$score,$comment,$id);
		$stmt->execute();
		$stmt->close();
	} else die('Error while preparing SQL');
}



/* UI */
function show_header($title,$menu_select=NULL){
	global $MENUS,$CONTEST_INFO;
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-133223223-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'UA-133223223-1');
		</script>

		<title><?= htmlentities($title) ?> - IQAC</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Content-Language" content="en-US">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="/assets/pure-min.css">
		<link rel="stylesheet" href="/assets/grids-responsive-min.css">
		<link rel="stylesheet" href="/assets/style.css">
		<link rel="stylesheet" href="/assets/pell.min.css">
	</head>
	<body>
		<div class="menu-wrapper pure-menu pure-menu-horizontal pure-menu-scrollable">
			<a href="/" class="pure-menu-heading pure-menu-link"><?= isset($CONTEST_INFO) ? $CONTEST_INFO['NAME'] : 'Instant Q&A Contest' ?></a>
			<ul class="pure-menu-list">
				<? if(isset($_SESSION['MENU'])): ?>
					<? foreach($MENUS[$_SESSION['MENU']] as $text => $url): ?>
						<li class="pure-menu-item <?= ($menu_select == $text ? 'pure pure-menu-selected' : '' ) ?>">
							<a href="<?= $url ?>" class="pure-menu-link"><?= $text ?></a>
						</li>
					<? endforeach; ?>
				<? endif; ?>
			</ul>
		</div>
		<?
}

function show_footer(){
	?>
	<footer> Code, Wiki and Discussion of IQAC is available at <a href="https://sourceforge.net/p/iqac/"><img alt="Download IQAC - Instant Question & Answer Contest" src="https://sourceforge.net/sflogo.php?type=13&group_id=3049754" ></a> </footer>
	<script src="/assets/ui.js"></script>
	</body>
	</html>
	<?
}
?>
