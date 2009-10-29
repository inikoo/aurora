{include file='header.tpl'}
<div id="bd" >
<div id="searches" style="float:right;xwidth:250px;margin-top:10px;padding:10px 10px 10px 10px;border:0px solid #6d84b4">
  <div class="search_box" style="padding:0px 0px 5px 0">
    <span class="search_title" style="padding-right:15px">{t}Product Code{/t}:</span><input size="8" class="text search" id="product_search" value="" name="search"/><img align="absbottom" id="product_submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
    <span  class="search_msg"   id="product_search_msg"    ></span> <span  class="search_sugestion"   id="product_search_sugestion"    ></span>
  </div>
    <div class="search_box" style="padding:5px 0px">
      <span class="search_title" style="padding-right:15px">{t}Order Number{/t}:</span> <input size="8" class="text search" id="order_search" value="" name="search"/><img align="absbottom" id="order_submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
    <span  class="search_msg"   id="order_search_msg"    ></span> <span  class="search_sugestion"   id="order_search_sugestion"    ></span>
  </div>
  <div class="search_box" style="padding:5px 0px">
      <span class="search_title" style="padding-right:15px">{t}Contact{/t}:</span> <input size="8" class="text search" id="contact_search" value="" name="search"/><img align="absbottom" id="contact_submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
    <span  class="search_msg"   id="order_search_msg"    ></span> <span  class="search_sugestion"   id="contact_search_sugestion"    ></span>
  </div>
</div>

<div style="float:left">
<img style="width:600px" src="art/home_baner_1.9.png">
</div>
</div>
{include file='footer.tpl'}
