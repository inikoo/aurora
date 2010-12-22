 {include file='header.tpl'}
  <script src="http://code.jquery.com/jquery-1.4.4.js"></script>
  <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  <script src="http://ui.jquery.com/latest/ui/effects.core.js"></script>
<script src="http://ui.jquery.com/latest/ui/effects.explode.js"></script>

<!--<link rel="stylesheet" href="dashboard_js_css/dashboard.css" type="text/css" media="all">-->

<link rel="stylesheet" href="dashboard_js_css/dashboard.css" type="text/css" media="all">

{literal}
<script language="javascript">
var i=1;

function addlist()
{
//alert("in addlist");
	
	for(;i<=5;)
	{
		//alert(i);
	if(i==0)
	  i++;
	if(document.getElementById(i).style.display = "none")
		{document.getElementById(i).style.display = "block";
		i=i+1;
		//alert(window.i);
		window.j=i-1;
		break;
		}
	
	
	}
	
	
	
}

function sublist()
{
	j=window.j;
	
	//alert("j in sub"+j);
	while(j>=1)
	{
		//alert(j);
		
	if(document.getElementById(j).style.display = "block")
		{
		document.getElementById(j).style.display = "none";
		break;
		}
	
	
	}
	j=j-1;
	window.i=j+1;
}
</script>
{/literal}

{literal}
<script language="javascript">

var k=6;
function addlist4()
{
//alert("in addlist4");
	
	for(;k<=10;)
	{
		//alert(k);
	if(k==4)
	  k++;
	if(document.getElementById(k).style.display = "none")
		{document.getElementById(k).style.display = "block";
		k=k+1;
		//alert(window.i);
		window.l=k-1;
		break;
		}
	
	
	}
	
	
	
}

function sublist4()
{
	
	l=window.l;
	//alert(l);
	//alert("j in sub"+j);
	while(l>=6)
	{
		//alert(j);
		
	if(document.getElementById(l).style.display = "block")
		{
		document.getElementById(l).style.display = "none";
		break;
		}
	
	
	}
	l=l-1;
	window.k=l+1;
}
</script>
{/literal}

{literal}
<script language="javascript">
function customise()
{
		//alert("customise");
		
	if(document.getElementById("crm_chk").checked==true)
	{
		//alert("checked");
		document.getElementById("dashboard_right_now").style.display="none";
		
	}
	if(document.getElementById("crm_chk").checked==false)
	{
		//alert("unchecked");
		document.getElementById('dashboard_right_now').style.display="block";
		
	}
	
	if(document.getElementById("invoice_chk").checked==true)
	{
		//alert("checked");
		document.getElementById("dashboard_recent_comments").style.display="none";
		
	}
	if(document.getElementById("invoice_chk").checked==false)
	{
		//alert("unchecked");
		document.getElementById('dashboard_recent_comments').style.display="block";
		
	}
	
	if(document.getElementById("stock_chk").checked==true)
	{
		//alert("checked");
		document.getElementById("dashboard_incoming_links").style.display="none";
		
	}
	if(document.getElementById("stock_chk").checked==false)
	{
		//alert("unchecked");
		document.getElementById('dashboard_incoming_links').style.display="block";
		
	}
	
	if(document.getElementById("ecom_chk").checked==true)
	{
		//alert("checked");
		document.getElementById("dashboard_plugins").style.display="none";
		
	}
	if(document.getElementById("ecom_chk").checked==false)
	{
		//alert("unchecked");
		document.getElementById('dashboard_plugins').style.display="block";
		
	}
	
	if(document.getElementById("report_chk").checked==true)
	{
		//alert("checked");
		document.getElementById("dashboard_primary").style.display="none";
		
	}
	if(document.getElementById("report_chk").checked==false)
	{
		//alert("unchecked");
		document.getElementById('dashboard_primary').style.display="block";
		
	}
	
	if(document.getElementById("news_chk").checked==true)
	{
		//alert("checked");
		document.getElementById("dashboard_secondary").style.display="none";
		
	}
	if(document.getElementById("news_chk").checked==false)
	{
		//alert("unchecked");
		document.getElementById('dashboard_secondary').style.display="block";
		
	}
	
	if(document.getElementById("marketing_chk").checked==true)
	{
		//alert("checked");
		document.getElementById("dashboard_recent_drafts").style.display="none";
		
	}
	if(document.getElementById("marketing_chk").checked==false)
	{
		//alert("unchecked");
		document.getElementById('dashboard_recent_drafts').style.display="block";
		
	}
	
	if(document.getElementById("quick_chk").checked==true)
	{
		//alert("checked");
		document.getElementById("dashboard_quick_press").style.display="none";
		
	}
	if(document.getElementById("quick_chk").checked==false)
	{
		//alert("unchecked");
		document.getElementById('dashboard_quick_press').style.display="block";
		
	}
}


<!--   This  function moves the web page to the top by clicking any where in the webpage
function dblclick() 
{ 
  window.scrollTo(0,0) 
}
if (document.layers) 
{ 
  document.captureEvents(ONDBLCLICK); 
}
document.ondblclick=dblclick; 
-->

<!-- This function moves the webpage at the top on clicking  the link "Back to top"
$(document).ready(function()
{

      $('a[href=#top]').click(function(){

        $('html, body').animate({scrollTop:0}, 'slow');

        return false;

    });

 

});


</script>
{/literal}

{literal}
<script type="text/javascript">

//<![CDATA[
addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
var userSettings = {
		'url': '/project/work/',
		'uid': '1',
		'time':'1291834922'
	},
	ajaxurl = 'http://primediart.com/project/work/wp-admin/admin-ajax.php',
	pagenow = 'dashboard',
	typenow = '',
	adminpage = 'index-php',
	thousandsSeparator = ',',
	decimalPoint = '.',
	isRtl = 0;
