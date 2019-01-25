<?
if(!isset($CONTEST_INFO)) die('No contest selected');

// Submit question
if(isset($_POST['title'],$_POST['content'])){
	if(isset($_GET['id'])) updateQuestion($_POST['title'],$_POST['content'],$_POST['judge'],$_GET['id']);
	else createQuestion($_POST['title'],$_POST['content'],$CONTEST_INFO['ID'],$_POST['judge']);
}

// Get questions
$questions = getQuestionsOfContest($CONTEST_INFO['ID']);

// Get selected questions
if(isset($_GET['id'])) $selected_question = getQuestion($_GET['id']);

if(isset($_GET['id'])) show_header($questions[$_GET['id']] , 'Questions');
else show_header('Questions' , 'Questions');
?>
<div class="pure-g panel">
	<div class="pure-u-1 pure-u-md-1-6">
		<div class="pure-menu pure-menu-scrollable">
			<span class="pure-menu-heading">Question List</span>

			<ul class="pure-menu-list">
				<li class="pure-menu-item"><a href="?new" class="pure-menu-link">+ Add New...</a></li>
				<? foreach( $questions as $id => $title ): ?>
					<? if($_GET['id'] == $id): ?>
						<li class="pure-menu-item pure-menu-selected">
					<? else: ?>
						<li class="pure-menu-item">
					<? endif; ?>
					<a href="?id=<?= $id ?>" class="pure-menu-link"><?= $title ?></a>
					</li>
				<? endforeach; ?>
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
						<? foreach( getJudgesOfContest($CONTEST_INFO['ID']) as $row ): ?>
							<? if( $row['JUDGE'] == $selected_question['JUDGE']): ?> 
								<option value="<?= $row['JUDGE'] ?>" selected><?= getAccount($row['JUDGE'])['NAME'] ?></option>
							<? else: ?>
								<option value="<?= $row['JUDGE'] ?>"><?= getAccount($row['JUDGE'])['NAME'] ?></option>
							<? endif; ?>
						<? endforeach; ?>
					</select>
					<button type="submit" class="pure-button pure-button-primary">Submit</button>
				</fieldset>
			</form>
		</div>
	</div>
</div>
<? show_footer(); ?>