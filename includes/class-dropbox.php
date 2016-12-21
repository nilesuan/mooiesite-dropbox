<?php
/**
 * Mooiesite Dropbox Dropbox
 *
 * @since NEXT
 * @package Mooiesite Dropbox
 */

/**
 * Mooiesite Dropbox Dropbox.
 *
 * @since NEXT
 */
class MD_Dropbox {
	/**
	 * Parent plugin class
	 *
	 * @var   class
	 * @since NEXT
	 */
	protected $plugin = null;

	/**
	 * The dropbox app object
	 * @var object
	 * @since  NEXT
	 */
	protected $dropboxapp;

	/**
	 * The dropbox controller object
	 * @var object
	 * @since  NEXT
	 */
	protected $dropbox;

	/**
	 * Dropbox app id
	 * @var string
	 * @since  NEXT
	 */
	protected $dropboxappid;

	/**
	 * Dropbox app secret
	 * @var string
	 * @since  NEXT
	 */
	protected $dropboxappsecret;

	/**
	 * Dropbox access token
	 * @var string
	 * @since  NEXT
	 */
	protected $dropboxaccesstoken;

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
	public function __construct($plugin = null) {

		$this->plugin = $plugin;

		$this->dropboxappid = '1483l1k2n0jj31u';
		$this->dropboxappsecret = 'uik27qnfwidz085';
		$this->dropboxaccesstoken = 'yyEMmbjtTdcAAAAAAAAM_Jo2wcIq0i6Cy6qG4EpgSU3Sal4kfnly9dLEzVu8zMPD';
		
		// setup the dropbox api wrapper
		$this->dropboxapp = new Kunnu\Dropbox\DropboxApp($this->dropboxappid, $this->dropboxappsecret, $this->dropboxaccesstoken);
		$this->dropbox = new Kunnu\Dropbox\Dropbox($this->dropboxapp);

		$this->init();
		$this->createpage();
		$this->hooks();
	}

	public function init() {

		// check if the client folder exists create it if it doesn't exist
		try {
			$this->clientsdir = $this->dropbox->listFolder('/clients');
		} catch (Exception $e) {
			try {
				$this->dropbox->delete('/clients');
			} catch (Exception $e) { }
			$this->clientsdir = $this->dropbox->createFolder('/clients');
		}
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

		// check if the user folder inside clients exists create it if it doesn't exist
		try {
			$this->dropbox->listFolder('/clients/'.$user_id);
		} catch (Exception $e) {
			try {
				$this->dropbox->delete('/clients/'.$user_id);
			} catch (Exception $e) { }
			$this->dropbox->createFolder('/clients/'.$user_id);
		}
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

	public function getuserfiles() {

		$user_id = get_current_user_id();

		// check if the client folder exists create it if it doesn't exist and list files
		try {
			return $this->dropbox->listFolder('/clients/'.$user_id);
		} catch (Exception $e) {
			try {
				$this->dropbox->delete('/clients/'.$user_id);
			} catch (Exception $e) { }
			$this->dropbox->createFolder('/clients/'.$user_id);
			return $this->dropbox->listFolder('/clients/'.$user_id);
		}

	}
}
