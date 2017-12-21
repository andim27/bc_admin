function allSelect(){
    var mas_viewing="";
    var i=0;
    $('#viewing option:selected').each(function(){
        if(i==0){
            mas_viewing+=$(this).val();
        }
        else{
            mas_viewing+=','+$(this).val();
        }
        i++;
    });
    var mas_editing="";
    var i=0;
    $('#editing option:selected').each(function(){
        if(i==0){
            mas_editing+=$(this).val();
        }
        else{
            mas_editing+=','+$(this).val();
        }
        i++;
    });
    var mas={'mas_viewing':mas_viewing, 'mas_editing':mas_editing};
    return mas;
}

function ajaxSave(uid,rigth){
    $.ajax({
        type: 'GET',
        data:{
            "id":uid,
            "right":rigth
        },
        url: '/'+LANG+'/settings/administrator-rights/saves',
        success: function(data){
            console.log(data);
        }
    });
}

jQuery(document).ready(function() {
    $('#viewing').change(function() {
        ajaxSave($("#account").val(),allSelect());
    });
    $('#editing').change(function() {
        ajaxSave($("#account").val(),allSelect());
    });
    $('.select_all').on('click',function(){
        var father= $(this).parent().parent().find('select').attr('id');
        father='#'+father+' option';
        $(father).each(function(){
            this.selected=true;
        });
        ajaxSave($("#account").val(),allSelect());
    });
    $('.remove_all').on('click',function(){
        $(this).parent().parent().find('select option:selected').each(function(){
            this.selected=false;
        });
        ajaxSave($("#account").val(),allSelect());
    });

});
