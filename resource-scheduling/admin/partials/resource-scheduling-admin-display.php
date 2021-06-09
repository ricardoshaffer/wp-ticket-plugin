<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       ricardoshaffer.com
 * @since      1.0.0
 *
 * @package    Resource_Scheduling
 * @subpackage Resource_Scheduling/admin/partials
 */

if(isset($_POST['ticketUpdate'])){
	$ticketSubmit = new InsertNote();
	$ticketSubmit->validator($_POST);
};
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->


<div class="container-sm">
    <table id="ticketsTable">
        <thead>
            <th>First Name</th>
            <th> Creation Date</th>
            <th>Status</th>
            <th>Issue</th>
            <th>View</th>
        </thead>
        <tbody id="ticketRow">
            </tbody>
            </table>
        </tbody>
    </table>
</div>
<div class='ticketNotes container-sm'>
        <td class='unique-ticket-form'>
            <form class='update-ticket-form' class='row' action='' method='post' >
                <label for="ticket_status_radio" class="form-label">Ticket Status*</label>
                    <div class='col-12 g-3 mb-3' id="ticket_status_radio">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ticket_status" value="CLOSED" id="ticket_radio_close">
                            <label class="form-check-label" for="ticket_radio_close">
                            CLOSE
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ticket_status" value="IN-PROGRESS" id="ticket_radio_in_progress">
                            <label class="form-check-label" for="ticket_radio_in-progress">
                            IN-PROGRESS
                            </label>
                        </div>
                    </div>

                <div class='col-12 g-3 mb-3'>
                    <div class="input-group">
                        <span class="input-group-text">Update Ticket</span>
                        <textarea class="form-control" aria-label="With textarea" type='text' id='note_description' autocomplete='off' name='note_description' value=''></textarea>
                    </div>
                </div>
                <div class='col-12 g-3 mb-3'>
                    <input hidden type='text' id='ticket_ID' autocomplete='off' name='ticket_ID'></input>
                </div>
                <div class='col-12 g-3 mb-3'>
                    <button class='btn btn-primary' id='ticketUpdate' name='ticketUpdate' type='submit'>Update</button>
                    <button class='btn btn-dark' id='cancel_update' type='button role='button' type='submit'>CANCEL</button></a>
                </div>       
            </form>
        </td>
</div>

	<?php 
$dbhCall = new Retrieve();
echo $dbhCall->retrieveTickets();
?>


