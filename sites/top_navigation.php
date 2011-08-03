<link rel="stylesheet" type="text/css" href="../top_navigation.css" />
<script type="text/javascript" src="../external_libs/yui/2.9/build/utilities/utilities.js"></script>
<script type="text/javascript" src="../external_libs/yui/2.9/build/dragdrop/dragdrop-min.js"></script>
<script type="text/javascript" src="../external_libs/yui/2.9/build/json/json-min.js"></script>
<script type="text/javascript" src="../external_libs/yui/2.9/build/element/element-min.js"></script>

<script type="text/javascript" src="../js/sha256.js"></script>
<script type="text/javascript" src="../js/aes.js"></script>

<script type="text/javascript" src="../js/login.js"></script>
<script type="text/javascript" src="../basket.js"></script>

<script type="text/javascript" src="../top_navigation.js.php"></script>

<input type="hidden" value="<?php echo $store_key?>" id="store_key">



<div id="top_navigator" style="width:855px">

<button id="show_register_dialog">Register</button>
<button id="show_login_dialog">Log In</button>
</div>



<div id="dialog_login"    class="dialog"    style="position:relative;left:575px;top:-22px;width:300px">
<input type="hidden" value="<?php echo $St?>" id="ep">
<h2>Login</h2>
<table>

<tr><td>Email: </td><td><input id="login_handle"></td></tr>
<tr><td>Password: </td><td><input  id="login_password"></td></tr>
<tr class="button" style=""><td colspan="2"><span id="invalid_credentials" style="display:none">Wrong credentials!</span>  <button id="submit_login">Log In</button></td></tr>
<tr class="link"><td colspan=2>Forgot your password? <span class="link"   id="link_forgot_password_from_login" >Click Here</span></td></tr>
<tr class="link"><td colspan=2>First visit? <span class="link" id="link_register_from_login">Register Here</span></td></tr>
<tr class="button" ><td></td><td ><button id="hide_login_dialog">Close</button></td></tr>
</table>
</div>

<div id="dialog_register"    class="dialog"    style="position:relative;left:575px;width:300px;top:-22px;">
<h2>Registration</h2>
<table>
<tr><td>Email: </td><td><input id="register_email"></td></tr>
<tr class="button" style=""><td></td><td><button id="submit_check_email">Continue</button></td></tr>

<tr class="button" ><td></td><td ><button id="hide_register_dialog">Close</button></td></tr>
</table>
</div>


<div id="dialog_forgot_password"    class="dialog"    style="position:relative;left:575px;width:300px;top:-22px;">
<h2>Forgotten password</h2>
<table>
<tr><td>Email: </td><td><input id="forgot_password_handle"></td></tr>
<tr class="button" style=""><td></td><td><button id="submit_forgot_password">Continue</button></td></tr>

<tr class="button" ><td></td><td ><button id="hide_forgot_password_dialog">Close</button></td></tr>
</table>
</div>
