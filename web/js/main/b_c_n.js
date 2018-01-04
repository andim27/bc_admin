
jQuery(document).ready(function() {

    $.ajax({
        "url":'/'+LANG+'/business/notes/add-id',
        "type":'GET',
        "data":{
            'id' : $(this).data()
        },
        success:function(data){
            data = JSON.parse(data);
            $('#hid').val(data[0]['id'] + 1);
        }
    });

});