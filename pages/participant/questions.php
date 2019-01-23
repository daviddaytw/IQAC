<?
if( strtotime($CONTEST_INFO['BEGIN']) > time() ){
	show_header('Questions' , 'Questions');
	echo '<h2>Contest has not start yet!!</h2>';
	show_footer();
	exit;
}
// Get questions
$questions = getQuestionsOfContest($CONTEST_INFO['ID']);
if(isset($_GET['id'])){
	$_GET['id'] = intval($_GET['id']);
	if(in_array($_GET['id'], array_keys($questions))){
		$current_question = getQuestion($_GET['id']);
// Submit answer
		if(isset($_SESSION['ID'],$_POST['answer'])){
			createSubmission($_GET['id'],$_SESSION['ID'],$_POST['answer']);
			header('Location: /submissions');
			exit;
		}
	}
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
<? if(isset($current_question)): ?>
			<h1><?= htmlentities($current_question['TITLE']) ?></h1>
			<div><?= $current_question['CONTENT'] ?></div>
			<form class="pure-form pure-form-stacked" method="POST">
				<fieldset>
					<legend>Your Answer</legend>
					<textarea name="answer"></textarea>
					<button type="submit" class="pure-button pure-button-primary">Submit</button>
				</fieldset>
			</form>
<? endif; ?>
		</div>
	</div>
</div>
<? show_footer(); ?>