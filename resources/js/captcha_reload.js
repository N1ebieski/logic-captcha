jQuery(document).on('click', '.reload_captcha_base64', function(e) {
    e.preventDefault();

    let $element = $(this);
    let $form = $element.closest('form');

    jQuery.ajax({
        url: $element.attr('data-route') + '?captcha_id=' + $form.find('input[name="captcha_id"]').val(),
        method: 'get',
        beforeSend: function() {
        },
        complete: function() {
        },
        success: function(response) {
            $form.find('img').attr('src', $($.parseHTML(response.img)).text());
        }
    });
});

jQuery(document).on('click', '.reload_captcha_img', function(e) {
    e.preventDefault();

    let $img = $(this).closest('form').find('img');
    $img.attr('src', $($.parseHTML($img.attr('src').split('?')[0] + '?' + Math.random())).text());
});
