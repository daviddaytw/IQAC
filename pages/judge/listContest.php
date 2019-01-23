<?
show_header('Welcome, '.$_SESSION['NAME'] , 'Contests');
$contest = getContestsOfJudge($_SESSION['ID']);
?>
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
				<? foreach( $contest as $row ): ?>
				<tr>
					<td><?= $row['CONTEST'] ?></td>
					<? $detail = getContest($row['CONTEST']) ?>
						<td>
							<a href="?contest=<?= $row['CONTEST'] ?>"><?= $detail['NAME']?></a>
						</td>
						<td><?= $detail['BEGIN'] ?></td>
						<td><?= $detail['FINISH'] ?></td>
						
				</tr>
				<? endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
<? show_footer(); ?>