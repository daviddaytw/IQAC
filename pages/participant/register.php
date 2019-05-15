<? show_header('Instant Q&A Contest'); ?>
<div style="text-align: center;">
	<form class="pure-form pure-form-aligned" method="POST">
		<fieldset>
			<div class="pure-control-group">
				<label for="contest">Contest ID</label>
				<input id="contest" name="contest" type="text" value="<?= $_GET['Contest'] ?>"required>
			</div>

			<div class="pure-control-group">
				<label for="name">Nickname</label>
				<input id="name" name="name" type="text" required>
			</div>

            <div class="pure-control-group">
				<label for="password">Password</label>
				<input id="password" name="password" type="password" required>
			</div>

			<div class="pure-controls">
				<button type="submit" class="pure-button pure-button-primary">Go!</button>
			</div>
		</fieldset>
	</form>
</div>
<? show_footer(); ?>
