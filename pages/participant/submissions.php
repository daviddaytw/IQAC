<?
show_header('Submissions' , 'Submissions');

// Get questions of judge
$questions = array();
if ($stmt = $db->prepare("SELECT * FROM `Questions` WHERE CONTEST=?")) {
	$stmt->bind_param("s",$_SESSION['CONTEST']);
	$stmt->execute();
	$result = $stmt->get_result();
	while( ($row=$result->fetch_assoc()) != NULL ) $questions[$row['ID']] = $row['TITLE'];
	$result->free();
	$stmt->close();
} else die('Error while preparing SQL');
?>

<div class="panel">
	<table class="pure-table">
		<thead>
			<tr>
				<th>#</th>
				<th>Question</th>
				<th>Answer</th>
				<th>Status</th>
				<th>Comment</th>
			</tr>
		</thead>
		<tbody>
<?
// Get submissions of participant
if ($stmt = $db->prepare("SELECT * FROM `Submissions` WHERE PARTICIPANT=? ORDER BY ID DESC LIMIT 20")) {
	$stmt->bind_param("s",$_SESSION['ID']);
	$stmt->execute();
	$result = $stmt->get_result();
	while( ($row=$result->fetch_assoc()) != NULL ){
		echo '<tr>';
		echo '<td>'.$row['ID'].'</td>';
		echo '<td>'.$questions[$row['QUESTION']].'</td>';
		echo '<td>'.$row['CONTENT'].'</td>';
		echo '<td class="score">'.( is_null($row['SCORE']) ? 'Pending' : $row['SCORE']).'</td>';
		echo '<td>'.( empty($row['COMMENT']) ? '(No comment)' : $row['COMMENT']).'</td>';
		echo '</tr>';
	}
	$result->free();
	$stmt->close();
} else die('Error while preparing SQL');
?>
		</tbody>
	</table>
</div>
<? show_footer(); ?>