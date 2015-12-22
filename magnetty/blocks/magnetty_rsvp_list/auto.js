ccmValidateBlockForm = function() {
	
	if ($('select[name=field_1_select_value]').val() == '' || $('select[name=field_1_select_value]').val() == 0) {
		ccm_addError('Missing required selection: 色とリンク数');
	}

	if ($('#ticketName').val() == '') {
		ccm_addError('Missing required text: Ticket Name');
	}

	if ($('#ticketPrice').val() == '') {
		ccm_addError('Missing required text: Ticket Price');
	}

	return false;
}