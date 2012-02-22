<?php /* Smarty version 2.6.22, created on 2012-02-20 17:06:24
         compiled from portfolio.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<div id="content">

    <div id="home-sections" class="page_portfolio">
        <h2 class="sprite-title latest-work-title">Recent Projects we have done!</h2>


        <ul class="gallery clearfix">
            <li><p class="icon branding">

                    <a href="images/inikoo_crm.png" rel="prettyPhoto" title=""><img alt="Inikoo CRM" src="images/inikoo_crm.png" class="image" style="opacity: 1;  "/></a>
                                </p><br />
                <h4><a href="images/inikoo_crm.png" rel="prettyPhoto" title="">Inikoo CRM</a></h4>
                <p>Inikoo CRM is a customer management system in new era. Its performance is unbelievable. Our CRM system is up and running and we have already got happy clients using it. The stability of the system is proven since one of our clients is maintaining over 60000 contacts and more than 10 users can have simultaneous access. Inikoo is capable of managing customer contacts, order processing, product management, marketing functionalities, location based processing, inventory management, and supplier and staff management. <strong><a href="inikoo_crm.php">Read more..</a></strong></p></li>
            <li style="height:380px;" ><p class="icon portfolio">

                <a href="images/inikoo_email_marketing.png" rel="prettyPhoto" title=""><img alt="Email Marketing" src="images/inikoo_email_marketing.png" class="image" style="opacity: 1;  "/></a>
                </p><br />
                <h4><a href="images/inikoo_email_marketing.png" rel="prettyPhoto" title="">Email Marketing</a></h4>
                <p>This comes as a separate module of the core CRM system and provide cool features. You can create marketing emails with your favourite template and save as many as you want for later use. You can organise huge email lists and easy to maintain customizable lists according to your customer interests. Now you can send your weekly/ monthly newsletter in one click. Not only that but also you can schedule and automate email sending tasks.</p></li>
<li style="height:250px;" ><p class="icon portfolio">

                 <a href="images/inikoo_cms.png" rel="prettyPhoto" title=""><img alt="Content Management System (CMS)" src="images/inikoo_cms.png" class="image" style="opacity: 1;  "/></a>
                </p><br />
                <h4><a href="images/inikoo_cms.png" rel="prettyPhoto" title="">Content Management System (CMS)</a></h4>
                <p>Inikoo CMS is easy to use. Now you can create your business web pages using Inikoo CMS easily. You can directly import data from the CRM and display it on your product webpages. Creating your web page is few clicks away with Inikoo CMS. In addition you can keep eye on detailed statistics of your web pages such as page hits, number of visitors, etc.  </p></li>


            

<li style="display:none;"><p class="icon services" >
                <img alt="asterisk" src="images/asterisk.jpg" class="image" style="opacity: 1; "/>
                </p><br />
                
                <h4>Stock Control System</h4>
                <p>This system can be used to automate most of the tasks in a warehouse where efficiency and accuracy is important as the scope of the business gets bigger. Out system can handles stock levels and increase/ decrease the quantities accordingly, ensure there are enough quantities for sales, generate alert whenever reordering is required and automatically reorder stocks. Apart from those main features there are so many other features which will ease your day to day tasks.</p></li>
            <li ><p class="icon services">
                <a href="images/inikoo_reporting.png" rel="prettyPhoto" title=""><img alt="Report Generating Tool" src="images/inikoo_reporting.png" class="image" style="opacity: 1;  "/></a>
                </p><br />
                
                <h4><a href="images/inikoo_reporting.png" rel="prettyPhoto" title="">Report Generating Tool</a></h4>
                <p>Reports are crucial and vital in decision making. It is an easy way of analysing statistics you have collected over time. Our report generating tool is capable of analysing the data in an efficient way and output the result quickly. Reports can be generated with charts with modern graphics. Our clients may have sensitive data and required to be kept confidential. We provide option to generate reports for management and other employees based on types of data.</p></li>

            <li style="display:none;"><p class="icon services">
                <a href="images/asterisk.png" rel="prettyPhoto" title=""><img alt="IP Telephony for small office" src="images/asterisk.jpg" class="image" style="opacity: 1; "/></a>
                </p><br />
                
                <h4>IP Telephony for small office</h4>
                <p>We have recently developed an IP telephony system using Asterisk Free PBX server which can be easily installed in any office environment on top of an existing network infrastructure. This system provides you with most of the services such as voice mail, call conferencing, call forwarding, call waiting and call holding. We're currently working on this project in view of improving it in a way to integrate the telephony system with the CRM system.</p></li>
        </ul>



<?php echo '
<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
				$("area[rel^=\'prettyPhoto\']").prettyPhoto();
				
				$(".gallery:first a[rel^=\'prettyPhoto\']").prettyPhoto({animation_speed:\'normal\',theme:\'light_square\',slideshow:3000, autoplay_slideshow: false});
				$(".gallery:gt(0) a[rel^=\'prettyPhoto\']").prettyPhoto({animation_speed:\'fast\',slideshow:10000, hideflash: true});
		
				$("#custom_content a[rel^=\'prettyPhoto\']:first").prettyPhoto({
					custom_markup: \'<div id="map_canvas" style="width:260px; height:265px"></div>\',
					changepicturecallback: function(){ initialize(); }
				});

				$("#custom_content a[rel^=\'prettyPhoto\']:last").prettyPhoto({
					custom_markup: \'<div id="bsap_1259344" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div><div id="bsap_1237859" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6" style="height:260px"></div><div id="bsap_1251710" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div>\',
					changepicturecallback: function(){ _bsap.exec(); }
				});
			});
			</script>
'; ?>


    </div>
    

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>