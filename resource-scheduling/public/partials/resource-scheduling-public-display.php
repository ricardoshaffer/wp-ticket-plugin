<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       ricardoshaffer.com
 * @since      1.0.0
 *
 * @package    Resource_Scheduling
 * @subpackage Resource_Scheduling/public/partials
 */
if(isset($_POST['ticketScheduling'])){
	$ticketSubmit = new InsertTicket();
	$ticketSubmit->ticketValidator($_POST);
};
$reqType= array(
	'INSTALL',
	'DECOMMISSION',
	'REPAIR'
)
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<form class='row' action='' method='post' >
				<div class='mb-3 ticket_first_name'>
					<label for='ticket_first_name'>first name*</label>
					<input type='text' id='ticket_first_name' autocomplete='off' name='ticket_first_name' value=''></input>
				</div>

				<div class='mb-3 ticket_email'>
					<label for='ticket_email'>email*</label>
					<input type='text' id='ticket_email' autocomplete='off' name='ticket_email' value=''></input>
				</div>
                <div class='mb-3 ticket_request'>
					<select name="ticket_request" id="ticket_request">
						<option value="">Select</option>
						<?php
							foreach($reqType as $key => $value):
								echo '<option value="'.$value.'">'.$value.'</option>'; 
							endforeach;
						?>
					</select>
				</div>
                <div class='mb-3 ticket_issue'>
					<label for='ticket_issue'>describe the request</label>
					<input type='text' id='ticket_issue' autocomplete='off' name='ticket_issue' value=''></input>
				</div>
                <div class='mb-3 ticket_resource'>
					<label for='ticket_resource'>enter resource (phone, computer, etc)</label>
					<input type='text' id='ticket_resource' autocomplete='off' name='ticket_resource' value=''></input>
				</div>
				<div class='col-12 g-3'>
					<button class='btn btn-primary' id='ticketScheduling' name='ticketScheduling' type='submit'>Submit</button>
					<a href='?page_id=40'><button class='btn btn-dark' type='button'>CANCEL</button></a>
				</div>
</form>


