
{include file='header.tpl'}


  <script src="http://code.jquery.com/jquery-1.4.4.js"></script>
  
  <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  <script src="http://ui.jquery.com/latest/ui/effects.core.js"></script>
<script src="http://ui.jquery.com/latest/ui/effects.explode.js"></script>

<link rel="stylesheet" href="dashboard_js_css/dashboard.css" type="text/css" media="all">


<link rel="stylesheet" href="dashboard_js_css/load-styles.css" type="text/css" media="all">
<link rel="stylesheet" id="thickbox-css" href="dashboard_js_css/thickbox.css" type="text/css" media="all">
<link rel="stylesheet" id="colors-css" href="dashboard_js_css/colors-fresh.css" type="text/css" media="all">
{literal}
<script type="text/javascript">
/* <![CDATA[ */
var quicktagsL10n = {
	quickLinks: "(Quick Links)",
	wordLookup: "Enter a word to look up:",
	dictionaryLookup: "Dictionary lookup",
	lookup: "lookup",
	closeAllOpenTags: "Close all open tags",
	closeTags: "close tags",
	enterURL: "Enter the URL",
	enterImageURL: "Enter the URL of the image",
	enterImageDescription: "Enter a description of the image"
};
try{convertEntities(quicktagsL10n);}catch(e){};
/* ]]> */
</script>
<script type="text/javascript" src="dashboard_js_css/load-scripts.js"></script>

<script type="text/javascript">
//<![CDATA[
(function(){
var c = document.body.className;
c = c.replace(/no-js/, 'js');
document.body.className = c;
})();
//]]>
</script>
{/literal}
<div id="wpwrap">
<div id="wpcontent">


<div id="wpbody">



<div style="overflow: hidden;" id="wpbody-content">
<div id="screen-meta">


	<div id="contextual-help-wrap" class="hidden" style="display:none;">
		<div class="metabox-prefs"><p>Welcome to KAKTUS Dashboard! </p>
		<p>The top menu 
provides links to the administration screens in your Administrative Area. You can expand or collapse navigation sections by clicking 
on the arrow that appears on the right side of each navigation tab. You can also minimize the navigation menu by clicking again on the down arrow.</p>

		<p>The modules on your Dashboard screen are:</p><p><strong>TOP PRODUCTS</strong> - Displays a summary of the content of the top enlisted product list.</p>
		<p><strong>WORST PRODUCT</strong> - Currently showing top product list by default. </p>
		<p><strong>TOP CUSTOMER</strong> - Shows top customer's name.</p><p>
	</div>
	</div>

<div id="screen-meta-links">
<div id="contextual-help-link-wrap" class="hide-if-no-js screen-meta-toggle">
<a href="#contextual-help" id="contextual-help-link" class="show-settings">Help</a>
</div>

</div>
</div>

<div class="wrap">
	<div id="icon-index" class="icon32"><br></div>
<h2>Dashboard</h2>

<div id="dashboard-widgets-wrap">

<div id="dashboard-widgets" class="metabox-holder">
	<table border="0" align="center" cellspacing="10">
    	<tr>
        	<td width="350px">
<div class="postbox-container">
<div id="normal-sortables" class="meta-box-sortables ui-sortable" style="width:350px;">


			<div id="dashboard_right_now" class="postbox "  >

<div id="cross1"><span class="cross" title="Click to Delete"><img width="15px" height="15px;" src="art/x.png" /> </span></div>
<div class="edit_click" title="Click to toggle"><span class="editTop"><img width="15px" height="15px;" src="art/edit.JPG" /></span></div>

<h3 class="hndle"><span>NO CONTACTS AND CUSTOMERS</span></h3>

<div id="div1" style="display:none; background-color:#999999;">
	<center>
     {foreach from=$splinters key=key item=splinter}

               {if $splinter.index == 4}
             
                    <div class="pane" style="width:350px;"  id="pane_{$key}" {if $display_block!=$key}style="display:none"{/if}>
                    {include file=$splinter.tpl index=$splinter.index}
                    </div>
               {/if}
           
        { /foreach }
    
    </center>
 
  </div>
{literal}
  <script>
    $(".editTop").click(function () {
      $('#div1').slideToggle("slow");
    });
	function slideCrm()
	{
      $('#div1').slideToggle("slow");
		}
</script>
<script>
  $(document).ready(function() {
    $('#inner1').click(function () {
      $('#div1').effect("explode");
    });
  });
  </script>
  <script>
  $('#cross1').click(function () {
  $('#dashboard_right_now').fadeOut("slow");
  });
  </script>
{/literal}
</div>


<br>

			<div id="dashboard_recent_comments" class="postbox ">
<div class="cross" title="Click to Delete"><img onClick="fadeout()" width="15px" height="15px;" src="art/x.png" /> </div>

