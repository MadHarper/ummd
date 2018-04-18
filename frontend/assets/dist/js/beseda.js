function changeBesedaControl(newDate){
    $.get('/beseda/default/check-control', {
        day: newDate
    }, function(res){
        $('.beseda-form #beseda-control_date').val(res);
    });
}

