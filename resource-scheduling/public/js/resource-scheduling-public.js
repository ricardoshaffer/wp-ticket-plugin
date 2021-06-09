(function( $ ) {
	'use strict';
	var alertList = document.querySelectorAll('.alert')
	alertList.forEach(function (alert) {
	new bootstrap.Alert(alert)
	});

})( jQuery );
