<script type="text/javascript">

var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>

<?php
include_once('conf/google_analytics.php');
?>
<script type="text/javascript">
var pageTracker = _gat._getTracker("<?php echo $google_id ?>");
pageTracker._initData();
pageTracker._trackPageview();
</script>
