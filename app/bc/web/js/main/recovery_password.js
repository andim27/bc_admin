(function($){
    'use strict';

    var $emailField = $('#passresetform-email');
    var $phoneField = $('#passresetformmessenger-messengernumber');
    var $recoveryFormEmail = $('#recovery-form-email');
    var $recoveryFormMessenger = $('#recovery-form-messenger');
    var $msgNumberBlock = $('#messenger-number-block');

    var type = 'input[name="PassResetFormEmail[type]"]';
    var messenger = 'input[name="PassResetFormMessenger[messenger]"]';


    $(document).ready(function() {
        $emailField.keydown(function(event){
            if(event.keyCode === 13) {
                event.preventDefault();
                return false;
            }
        });

        togglePasswordRecoveryType();

        addCharBefore($phoneField, '+');
    });

    $('#recovery-submit').click(function() {
        $(this).hide();
        var btn = $(this);
        setTimeout(function() {
            btn.show();
        }, 3000);
    });

    $(document).on('beforeSubmit', '#recovery-form-email', function() {
        return loadSuccess('#recovery-email-success', $(this));
    });

    $(document).on('beforeSubmit', '#recovery-form-messenger', function() {
        return loadSuccess('#recovery-messenger-success', $(this));
    });

    $(document).on('change', type, function() {
        togglePasswordRecoveryType();
    });

    $(messenger).change(function() {
        var value = $(this).val();
        var messengerNumberBlock = $('#messenger-number-block');

        if (value) {
            messengerNumberBlock.show();
        } else {
            messengerNumberBlock.hide();
        }
    });

    function loadSuccess(node, form){
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            dataType: 'JSON',
            data: form.serialize(),
            success: function() {
                form.hide();
                $('#recovery-form-type').hide();
                $(node).show();

                setTimeout(function() {
                    $('.close').click();
                }, 3000);
            }
        });

        return false;
    }

    function togglePasswordRecoveryType(){
        if( $(type + ':checked').val() === 'messenger' ){
            $recoveryFormEmail.hide();
            $recoveryFormMessenger.show();

            if($(messenger + ':checked').val()){
                $msgNumberBlock.show();
            }
        } else {
            $recoveryFormEmail.show();
            $recoveryFormMessenger.hide();
            $msgNumberBlock.hide();
        }
    }

    /**
     *
     * @param $elem
     * @param char
     */
    function addCharBefore($elem, char){
        char = char || '+';

        $elem.focus(function() {
            console.log(1);
            var $this = $(this);
            if (!$this.val().length){
                $this.val(char);

                setTimeout(function(){  console.log(2);
                    $this.setCursorPosition(1);
                }, 400);
            }
        });

        $elem.click(function() {
            var $this = $(this);

            if (!$this.val().length || $this.val().length <= 1){
                $this.val(char);
                $this.setCursorPosition(1);
            }
        });

        $elem.keyup(function(){
            var $this = $(this);

            if (!$this.val().length){
                $this.val(char);
                $this.setCursorPosition(1);
            }
        });

        $elem.focusout(function() {
            var $this = $(this);
            if ($this.val() === char)
                $(this).val('');
        });
    }

    /**
     *
     * @param pos
     * @returns {jQuery}
     */
    $.fn.setCursorPosition = function(pos) {
        this.each(function(index, elem) {
            if (elem.setSelectionRange) {
                elem.setSelectionRange(pos, pos);
            } else if (elem.createTextRange) {
                var range = elem.createTextRange();
                range.collapse(true);
                range.moveEnd('character', pos);
                range.moveStart('character', pos);
                range.select();
            }
        });

        return this;
    };
})(jQuery);