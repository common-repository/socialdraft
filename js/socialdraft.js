/**$(document).ready(function() {
	$("#TB_ajaxWindowTitle").val('')
	//$('#offset').hide();

    $('div.pagination > a').click(function() {
	
	current_biz = $('#customers-form #biz option:selected').val(); // returns the number of the selected option
	current_limit = $('#customers-form #limit option:selected').val(); // returns the number of the selected option
	current_offset = $('#customers-form #offset option:selected').text() + current_limit; // returns the number of the selected option
	replace_biz = current_biz.replace(/\s/g,"-");
	$('#customers-form #biz option[name='+replace_biz+']').attr("selected", true); // give the attribute of selected to the specified option
	$('#customers-form #limit option[name=limit_'+current_limit+']').attr("selected", true); // give the attribute of selected to the specified option
	$('#customers-form #offset option[name=offset_'+current_offset+']').attr("selected", true); // give the attribute of selected to the specified option
	//$('#customers-form, #reviews-form').submit();
	$("#submit").click();
	//$("input#submit.button").submit();
	$.ajax({
        type: "POST",
        url: window.location,
		data: {biz:current_biz,limit:current_limit,offset:current_offset}
		});
    });
	
/* 	var data2 = jQuery("form[name='customers-form']").serialize();
	jQuery.ajax({
		type: 'POST',
		url: window.location,
		data: data2 + '&action=savedata',
		success: function() {
		}
	});	
 });*/