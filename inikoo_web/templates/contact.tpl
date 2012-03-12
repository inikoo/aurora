{include file="header.tpl"}


<div id="content">

<div>
		
			<div class="page-left-small">

				<div id="top-small">
					<div id="top-inner-small">

						<img style="width:270px; height:225px;" src="images/send_email.png" alt="send_email"/>
					</div>
				</div>
			
			
			
				<div id="bottom-small">
					<div id="bottom-inner-small">
				
						<div class="sidebar-text">
							<h3 class="sprite-title contact-title" style="margin-top:0px">Contact Us!</h3>

<p>Think we suit you best for your project? Contact us, we're happy to help you!</p>


 <p><strong>{t}Office{/t}: Unit 15, 1st Floor<br/>Parkwood Business Park<br/>75 Parkwood Road<br/>Sheffield,UK<br/>S3 8AL</strong></p>
 <p><strong>{t}Company Number{/t}: 7618223</strong></p>
 <p><strong>{t}Vat Number{/t}: 114 6660 28</strong></p>
 <p><strong>{t}Telephone{/t}:(+44) 114 360 9600</strong></p>
<p><strong><a href="mailto:sales@inikoo.com"><span>{t}Email{/t}: sales@inikoo.com</span></a></strong></p>


<h3 class="sprite-title contact-title">Follow Us!</h3>

<a onclick="target='_blank';" href="http://www.facebook.com/home.inikoo"><img src="images/facebook.png" alt="facebook"/></a>
<a onclick="target='_blank';" href="http://www.twitter.com/inikoo_devel"><img src="images/twitter.png" alt="twitter"/></a>
<a style="display:none;" onclick="target='_blank';" href=""><img src="images/linkedin.png" alt="linkedin"/></a>
						</div>
			
					</div>
				</div>

							</div>
			
			<div class="page-right-large">
				<div id="contact-form-wrapper">
					<h1 class="sprite-title quote-title">Drop us a message!</h1>
				
					<form method="post" action="send_mail.php" id="contact-form" class="form">
					    
					    <noscript>
					        <p class="noscript">JavaScript is required to use this form, please make sure your browser supports it.</p>
					    </noscript>

					    <p class="input">
					        <label for="name" class="label">Name</label>
					        <input type="text" name="name" id="name"/>
					    </p>
						<p class="input">
							<label for="company" class="label">Company</label>
							<input type="text" name="company" id="company"/>
						</p>

						<p class="input">
							<label for="email" class="label">Email</label>
							<input type="text" name="email" id="email"/>
						</p>
						<p class="input">
							<label for="phone" class="label">Phone</label>
							<input type="text" name="phone" id="phone"/>
						</p>

						<p class="input">
							<label for="title" class="label">Title</label>
							<input type="text" name="title" id="title"/>
						</p>

						<p class="textarea">
							<label for="details" class="label">Details</label>
							<textarea name="details" id="details" rows="20" cols="100"></textarea>
						</p>
						<p>

						
							<input type="submit" value="Send" name="send" class="xbutton" id="submit_email"/>
						</p>
					</form>
					
				</div>

			</div>
		</div><!-- [END] content -->
  		



{include file="footer.tpl"}