jQuery(document).ready(function() {

    $('body').on("click", ".business_alert", function(){

        var addressaa = $('.apiaddress').val();
        var i = parseInt($('.ac_id').val());

        if($('.deliveryEMail').prop("checked") == true) {
            var deliveryEMail = 1;
        } else deliveryEMail = 0;
        if($('.deliverySMS').prop("checked") == true) {
            var deliverySMS = 1;
        } else deliverySMS = 0;
        if($('.notifyAboutCheck').prop("checked") == true) {
            var notifyAboutCheck = 1;
        } else notifyAboutCheck = 0;
        if($('.notifyAboutJoinPartner').prop("checked") == true) {
            var notifyAboutJoinPartner = 1;
        } else notifyAboutJoinPartner = 0;

        $.ajax({
            url:''+addressaa+'user/',
            method:"PUT",
            data: {
                accountId: i,
                deliveryEMail: deliveryEMail,
                deliverySMS: deliverySMS,
                notifyAboutCheck: notifyAboutCheck,
                notifyAboutJoinPartner: notifyAboutJoinPartner
            },
            success: function(){
                setTimeout(function(){
                    location.reload();
                }, 200);
            }
        });

    });

});