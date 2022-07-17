jQuery(function () {
    jQuery('#rv_send').click(function (e) {
        e.preventDefault();
        let btn = jQuery(this);
        btn.prop('disabled', true);
        jQuery.ajax({
            type: "POST",
            url: btn.attr('data-post'),
            data: {
                "order_id": btn.attr('data-id'),
                "rv_width": jQuery('#rv_width').val(),
                "rv_height": jQuery('#rv_height').val(),
                "rv_length": jQuery('#rv_length').val(),
                "rv_weight": jQuery('#rv_weight').val(),
                "rv_type": jQuery('#rv_type').val(),
            },
            dataType: "JSON",
            success: function (response) {
                console.log(response);
                if (response.result) {
                    btn.parent().html(`
                    <button type='button' id='rv_end' data-id='${response.id}' class='button save_order button-primary'>Send to client</button>                  
                    `);
                }
                else {
                    console.log(response.text);
                }
            }
        });
    });

    jQuery('body').delegate('#rv_end', 'click', function () {
        e.preventDefault();
        let btn = jQuery(this);
        btn.prop('disabled', true);
    });
});