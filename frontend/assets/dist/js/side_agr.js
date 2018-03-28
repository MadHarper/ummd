function loadEmployee(orgId){
    var carSelect = $('select[name="SideAgr[employee_id]"]');
    var historic = $("#with_historic").is(":checked") ? 1 : 0;
    //console.log($("#with_historic").attr("checked"));
    $.get('/agreement/side/list', {
        id: orgId,
        historic: historic
    }, function(res){
        carSelect.html('');
        carSelect.append(res);
    });
}

function loadAnother(orgId){
    $.get('/agreement/side/org-info', {
        id: orgId
    }, function(res){
        if(res.res){
            if(res.data.iogv && $('.field-sideagr-subdivision').hasClass('unvisible_field')){
                $('.field-sideagr-subdivision').removeClass('unvisible_field');
            }
            if(!res.data.iogv && !$('.field-sideagr-subdivision').hasClass('unvisible_field')){
                $('.field-sideagr-subdivision').addClass('unvisible_field');
            }

            $('#sideagr-org_country').val(res.data.country);
            $('#sideagr-org_city').val(res.data.city);
            //$('#sideagr-subject_rf').val(res.data.subject_rf);

            if(res.data.subject_rf){
                $('#sideagr-subject_rf').prop('checked',true);
            }else{
                $('#sideagr-subject_rf').prop('checked',false);
            }
        }
    });
}

