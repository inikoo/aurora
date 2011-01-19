<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>ammap</title>
<style>
{literal}
  body{
    margin: 0px;
  }
  {/literal}
</style>
</head>

<body">
<!-- saved from url=(0013)about:internet -->
<!-- ammap script-->
  <script type="text/javascript" src="{$ammap_path}/ammap/swfobject.js"></script>
	<div id="flashcontent">
		<strong>You need to upgrade your Flash Player</strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("{$path}/ammap/ammap.swf", "ammap", "100%", "100%", "8", "#FFFFFF");
        so.addVariable("path", "{$path}/ammap/");
		so.addVariable("data_file", escape("{$data_file}"));
        so.addVariable("settings_file", escape("{$settings_file}"));		
		so.addVariable("preloader_color", "#999999");
		so.write("flashcontent");
		// ]]>
	</script>
<!-- end of ammap script -->
</body>
</html>