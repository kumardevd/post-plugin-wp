<?php
/**
 * @package Front End Post
 * @version 1.0.0
 */
/*
Plugin Name: Front End Post
Plugin URI: 
Description: This plugin create post from front end loging user.
Author: WP develper
Version: 1.0.0
Author URI: 
*/

define('WPDEVAPFSURL', WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ) );
define('WPDEVAPFSPATH', WP_PLUGIN_DIR."/".dirname( plugin_basename( __FILE__ ) ) );

add_shortcode( 'wpdev_frontend_post', 'wpdev_frontend_post' );
function wpdev_frontend_post() {

	wpshout_save_post_if_submitted();
	if (!is_user_logged_in()){
		echo '<h5 style="text-align:center;">You are not authorized user. Please login<h5>';
		return;
	}else{

    ?>

<div class="col-sm-12">
	<h3>Add New Post</h3>
	<form class="form-horizontal" id="post-submission-form" name="form" method="post" enctype="multipart/form-data">
		<input type="hidden" name="ispost" value="1" />
		<input type="hidden" name="userid" value="" />
		<div class="col-md-12">
			<label class="control-label">Title</label>
			<input type="text" id="post-submission-title" class="form-control" name="title" />
		</div>

		<div class="col-md-12">
			<label class="control-label">Content</label>
			<textarea class="form-control" id="post-submission-content" rows="8" name="sample_content"></textarea>
		</div>
		
		<div class="col-md-12">
			<label class="control-label">Excerpt</label>
			<textarea class="form-control" id="post-submission-excerpt" rows="2" name="excerpt"></textarea>
		</div>

		<p><?php wp_dropdown_categories( 'show_option_none=Category&tab_index=4&taxonomy=category' ); ?></p>

		<div class="col-md-12">
			<label class="control-label">Upload Post Image</label>
			<input type="file" name="sample_image" class="form-control" />
		</div>

		<div class="col-md-12">
			<input type="submit" class="btn btn-primary" value="SUBMIT" name="submitpost" />
		</div>
	</form>
	<div class="clearfix"></div>
</div>

    <?php
} }

function wpshout_save_post_if_submitted() {
	
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		if(is_user_logged_in())
		{
				global $current_user;
				get_currentuserinfo();

				$user_login = $current_user->user_login;
				$user_email = $current_user->user_email;
				$user_firstname = $current_user->user_firstname;
				$user_lastname = $current_user->user_lastname;
				$user_id = $current_user->ID;

				$post_title = $_POST['title'];
				$sample_image = $_FILES['sample_image']['name'];
				$post_content = $_POST['sample_content'];
				$category = $_POST['category'];

				$new_post = array(
					'post_title' => $post_title,
					'post_content' => $post_content,
					'post_status' => 'pending',
					'post_name' => 'pending',
					'post_type' => $post_type,
					'post_category' => $category,
					'post_author'       => '1'
					
				);

				$pid = wp_insert_post($new_post);
				add_post_meta($pid, 'meta_key', true);

				if (!function_exists('wp_generate_attachment_metadata'))
				{
					require_once(ABSPATH . "wp-admin" . '/includes/image.php');
					require_once(ABSPATH . "wp-admin" . '/includes/file.php');
					require_once(ABSPATH . "wp-admin" . '/includes/media.php');
				}
				if ($_FILES)
				{
					foreach ($_FILES as $file => $array)
					{
						if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK)
						{
							return "upload error : " . $_FILES[$file]['error'];
						}
						$attach_id = media_handle_upload( $file, $pid );
					}
				}
				if ($attach_id > 0)
				{
					//and if you want to set that image as Post then use:
					update_post_meta($pid, '_thumbnail_id', $attach_id);
				}

				//$my_post1 = get_post($attach_id);
				//$my_post2 = get_post($pid);
				//$my_post = array_merge($my_post1, $my_post2);
				echo "<h4 style='text-align:center;'>create post successfully</h4>";
		}
		else
		{
			echo "<h2 style='text-align:center;'>User must be login for add post!</h2>";
		}
	}
}

?>