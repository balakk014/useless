<div class="vbx-content-container">
<?php $cookie_data=$_COOKIE;
$cookie_data=(unserialize($cookie_data['openvbx_session']));
//print_r($cookie_data['email']);exit;
?>
	<div id="login">
		<form action="<?php echo site_url('auth/login'); ?>?redirect=<?php echo urlencode($redirect) ?>" method="post" class="vbx-login-form vbx-form">

				<div class="vbx-input-container">
				<label for="iEmail" class="field-label">E-Mail Address
					<input type="text" id="iEmail" name="email"  value="<?php echo isset($cookie_data['email'])?$cookie_data['email']:'';?>" class="medium" />
				</label>

				<label for="iPass" class="field-label">Password
					<input type="password" id="iPass" name="pw" value="<?php isset($_COOKIE['password'])?$_COOKIE['password']:'';?>" class="medium" />
				</label>
				</div>

				<?php if(isset($captcha_url)): ?>
				<input type="hidden" name="captcha_token" value="<?php echo $captcha_token?>" />
				<img alt="captcha me" src="http://www.google.com/accounts/<?php echo $captcha_url ?>" />
				<label for="iCaptcha">Captcha</label>
				<input type="text" id="iCaptcha" name="captcha" />
				<?php endif; ?>

				<input type="hidden" name="login" value="1" />
				<button type="submit" class="submit-button"><span>Log In</span></button>
				<!--<a class="forgot-password" href="../auth/reset">Forgot password?</a>-->
		</form>
	</div><!-- #login .vbx-content-section -->

</div><!-- .vbx-content-container -->
