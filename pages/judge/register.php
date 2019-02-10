<? show_header('Instant Q&A Contest'); ?>
<div style="text-align: center;">
	<form class="pure-form pure-form-aligned" method="POST">
		<fieldset>
			<div class="pure-control-group">
				<label for="name">Username</label>
				<input id="name" name="name" type="text" placeholder="Taiwan NO.1" required>
			</div>
			<div class="pure-control-group">
				<label for="password">Password</label>
				<input id="password" name="password" type="password" placeholder="This is a password" required>
			</div>
			<div class="pure-control-group">
			    <label for="action-login" class="pure-radio">
			    	<input id="class-login" type="radio" name="action" value="login" checked>
			   		Login
    			</label>
    			<label for="action-signup" class="pure-radio">
    				<input id="action-signup" type="radio" name="action" value="signup">
    				Sign Up
    			</label>
    		</div>

			<div class="pure-controls">
				<button type="submit" class="pure-button pure-button-primary">Go!</button>
			</div>
		</fieldset>
	</form>
</div>
<? show_footer(); ?>