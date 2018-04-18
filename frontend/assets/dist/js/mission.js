function seeRegion(country_id){

    var russia_id = $('#missform').data('russia_id');

    console.log(country_id);

    if(country_id == russia_id){
        if($('.region_form_area').hasClass('unvisible_field')){
            $('.region_form_area').removeClass('unvisible_field');
        }
    }else{
        if(!$('.region_form_area').hasClass('unvisible_field')){
            $('.region_form_area').addClass('unvisible_field');
        }
    }
}



function changeMissionControl(newDate)
{
    if($("#mission-update-locus").length == 0){
        return;
    }

    var missionId = $("#mission-update-locus").data('missionid');

    $.get('/mission/default/check-control', {
        day: newDate,
        mission: missionId
    }, function(res){
        $('#mission-contol_date').val(res);
    });

}