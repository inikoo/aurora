{include file="$language/header.tpl"}
<!-- Start Content -->
<div id="content">
	<!-- Start Main Content -->	
	<div id="main-content-full">
		<div id="contact-info" class="two-col">
			<h1>Contact Us Translated</h1>
			<div class="main-ruler"></div>									
			<p>Aenean tincidunt pharetra leo. Curabitur euismod sollicitudin elit. Donec faucibus lacus nec sapien. Aliquam ipsum nisi, scelerisque et, commo. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Cupidatat rollover sunt in anim id est.</p>
			<div class="fl mar-right">
				<h3>Address</h3>
				<p>Unit 15, 1st Floor<br />Parkwood Business Park<br />75 Parkwood Road<br />Sheffield, S3 8AL<br/>United Kingdom</p>
			</div>
			<h3>Contact Info.</h3>
			<p><strong>Email:</strong> mail@inikoo.com<br /><strong>Fax:</strong> 1-888-888-8888</p>
			<div class="clear"></div>
			<img class="img-box-large section" alt="" src="images/map.jpg" />
		</div>		
		<div class="two-col end-col">
			<form id="contact-form" action="php/contact-send.php" method="post">
				<fieldset>
					<p class="hide" id="response"></p>				
					<div class="field-group">
						<label class="required" for="form_name">Name<span>*</span></label>
						<input id="form_name" name="name" class="field" type="text" />
					</div>
					<div class="field-group">
						<label class="required" for="form_email">Email<span>*</span></label>
						<input id="form_email" name="email" class="field" type="text" />
					</div>
					<div class="clear"></div>
					<label class="required" for="form_message">Message<span>*</span></label>						
					<textarea id="form_message" name="message" rows="" cols=""></textarea>
					<input id="form_submit" class="small-primary-btn input-btn" type="submit" name="submit" value="Send Message" />
            		<div class="hide">
               			<label for="spam_check">Do not fill out this field</label>
                		<input id="spam_check" name="spam_check" type="text" value="" />
          			</div>					
				</fieldset>
			</form>		
		</div>
		<div class="ruler"></div>	
	</div>
	<!-- End Main Content -->			
	<div class="clear"></div>
</div>
<!-- End Content -->
{include file="$language/footer.tpl"}