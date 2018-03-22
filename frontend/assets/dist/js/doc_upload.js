console.log('work!');

$("form[name='doc_upload']").on('beforeSubmit', function(e) {

    var url = $('#document-upload-index').data('url');
    var formData = new FormData($(this)[0]);

    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        //async: false,
        success: function (res) {
            console.log(res.res);
            if(res.result){
                toastr.success('', 'Документ загружен')
                $.pjax.reload({container:"#agreement_docs"});
                $('#ajax_doc_form')[0].reset();
            }else{
                toastr.error('Ошибка загрузки документа')
            }
        },
        // error: function(res) {
        //     alert('Ошибка!');
        // },
        cache: false,
        contentType: false,
        processData: false
    });

    e.preventDefault();
    return false;
});