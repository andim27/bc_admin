
$(document).ready(function(){
    $(document).on("shown.bs.modal",'#ajaxModal', function() {
        setTimeout(function(){
            $('#stockbuy-product_id').change();
            $('#stockstep-product_id').change();
            $('#stockstatus-carrier_id').change();
        },700);
    });
});

$(document).on('change', '#stockbuy-product_id', function(){
    var id=$(this).val();
    $.ajax({
        type: 'GET',
        data:{
            "id":id
        },
        url: '/'+LANG+'/handbook/shares/change',
        success: function(result){
            if(result!=false){
                $('#stockbuy-product_title').val(result);
            }
        }
    });
});
$(document).on('change', '#stockstep-product_id', function(){
    var id=$(this).val();
    $.ajax({
        type: 'GET',
        data:{
            "id":id
        },
        url: '/'+LANG+'/handbook/shares/change',
        success: function(result){

            if(result!=false){
                $('#stockstep-product_title').val(result);
            }
        }
    });
});
$(document).on('change', '#stockstatus-carrier_id', function(){
    var id=$(this).val();
    $.ajax({
        type: 'GET',
        data:{
            "id":id
        },
        url: '/'+LANG+'/handbook/shares/change-status',
        success: function(result){
            $('#stockstatus-carrier_id').val(result);
        }
    });
});