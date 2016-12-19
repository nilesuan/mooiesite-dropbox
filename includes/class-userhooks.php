<?php
/**
 * Mooiesite Dropbox Userhooks
 *
 * @since NEXT
 * @package Mooiesite Dropbox
 */

/**
 * Mooiesite Dropbox Userhooks.
 *
 * @since NEXT
 */
class MD_Userhooks {
	/**
	 * Parent plugin class
	 *
	 * @var   class
	 * @since NEXT
	 */
	protected $plugin = null;

	/**
	 * Path of the clients folder
	 * @var string
	 * @since  NEXT
	 */
	protected $clientsdir;

	/**
	 * Slug of the page that lists the files
	 * @var string
	 * @since  NEXT
	 */
	protected $listfilepageslug = 'files';

	/**
	 * Title of the page that lists the files
	 * @var string
	 * @since  NEXT
	 */
	protected $listfilepagetitle = 'List Files';

	/**
	 * Constructor
	 *
	 * @since  NEXT
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->init();
		$this->createpage();
		$this->hooks();
	}

	public function init() {
    	$upload_dir = wp_upload_dir();
    	$this->clientsdir = $upload_dir['basedir'] . '/clients/';
    	if(!file_exists($this->clientsdir))
    		wp_mkdir_p($this->clientsdir);
	}

	/**
	 * Create list files page if it doesn't exist
	 * @since NEXT
	 * @return void
	 */
	public function createpage() {
		if(get_page_by_title($this->listfilepagetitle, OBJECT, 'page') === NULL) {
	        $createPage = array(
	          'post_title'    => $this->listfilepagetitle,
	          'post_content'  => '',
	          'post_status'   => 'publish',
	          'post_author'   => 1,
	          'post_type'     => 'page',
	          'post_name'     => $this->listfilepageslug
	        );

	        // Insert the post into the database
	        wp_insert_post( $createPage );
        }
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function hooks() {
		add_action('user_register', array($this, 'createuserdir')); // create user directory upon registration
		add_filter('template_include', array($this, 'listfilepagetemplate'), 99 ); // list files in the user directory
	}

	/**
	 * Create user directory upon user registration
	 * @since NEXT
	 * @param  int $user_id
	 * @return void
	 */
	public function createuserdir($user_id) {
    	$userdir = $this->clientsdir.$user_id;
    	if(!file_exists($userdir))
    		wp_mkdir_p($userdir);
	}

	/**
	 * Change the page template if the page matches the one that lists files
	 * @since  NEXT
	 * @param  object $template wWrdpress template object
	 * @return object
	 */
	public function listfilepagetemplate($template) {
		if(is_page($this->listfilepageslug) && file_exists($this->plugin->path.'templates/'.$this->listfilepageslug.'.php'))
			return $this->plugin->path.'templates/'.$this->listfilepageslug.'.php';

		return $template;
	}
}
