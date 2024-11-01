<?php 
// Adds Plan B email box
////////////////////////
function sliced_todo_meta_box() {
	add_meta_box( 'sliced_todo', 'To Do List', 'sliced_todo_callback', array('post', 'page'), 'side', 'core' );
}
add_action( 'add_meta_boxes', 'sliced_todo_meta_box' );


// Callback for above function (Plan B email)
/////////////////////////////////////////////
function sliced_todo_callback() {
		global $post; ?>
	
		<form id="sliced_audit_settings_form" method="post">

			<?php wp_nonce_field( '78o34tycn3984myxt3498tcnu3m948li38947xra4ct9834ymx893m', 'todo_meta_nonce' ); ?>

			<ul class="sliced_todo_tabs">
				<li class="sliced_todo_tab sliced_todo_switchable selected"><a href="#here">Current</a></li>
			</ul>

			<div class="sliced_todo_container sliced_todo_current_tasks" id="container0">

				<ul class="sliced_todo_list_container sliced_todo_script_anchor">

						<?php 
							$todo_items = get_post_meta( $post->ID,'sliced_todo', true ); 

							if ( $todo_items ) :

							foreach ( $todo_items as $item ) : ?>

								<li class="todo_task_single">
									<p class="todo_task_title"><?php echo $item ?></p>
									<input class="todo_input" type="text" value="<?php echo $item ?>">
									<span class="todo_task_controls"><a class="todo_action_edit" data-action="edit">edit</a> <a class="todo_action_save" data-action="save">save</a> <a class="todo_action_delete" data-action="delete">delete</a></span>
									<input class="todo_hidden" name="sliced_todo_list[]" type="text" value="<?php echo $item ?>">
								</li>

							<?php endforeach;

							else : 

								echo '<p class="no_tasks">You have no tasks for this post!</p>';

							endif; 

						?>
				</ul>

			</div>

			<div id="task-adder" class="wp-hidden-children">
				<a id="task-add-toggle" href="#task-add" class="hide-if-no-js task_add_new">+ Add New Task</a>
				<p id="task-add" class="task-add wp-hidden-child">

					<label class="screen-reader-text" for="newtask">Add New Task</label>
					<label for="task_title" class="task_field_label">Task Title</label>
					<input name="task_title" id="task_title" class="form-required" placeholder="New Task" aria-required="true" type="text">

					<input type="submit" id="sliced_todo_submit" class="button" value="Add New Task">

					<span id="task-ajax-response"></span>

				</p>
			</div>

		</form>

<? }


// function that updates the meta data from the meta boxes above
////////////////////////////////////////////////////////////////
function sliced_check_post_meta( $post_id, $audit_id ) {
	/* Verify the nonce before proceeding. */
	if ( 
		!isset( $_POST['sliced_audit_meta_nonce'] )
		|| !wp_verify_nonce( 
			$_POST['sliced_audit_meta_nonce'], 
			'78o34tycn3984myxt3498tcnu3m948li38947xra4ct9834ymx893m' 
		) 
	) return $post_id;

}


?>