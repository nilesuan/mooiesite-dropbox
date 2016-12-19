<?php
/**
 * The template for displaying files
 *
 * This is the template that displays all files of the client folder.
 * @since NEXT
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
			// get directories
			$currentuser = get_current_user_id();
			$uploadsdir = wp_upload_dir();
			$clientsdir = $uploadsdir['basedir'].'/clients/'.$currentuser.'/';
			$files = array_diff(scandir($clientsdir), array('..', '.'));

			if(!file_exists($clientsdir)) {
				echo 'Your client folder does not exist!';
			} else if($files === null) {
				echo 'You have no files inside your folder.';
			} else {
				foreach($files as $file) {
					echo $file.'<br />';
				}
			}
		?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>