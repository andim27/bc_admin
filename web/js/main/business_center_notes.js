$(function(){
    var currentNote;

    function convertTimestamp(timestamp) {
        var date = new Date(timestamp * 1000);

        var hours = ('0' + date.getHours()).slice(-2);
        var minutes = ('0' + date.getMinutes()).slice(-2);
        var day = ('0' + date.getDate()).slice(-2);
        var month = ('0' + (date.getMonth() + 1)).slice(-2);
        var year = date.getFullYear();

        return day + '-' + month + '-' + year + ', ' + hours + ':' + minutes;
    }

    $('body').on('click', '.note-it', function() {
        showNote($(this).data().id, false);
        return false;
    });

    $('body').on('click', '.close-note', function() {
        if (confirm($(this).data().confirmation)) {
            removeNote($(this).data().id);
        }
        return false;
    });

    $('body').on('keyup', '#search-note', function() {
        var searchText = $(this).val();
        $('.note-it').each(function() {
            if ($(this).find('.note-name strong').html().trim().indexOf(searchText.trim()) + 1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    var userId = $('#profileform-id').val();

    $('body').on('click', '#new-note', function() {
        var rmButton = $(this);
        rmButton.attr('disabled', 'disabled')
        $.ajax({
            url: '/' + LANG + '/business/user/add-note',
            method: 'POST',
            data: {
                u: userId
            },
            success: function(data) {
                $(data).addClass('act').prependTo('#note-items');
                showNote($(data).data().id, true);
                rmButton.removeAttr('disabled');
            }
        });
        return false;
    });

    $('body').on('keyup', '#area', function() {
        if (currentNote) {
            var title = stringMax($(this).val().replace(/(<([^>]+)>)/ig,""), 30);
            var body = $(this).val().replace(/(<([^>]+)>)/ig,"");
            currentNote.find('.note-name strong').html(title);
            $.ajax({
                url: '/' + LANG + '/business/user/update-note',
                method: 'POST',
                data: {
                    id : currentNote.data().id,
                    title : title,
                    body : body
                },
                success: function(data) {

                }
            });
        }
        return false;
    });

    function removeNote(noteId) {
        $.ajax({
            url: '/' + LANG + '/business/user/remove-note',
            method: 'GET',
            data: {
                id : noteId
            },
            success: function(data) {
                $('#note-' + noteId).remove();
                if (currentNote && currentNote.data().id == noteId) {
                    $('.op').css('visibility', 'hidden');
                }
            }
        });
    }

    function showNote(noteId, isNew) {
        $('.note-it').removeClass('act');
        $.ajax({
            url: '/' + LANG + '/business/user/show-note',
            method: 'GET',
            data: {
                id : noteId,
                isNew : isNew
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data) {
                    currentNote = $('#note-' + data['id']);
                    $('.dat').html(convertTimestamp(data['dateCreate']));
                    if (! isNew) {
                        $('#area').val(data['body']);
                    } else {
                        $('#area').val('');
                    }
                    $('.op').css('visibility', 'visible');
                    currentNote.addClass('act').removeClass('active');
                    $('#area').focus();
                }
            }
        });
    }

    function stringMax(text, length) {
        if (text.length > length){
            var a = text.substring(0, length);
            return a.substring(0, a.lastIndexOf(' '));
        } else{
            return text;
        }
    };

});
