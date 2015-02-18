jQuery(document).ready(function ($) {
    var $form = $('#edd_purchase_form');

    $form.on('change', '#tax-id, #company, .card-zip, #billing_country', function (e) {
        e.preventDefault();
        recalculate_taxes();
        return false;
    });

    function recalculate_taxes() {
        if ('1' != edd_global_vars.taxes_enabled)
            return; 

        var postData = {
            action: 'edd_recalculate_taxes',
            company: $form.find('#company').val(),
            tax_id: $form.find('#tax-id').val(),
            card_zip: $form.find('.card-zip').val(),
            billing_country: $form.find('#billing_country').val()
        };

        $.ajax({
            type: "POST",
            data: postData,
            dataType: "json",
            url: edd_global_vars.ajaxurl,
            xhrFields: {
                withCredentials: true
            },
            success: function (tax_response) {
                $('#edd_checkout_cart').replaceWith(tax_response.html);
                $('.edd_cart_amount').html(tax_response.total);
                var tax_data = new Object();
                tax_data.postdata = postData;
                tax_data.response = tax_response;
                $('body').trigger('edd_taxes_recalculated', [tax_data]);
            }
        }).fail(function (data) {
            if (window.console && window.console.log) {
                console.log(data);
            }
        });

        var postData = {
            action: 'edd_get_shop_states',
            country: $form.find('#billing_country').val(),
            field_name: 'card_state'
        };

        $.ajax({
            type: "POST",
            data: postData,
            url: edd_global_vars.ajaxurl,
            xhrFields: {
                withCredentials: true
            },
            success: function (response) {
                if ('nostates' == response) {
                    var text_field = '<input type="text" name="card_state" class="cart-state edd-input required" value=""/>';
                    $form.find('input[name="card_state"], select[name="card_state"]').replaceWith(text_field);
                } else {
                    $form.find('input[name="card_state"], select[name="card_state"]').replaceWith(response);
                }
                $('body').trigger('edd_cart_billing_address_updated', [response]);
            }
        }).fail(function (data) {
            if (window.console && window.console.log) {
                console.log(data);
            }
        }).done(function (data) {
            
        });



    }

});
