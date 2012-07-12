$(document).ready(function() {
	
	$("#form1").submit(function(event) {
		event.preventDefault();

		//regex to match a single word, ignoring trailing/leading whitespace
		var well_formed_word_filter = /^\s*\b\w+\b\s*$/;
 		var word_form_text = $.trim($("#word_field").val());
		
		if( !well_formed_word_filter.test(word_form_text))
		{
			show_error_dialog('Please type in a single word.');
		}
		else
		{
		 	$.post( 'validate_word_ajax.php',{word: word_form_text},
			function(response){
				show_word_suggestions(word_form_text, response);
			},"json"
			 )
			.error(function(){
				show_error_dialog("Error making spellcheck request. Try again later.");	
			});
		}
	
	});

	function show_error_dialog( message){
		var dialog_container= $("#error_dialog");
		dialog_container.html(message);
		dialog_container.dialog(
		{  modal: true, show: 'slide', hide: 'fade', title: 'Oops!',
		   buttons: { "Ok": function() {$(this).dialog("close"); } }
		});
	}

	function show_word_suggestions(word, response){

		var parsed_response = $.parse
		if(response.is_valid_word)
		{		
			$("#valid_word_feedback")
			.html("<i>"+word+"</i> is a valid word, but it's possible that you meant to type:<br /><br />");
		}
		else{
			$("#valid_word_feedback")
			.html("<i>"+word+'</i> is not a valid word in our dictionary.<br />You may have meant:<br /><br />');
		}
		
		var suggestion_html = '';
		
		if(response.suggestions.length==0)
		{
			suggestion_html = "No suggestions available";
		}
		else{
			for(var i=0; i<response.suggestions.length; i++)
			{
				suggestion_html+="<li>"+response.suggestions[i]+"</li>";
			}
		}	
		//set the html to contain suggestions
		$("#suggestion_container").html(suggestion_html);

		$( "#validation_response_container" ).dialog({
					modal: true,
					show: "slide",
					hide: "fade",
					buttons: {
						Ok: function() {
							$( this ).dialog( "close" );
						}
					}
		});
		
	}

	function callback() {
		setTimeout(function() {
			$( "#validation_response_container:visible" ).fadeOut();
		}, 1000 );
	}
});
function show_error_dialog( message){
	var dialog_container= $("#error_dialog");
	dialog_container.html(message);
	dialog_container.dialog(
	{  modal: true, show: 'slide', hide: 'fade', title: 'Oops!',
	   buttons: { "Ok": function() {$(this).dialog("close"); } }
	});
}	

