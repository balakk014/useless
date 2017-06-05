<div id="client-ui-status" class="clearfix">
	<h2 class="phone_call" id="client-ui-message">Initializing...</h2>
	<input name="phone_call" type="hidden" id="phone_call">
        <script>
	var phone_call;
	var watch_var = '0';
	var interval_1 = setInterval(function()
	{
		check_call_status();
		watch_var='1';

	},1000);
	var interval_2 = setInterval(function()
	{
		if(watch_var == '0')
		{
			starttimer();
		}
		else
		{
		watch_var = '0';
		}
	},1000);
	function check_call_status()
	{
		phone_call = $(".phone_call").html();
		$("#phone_call").val(phone_call);
	}
	function starttimer()
	{
	  interval_1=setInterval(check_call_status, 1000);
	}

	 </script>

	<h3 class="client-ui-timer">0:00</h3>
</div><!-- #client-ui-status -->
<div id="client-ui-pad" class="clearfix">
	<div class="client-ui-button-row">
		<div class="client-ui-button">
			<div class="client-ui-button-number">1</div>
			<div class="client-ui-button-letters"></div>
		</div>
		<div class="client-ui-button">
			<div class="client-ui-button-number">2</div>
			<div class="client-ui-button-letters">abc</div>
		</div>
		<div class="client-ui-button">
			<div class="client-ui-button-number">3</div>
			<div class="client-ui-button-letters">def</div>
		</div>
	</div>
	<div class="client-ui-button-row">
		<div class="client-ui-button">
			<div class="client-ui-button-number">4</div>
			<div class="client-ui-button-letters">ghi</div>
		</div>
		<div class="client-ui-button">
			<div class="client-ui-button-number">5</div>
			<div class="client-ui-button-letters">jkl</div>
		</div>
		<div class="client-ui-button">
			<div class="client-ui-button-number">6</div>
			<div class="client-ui-button-letters">mno</div>
		</div>
	</div>
	<div class="client-ui-button-row">
		<div class="client-ui-button">
			<div class="client-ui-button-number">7</div>
			<div class="client-ui-button-letters">pqrs</div>
		</div>
		<div class="client-ui-button">
			<div class="client-ui-button-number">8</div>
			<div class="client-ui-button-letters">tuv</div>
		</div>
		<div class="client-ui-button">
			<div class="client-ui-button-number">9</div>
			<div class="client-ui-button-letters">wxyz</div>
		</div>
	</div>
	<div class="client-ui-button-row">
		<div class="client-ui-button">
			<div class="client-ui-button-number asterisk">*</div>
			<div class="client-ui-button-letters"></div>
		</div>
		<div class="client-ui-button">
			<div class="client-ui-button-number">0</div>
			<div class="client-ui-button-letters"></div>
		</div>
		<div class="client-ui-button">
			<div class="client-ui-button-number">#</div>
			<div class="client-ui-button-letters"></div>
		</div>
	</div>
</div><!-- /client-ui-pad -->
<div id="client-ui-actions">
	<button id="client-ui-mute" class="client-ui-action-button mute">Mute</button>
	<button id="client-ui-answer" class="client-ui-action-button answer">Answer</button>
	<button id="client-ui-hangup" class="client-ui-action-button hangup">Hangup</button>
	<button id="client-ui-close" class="client-ui-action-button close">Close</button>
</div><!-- #client-ui-actions -->
