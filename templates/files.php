<?php
/**
 * The template for displaying files
 *
 * This is the template that displays all files of the client folder.
 * @since 1.1.0
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
			// get directories
			$dropbox = new MD_Dropbox();
			$userfiles = $dropbox->getuserfiles(); // list folder object
			$files = $userfiles->getItems(); // files object

			if(count($files) === 0) {
				echo 'Your folder is empty!';
			} else {
				foreach($files as $file) {
					echo $file->getName();
					echo '<br />';
				}
			}
		?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>