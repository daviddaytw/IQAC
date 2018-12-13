<?php show_header('Instant Q&A Contest'); ?>
<div style="text-align: center;">
	<form class="pure-form pure-form-aligned" method="POST">
		<fieldset>
			<div class="pure-control-group">
				<label for="contest">Contest ID</label>
				<input id="contest" name="contest" type="text" placeholder="8787" required>
			</div>

			<div class="pure-control-group">
				<label for="name">Nickname</label>
				<input id="name" name="name" type="text" placeholder="Taiwan NO.1" required>
			</div>

			<div class="pure-controls">
				<button type="submit" class="pure-button pure-button-primary">Go!</button>
			</div>
		</fieldset>
	</form>
</div>
<?php show_footer(); ?>