//]]>
</script>
{/literal}
<link rel="stylesheet" href="dashboard_js_css/load-styles.css" type="text/css" media="all">
<link rel="stylesheet" id="thickbox-css" href="dashboard_js_css/thickbox.css" type="text/css" media="all">
<link rel="stylesheet" id="colors-css" href="dashboard_js_css/colors-fresh.css" type="text/css" media="all">
<!--[if lte IE 7]>
<link rel='stylesheet' id='ie-css'  href='http://primediart.com/project/work/wp-admin/css/ie.css?ver=20100610' type='text/css' media='all' />
<![endif]-->
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
{/literal}
<script type="text/javascript" src="dashboard_js_css/load-scripts.js"></script>
</head><body class="wp-admin js  index-php">
{literal}
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
<div id="bd" style="padding:0px">

<div id="wpcontent">

</div>

<div id="wpbody">



<div style="overflow: hidden;" id="wpbody-content">
<div id="screen-meta">
<div id="screen-options-wrap" class="hidden">
	<form id="adv-settings" action="" method="post">
			<h5>Show on screen</h5>
		<div class="metabox-prefs">
			<label for="dashboard_right_now-hide"><input class="hide-postbox-tog" name="dashboard_right_now-hide" id="dashboard_right_now-hide" value="dashboard_right_now" checked="checked" type="checkbox">English</label>
<label for="dashboard_recent_comments-hide"><input class="hide-postbox-tog" name="dashboard_recent_comments-hide" id="dashboard_recent_comments-hide" value="dashboard_recent_comments" checked="checked" type="checkbox">Chinese <span class="postbox-title-action"><a href="http://primediart.com/project/work/wp-admin/?edit=dashboard_recent_comments#dashboard_recent_comments" class="edit-box open-box">Configure</a></span></label>
<label for="dashboard_incoming_links-hide"><input class="hide-postbox-tog" name="dashboard_incoming_links-hide" id="dashboard_incoming_links-hide" value="dashboard_incoming_links" checked="checked" type="checkbox">Spanish <span class="postbox-title-action"><a href="http://primediart.com/project/work/wp-admin/?edit=dashboard_incoming_links#dashboard_incoming_links" class="edit-box open-box">Configure</a></span></label>
<!--<label for="dashboard_plugins-hide"><input class="hide-postbox-tog" name="dashboard_plugins-hide" id="dashboard_plugins-hide" value="dashboard_plugins" checked="checked" type="checkbox">Plugins</label>
<label for="dashboard_quick_press-hide"><input class="hide-postbox-tog" name="dashboard_quick_press-hide" id="dashboard_quick_press-hide" value="dashboard_quick_press" checked="checked" type="checkbox">QuickPress</label>
<label for="dashboard_recent_drafts-hide"><input class="hide-postbox-tog" name="dashboard_recent_drafts-hide" id="dashboard_recent_drafts-hide" value="dashboard_recent_drafts" checked="checked" type="checkbox">Recent Drafts</label>
<label for="dashboard_primary-hide"><input class="hide-postbox-tog" name="dashboard_primary-hide" id="dashboard_primary-hide" value="dashboard_primary" checked="checked" type="checkbox">WordPress Blog <span class="postbox-title-action"><a href="http://primediart.com/project/work/wp-admin/?edit=dashboard_primary#dashboard_primary" class="edit-box open-box">Configure</a></span></label>
<label for="dashboard_secondary-hide"><input class="hide-postbox-tog" name="dashboard_secondary-hide" id="dashboard_secondary-hide" value="dashboard_secondary" checked="checked" type="checkbox">Other WordPress News <span class="postbox-title-action"><a href="http://primediart.com/project/work/wp-admin/?edit=dashboard_secondary#dashboard_secondary" class="edit-box open-box">Configure</a></span></label>-->
			<br class="clear">
		</div>
		<!--<h5>Screen Layout</h5>-->
<!--<div class="columns-prefs">Number of Columns:
<label><input name="screen_columns" value="1" type="radio"> 1</label>
<label><input name="screen_columns" value="2" checked="checked" type="radio"> 2</label>
<label><input name="screen_columns" value="3" type="radio"> 3</label>
<label><input name="screen_columns" value="4" type="radio"> 4</label>
</div>-->
<div><input id="screenoptionnonce" name="screenoptionnonce" value="ac1f1e5121" type="hidden"></div>
</form>
</div>

	<div id="contextual-help-wrap" class="hidden">
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
<div id="screen-options-link-wrap" class="hide-if-no-js screen-meta-toggle">
<!--<a href="#screen-options" id="show-settings-link" class="show-settings">Language Options</a></div>-->
</div>
</div>

<div class="wrap">
	<div id="icon-index" class="icon32"><br></div>
<h2>Dashboard</h2>

<div id="dashboard-widgets-wrap">

<div id="dashboard-widgets" class="metabox-holder">
	<div class="postbox-container" style="width: 49%;">
<div id="normal-sortables" class="meta-box-sortables ui-sortable">

<div id="dashboard_right_now" class="postbox "  >

<div id="cross1"><span class="cross" title="Click to Delete"><img width="15px" height="15px;" src="images/x.png" /> </span></div>
<div class="edit_click" title="Click to toggle"><span class="editTop3">Edit</span></div>

<h3 class="hndle">NO CONTACTS AND CUSTOMERS</h3>

