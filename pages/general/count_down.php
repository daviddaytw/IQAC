<h1>Contest has not start yet!!</h1>

<? if($_SESSION['ROLE'] == 'judge'): ?>
<h2>
	<a href="https://<?= $_SERVER['REQUEST_URI'] ?>/auth?Contest=<?= $CONTEST_INFO['ID'] ?>">
		Contest ID: <?= $CONTEST_INFO['ID'] ?>
	</a>
</h2>
<? endif; ?>

<h2 id="count_down"><?= strtotime($CONTEST_INFO['BEGIN'])-time() ?></h2>
<div class="pure-g" style="text-align: center;">';

<? foreach(getPartcipantsOfContest($CONTEST_INFO['ID']) as $name): ?>
	<div class="pure-u-1-3 pure-u-md-1-6">
		<p><?= htmlentities($name) ?></p></div>
	</div>
<? endforeach; ?>

<script>
	var timeleft = document.getElementById("count_down").innerHTML;
	setTimeout(function(){ location = '' }, 20000);
	setInterval( function(){
		document.getElementById("count_down").innerHTML = Math.floor(timeleft/3600)+" : "+Math.floor(timeleft/60%60)+" : "+Math.floor(timeleft%60);
		if(timeleft == 0) window.location = '';
		timeleft--;
	},1000);
</script>
