<?php /* Smarty version 2.6.22, created on 2012-02-23 11:51:49
         compiled from contact.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'contact.tpl', 28, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


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


 <p><strong><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Office<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>: Unit 15, 1st Floor<br/>Parkwood Business Park<br/>75 Parkwood Road<br/>Sheffield,UK<br/>S3 8AL</strong></p>
 <p><strong><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Company Number<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>: 7618223</strong></p>
 <p><strong><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Vat Number<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>: 114 6660 28</strong></p>
 <p><strong><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Telephone<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:(+44) 114 299 8401</strong></p>
<p><strong><a href="mailto:sales@inikoo.com"><span><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Email<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>: sales@inikoo.com</span></a></strong></p>


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
  		



<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>