(function($) {

    $(function() {
        $('.language-helper').click(function(event) {
            var $target = $(this);
            if (event.ctrlKey) {
                event.preventDefault();
                event.stopPropagation();
                var $box = $('#language_helper_box');
                if ($box.length) {
                    $box.css('top', event.pageY).css('left', event.pageX).find('input').val($target.text()).attr('data-id', $target.attr('id')).end().show();
                } else {
                    $box = $('<div>', {
                        'id': 'language_helper_box',
                        'css': {
                            'top': event.pageY,
                            'left': event.pageX
                        }
                    })
                        .append(
                            $('<div>')
                            .append(
                                $('<span>')
                                .text($target.attr('id'))
                            )
                    )
                        .append(
                            $('<div>')
                            .append(
                                $('<span>')
                                .text(simple.language.current + ' ')
                            )
                            .append(
                                $('<input>', {
                                    'type': 'text',
                                    'name': simple.language.current,
                                    'value': $target.text(),
                                    'size': 50,
                                    'data-id': $target.attr('id')
                                })
                            )
                    )
                        .append(
                            $('<div>', {
                                'style': 'margin-top:5px;'
                            })
                            .append(
                                $('<a>', {
                                    'class': 'button',
                                    'id': 'language_helper_save',
                                    'style': 'margin-right: 5px;'
                                })
                                .append(
                                    $('<span>')
                                    .text('save')
                                )
                            ).append(
                                $('<a>', {
                                    'class': 'button',
                                    'id': 'language_helper_close'
                                })
                                .append(
                                    $('<span>')
                                    .text('close')
                                )
                            )
                    );
                }

                $('body').append($box);
                $box.find('input').focus();
            }
        });

        $(document).on('click', '#language_helper_save', function() {
            var $box = $('#language_helper_box input');

            $.ajax({
                url: simple.language.helperUrl,
                type: 'POST',
                dataType: 'text',
                data: {
                    code: simple.language.current,
                    id: $box.attr('data-id'),
                    text: $box.val()
                },
                success: function(data) {
                    $('#language_helper_box').hide();
                    $('#' + $box.attr('data-id')).text(data);
                }
            });
        });

        $(document).on('click', '#language_helper_close', function() {
            $('#language_helper_box').hide();
        });

        $(document).on('click', '#language_helper_box input', function(event) {
            if (event.keyCode === 13) {
                $('#language_helper_save').click();
            }
            if (event.keyCode === 27) {
                $('#language_helper_close').click();
            }
        });
    });

})(jQuery);