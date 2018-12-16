<?
echo '<h2>Contest has not start yet!!</h2>';
//TODO: echo '<h3 id="count_down">'.$contest_data['BEGIN'].'</h3>';
echo '<div class="pure-g" style="text-align: center;">';
foreach($participants as $name){
	echo '<div class="pure-u-1-3 pure-u-md-1-6"><p>'.htmlentities($name).'</p></div>';
}
echo '</div>';
?>