<div class="edit_click" title="Click to toggle"><span class="editTop2"><img width="15px" height="15px;" src="art/edit.JPG" /></span></div>


<h3 class="hndle">TOP CUSTOMERS</h3>

<div id="divR" style="display:none; background-color:#999999;">
<center>
  {foreach from=$splinters key=key item=splinter}

               {if $splinter.index == 2}
             
                    <div class="pane" style="width:350px;"  id="pane_{$key}" {if $display_block!=$key}style="display:none"{/if}>
                    {include file=$splinter.tpl index=$splinter.index}
                    </div>
               {/if}
           
        { /foreach }
</center>
<div></div>
</div>
{literal}
<script>
$(".editTop2").click(function () {
$('#divR').slideToggle("slow");
});
function slideRcm()
{
$('#divR').slideToggle("slow");
}
</script>
<script>
function slideCrm()
{
$('#div1').slideToggle("slow");
}
function fadeout()
{
$("#dashboard_recent_comments").fadeOut();
}
</script>
{/literal}
</div>

<br>
<!--==================================================DIV 3=============================================================-->
		
        	<div id="dashboard_incoming_links" class="postbox ">
<div id="cross3" class="cross"><img width="15px" height="15px;" src="art/x.png" /></div>
<div id="edit3" class="edit"><strong><img width="15px" height="15px;" src="art/edit.JPG" /></strong></div>



<div class="handlediv" title="Click to toggle"></div>
<h3 class="hndle">WORST PRODUCTS</h3>
<div id="div3" style="display:none; background-color:#999999;">
	<center>
    {foreach from=$splinters key=key item=splinter}

               {if $splinter.index == 6}
             
                    <div class="pane" style="width:350px;"  id="pane_{$key}" {if $display_block!=$key}style="display:none"{/if}>
                    {include file=$splinter.tpl index=$splinter.index}
                    </div>
               {/if}
           
        { /foreach }
    </center>
  
  
  </div>


{literal}
<script>
    $("#edit3").click(function () {
      $('#div3').slideToggle("slow");
    });
	function slide3()
	{
      $('#div3').slideToggle("slow");
		}
</script>
<script>
  $('#cross3').click(function () {
  $('#dashboard_incoming_links').fadeOut("slow");
  });
  </script>
  <script>
    $("#save3").click(function () {
      $('#div3').slideToggle("slow");
    });
	function slide3()
	{
      $('#div3').slideToggle("slow");
		}
</script>
<script>
    $("#exit3").click(function () {
      $('#div3').slideToggle("slow");
    });
	function slide3()
	{
      $('#div3').slideToggle("slow");
		}
</script>
<script>
  $(document).ready(function() {
    $('#inner3').click(function () {
      $('#dashboard_incoming_links').effect("explode");
    });
  });
  </script>
{/literal}
</div>

<!--=========================================================END DIV 3==================================================================-->



<!--============================================================DIV 4==========================================================================-->
</div></div></td>
    		<td width="270px"><div class="postbox-container">
<div id="side-sortables" class="meta-box-sortables ui-sortable" style="width:350px;">
<!--====================================== DIV 5=======================================-->
			<div id="dashboard_quick_press" class="postbox ">
<div class="cross" id="cross5"><img width="15px" height="15px;" src="art/x.png" /></div>
<div class="edit" id="edit5"><strong><img width="15px" height="15px;" src="art/edit.JPG" /></strong></div>



<div class="handlediv" title="Click to toggle"><h3 class="hndle">TOP PRODUCTS</h3></div>


<div id="div4" style="display:none; background-color:#999999;">
	<center>
    {foreach from=$splinters key=key item=splinter}

               {if $splinter.index == 3}
             
                    <div class="pane" style="width:350px;"  id="pane_{$key}" {if $display_block!=$key}style="display:none"{/if}>
                    {include file=$splinter.tpl index=$splinter.index}
                    </div>
               {/if}
           
        { /foreach }
    </center>
  
  
  </div>


{literal}
<script>
    $("#edit5").click(function () {
								
      $('#div4').slideToggle("slow");
    });
	function slide4()
	{
      $('#div4').slideToggle("slow");
		}
</script>
<script>
  $('#cross5').click(function () {
  $('#dashboard_quick_press').fadeOut("slow");
  });
  </script>
  <script>
    $("#save5").click(function () {
      $('#div4').slideToggle("slow");
    });
	function slide3()
	{
      $('#div4').slideToggle("slow");
		}
</script>
<script>
    $("#exit5").click(function () {
      $('#div5').slideToggle("slow");
    });
	function slide3()
	{
      $('#div4').slideToggle("slow");
		}
</script>
<script>
  $(document).ready(function() {
    $('#inner5').click(function () {
      $('#dashboard_quick_press').effect("explode");
    });
  });
  </script>
{/literal}
</div>

<br>
<!--================================END DIV 5=====================================-->

