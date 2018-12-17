<?
if( strtotime($_SESSION['CONTEST']['BEGIN']) > time() ){
	show_header('Questions' , 'Questions');
	echo '<h2>Contest has not start yet!!</h2>';
} else {
	// Get questions
	$questions = array();
	if ($stmt = $db->prepare("SELECT * FROM `Questions` WHERE CONTEST=?")) {
		$stmt->bind_param("i",$_SESSION['CONTEST']['ID']);
		$stmt->execute();
		$result = $stmt->get_result();

		while( ($row = $result->fetch_assoc()) != NULL ){
			// If no specific, give the first one
			if(!isset($_GET['id']) && empty($questions)) $_GET['id'] = $row['ID'];

			$questions[$row['ID']] = $row['TITLE'];
			if($_GET['id'] == $row['ID']) $content = $row['CONTENT'];
		}
		$result->free();
		$stmt->close();
	} else die('Error while preparing SQL');

	// Submit answer
	if(isset($content,$_GET['id'],$_POST['answer'])){
		if ($stmt = $db->prepare("INSERT INTO `Submissions` (`PARTICIPANT`,`QUESTION`,`CONTENT`) VALUES (?,?,?);")) {
			$stmt->bind_param("sis",$_SESSION['ID'],$_GET['id'],$_POST['answer']);
			$stmt->execute();
			$stmt->close();
		} else die('Error while preparing SQL');
	}

	show_header($questions[$_GET['id']] , 'Questions');
	?>
	<div class="pure-g panel">
		<div class="pure-u-1 pure-u-md-1-6">
			<div class="pure-menu pure-menu-scrollable">
				<span class="pure-menu-heading">Question List</span>

				<ul class="pure-menu-list">
	<?
	foreach( $questions as $id => $title ){
		if($_GET['id'] == $id) echo '<li class="pure-menu-item pure-menu-selected">';
		else echo '<li class="pure-menu-item">';
		echo '<a href="?id='.$id.'" class="pure-menu-link">'.$title;
		echo '</a></li>';
	}
	?>
				</ul>
			</div>
		</div>

		<div class="pure-u-1 pure-u-md-5-6">
			<div class="panel">
				<h1><?= htmlentities($questions[$_GET['id']]) ?></h1>
				<div><?= $content ?></div>
				<form class="pure-form pure-form-stacked" method="POST">
					<fieldset>
						<legend>Your Answer</legend>
						<textarea name="answer"></textarea>
						<button type="submit" class="pure-button pure-button-primary">Submit</button>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
<?
}
show_footer();
?>