jQuery(document).ready(function() {
	var form = jQuery('form');
	var select = jQuery('select[name = "with-selected"]');
	var option = jQuery('<option value="export">Export</option>');
	
	if (select.find('optgroup:first').length) {
		option.insertBefore(select.find('optgroup:first'));
	}
	
	else {
		option.appendTo(select);
	}
	
	form.bind('submit', function() {
		if (select.val() == 'export') {
			form.attr('action', Symphony.WEBSITE + '/symphony/extension/quickexport/run/');
		}
	});
});