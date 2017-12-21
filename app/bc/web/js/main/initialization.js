$(document).ready(function() {
    /*$('#users_list_table').dataTable({
     "ajax": "/users/user/get-users",
     "columns": [
     { "data": "login" },
     { "data": "email" },
     { "data": "name" }
     ]
     });*/
    //var TRANSLATION='';
    $('table.unique_table_class').dataTable({
        language: TRANSLATION,
        sDom: "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
        lengthMenu: [ 25, 50, 75, 100 ]
    });
});