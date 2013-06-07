//declare a group array
var wmgroups = new Array();

jQuery('document').ready(function($){

	//add new group fucntion
	$('#wm-add').click(function(){
		//get selected group value
		var selectedGroup = $('#wm-groups').val();

		//get selected group text
		var selectedText = $('#wm-groups :selected').text();

		//check if current group already exists then return
		if ( $.inArray(selectedGroup, wmgroups) > -1 ) {
			alert('This group already added. Please add other group without this.');
			return;
		}

		//push current group in group array
		wmgroups.push(selectedGroup);

		//append new group object
		$('#wm-btn-groups').append('<a class="button" data-val="' + selectedGroup + '" href="#" title="click for remove">' + selectedText + '</a>');
	});

	//add function for remove a group
	$('#wm-btn-groups a.button').live('click', function(event){
		//prevent navigation link
		event.preventDefault();

		//get removed group value
		var removeGroup = $(this).attr('data-val');

		//remove select group value from array
		wmgroups.splice(wmgroups.indexOf(removeGroup), 1);

		//remove selected group
		$(this).remove();
	});

	//add function for insert wm-shortcode
	$('#wm-insert').click(function(){

		//start shortcode value
		var shortcodeText = '[wmpost';

		//add title if title value if exists
		var wm_title = $('#wm-title').val();
		if ( wm_title.length > 3 )
			shortcodeText += ' title="' + wm_title + '"';

		//add groups to shortcode if user added groups
		if ( wmgroups.length > 0 ) {
			shortcodeText += ' groups="';
			for (var i = 0; i < wmgroups.length; i++) {
				if (i > 0 ) shortcodeText += ',';
				shortcodeText += wmgroups[i];
			};
			shortcodeText += '"';
		}

		//activate excerpt value if user select show excerpt checkbox
		if ( $('#wm-show-excerpt').is(':checked') )
			shortcodeText += ' excerpt=true';

		//show alert box if user not input integer value
		var showpostsVal = $('#wm-showposts').val();
		if ( showpostsVal.length > 0 && !$.isNumeric(showpostsVal) ) {
			$('#wm-showposts').focus();
			alert('Please input number value.');
			return
		}

		//activate showposts value if user input integer value
		if ( showpostsVal.length > 0 )
			shortcodeText += ' showposts=' + showpostsVal;

		//activate faq option if user checked faq checkbox
		if ( $('#wm-faq').is(':checked') )
			shortcodeText += ' faq=true';

		//close shortcode text
		shortcodeText += ']'

		//print shortcode to activated text editor
		window.send_to_editor(shortcodeText);

		//hide wm-child-post shortcode container
		$('#wm-container').hide(100);
	})

	//add function for hide wm-shortcode container
	$('#wm-child-post .wm-close-btn').click(function(){
		//hide wm-child-post shortcode container
		$('#wm-container').hide(100);
	})
});