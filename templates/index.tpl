{include file='header.tpl'}
<div id="bd" style="padding:0px">
	<script src="js/index_tools.js" type="text/javascript"></script>
	<script src="js/index_sliding_tabs.js" type="text/javascript"></script>
 <div id="search" style="border:0px solid black;margin:auto;text-align:center;padding:10px;margin:10px">
    <span  >{t}Search{/t}:</span>
    <input size="45" class="text" id="{$search_scope}_search" value="" state="" name="search"/>
   
    <div id="{$search_scope}_search_Container" style="display:none"></div>
    <div style="position:relative;font-size:80%">
      <div id="{$search_scope}_search_results" style="display:none;background:#fff;border:1px solid #777;padding:10px;margin-top:0px;width:500px;position:absolute;z-index:20;left:-520px">
	<table id="{$search_scope}_search_results_table"></table>
      </div>
    </div>
  </div>

	<div id="wrapper">
		<div id="wid_menu" >
			<img src="images/leftAlt3.png" alt="" id="previous" />
			<ul id="buttons">
				<li>Alpha</li>

				<li>Beta</li>
				<li>Gamma</li>
				<li>Delta</li>
				<li>Epsilon</li>
			</ul>
			<img src="images/rightAlt3.png" alt="" id="next" />
		</div>

		
		<!-- this section has our panes, unfortunately we need two divs to make the effect work -->
		<div id="panes">
			<div id="content">
				<div class="pane">
					<p>This right here is a doodad with the confusing name “Sliding Tabs”. It’s one of those designer thingo’s to make stuff look shiny and new and buzzwords! You can use it on your site, but before you run off with the source code, you’ll need to give Blueberry the Javascripting Pony a gift! A gift you ask? Yes! A gift! It's not free, yaknow. But what kind of gift? Well, the pony is lazy, and wants a motorbike to get around town! She's a pensioner with only a tiny bit of money for clothes and food. You could send some paypal dollars to her and <a href="#" onclick="myTabs.changeTo('bike')">help her get that old posties bike</a>!</p>
					<p>Blueberry the Javascripting Pony is not just any pony though. This pony understands that not everyone has money and not everyone has paypal and not everyone has a carrot in their pocket, and that's okay! If you are one of these moneyless/carrotless people, you could give a different gift! Perhapsedly something physical that comes in a box (whois the domain to find address!), or maybe a poem or font or pretty drawing or a program for her mac! These things can be sent to her <a href="mailto:blue@creativepony.com">email address</a>.</p>

					<p>Blueberry the Javascripting Pony also understands that sometimes designers can get lost in a sea of javascript. Those ones should check out <a href="/journal/scripts/sliding-tabs/">the docs</a>! And if that doesn't help, they should email her too!</p>
				</div>
				<div class="pane" id="bike">
					<!--<div style="width: 234px; margin: 0 auto 0 auto;"><object width="234" height="60"><param name="movie" value="http://widget.chipin.com/widget/id/ed8a64d639d69720"></param><param name="allowScriptAccess" value="always"></param><param name="wmode" value="transparent"></param><param name="color_scheme" value="brown"></param><embed src="http://widget.chipin.com/widget/id/ed8a64d639d69720" flashVars="color_scheme=brown" type="application/x-shockwave-flash" allowScriptAccess="always" wmode="transparent" width="234" height="60"></embed></object></div>--><p>Test Test... 1, 2, 3...</p>
				</div>
				<div class="pane">
					<p>There once was a pony who felt rather lonely, she wanted some friends to come play! She whinnied and blustered in to the breeze and the horses at work for a moment did freeze. They all wanted fun in the lovely bright sun, but the kiddies need rides to be pleased!<a href="#" onclick="myTabs.changeTo('rhyme2')">…</a></p>

				</div>
				<div class="pane" id="rhyme2">
					<p>She jumped over the fence with a playful defence, and the kids did they scream for the horse was now free, looking around, near a tree. And the children were out in the open about. Everyone froze, ears focused on pony as she wandered right down near the seating. She nickered and sniffed at the air near their chests, looking for carrots within them. But none to be found, kids pulled at her mane rather abruptly<a href="#" onclick="myTabs.changeTo('rhyme3')">…</a></p>
				</div>
				<div class="pane" id="rhyme3">
					<p>She snorted and ran from the grabby grab hands! There was nothing but dust moments later. The pony was gone and the kids were alone with the horses and teachers, all silenced. And then came a day when designers did say “Hey, there's hoofprints all over my code here!” and the question remains unanswered to this very day, as the clippity clop on a keyboard does knock, why a pony would javascript anyway!</p>
				</div>

			</div>
		</div>
	</div>
	{literal}
	<script type="text/javascript" charset="utf-8">
		window.addEvent('load', function () {
			myTabs = new SlidingTabs('buttons', 'panes');
			
			// this sets up the previous/next buttons, if you want them
			$('previous').addEvent('click', myTabs.previous.bind(myTabs));
			$('next').addEvent('click', myTabs.next.bind(myTabs));
			
			// this sets it up to work even if it's width isn't a set amount of pixels
			window.addEvent('resize', myTabs.recalcWidths.bind(myTabs));
		});
	</script>
{/literal}

</div>
{include file='footer.tpl'}
