<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       ricardoshaffer.com
 * @since      1.0.0
 *
 * @package    Resource_Scheduling
 * @subpackage Resource_Scheduling/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Resource_Scheduling
 * @subpackage Resource_Scheduling/public
 * @author     Ricardo Shaffer <hello@ricardoshaffer.com>
 */
class Resource_Scheduling_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Resource_Scheduling_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Resource_Scheduling_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/resource-scheduling-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Resource_Scheduling_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Resource_Scheduling_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/resource-scheduling-public.js', array( 'jquery' ), $this->version, false );

	}
	/**
 * Create Shortcode for Admin to add the form & function for form. Added by Ricardo Shaffer.
 *
 * @since    1.0.0
 */
public function create_tickets() {
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/resource-scheduling-public-display.php';

	
}

}

// ESTABLISH DATABASE CONNECTION VIA PRIVATE NETWORK
// CHANGE HOST PRIOR TO ACTIVATING
class PDB{
	private $dsn = 'mysql:dbname='.DB_NAME.';host=127.0.0.1';
	private $user = DB_USER;
	private $password = DB_PASSWORD;
protected function db_connect() {
    $pdo = new PDO($this->dsn, $this->user, $this->password);
		try {
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
		return $pdo;

		} catch (PDOException $exception) {
			// If there is an error with the connection, stop the script and display the error.
			exit('Failed to connect to server,refresh the page and try again');
		}
	}
}

// FORM SUBMISSION VALIDATION
class InsertTicket extends ticketValidate {
	// $FORMDATA FED FROM 'VALIDATE' CLASS.
	public function ticketValidator($formData){
		$gump = new GUMP();
		$valid_data = $gump->sanitize($formData);
		
		// validation rules
		$gump->validation_rules([
			'ticket_first_name'	=> 'required|alpha_numeric|max_len,100|min_len,2',
			'ticket_email'	    => 'required|valid_email',
			'ticket_issue'		=> 'required|max_len,500|min_len,6',
			'ticket_request'	=> 'required|max_len,20|contains,install;repair;decommission',
			'ticket_resource'	=> 'required|max_len,50|min_len,2'
			//'ticket_status'		=> 'required|max_len,20|contains,open;closed;in-progress'
		]);
	
		//field-rule specific error messages
		$gump->set_fields_error_messages([
			'ticket_first_name'	=> ['required' => 'Fill the first name field please, its required.'],
			'ticket_email'		=> ['valid_email' => 'hmm... your email doesn\'t pass.'],
			'ticket_issue'		=> ['required' => 'you must tell us about the issue'],
			'ticket_request'	=> ['required' => 'make a valid selection'],
			'ticket_resource'	=> ['required' => 'select a resource, not added? contact someone ASAP.']
			//'ticket_status'		=> ['required' => 'this is internal and you should not see it']
		]);
	
		//filter rules
		$gump->filter_rules([
			'ticket_first_name' => 'trim|sanitize_string|upper_case',
			'ticket_email'		=> 'trim|sanitize_email|upper_case',
			'ticket_issue'		=> 'trim|sanitize_string|ms_word_characters|upper_case',
			'ticket_request'	=> 'trim|sanitize_string|upper_case',
			'ticket_resource'	=> 'trim|sanitize_string|upper_case'
			//'ticket_status'		=> 'trim|sanitize_string|upper_case'
		]);
	
		// on success: returns array with same input structure, but after filters have run
		// on error: returns false
		
		$valid_data = $gump->run($formData);

			if ($gump->errors()) {
		
				$parsedmsg = $gump->get_errors_array();
				$allErrors = json_encode($parsedmsg);

				?>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
				<script type="text/javascript">
				var errorList = <?php echo $allErrors; ?>;
				console.log(errorList);
				$(document).ready(function() {
				for (const [key, value] of Object.entries(errorList)) {
					var errField =( "."+`${key}`);
					var errVal = value;
					console.log(`${key}: ${value}`);
					console.log(errField + errVal);
					$(errField).prepend($("<div class='invalid-feedback'>").append($("<span class='error-text'>").text(errVal)));
			}
		});
			</script><?
			} else{
			if($valid_data === false) {
				$msg = var_dump($gump->get_readable_errors());
				echo'<p>'.$msg.'</p>';
			}
			else{
				$plugin = new TicketValidate();
				$plugin->submitTicket($valid_data);
				
			}
		}
	}
}

// INSERTING INTO TICKETS TABLE
class TicketValidate extends PDB{
	public function submitTicket($valid_data) {
	// $VALID_DATA FED FROM PARTIALS/RESOURCE-SCHEDULING-ADMIN.PHP, WHICH SENDS $_POST BACK
		$stmt = $this->db_connect()->prepare('INSERT INTO tickets(ticket_first_name, ticket_email, ticket_issue, ticket_request, ticket_resource) VALUES (?, ?, ?, ?, ?)');
		try{$stmt->execute([
			$valid_data['ticket_first_name'],
			$valid_data['ticket_email'],
			$valid_data['ticket_issue'],
			$valid_data['ticket_request'],
			$valid_data['ticket_resource'],
		]);

		if($stmt->rowCount() > 0){
		
			echo'
			<div class="alert alert-success alert-dismissible fade show" id="update-ticket-msg" role="alert">
				the ticket was submitted successfully.
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>';
		} 
		}catch (PDOException $e) {
			$dbFail = "something went wrong with your entry";
			if (strpos($e->getMessage(), $dbFail) !== FALSE) {
			} else {
				throw $e;
			}
		}
		exit;
	}
}


