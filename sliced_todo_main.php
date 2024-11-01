<?php

/*
	Plugin Name: A simple todo plugin
	Description: A simple plugin that adds notes/ a todo list to your posts and pages
	Author: Liam Maclachlan
	Author url: http://codebites.io
	Version: 0.0.5
*/

function sliced_todo_enqueue_scripts( $hook ) {

    if ( $hook === 'post.php' ) :

        /*
        * Main JS Files
        */
        wp_enqueue_script( 'sliced_todo_JS', plugins_url( 'sliced-to-do' ) . '/js/script.js' );

        /*
        * Main CSS files
        */
        wp_enqueue_style( 'sliced_todo_CSS', plugins_url( 'sliced-to-do' ) . '/css/style.css' );

	endif;

}
add_action( 'admin_enqueue_scripts', 'sliced_todo_enqueue_scripts' );


// check if meta box has been submited and then save it to the post
function sliced_todo_add_todo_meta( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; // stops audits on autosave
	if ( get_post_status( $post_id ) == 'auto-draft' ) return; // stops audits on auto-draft
	if ( get_post_status( $post_id ) == 'trash' ) return; // stops audits on auto-draft

	if ( isset($_POST['todo_meta_nonce']) 
		&& wp_verify_nonce( $_POST['todo_meta_nonce'], '78o34tycn3984myxt3498tcnu3m948li38947xra4ct9834ymx893m' ) 
		&& current_user_can( 'edit_posts', $post_id ) ) :

		if ( isset($_POST['sliced_todo_list']) ) :
			$items = array_map( 'sanitize_text_field', $_POST['sliced_todo_list'] );
			update_post_meta( $post_id, 'sliced_todo', $items );

		else :
			delete_post_meta( $post_id, 'sliced_todo' );

		endif;

	endif;

}
add_action('save_post', 'sliced_todo_add_todo_meta' );


// Adds Meta box to posts
/////////////////////////
function sliced_todo_meta_box() {
	add_meta_box( 'sliced_todo', 'To Do List', 'sliced_todo_callback', 'post', 'side', 'core' );
}
add_action( 'add_meta_boxes', 'sliced_todo_meta_box' );


// Callback for above function 
//////////////////////////////
function sliced_todo_callback() {
    global $post; ?>

    <form id="sliced_audit_settings_form" method="post">

        <?php wp_nonce_field( '78o34tycn3984myxt3498tcnu3m948li38947xra4ct9834ymx893m', 'todo_meta_nonce' ); ?>
        <ul class="sliced_todo_tabs">
            <li class="sliced_todo_tab sliced_todo_switchable selected"><a href="#">Current</a></li>
        </ul>

        <div class="sliced_todo_container sliced_todo_current_tasks" id="container0">

            <ul class="sliced_todo_list_container sliced_todo_script_anchor"> <?php

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

                else : ?>
                    <p class="no_tasks">You have no tasks for this post!</p> <?php
                endif; ?>
            </ul>

        </div>

        <div id="task-adder" class="wp-hidden-children">
            <a id="task-add-toggle" href="#task-add" class="hide-if-no-js task_add_new">+ Add New Task</a>
            <p id="task-add" class="task-add wp-hidden-child">

                <label class="screen-reader-text" for="newtask">Add New Task</label>
                <label for="stt_ask_title" class="task_field_label">Task Title</label>
                <input name="stt_ask_title" id="stt_ask_title" class="form-required" placeholder="New Task" aria-required="true" type="text">

                <input type="submit" id="sliced_todo_submit" class="button" value="Add New Task">

                <span id="task-ajax-response"></span>

            </p>
        </div>

    <p>Note: save post for changes in task list to tak effect.</p>

    </form><?php

}