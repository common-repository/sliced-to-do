jQuery(document).ready(function($) {

	// add cancel button to the todo controls

	/*
	* Prevent tool anchors causing page reload 
	*/
	$('.sliced_todo_tabs').on('click', 'a', function(e){
		e.preventDefault()
	})

	/*
	* Tools script (edit/delete/save)
	*/
	$('.sliced_todo_script_anchor').on('click', '.todo_task_controls a', function(e){

		e.preventDefault()
		
		var toplevel = $(this).parents('li')
		var action = $(this).attr('data-action')

		switch(action) {
			case 'edit':

				$('.todo_input').each(function() {
					$(this).hide()
				})

				$('.todo_task_title').each(function() {
					$(this).show()
					.siblings('.todo_task_controls')
					.children('.todo_action_save').hide()
					.siblings('.todo_action_edit').show()
				})

				// Hide edit button
				$(this).hide()
				// Show save button
				.siblings('.todo_action_save').show()
				// Locate and show relevant input field
				.parent().prev('.todo_input').css('display', 'inline-block').focus()
				// Hide the relevant p tag
				.prev('p').hide()

				break

			case 'save':

				newValue = toplevel.children('.todo_input').val()	

				if (newValue == '' ) return	

				// hide save button
				$(this).hide()
				// Show sibling edit button
				.siblings('.todo_action_edit').show()
				// Locate and show relevant input field
				.parent().prev('.todo_input').css('display', 'none')				
				// Hide the relevant p tag
				.prev('p').show()

				// assign values to inputs that are pased on form submission
				var newValue = toplevel.children('.todo_input').val()
				// Assign new value to hidden field
				toplevel.children('.todo_hidden').attr('value', newValue)
				// Assign new value to list item that is visible
				.siblings('.todo_task_title').text(newValue)

				break
				 
			case 'delete':
				$(this).parents('li').remove()

				if ( !$('.todo_task_single').length  ) {

					if ( $('.no_tasks').length ) {
						$('.no_tasks').show()

					} else {
						$('.sliced_todo_script_anchor').html('<p class="no_tasks">You have no tasks for this post!</p>')
					}
				}
				break
		
		}

	});

	
	/*
	* helper function that updates list with new item
	*/
	function append_new_task() {


		var new_task = $task_title.val();
		var $no_task = $('.no_tasks');

		console.log(new_task);

		console.log(1);

		if ( $.trim(new_task) !== '' ) {
            console.log(2);
			// Get the value of the inputed task

			// create html string to append to the list
			var text = '<li class="todo_task_single">';
				text += '<p class="todo_task_title">' + new_task + '</p>';
				text += '<input class="todo_input" type="text" value="' + new_task + '">';
				text += '<span class="todo_task_controls">';
					text += '<a class="todo_action_edit" data-action="edit">edit</a> ';
					text += '<a class="todo_action_save" data-action="save">save</a> ';
					text += '<a class="todo_action_delete" data-action="delete">delete</a>';
				text += '</span>';
				text += '<input class="todo_hidden" name="sliced_todo_list[]" type="text" value="' + new_task + '">';
			text += '</li>';

			// append the item to the list
			$('.sliced_todo_script_anchor').append(text)


            console.log(3);
			if ( $no_task.length )
                $no_task.hide()
			
		}

	}


    var $task_title = $('#stt_ask_title');

	/*
	* fires with new task button
	*/
	$('.task_add_new').on('click', function(e){
		e.preventDefault();

		// Show the form the allow additions to the list
		$(this).parent().toggleClass('wp-hidden-children');

		// Focus on the text title input field
        $task_title.focus();

	});


	/*
	* fires with return key hit
	*/
	$('#stt_ask_title').on('keypress', function(e){

		if ( e.which == 13 ) {
				e.preventDefault()

			var value = $(this).val();

			if ( $.trim(value) !== '' ) {
				append_new_task();
				$(this).val('');
			}
  		}

	});


	/*
	* fires with new task
	*/
	$('#sliced_todo_submit').on('click', function(e) {
		e.preventDefault();
		append_new_task();

	});


	/*
	* Updates field being edited on return key being struck
	*/
	$('.sliced_todo_script_anchor').on('keypress', '.todo_input', function(e) {


		if ( e.which == 13 ) {
			e.preventDefault()

			var newValue = $(this).val()

			// Hide input field
			$(this).hide()
			// Change task title and reveal it
			.siblings('.todo_task_title').text(newValue).show()
			// Change value of hidden field to new value
			.siblings('.todo_hidden').attr('value', newValue)
			// Locate controls
			.siblings('.todo_task_controls')
			// show edit button
			.children('.todo_action_edit').show()
			// hide save button
			.siblings('.todo_action_save').hide()
  		}

	})

})























