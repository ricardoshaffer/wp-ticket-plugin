(function( $ ) {
	'use strict';
    $(document).ready(function() {
        $('#ticketsTable').DataTable( {
			responsive: true
		} );
		
    });
	var alertList = document.querySelectorAll('.alert')
	alertList.forEach(function (alert) {
	new bootstrap.Alert(alert)
	$('#unique-ticket-form').appendTo("id$='ticket_container_view_']");
	})

	// CANCEL TOGGLE SECTION APPEARANCE
		$('.cancel_update').on('click', function() {
			$('[id^="ticket_container_"]').hide();
		})
})( jQuery );
