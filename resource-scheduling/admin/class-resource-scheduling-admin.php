<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       ricardoshaffer.com
 * @since      1.0.0
 *
 * @package    Resource_Scheduling
 * @subpackage Resource_Scheduling/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Resource_Scheduling
 * @subpackage Resource_Scheduling/admin
 * @author     Ricardo Shaffer <hello@ricardoshaffer.com>
 */
class Resource_Scheduling_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/resource-scheduling-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'jquery-ui-datepicker-style' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_register_style('prefix_bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css');
		wp_enqueue_style('prefix_bootstrap');
		wp_register_style('prefix_datatables', '//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css');
		wp_enqueue_style('prefix_datatables');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/resource-scheduling-admin.js', array( 'jquery' ), $this->version, false );
		wp_register_script('prefix_bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js');
		wp_enqueue_script('prefix_bootstrap');
		wp_register_script('prefix_datatables', '//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js');
		wp_enqueue_script('prefix_datatables');
		wp_register_script('prefix_momentjs', '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js');
		wp_enqueue_script('prefix_momentjs');
	}
	
	/*
	* Register the settings page for the admin area.
	*
	* @since    1.0.0
	*/
	
	public function admin_page() {
		// Create settings page as a menu page.
		 add_menu_page(
			 __('Resource Scheduling'), // Page Title
			 __('Resource Scheduling'), // Menu Title
			 'manage_options', // CRUD Capability 
			 'resource-scheduling',  // Slug
			 array( $this, 'display_scheduling_page' )  // callable function
		 );
		}

	 /**
  * Display the settings page content
  *
  * @since    1.0.0
  */
  public function display_scheduling_page() {
 
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/resource-scheduling-admin-display.php';

	}
}
// ESTABLISH DATABASE CONNECTION VIA PRIVATE NETWORK
// CHANGE HOST PRIOR TO ACTIVATING
class Dbh{
	private $dsn = 'mysql:dbname='.DB_NAME.';host=127.0.0.1';
	private $user = DB_USER;
	private $password = DB_PASSWORD;
	protected function db_connect() {
	// added additional Array below to attr_init_command to allow 'group by' within the SQL.
    	$pdo = new PDO($this->dsn, $this->user, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="TRADITIONAL"') );
		try {
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
		return $pdo;

		} catch (PDOException $exception) {
			// If there is an error with the connection, stop the script and display the error.
			exit('Failed to connect to database!- if you installed, make sure you have updated plugin code @ resource-scheduling/admin/class-resource-scheduling-admin.php from 127.0.0.1 to  e.g.(DB_HOST)');
		}
	}
}

class Retrieve extends Dbh{
	public function retrieveTickets() {
		$parseString = new ParseArray();

	// READING RESULTS AND ITERATION VIA DATATABLES
		$sql = 'SELECT *,COUNT(ticket_ID) as counter,JSON_ARRAYAGG(JSON_OBJECT( "note_id", note_id,"note_description",note_description,"note_updatedAt", note_updatedAt,"ticket_ID", ticket_ID)) as ticketNote FROM tickets Left JOIN ticket_notes ON ticket_notes.ticket_ID = tickets.ID GROUP BY ID';
		$stmt = $this->db_connect()->query($sql);
		while($row = $stmt->fetch()){
			$results[] = $row;
		}
		$allTickets = json_encode($results);
		$parseString->parseArrayF($allTickets);
	}
}


class ParseArray extends Retrieve {
	public function parseArrayF($allTickets) {
	?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script type="text/javascript">
		// LIST OF ALL TICKETS
			var tList =<?php echo $allTickets; ?>;
			var ticketDiv = $("#ticketRow");
			var tktUl =$(".ticketNotes");
			console.log(tList);
			$('.update-ticket-form').hide();
			
			for (var i = 0; i < tList.length; i++) {
				ticket_status = '';
				if(!tList[i].ticket_status || tList[i].ticket_status == null){
					ticket_status = 'NEW'
				}else{
					ticket_status = tList[i].ticket_status;
				}
				ticketDiv.append(
				$('<tr>').attr("id", "ticketID-"+tList[i].ID).append(
					$('<td>').append(tList[i].ticket_first_name),
					$('<td>').append(moment(tList[i].ticket_createdAt).calendar()),//format("MM/DD/yyyy hh:mm A")),
					$('<td>').attr('class', 'status-'+ticket_status).append('<span>'+ticket_status+'<span class="badge bg-custom">'+tList[i].counter+'</span>'),
					$('<td>').append(tList[i].ticket_request),
					$('<td>').append(
						$('<button>').
						attr({
							"value": tList[i].ID,
							"type" : "submit",
							"class": "btn btn-primary toggle_ticket",
							"role": "button",
							"id": 'ticket_view_button_'+tList[i].ID
						}).html("View / Update")
					))
				)

				// DETAILED VIEW OF TICKET DATA
				$('#ticket_ID').attr('value', tList[i].ID);
				tktUl.prepend(
					$('<tr class="row unique-ticket-row">').attr( 'id','ticket_container_view_'+ tList[i].ID).hide().append(
						$('<td class="col-7">').append(
							$('<ul class="list-group">').attr("id",'ticketNote-' + tList[i].ID)
						),
						$('<td class="col-5">').append(
							$('<p>').append('<span class="label">Submitted By:</span>'+tList[i].ticket_first_name),
							$('<p>').append('<span class="label">Submitted On:</span>'+moment(tList[i].ticket_createdAt).format("MM/DD/yyyy hh:mm A")),
							$('<p>').append('<span class="label">Ticket Status:</span>'+ticket_status),
							$('<p>').append('<span class="label">Request Type:</span>'+tList[i].ticket_request),
							$('<p>').append('<span class="label">Request Issue:</span>'+tList[i].ticket_issue),
							$('<p>').append('<span class="label">Resource:</span>'+tList[i].ticket_resource)
						)
					)
					
				);
				// TOGGLE TICKET VIEW TO BLOCK EXTENDING LIST.
				$('[id^="ticket_view_button_"]').on('click', function() {
					$('[id^="ticket_container_"]').hide();
						var notesVal ="#ticket_container_view_" + $(this).val();
							$(notesVal).show();
							$('#ticket_ID').attr('value',$(this).val()); // SETS VALUE OF THE TICKET ID BEING UPDATED.
							$('.update-ticket-form').show()
					console.log(notesVal);
					});
				var tktID =tList[i].ID;
				var nArr =[];
				nArr = JSON.parse(tList[i].ticketNote);
				console.log(nArr);
					for (var z = 0; z < nArr.length; z++) {
						if(nArr[z].note_id ==null){
							$('#ticketNote-'+tList[i].ID).innerText('It looks like there are no updates!')
							;
						}else{
							let ticketUpdated =  moment(nArr[z].note_updatedAt).calendar();
							$('#ticketNote-'+nArr[z].ticket_ID).append(
								$('<li class="list-group-item">').append(
									$('<p>').append( "entered at: ", ticketUpdated).prepend(
										$('<span class="ticket-note">').append(nArr[z].note_description))
									)
							); // matches the ticket for ID to the list item with the corresponding ID.
						}
					}
			};
			console.log(tList);
		</script>
	<?php

	}
}

// FORM SUBMISSION VALIDATION
class InsertNote extends AdminValidate {
	// $FORMDATA FED FROM 'VALIDATE' CLASS.
	public function validator($formData){
		$gump = new GUMP();
		$valid_data = $gump->sanitize($formData);
		
		// validation rules
		$gump->validation_rules([
			'ticket_ID'			=> 'required|integer',
			'note_description'	=> 'required|max_len,1000|min_len,6',
			'ticket_status'		=> 'required|max_len,20|contains,IN-PROGRESS;CLOSED',
		]);
		//field-rule specific error messages
		$gump->set_fields_error_messages([
			'ticket_ID'			=> ['required' => 'hmm... it looks like something went wrong; refresh the page and try again.'],
			'note_description'	=> ['required' => 'you must state what you\'ve updated.'],
			'ticket_status'		=> ['required' => 'The ticket status must be either In-progress or closed']
		]);
		//filter rules
		$gump->filter_rules([
			'ticket_ID'			=> 'trim',
			'note_description'	=> 'trim|sanitize_string|ms_word_characters|upper_case',
			'ticket_status'		=> 'trim|upper_case'
		]);
		// on success: returns array with same input structure, but after filters have run
		// on error: returns false
		$valid_data = $gump->run($formData);
		$msg ='';
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
					$("#ticketsTable_wrapper").prepend($("<div class='alert alert-danger' role='alert'>").text(errVal).prepend('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'));
			}
		});
			</script><?
			} else
			{if($valid_data === false) {
				$msg = var_dump($gump->get_readable_errors());
				echo'<p>'.$msg.'</p>';
			}
			else{
				$this->submitNote($valid_data);
			}
		}
	}
}
// INSERTING INTO TICKETS TABLE
class AdminValidate extends Dbh{
	public function submitNote($valid_data) {
	// $VALID_DATA FED FROM PARTIALS/RESOURCE-SCHEDULING-ADMIN.PHP, WHICH SENDS $_POST BACK
		$status= $valid_data['ticket_status'];
		$id =$valid_data['ticket_ID'];
        $updt = $this->db_connect()->prepare("INSERT INTO ticket_notes(ticket_ID, note_description) VALUES (?, ?) ; UPDATE tickets SET ticket_status = '$status' WHERE ID = '$id'");
		try{$updt->execute([
				$valid_data['ticket_ID'],
				$valid_data['note_description']
			]);
			if($updt->rowCount() > 0){
				echo'
				<div class="alert alert-success alert-dismissible fade show" id="update-ticket-msg" role="alert">
					the ticket was updated successfully.
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			  </div>';
			} 
	}catch (PDOException $e) {
			$dbFail = "something went wrong with your entry";
			if (strpos($e->getMessage(), $dbFail) !== FALSE) {
		
				// Take some action if there is a key constraint violation, i.e. duplicate name
			} else {
				throw $e;
			}
		}
	}
}


