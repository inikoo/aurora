
$(document).ready(function(){
  $(".comment_table").find('textarea, input:text').blur(function () {
	if ($(this).val() != ''){
		$(this).removeClass("required");
	}
  });	
  $("#submit").click(function(){
	  var anyBlank = 0;
	  $(".comment_table").find('textarea, input:text').each(function () {
		if ($(this).val() == ''){
			$(this).addClass("required");
			anyBlank = 1;
		}
	  });	
	  if(anyBlank == "0")
	  {
		  var name    = $("#name").val();
		  var email   = $("#email").val();
		  var comment = $("#comment").val();
		  comment = comment.replace(/\n\r?/g, '<br />');
		  $("#loading").css("visibility","visible");
			$.ajax({
			   type: "POST",
			   url: "ajax_server.php",
			   data: "name="+name+"&email="+email+"&comment="+comment,
			   success: function(date_added){
				  if(date_added != 0)
				   {
					   structure = '<div align="center"><div class="comment_holder"><div id="photo"><img src="images/user.JPG"><br>'+name+'</div><div id="comment_text"><div id="date_posted">'+date_added+'</div>'+comment+'</div></div></div>';				  	
					   $(".no_comments").fadeOut("slow");
					   $("#ajax_response").prepend(structure);
					   $(".comment_table").find('textarea, input:text').each(function () {
						   $(this).val("");
					   });
				   }
				  else
					  alert("Unexpected error...!");
  					  $("#loading").css("visibility","hidden");
			   }
			 });
	  }
  });
  $("#ajax_response").mouseover(function(){
	 $(this).find(".comment_holder").mouseover(function(){
		$(this).addClass("highlight");
	 });
  });
  $("#ajax_response").mouseout(function(){
	 $(this).find(".comment_holder").mouseout(function(){
		$(this).removeClass("highlight");
	 });
  });
});