<? show_header($CONTEST_INFO['NAME'] , 'ScoreBoard'); ?>
<div class="panel">
<p>This contest start at <?= $CONTEST_INFO['BEGIN'] ?>, finish at <?= $CONTEST_INFO['FINISH'] ?></p>
<?
if( strtotime($CONTEST_INFO['BEGIN']) > time() ) require('./pages/general/count_down.php');
else require('./pages/general/scoreboard.php');
?>
</div>
<? show_footer(); ?>