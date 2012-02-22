// -----------------------------------------------------------------------------------
// http://wowslider.com/
// JavaScript Wow Slider is a free software that helps you easily generate delicious 
// slideshows with gorgeous transition effects, in a few clicks without writing a single line of code.
// Last updated: 2011-10-27
//
//***********************************************
// Obfuscated by Javascript Obfuscator
// http://javascript-source.com
//***********************************************
function ws_blinds(c,b,h){var f=jQuery;var e=3;b.each(function(i){if(i){f(this).hide()}});var a=f("<div></div>");a.css({position:"absolute",width:c.width+"px",height:c.height+"px",left:(c.outWidth-c.width)/2+"px",top:(c.outHeight-c.height)/2+"px"});h.append(a);var g=[];for(var d=0;d<e;d++){g[d]=f("<div></div>")}f(g).each(function(i){f(this).css({position:"absolute","z-index":2,"background-repeat":"no-repeat",height:"100%",border:"none",margin:0,top:0,left:Math.round(100/e)*i+"%",width:((i<e-1)?Math.round(100/e):100-Math.round(100/e)*(e-1))+"%"});a.append(this);a.hide()});this.go=function(m,o,k){var l=o>m?1:0;if(k){if(k<=-1){m=(o+1)%b.length;l=0}else{if(k>=1){m=(o-1+b.length)%b.length;l=1}else{return -1}}}for(var n=0;n<g.length;n++){g[n].stop(true,true)}function j(r,s){var t=g[r];var i=t.width();var q=b.get(m);t.css({"background-position":(!l?(-f(q).width()):(f(q).width()-t.position().left))+"px 0","background-image":"url("+q.src+")"});t.animate({"background-position":-t.position().left+"px 0"},(c.duration/(g.length+1))*(l?(g.length-r+1):(r+2)),s)}function p(){b.hide();f(b.get(m)).show();a.hide();f(g).each(function(){f(this).css({"background-image":"none"})})}a.show();for(var n=0;n<g.length;n++){j(n,(!l&&n==g.length-1||l&&!n)?p:null)}return m}}(function(b){if(!document.defaultView||!document.defaultView.getComputedStyle){var d=b.curCSS;b.curCSS=function(g,e,h){if(e==="background-position"){e="backgroundPosition"}if(e!=="backgroundPosition"||!g.currentStyle||g.currentStyle[e]){return d.apply(this,arguments)}var f=g.style;if(!h&&f&&f[e]){return f[e]}return d(g,"backgroundPositionX",h)+" "+d(g,"backgroundPositionY",h)}}var c=b.fn.animate;b.fn.animate=function(e){if("background-position" in e){e.backgroundPosition=e["background-position"];delete e["background-position"]}if("backgroundPosition" in e){e.backgroundPosition="("+e.backgroundPosition}return c.apply(this,arguments)};function a(f){f=f.replace(/left|top/g,"0px");f=f.replace(/right|bottom/g,"100%");f=f.replace(/([0-9\.]+)(\s|\)|$)/g,"$1px$2");var e=f.match(/(-?[0-9\.]+)(px|\%|em|pt)\s(-?[0-9\.]+)(px|\%|em|pt)/);return[parseFloat(e[1],10),e[2],parseFloat(e[3],10),e[4]]}b.fx.step.backgroundPosition=function(f){if(!f.bgPosReady){var h=b.curCSS(f.elem,"backgroundPosition");if(!h){h="0px 0px"}h=a(h);f.start=[h[0],h[2]];var e=a(f.end);f.end=[e[0],e[2]];f.unit=[e[1],e[3]];f.bgPosReady=true}var g=[];g[0]=((f.end[0]-f.start[0])*f.pos)+f.start[0]+f.unit[0];g[1]=((f.end[1]-f.start[1])*f.pos)+f.start[1]+f.unit[1];f.elem.style.backgroundPosition=g[0]+" "+g[1]}})(jQuery);// -----------------------------------------------------------------------------------
// http://wowslider.com/
// JavaScript Wow Slider is a free software that helps you easily generate delicious 
// slideshows with gorgeous transition effects, in a few clicks without writing a single line of code.
// Last updated: 2011-10-27
//
//***********************************************
// Obfuscated by Javascript Obfuscator
// http://javascript-source.com
//***********************************************
jQuery("#wowslider-container1").wowSlider({effect:"blinds",prev:"",next:"",duration:20*100,delay:20*100,outWidth:960,outHeight:360,width:960,height:360,autoPlay:true,stopOnHover:false,loop:true,bullets:true,caption:true,controls:true});