<div id="div1" style="display:none;">
	<center>    
        {foreach from=$splinters key=key item=splinter}

               {if $splinter.index == 4}
             
                    <div class="pane" style="width:350px;"  id="pane_{$key}" {if $display_block!=$key}style="display:none"{/if}>
                    {include file=$splinter.tpl index=$splinter.index}
                    </div>
               {/if}
           
        { /foreach }
    
    </center>
    <!--<div id="inner1" style="background-color:#CCCCCC; height:35px; font-size:14px;">Remove this section</div>-->
  
  	{literal}
	<script>
    $(".editTop3").click(function () {
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
<div id="crm" class="inside">

<!--
	<div class="table table_content">
	<p class="sub">Content</p>
	<table>
	<tbody><tr class="first"><td class="first b b-posts"><a href="http://primediart.com/project/work/wp-admin/edit.php">2</a></td><td class="t posts"><a href="http://primediart.com/project/work/wp-admin/edit.php">Posts</a></td></tr><tr><td class="first b b_pages"><a href="http://primediart.com/project/work/wp-admin/edit.php?post_type=page">2</a></td><td class="t pages"><a href="http://primediart.com/project/work/wp-admin/edit.php?post_type=page">Pages</a></td></tr><tr><td class="first b b-cats"><a href="http://primediart.com/project/work/wp-admin/edit-tags.php?taxonomy=category">1</a></td><td class="t cats"><a href="http://primediart.com/project/work/wp-admin/edit-tags.php?taxonomy=category">Category</a></td></tr><tr><td class="first b b-tags"><a href="http://primediart.com/project/work/wp-admin/edit-tags.php">0</a></td><td class="t tags"><a href="http://primediart.com/project/work/wp-admin/edit-tags.php">Tags</a></td></tr>
	</tbody></table>
	</div>
	<div class="table table_discussion">
	<p class="sub">Discussion</p>
	<table>
	<tbody><tr class="first"><td class="b b-comments"><a href="http://primediart.com/project/work/wp-admin/edit-comments.php"><span class="total-count">1</span></a></td><td class="last t comments"><a href="http://primediart.com/project/work/wp-admin/edit-comments.php">Comment</a></td></tr><tr><td class="b b_approved"><a href="http://primediart.com/project/work/wp-admin/edit-comments.php?comment_status=approved"><span class="approved-count">1</span></a></td><td class="last t"><a class="approved" href="http://primediart.com/project/work/wp-admin/edit-comments.php?comment_status=approved">Approved</a></td></tr>
	<tr><td class="b b-waiting"><a href="http://primediart.com/project/work/wp-admin/edit-comments.php?comment_status=moderated"><span class="pending-count">0</span></a></td><td class="last t"><a class="waiting" href="http://primediart.com/project/work/wp-admin/edit-comments.php?comment_status=moderated">Pending</a></td></tr>
	<tr><td class="b b-spam"><a href="http://primediart.com/project/work/wp-admin/edit-comments.php?comment_status=spam"><span class="spam-count">0</span></a></td><td class="last t"><a class="spam" href="http://primediart.com/project/work/wp-admin/edit-comments.php?comment_status=spam">Spam</a></td></tr>
	</tbody></table>
	</div>-->
	<!--<div class="versions">
	<p><a href="http://primediart.com/project/work/wp-admin/themes.php" class="button rbutton">Change Theme</a>Theme <span class="b"><a href="http://primediart.com/project/work/wp-admin/themes.php">Twenty Ten</a></span> with <span class="b"><a href="http://primediart.com/project/work/wp-admin/widgets.php">7 Widgets</a></span></p><span id="wp-version-message">You are using <span class="b">WordPress 3.0.2</span>.</span>
	<br class="clear"></div>-->
	
	</div>
</div>
</div>







<div id="dashboard_recent_comments" class="postbox ">
<div class="cross" title="Click to Delete"><img onClick="fadeout()" width="15px" height="15px;" src="images/x.png" /> </div>

<div class="edit_click"><span class="editTop2">Edit</span></div>


<h3 class="hndle">TOP PRODUCTS</h3>

<div id="divR" style="display:none;">
<center>
		{foreach from=$splinters key=key item=splinter}

               {if $splinter.index == 3}
             
                    <div class="pane" style="width:350px;"  id="pane_{$key}" {if $display_block!=$key}style="display:none"{/if}>
                    {include file=$splinter.tpl index=$splinter.index}
                    </div>
               {/if}
           
        { /foreach }
</center>
<div></div>

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
{/literal}
<div class="inside">
<div id="the-comment-list" style="padding-top:-20px;" class="list:comment">

<div id="comment-1" class="comment even thread-even depth-1 comment-item approved">

<!--<img alt="" src="dashboard_js_css/ad516503a11cd5ca435acc9bb6523536.png" class="avatar avatar-50 photo avatar-default" width="50" height="50">-->
<!--<div class="dashboard-comment-wrap">
<h4 class="comment-meta">
From <cite class="comment-author"><a href="http://wordpress.org/" rel="external nofollow" class="url">Mr WordPress</a></cite> on <a href="http://primediart.com/project/work/wp-admin/post.php?post=1&action=edit">WELCOME TO OUR WEBSITE</a> <a class="comment-link" href="http://primediart.com/project/work/?p=1#comment-1">#</a> <span class="approve">[Pending]</span> </h4>

<blockquote><p>Hi, this is a comment.To delete a comment, just log in and view the post's comments. There you will have ...</p></blockquote>
<p class="row-actions"><span class="approve"><a href="http://primediart.com/project/work/wp-admin/comment.php?action=approvecomment&p=1&c=1&_wpnonce=2b177a8d88" class="dim:the-comment-list:comment-1:unapproved:e7e7d3:e7e7d3:new=approved vim-a" title="Approve this comment">Approve</a></span><span class="unapprove"><a href="http://primediart.com/project/work/wp-admin/comment.php?action=unapprovecomment&p=1&c=1&_wpnonce=2b177a8d88" class="dim:the-comment-list:comment-1:unapproved:e7e7d3:e7e7d3:new=unapproved vim-u" title="Unapprove this comment">Unapprove</a></span><span class="reply hide-if-no-js"> | <a onclick="commentReply.open('1','1');return false;" class="vim-r hide-if-no-js" title="Reply to this comment" href="#">Reply</a></span><span class="edit"> | <a class="" href="#" title="Edit comment">Edit</a></span><span class="spam"> | <a href="http://primediart.com/project/work/wp-admin/comment.php?action=spamcomment&p=1&c=1&_wpnonce=c995c354e0" class="delete:the-comment-list:comment-1::spam=1 vim-s vim-destructive" title="Mark this comment as spam">Spam</a></span><span class="trash"> | <a href="http://primediart.com/project/work/wp-admin/comment.php?action=trashcomment&p=1&c=1&_wpnonce=c995c354e0" class="delete:the-comment-list:comment-1::trash=1 delete vim-d vim-destructive" title="Move this comment to the trash">Trash</a></span></p>
</div>-->
<!--<span class="textright textleftRectified"><a href="http://primediart.com/project/work/wp-admin/edit-comments.php" class="button">View all</a></span>-->
</div>
</div>


<form method="get" action="">
<div id="com-reply" style="display: none;"><div id="replyrow" style="display: none;">
<div id="replyhead" style="display: none;">Reply to Comment</div>

<div id="edithead" style="display: none;">
<div class="inside">
<label for="author">Name</label>
<input name="newcomment_author" size="50" tabindex="101" id="author" type="text">
</div>

<div class="inside">
<label for="author-email">E-mail</label>
<input name="newcomment_author_email" size="50" tabindex="102" id="author-email" type="text">
</div>

<div class="inside">
<label for="author-url">URL</label>
<input id="author-url" name="newcomment_author_url" size="103" tabindex="103" type="text">
</div>
<div style="clear: both;"></div>
</div>

<div id="ed_reply_qtags"><div id="ed_reply_toolbar"><input id="ed_reply_strong" accesskey="b" class="ed_button" onClick="ed_reply.edInsertTag(0);" value="b" type="button"><input id="ed_reply_em" accesskey="i" class="ed_button" onClick="ed_reply.edInsertTag(1);" value="i" type="button"><input id="ed_reply_link" accesskey="a" class="ed_button" onClick="ed_reply.edInsertLink(2);" value="link" type="button"><input id="ed_reply_block" accesskey="q" class="ed_button" onClick="ed_reply.edInsertTag(3);" value="b-quote" type="button"><input id="ed_reply_del" accesskey="d" class="ed_button" onClick="ed_reply.edInsertTag(4);" value="del" type="button"><input id="ed_reply_ins" accesskey="s" class="ed_button" onClick="ed_reply.edInsertTag(5);" value="ins" type="button"><input id="ed_reply_img" accesskey="m" class="ed_button" onClick="edInsertImage(ed_reply.Canvas);" value="img" type="button"><input id="ed_reply_ul" accesskey="u" class="ed_button" onClick="ed_reply.edInsertTag(7);" value="ul" type="button"><input id="ed_reply_ol" accesskey="o" class="ed_button" onClick="ed_reply.edInsertTag(8);" value="ol" type="button"><input id="ed_reply_li" accesskey="l" class="ed_button" onClick="ed_reply.edInsertTag(9);" value="li" type="button"><input id="ed_reply_code" accesskey="c" class="ed_button" onClick="ed_reply.edInsertTag(10);" value="code" type="button"><input id="ed_reply_ed_spell" class="ed_button" onClick="edSpell(ed_reply.Canvas);" title="Dictionary lookup" value="lookup" type="button"><input id="ed_reply_ed_close" class="ed_button" onClick="ed_reply.edCloseAllTags();" title="Close all open tags" value="close tags" type="button"></div></div><div id="replycontainer"><textarea rows="8" cols="40" name="replycontent" tabindex="104" id="replycontent"></textarea></div>

<p id="replysubmit" class="submit">
<a href="#comments-form" class="cancel button-secondary alignleft" tabindex="106">Cancel</a>
<a href="#comments-form" class="save button-primary alignright" tabindex="104">
<span id="savebtn" style="display: none;">Update Comment</span>
<span id="replybtn" style="display: none;">Submit Reply</span></a>
<img class="waiting" style="display: none;" src="dashboard_js_css/wpspin_light.gif" alt="">
<span class="error" style="display: none;"></span>
<br class="clear">
</p>

<input name="user_ID" id="user_ID" value="1" type="hidden">
<input name="action" id="action" value="" type="hidden">
<input name="comment_ID" id="comment_ID" value="" type="hidden">
<input name="comment_post_ID" id="comment_post_ID" value="" type="hidden">
<input name="status" id="status" value="" type="hidden">
<input name="position" id="position" value="-1" type="hidden">
<input name="checkbox" id="checkbox" value="0" type="hidden">
<input name="mode" id="mode" value="dashboard" type="hidden">
<input id="_ajax_nonce-replyto-comment" name="_ajax_nonce-replyto-comment" value="4f34ee7716" type="hidden"> <input id="_wp_unfiltered_html_comment" name="_wp_unfiltered_html_comment" value="70cafe5b81" type="hidden"></div></div>
</form>
<div class="hidden" id="trash-undo-holder">
<div class="trash-undo-inside">Comment by <strong></strong> moved to the trash. <span class="undo untrash"><a href="#">Undo</a></span></div>
</div>
<div class="hidden" id="spam-undo-holder">
<div class="spam-undo-inside">Comment by <strong></strong> marked as spam. <span class="undo unspam"><a href="#">Undo</a></span></div>
</div>
</div>


</div>
</div>
{literal}
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
<!--==================================================DIV 3=============================================================-->
<div id="dashboard_incoming_links" class="postbox ">
<div id="cross3" class="cross" onclick="sublist();"><img width="15px" height="15px;" src="images/x.png" /></div>
<div id="edit3" class="edit" onclick="sublist();"><strong>Edit</strong></div>
<div class="sub" onclick="sublist();"><strong>-</strong></div>
<div class="add" onclick="addlist();"><strong>+</strong></div>


<div class="handlediv" title="Click to toggle"><br></div>
<h3 class="hndle"><span>GUIDE TO PROGRAMMER<span class="postbox-title-action"></span></span></h3>
<div id="div3" style="display:none; background-color:#999999;">
	<center>
    
    	<p>Hi Guys ! Here you will get a step by step direction on how to develope a widget in dashboard. At first see the code in editor (Page Name : dashboard.tpl). Then go to design panel. You can now select any widget and then go to code. </p>
        
        <p>Find the respective div. If you want to make a new div then you just copy any of the existing div and paste it to your required place and also dont forget to edit the script of jquery. Ok you are done. Now you can see the div working. If you need to edit the content of the inside div then just put your logic to retrive dynamic data from database.</p>
    
    </center>
  </div>
<div style="" class="inside">
</div>
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
<!--=========================================================END DIV 3==================================================================-->



<!--============================================================DIV 4==========================================================================-->
<div id="dashboard_incoming_links" class="postbox ">
<div onMouseOver="this.className='crm_over'" onMouseOut="this.className='menuOver'"  >
<!--<div id="dashboard_plugins" class="postbox">
<div id="cross4" class="cross"><img width="15px" height="15px;" src="images/x.png" /></div>
<div id="edit4" class="edit"><strong>Edit</strong></div>
<div id="sub4" class="sub" onclick="sublist4();"><strong>-</strong></div>
<div id="sub4" class="add" onclick="addlist4();"><strong>+</strong></div>
<div class="handlediv" title="Click to toggle"><br></div>
<h3 class="hndle"><span>Ecommerce</span></h3>

<div id="div4" style="display:none; background-color:#999999;">
	<center><form action="#" method="get">
    <br />
    <input name="CRM" type="checkbox" value="" />details about CRM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="Invoicing" type="checkbox" value="" />details about Invoicing<br>
    <hr />
    <input name="CRM" type="checkbox" value="" />details about CRM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="Invoicing" type="checkbox" value="" />details about Invoicing<br>
    <input name="CRM" type="checkbox" value="" />details about CRM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="Invoicing" type="checkbox" value="" />details about Invoicing<br>
    <hr />
    <input name="save4" id="save4" type="button" value="save"/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input  name="exit4" id="exit4" type="button" value="exit"/>
    </form>
    </center>
    <div id="inner4" style="background-color:#CCCCCC; height:35px; font-size:14px;">Remove this section</div>
  
  </div>

<div style="" class="inside"><h4>Most Popular</h4>
<div>
<form name="form3" id="form3">
<li id="6" style="display:none">list 1</li>
<li id="7" style="display:none">list 2</li>
<li id="8" style="display:none">list 3</li>
<li id="9" style="display:none">list 4</li>
<li id="10" style="display:none">list 5</li>
</form>
</div>

<h5><a href="http://wordpress.org/extend/plugins/si-contact-form/">Fast Secure Contact Form</a></h5>&nbsp;<span>(<a href="http://primediart.com/project/work/wp-admin/plugin-install.php?tab=plugin-information&amp;plugin=si-contact-form&amp;_wpnonce=e514a0c75e&amp;TB_iframe=true&amp;width=640&amp;height=493" class="thickbox" title="Fast Secure Contact Form">Install</a>)</span>
<p>A super customizable contact form that lets your visitors send you 
email. Blocks all automated spammers. Packed with settings and features.</p>
<h4>Newest Plugins</h4>
<h5><a href="http://wordpress.org/extend/plugins/menu-on-footer/">Menu on footer</a></h5>&nbsp;<span>(<a href="http://primediart.com/project/work/wp-admin/plugin-install.php?tab=plugin-information&amp;plugin=menu-on-footer&amp;_wpnonce=4b158bcf24&amp;TB_iframe=true&amp;width=640&amp;height=493" class="thickbox" title="Menu on footer">Install</a>)</span>
<p>Creates a simple configurable unrolled menu on your wp footer.</p>
<h4>Recently Updated</h4>
<h5><a href="http://wordpress.org/extend/plugins/wp-admin-icons/">WP No Tag Base</a></h5>&nbsp;<span>(<a href="http://primediart.com/project/work/wp-admin/plugin-install.php?tab=plugin-information&amp;plugin=wp-admin-icons&amp;_wpnonce=8936dd0777&amp;TB_iframe=true&amp;width=640&amp;height=493" class="thickbox" title="WP No Tag Base">Install</a>)</span>
<p>WordPress Admin Icons allows WordPress Administrators to customize 
the WordPress backend icons to suit their needs through an easy-to-use 
GUI interfac</p>
</div>
</div>-->
{literal}
<script>
    $("#edit4").click(function () {
      $('#div4').slideToggle("slow");
    });
	function slide4()
	{
      $('#div4').slideToggle("slow");
		}
</script>
<script>
  $('#cross4').click(function () {
  $('#dashboard_plugins').fadeOut("slow");
  });
  </script>
  <script>
    $("#save4").click(function () {
      $('#div4').slideToggle("slow");
    });
	function slide3()
	{
      $('#div4').slideToggle("slow");
		}
</script>
<script>
    $("#exit4").click(function () {
      $('#div4').slideToggle("slow");
    });
	function slide3()
	{
      $('#div4').slideToggle("slow");
		}
</script>
<script>
  $(document).ready(function() {
    $('#inner4').click(function () {
      $('#dashboard_plugins').effect("explode");
    });
  });
  </script>
{/literal}

</div></div></div>	</div>
<!--==================================================================END DIV 4====================================================================================-->
<div class="postbox-container" style="width: 49%;">
<div id="side-sortables" class="meta-box-sortables ui-sortable">
<!--==================================================================== DIV 5====================================================================================-->
<div onMouseOver="this.className='crm_over'" onMouseOut="this.className='menuOver'"  >

</div>
{literal}
<script>
    $("#edit5").click(function () {
      $('#div5').slideToggle("slow");
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
      $('#div5').slideToggle("slow");
    });
	function slide3()
	{
      $('#div5').slideToggle("slow");
		}
</script>
<script>
    $("#exit5").click(function () {
      $('#div5').slideToggle("slow");
    });
	function slide3()
	{
      $('#div5').slideToggle("slow");
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

<!--==================================================================END DIV 5====================================================================================-->

<!--===================================================================== DIV 6====================================================================================-->

<div id="dashboard_recent_drafts" class="postbox ">
<div class="cross" id="cross6"><img width="15px" height="15px;" src="images/x.png" /></div>
<div class="edit" id="edit6"><strong>Edit</strong></div>
<div class="sub" onClick="sublist6();"><strong>-</strong></div>
<div class="add" onClick="addlist6();"><strong>+</strong></div>
<div class="handlediv" title="Click to toggle"><br></div>
<h3 class="hndle"><span>TOP CUSTOMERS </span></h3>
<div class="inside">
<div id="div6" style="display:none;">
	<center>
    
     {foreach from=$splinters key=key item=splinter}

               {if $splinter.index == 2}
             
                    <div class="pane" style="width:350px;"  id="pane_{$key}" {if $display_block!=$key}style="display:none"{/if}>
                    {include file=$splinter.tpl index=$splinter.index}
                    </div>
               {/if}
           
        { /foreach }
    
    </center>
    <!--<div id="inner6" style="background-color:#CCCCCC; height:35px; font-size:14px;">Remove this section</div>-->
  
  </div>
</div>
</div>


{literal}
<script>
    $("#edit6").click(function () {
      $('#div6').slideToggle("slow");
    });
	function slide6()
	{
      $('#div6').slideToggle("slow");
		}
</script>
<script>
  $('#cross6').click(function () {
  $('#dashboard_recent_drafts').fadeOut("slow");
  });
  </script>
  <script>
    $("#save6").click(function () {
      $('#div6').slideToggle("slow");
    });
	function slide6()
	{
      $('#div6').slideToggle("slow");
		}
</script>
<script>
    $("#exit6").click(function () {
      $('#div6').slideToggle("slow");
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

<!--===================================================================== DIV 7====================================================================================-->




<div id="dashboard_recent_drafts" class="postbox ">
<div class="cross" id="cross7"><img width="15px" height="15px;" src="images/x.png" /></div>
<div class="edit" id="edit7"><strong>Edit</strong></div>
<div class="sub" onClick="sublist7();"><strong>-</strong></div>
<div class="add" onClick="addlist7();"><strong>+</strong></div>
<div class="handlediv" title="Click to toggle"><br></div>
<h3 class="hndle"><span>OUTSTANDING ORDER</span></h3>
<div class="inside">
<div id="div7" style="display:none;">
	<center>
         
{foreach from=$splinters key=key item=splinter}

               {if $splinter.index == 5}
             
                    <div class="pane" style="width:350px;"  id="pane_{$key}" {if $display_block!=$key}style="display:none"{/if}>
                    {include file=$splinter.tpl index=$splinter.index}
                    </div>
               {/if}
           
        { /foreach }
    
    </center>
    <!--<div id="inner6" style="background-color:#CCCCCC; height:35px; font-size:14px;">Remove this section</div>-->
  
  </div>
</div>
</div>






{literal}
<script>
    $("#edit7").click(function () {
      $('#div7').slideToggle("slow");
    });
	function slide7()
	{
      $('#div7').slideToggle("slow");
	}
</script>

<script>
  $('#cross7').click(function () {
  $('#dashboard_recent_drafts').fadeOut("slow");
  });
  </script>
  <script>
    $("#save6").click(function () {
      $('#div7').slideToggle("slow");
    });
	function slide7()
	{
      $('#div7').slideToggle("slow");
		}
</script>
<script>
    $("#exit6").click(function () {
      $('#div7').slideToggle("slow");
    });
	
</script>
<script>
  $(document).ready(function() {
    $('#inner6').click(function () {
      $('#dashboard_recent_drafts').effect("explode");
    });
  });
  </script>
  {/literal}
<!--==================================================================END DIV 6====================================================================================-->
<!--==================================================================DIV WORST PRODUCT===========================================================================-->


<div id="worst products" class="postbox ">
<div class="cross" id="cross_worst"><img width="15px" height="15px;" src="images/x.png" /></div>
<div class="edit" id="edit_worst"><strong>Edit</strong></div>
<div class="sub" onClick="sublist_worst();"><strong>-</strong></div>
<div class="add" onClick="addlist_worst();"><strong>+</strong></div>
<div class="handlediv" title="Click to toggle"><br></div>
<h3 class="hndle"><span>WORST PRODUCTS </span></h3>
<div class="inside">
<div id="div_worst" style="display:none;">
	<center>
    
     {foreach from=$splinters key=key item=splinter}

               {if $splinter.index == 6}
             
                    <div class="pane" style="width:350px;"  id="pane_{$key}" {if $display_block!=$key}style="display:none"{/if}>
                    {include file=$splinter.tpl index=$splinter.index}
                    </div>
               {/if}
           
        { /foreach }

    
    </center>
    <!--<div id="inner6" style="background-color:#CCCCCC; height:35px; font-size:14px;">Remove this section</div>-->
  
  </div>
</div>
</div>


{literal}
<script>
    $("#edit_worst").click(function () {
      $('#div_worst').slideToggle("slow");
    });
	function slide6()
	{
      $('#div_worst').slideToggle("slow");
		}
</script>
<script>
  $('#cross_worst').click(function () {
  $('#worst products').fadeOut("slow");
  });
  </script>
  <script>
    $("#save6").click(function () {
      $('#div_worst').slideToggle("slow");
    });
	function slide6()
	{
      $('#div_worst').slideToggle("slow");
		}
</script>
<script>
    $("#exit6").click(function () {
      $('#div_worst').slideToggle("slow");
    });
	function slide6()
	{
      $('#div_worst').slideToggle("slow");
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

<!--==================================================================END DIV====================================================================================-->

<!--====================================================================== DIV 7====================================================================================-->

<!--<div id="dashboard_primary" class="postbox">
<div class="cross" id="cross7"><img width="15px" height="15px;" src="images/x.png" /></div>
<div class="edit" id="edit7"><strong>Edit</strong></div>
<div class="sub" i onclick="sublist();"><strong>-</strong></div>
<div class="add" onclick="addlist();"><strong>+</strong></div>
<div class="handlediv" title="Click to toggle"><br></div>
<h3 class="hndle"><span>Reporting Tools <span class="postbox-title-action"></span></span></h3>
<div id="div7" style="display:none; background-color:#999999;">
	<center><form action="#" method="get">
    <br />
    <input name="CRM" type="checkbox" value="" />details about CRM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="Invoicing" type="checkbox" value="" />details about Invoicing<br>
    <hr />
    <input name="CRM" type="checkbox" value="" />details about CRM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="Invoicing" type="checkbox" value="" />details about Invoicing<br>
    <input name="CRM" type="checkbox" value="" />details about CRM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="Invoicing" type="checkbox" value="" />details about Invoicing<br>
    <hr />
    <input name="save7" id="save7" type="button" value="save"/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input  name="exit7" id="exit7" type="button" value="exit"/>
    </form>
    </center>
    <div id="inner7" style="background-color:#CCCCCC; height:35px; font-size:14px;">Remove this section</div>
  
  </div>
<div style="" class="inside"><div class="rss-widget"><ul><li><a class="rsswidget" href="http://wordpress.org/news/2010/11/wordpress-3-0-2/" title="WordPress 3.0.2 is available and is a mandatory security update for all previous WordPress versions. Haiku has become traditional: Fixed on day zero One-click update makes you safe This used to be hard This maintenance release fixes a moderate security issue that could allow a malicious Author-level user to gain further access to the site, […]">WordPress 3.0.2</a> <span class="rss-date">November 30, 2010</span><div class="rssSummary">WordPress
 3.0.2 is available and is a mandatory security update for all previous 
WordPress versions. Haiku has become traditional: Fixed on day zero 
One-click update makes you safe This used to be hard This maintenance 
release fixes a moderate security issue that could allow a malicious 
Author-level user to gain further access to the site, […]</div></li><li><a class="rsswidget" href="http://wordpress.org/news/2010/11/wordpress-3-1-beta-1/" title="It’s that time in the release cycle again, when all the features are basically done, and we’re just squashing bugs. To the brave of heart and giving of soul: Won’t you help us test the new version of WordPress? As always, this is software still in development and we don’t recommend that you run it […]">WordPress 3.1 Beta 1</a> <span class="rss-date">November 25, 2010</span><div class="rssSummary">It’s
 that time in the release cycle again, when all the features are 
basically done, and we’re just squashing bugs. To the brave of heart and
 giving of soul: Won’t you help us test the new version of WordPress? As
 always, this is software still in development and we don’t recommend 
that you run it […]</div></li></ul></div></div>
</div>-->
</div>
{literal}
<script>
    $("#edit7").click(function () {
      $('#div7').slideToggle("slow");
    });
	function slide7()
	{
      $('#div7').slideToggle("slow");
		}
</script>
<script>
  $('#cross7').click(function () {
  $('#dashboard_primary').fadeOut("slow");
  });
  </script>
  <script>
    $("#save7").click(function () {
      $('#div7').slideToggle("slow");
    });
	function slide7()
	{
      $('#div7').slideToggle("slow");
		}
</script>
<script>
    $("#exit7").click(function () {
      $('#div7').slideToggle("slow");
    });
	function slide7()
	{
      $('#div7').slideToggle("slow");
		}
</script>
<script>
  $(document).ready(function() {
    $('#inner7').click(function () {
      $('#dashboard_primary').effect("explode");
    });
  });
  </script>
  {/literal}
<!--==================================================================END DIV 7====================================================================================-->

<!--===================================================================== DIV 8====================================================================================-->
<div onMouseOver="this.className='crm_over'" onMouseOut="this.className='menuOver'"  >
<!--<div id="dashboard_secondary" class="postbox ">
<div class="cross" id="cross8"><img width="15px" height="15px;" src="images/x.png" /></div>
<div class="edit" id="edit8" ><strong>Edit</strong></div>
<div class="sub" onclick="sublist();"><strong>-</strong></div>
<div class="add" onclick="addlist();"><strong>+</strong></div>
<div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Other  News <span class="postbox-title-action"></span></span></h3>
<div style="" class="inside">
<div id="div8" style="display:none; background-color:#999999;">
	<center><form action="#" method="get">
    <br />
    <input name="CRM" type="checkbox" value="" />details about CRM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="Invoicing" type="checkbox" value="" />details about Invoicing<br>
    <hr />
    <input name="CRM" type="checkbox" value="" />details about CRM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="Invoicing" type="checkbox" value="" />details about Invoicing<br>
    <input name="CRM" type="checkbox" value="" />details about CRM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="Invoicing" type="checkbox" value="" />details about Invoicing<br>
    <hr />
    <input name="save8" id="save8" type="button" value="save"/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input  name="exit8" id="exit8" type="button" value="exit"/>
    </form>
    </center>
    <div id="inner8" style="background-color:#CCCCCC; height:35px; font-size:14px;">Remove this section</div>
  
  </div>
<div class="rss-widget"><ul><li><a class="rsswidget" href="http://feedproxy.google.com/%7Er/weblogtoolscollection/UXMP/%7E3/QhpdwwBM2C0/" title="Joseph Scott who is working on the Akismet plugin for WordPress notified everyone via the WordPress core development blog that there would be some changes coming to Akismet, starting with version 2.5. In version 2.5 of the plugin, there will be some new files added such as admin.php, akismet,css, akismet.js, and widget.php. There will also be a test mode inc […]">Weblog Tools Collection: Changes Slated For Akismet</a></li><li><a class="rsswidget" href="http://feedproxy.google.com/%7Er/weblogtoolscollection/UXMP/%7E3/IINrLIbMJDw/" title="WordPress is certainly the most popular open source blogging platform, but how are development decisions made? You may have heard that WordPress is a democracy, that Matt Mullenweg makes all the decisions, or that Automattic governs the development of WordPress.&nbsp;Of course, neither of those are true. WordPress is actually a Meritocracy. It’s really quite simp […]">Weblog Tools Collection: How WordPress Decisions are Made</a></li><li><a class="rsswidget" href="http://feedproxy.google.com/%7Er/weblogtoolscollection/UXMP/%7E3/57tBRL-wfPU/" title="The fine folks over at Testking.com have created and shared an info-graphic that displays the power of WordPress. The image contains interesting information that can be found within the Codex but has been displayed in a nice, visual form. Notables include: time line of releases and major milestones from 2003 to 2010, web usage of WordPress, percentages of we […]">Weblog Tools Collection: One Image Shows The Power Of WordPress</a></li><li><a class="rsswidget" href="http://feedproxy.google.com/%7Er/weblogtoolscollection/UXMP/%7E3/e5XoRzeq-pM/" title="New plugins Snow Storm displays falling snow flakes on the front of your WordPress website for a festive presentation. WordPress Gzip Compression enables gzip-compression if the visitor’s browser can handle it. This will speed up your WordPress website drastically and reduces bandwidth usage. Updated plugins Easy FancyBox enables the FancyBox 1.3.4 jQuery ex […]">Weblog Tools Collection: WordPress Plugin Releases for 12/6</a></li><li><a class="rsswidget" href="http://wordpress.tv/2010/12/02/big-web-show/" title=" […]">WordPress.tv: The Big Web Show 29 with Dan Benjamin and Jeffrey Zeldman: Matt Mullenweg Interview</a></li></ul></div></div>
</div>-->
</div>
{literal}
<script>
    $("#edit8").click(function () {
      $('#div8').slideToggle("slow");
    });
	function slide8()
	{
      $('#div8').slideToggle("slow");
		}
</script>
<script>
  $('#cross8').click(function () {
  $('#dashboard_secondary').fadeOut("slow");
  });
  </script>
  <script>
    $("#save8").click(function () {
      $('#div8').slideToggle("slow");
    });
	function slide8()
	{
      $('#div8').slideToggle("slow");
		}
</script>
<script>
    $("#exit8").click(function () {
      $('#div8').slideToggle("slow");
    });
	function slide8()
	{
      $('#div8').slideToggle("slow");
		}
</script>
<script>
  $(document).ready(function() {
    $('#inner8').click(function () {
      $('#dashboard_secondary').effect("explode");
    });
  });
  </script>
 {/literal}
<!--==================================================================END DIV 8====================================================================================-->

</div>	</div>




<div class="postbox-container" style="display: none; width: 49%;">
<div style="" id="column3-sortables" class="meta-box-sortables ui-sortable"></div>	</div><div class="postbox-container" style="display: none; width: 49%;">
<div style="" id="column4-sortables" class="meta-box-sortables ui-sortable"></div></div></div>

<form style="display: none;" method="get" action="">
	<p>
<input id="closedpostboxesnonce" name="closedpostboxesnonce" value="7def193573" type="hidden"><input id="meta-box-order-nonce" name="meta-box-order-nonce" value="0155bfc370" type="hidden">	</p>
</form>
</div><!-- dashboard-widgets-wrap -->
<!--<div><h3>Customise Page</h3>
<h4>Remove your prefered topics</h4>
<form>
<input name="crm_chk" id="crm_chk" type="checkbox" value="" />CRM <br />
<input name="invoice_chk" id="invoice_chk" type="checkbox" value="" />Invoicing <br />
<input name="stock_chk" id="stock_chk" type="checkbox" value="" />Stock Control<br />
<input name="ecom_chk" id="ecom_chk" type="checkbox" value="" />Ecommerce<br />
<input name="marketing_chk" id="marketing_chk" type="checkbox" value="" />Electronic Marketing<br />
<input name="report_chk" id="report_chk" type="checkbox" value="" />Reporting Tools<br />
<input name="news_chk" id="news_chk" type="checkbox" value="" />Other News<br />
<input name="quick_chk" id="quick_chk" type="checkbox" value="" />Quick Press<br /><div style="padding-left:800px;"><a href="#top">Back to top</a></div>
<input name="submit" type="button" value="save" onclick="customise();" />
</form>
</div>-->  

</div><!-- wrap -->


<div class="clear"></div></div><!-- wpbody-content -->
<div class="clear"></div></div><!-- wpbody -->

<div class="clear"></div></div><!-- wpcontent -->

<!-- wpwrap -->

<div align="center">
	    <div id="ajax_response">	
<!-- post comment code----->
 		<!--/*{foreach from=$splinters key=key item=splinter}

               {if $splinter.index == 8}
             
                    <div class="pane" style="text-align:center;"  id="pane_{$key}" {if $display_block!=$key}style="display:none"{/if}>
                    {include file=$splinter.tpl index=$splinter.index}
                    </div>
               {/if}
           
        { /foreach }*/-->
        
        {section name=tplVar loop=$tplVar}
                <div align="center">         
                    <div class="comment_holder">
                        <div id="photo"><img src="images/user.JPG">  <br>{ $tplVar[tplVar].name }  </div>
                            
 <div id="comment_text"><div id="date_posted">{$tplVar[tplVar].date_added}</div>{$tplVar[tplVar].comment}</div>
                    </div>
                </div>
        {/section}
        
<!--end of post comment---->
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
	    <td align="left"><input type="button" value="Submit" id="submit">&nbsp;<img src="images/loading.gif" id="loading"></td>
	  </tr>
	</table>
</div>




<div class="clear"></div>
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
</body></html>
