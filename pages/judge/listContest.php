<? show_header('Welcome, '.$_SESSION['NAME'] , 'Contests'); ?>
<div class="pure-g panel">

	<div class="pure-u-1"><h1>Contests that you're judge</h1></div>
	<div class="pure-u-1 table-responsive">
		<table class="pure-table">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Begin</th>
					<th>Finish</th>
				</tr>
			</thead>

			<tbody>
				<?
// Search and list the contest that user judge
				if ($stmt = $db->prepare("SELECT * FROM `Judges` WHERE JUDGE=?")) {
					$stmt->bind_param("s",$_SESSION['ID']);
					$stmt->execute();
					$result = $stmt->get_result();

					$odd = true;
					while( $row = $result->fetch_assoc() ){
						if($odd) echo '<tr class="pure-table-odd">';
						else echo '<tr>';
						echo '<td>'.$row['CONTEST'].'</td>';

						if ($stmt2 = $db->prepare("SELECT * FROM `Contests` WHERE ID=?")) {
							$stmt2->bind_param("i",$row['CONTEST']);
							$stmt2->execute();
							$CONTEST_INFO_search = $stmt2->get_result();
							if( $detail = $CONTEST_INFO_search->fetch_assoc() ){
								echo '<td><a href="?contest='.$row['CONTEST'].'">'.$detail['NAME'].'</a></td>';
								echo '<td>'.$detail['BEGIN'].'</td>';
								echo '<td>'.$detail['FINISH'].'</td>';
							}
							$CONTEST_INFO_search->free();
							$stmt2->close();
						} else die('Error while preparing SQL');
						
						echo '</tr>';
						$odd = !$odd;
					}
					$result->free();
					$stmt->close();
				} else die('Error while preparing SQL');
				?>
			</tbody>
		</table>
	</div>
</div>
<? show_footer(); ?>