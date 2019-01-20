<?
if(!isset($CONTEST_INFO)) die('No contest selected');

// Submit question
if(isset($_POST['title'],$_POST['content'])){
	if(isset($_GET['id'])){
		// Update question
		if ($stmt = $db->prepare("UPDATE `Questions` SET `TITLE`=?,`CONTENT`=?,`CONTEST`=?,`JUDGE`=? WHERE `ID`=?")) {
			$stmt->bind_param("ssisi",$_POST['title'],$_POST['content'],$CONTEST_INFO['ID'],$_POST['judge'],$_GET['id']);
			$stmt->execute();
			$stmt->close();
		} else die('Error while preparing SQL');
	} else {
		// New question
		if ($stmt = $db->prepare("INSERT INTO `Questions` (`TITLE`,`CONTENT`,`CONTEST`,`JUDGE`) VALUES (?,?,?,?);")) {
			$stmt->bind_param("ssis",$_POST['title'],$_POST['content'],$CONTEST_INFO['ID'],$_POST['judge']);
			$stmt->execute();
			$stmt->close();
		} else die('Error while preparing SQL');
	}
}

// Get questions
$questions = array();
if ($stmt = $db->prepare("SELECT * FROM `Questions` WHERE CONTEST=?")) {
	$stmt->bind_param("i",$CONTEST_INFO['ID']);
	$stmt->execute();
	$result = $stmt->get_result();

	while( ($row = $result->fetch_assoc()) != NULL ) $questions[$row['ID']] = $row['TITLE'];
	$result->free();
	$stmt->close();
} else die('Error while preparing SQL');

// Get selected questions
if(isset($_GET['id'])){
	if ($stmt = $db->prepare("SELECT * FROM `Questions` WHERE CONTEST=? AND ID=?")) {
		$stmt->bind_param("ii",$CONTEST_INFO['ID'],$_GET['id']);
		$stmt->execute();
		$selected_question = $stmt->get_result()->fetch_assoc();
		$stmt->free_result();
		$stmt->close();
	} else die('Error while preparing SQL');
}

if(isset($_GET['id'])) show_header($questions[$_GET['id']] , 'Questions');
else show_header('Questions' , 'Questions');
?>
<div class="pure-g panel">
	<div class="pure-u-1 pure-u-md-1-6">
		<div class="pure-menu pure-menu-scrollable">
			<span class="pure-menu-heading">Question List</span>

			<ul class="pure-menu-list">
				<li class="pure-menu-item"><a href="?new" class="pure-menu-link">+ Add New...</a></li>
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
			<form class="pure-form pure-form-stacked" method="POST">
				<fieldset>
					<h1><input type="text" name="title" placeholder="Title" value="<?= htmlentities($selected_question['TITLE']) ?>" required></h1>
					<textarea name="content" placeholder="Content" required><?= htmlentities($selected_question['CONTENT']) ?></textarea>
					<label for="judge">Judge</label>
					<select id="judge" name="judge">
						<?
//Get Judges of the contest
						if ($stmt = $db->prepare("SELECT * FROM `Judges` WHERE CONTEST=?")) {
							$stmt->bind_param("i",$CONTEST_INFO['ID']);
							$stmt->execute();
							$result = $stmt->get_result();

							while( $row = $result->fetch_assoc() ){
								if( $row['JUDGE'] == $selected_question['JUDGE']) echo '<option value="'.$row['JUDGE'].'" selected>'.$row['JUDGE'].'</option>';
								else echo '<option value="'.$row['JUDGE'].'">'.$row['JUDGE'].'</option>';
							}
							$result->free();
							$stmt->close();
						} else die('Error while preparing SQL');
						?>
					</select>
					<button type="submit" class="pure-button pure-button-primary">Submit</button>
				</fieldset>
			</form>
		</div>
	</div>
</div>
<? show_footer(); ?>