<!--============================ DIV 6=============================-->
			<div id="dashboard_recent_drafts" class="postbox ">
<div class="cross" id="cross6"><img width="15px" height="15px;" src="art/x.png" /></div>
<div class="edit" id="edit6"><strong><img width="15px" height="15px;" src="art/edit.JPG" /></strong></div>

<div class="handlediv" title="Click to toggle"><h3 class="hndle">OUTSTANDING ORDER</h3></div>


	<div id="div7" style="display:none;">
		<center>
         
			{foreach from=$splinters key=key item=splinter}

               	{if $splinter.index == 5}
             
                    <div class="pane" style="width:350px;"  id="pane_{$key}" {if $display_block!=$key}style="display:none"{/if}>
                    {include file=$splinter.tpl index=$splinter.index}
                    </div>
               	{/if}
           
        	{/foreach }
    
    </center>
   
  
  </div>




{literal}
<script>
    $("#edit6").click(function () {
      $('#div7').slideToggle("slow");
    });
	function slide6()
	{
      $('#div7').slideToggle("slow");
		}
</script>
<script>
  $('#cross6').click(function () {
  $('#dashboard_recent_drafts').fadeOut("slow");
  });
  </script>
  <script>
    $("#save6").click(function () {
      $('#div7').slideToggle("slow");
    });
	function slide6()
	{
      $('#div7').slideToggle("slow");
		}
</script>
<script>
    $("#exit6").click(function () {
      $('#div7').slideToggle("slow");
    });
	function slide6()
	{
      $('#div6').slideToggle("slow");
		}
</script>
<script>
  $(document).ready(function() {
    $('#inner6').click(function () {
      $('#dashboard_recent_drafts').effect("explode");
    });
  });
  </script>
  {/literal}
  </div>
<!--===================END DIV 6=======================================-->



		</div>	
	</div></td>
<!--=================================END DIV 4===============================================-->
		</tr>
    </table>
</div>




<div class="clear"></div>
</div><!-- dashboard-widgets-wrap -->
  

</div><!-- wrap -->


<div class="clear"></div></div><!-- wpbody-content -->
<div class="clear"></div></div><!-- wpbody -->
<div class="clear"></div></div><!-- wpcontent -->
</div><!-- wpwrap -->




<div align="center">
	    <div id="ajax_response">	

       {$pagination_top} 
        {section name=tplVar loop=$tplVar}
	
             <div align="center">         
                .    <div class="comment_holder">
                        <div id="photo"><img src="art/user.jpg">  <br>{ $tplVar[tplVar].Name }  </div>
                            
 <div id="comment_text"><div id="date_posted">{$tplVar[tplVar].Date Added}</div>{$tplVar[tplVar].Comment}</div>
                    </div>
                </div>

        {/section}
      {$pagination_bottom} 

	</div>
    </div>

	

<div align="center">
	<table border="0" cellpadding="4" cellspacing="0" class="comment_table" width="40%">
	  <tr>
	    <td>Name :</td>
		<td><input type="text" name="name" id="name" size="30"></td>
	  </tr>
	  <tr>
	    <td>Email :</td>
		<td><input type="text" name="email" id="email" size="30"></td>
	  </tr>
	  <tr>
	    <td valign="top">Comment :</td>
		<td><textarea name="comment" id="comment" rows="5" cols="30"></textarea></td>
	  </tr>
	  <tr>
		<td></td>
	    <td align="left"><input type="button" value="Submit" id="submit">&nbsp;<img src="art/loading.gif" id="loading"></td>
	  </tr>
	</table>
</div>






{include file='footer.tpl'}

{literal}
<script type="text/javascript">
/* <![CDATA[ */
var commonL10n = {
	warnDelete: "You are about to permanently delete the selected items.\n  \'Cancel\' to stop, \'OK\' to delete."
};
try{convertEntities(commonL10n);}catch(e){};
var wpAjax = {
	noPerm: "You do not have permission to do that.",
	broken: "An unidentified error has occurred."
};
try{convertEntities(wpAjax);}catch(e){};
var adminCommentsL10n = {
	hotkeys_highlight_first: "",
	hotkeys_highlight_last: ""
};
var thickboxL10n = {
	next: "Next &gt;",
	prev: "&lt; Prev",
	image: "Image",
	of: "of",
	close: "Close",
	noiframes: "This feature requires inline frames. You have iframes disabled or your browser does not support them."
}; 
try{convertEntities(thickboxL10n);}catch(e){};
var plugininstallL10n = {
	plugin_information: "Plugin Information:",
	ays: "Are you sure you want to install this plugin?"
};
try{convertEntities(plugininstallL10n);}catch(e){};
/* ]]> */
</script>
<script type="text/javascript" src="dashboard_js_css/load-scripts_002.js"></script>

<script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>
{/literal}
