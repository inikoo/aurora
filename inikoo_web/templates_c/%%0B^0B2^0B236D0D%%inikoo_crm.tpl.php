<?php /* Smarty version 2.6.22, created on 2012-02-20 17:07:48
         compiled from inikoo_crm.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<div id="content">

    <div id="home-sections" class="page_portfolio">
        <h2 class="sprite-title latest-work-title">Inikoo CRM</h2>


        <ul class="gallery clearfix">
            <li><p class="icon branding">

                    <a href="images/inikoo_crm.png" rel="prettyPhoto" title=""><img alt="Inikoo CRM" src="images/inikoo_crm.png" class="image" style="opacity: 1;  "/></a>
                                </p><br />
                <h4><a href="images/inikoo_crm.png" rel="prettyPhoto" title="">Inikoo CRM</a></h4>
                <p>Inikoo CRM is a customer management system in new era. Its performance is unbelievable. Our CRM system is up and running and we have already got happy clients using it. The stability of the system is proven since one of our clients is maintaining over 60000 contacts and more than 10 users can have simultaneous access. Inikoo is capable of managing customer contacts, order processing, product management, marketing functionalities, location based processing, inventory management, and supplier and staff management. <strong><a href="inikoo_crm.php">Read more..</a></strong></p></li>
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