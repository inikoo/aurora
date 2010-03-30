	  <div id="main_menu" >
	    <dl class="dropdown">
	      <dt id="home-ddheader" onmouseover="ddMenu('home',1)" onmouseout="ddMenu('home',-1)">Home</dt>
	      
	      <dd id="home-home-ddcontent" onmouseover="cancelHide('home')" onmouseout="ddMenu('home',-1)">
		<ul>
		</ul>
	      </dd>
	    </dl>

	    
	    <dl class="dropdown">
	      <dt id="main_pages-ddheader" onmouseover="ddMenu('main_pages',1)" onmouseout="ddMenu('main_pages',-1)">Info</dt>
	      <dd id="main_pages-ddcontent" onmouseover="cancelHide('main_pages')" onmouseout="ddMenu('main_pages',-1)">
		<ul>
		  <li><a href="info.php?page=contact" class="underline">Contact</a></li>
		  <li><a href="info.php?page=terms_and_conditions" class="underline">Terms & Conditions</a></li>
		  <li><a href="info.php?page=company_ethics" class="underline">Company Ethics</a></li>
		  <li><a href="info.php?page=export_guide" class="underline">Export Guide</a></li>
		  <li><a href="info.php?page=showroom" class="underline">Showroom</a></li>

		  <li><a href="info.php?page=faq">FAQ & Other Info</a></li>

		</ul>
	      </dd>
	    </dl>

	   

	    
	    <dl class="dropdown">
	      <dt id="two-ddheader" onmouseover="ddMenu('two',1)" onmouseout="ddMenu('two',-1)">Catalogues</dt>
	      <dd id="two-ddcontent" onmouseover="cancelHide('two')" onmouseout="ddMenu('two',-1)">
		<ul>
		  {foreach from=$departments item=department}
		  <li><a href="department.php?code={$department.code}"  class="underline" >{$department.name}</a></li>
		  {/foreach}
		
		  
		 
		</ul>
	      </dd>
	    </dl>

	      <dl class="dropdown">
	      <dt id="three-ddheader" onmouseover="ddMenu('three',1)" onmouseout="ddMenu('three',-1)">Incentives!</dt>
	      <dd id="three-ddcontent" onmouseover="cancelHide('three')" onmouseout="ddMenu('three',-1)">
		<ul>
		  <li><a href="#" class="underline">First Order Bonus</a></li>
		  <li><a href="#" class="underline">Gold Reward</a></li>
		  <li><a href="#" class="underline">10p Special</a></li>
		  <li><a href="#" class="underline">Megaclearance</a></li>
		  <li><a href="#">BOGOF Page</a></li>
		</ul>
	      </dd>
	    </dl>
	        <dl class="dropdown">
	      <dt id="four-ddheader" onmouseover="ddMenu('four',1)" onmouseout="ddMenu('four',-1)">Inspiration</dt>
	      <dd id="four-ddcontent" onmouseover="cancelHide('four')" onmouseout="ddMenu('four',-1)">
		<ul>
		  <li><a href="#" class="underline">Navigation Item 1</a></li>
		  
		  <li><a href="#" class="underline">Navigation Item 2</a></li>
		  <li><a href="#" class="underline">Navigation Item 3</a></li>
		  <li><a href="#" class="underline">Navigation Item 4</a></li>
		  <li><a href="#">Navigation Item 5</a></li>
		</ul>
	      </dd>
	    </dl>

	  </div>
