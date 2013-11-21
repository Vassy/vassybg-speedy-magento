/*
 Ajax.Responders.register({
 onComplete: function(transport) {
 if (transport.url.toString().search('card_validation') != -1) {
 order.loadShippingRates(true);
 }
 
 }
 })
 */

AdminOrder.addMethods({
    setShippingMethod: function(method) {

        var data = {};
        data['order[shipping_method]'] = method;
        data['speedy_exact_hour'] = $('speedy_exact_hour').getValue();
        data['speedy_exact_minutes'] = $('speedy_exact_minutes').getValue();

        var speedyExactTime = null;


        if (speedyExactTime) {
            data['speedyExactTime'] = speedyExactTime;
        }


        data['collect_shipping_rates'] = 1;
        this.loadArea(['shipping_method', 'totals', 'billing_method'], true, data);

    },
    switchPaymentMethod: function(method) {
        this.setPaymentMethod(method);
        this.isShippingMethodReseted = false;
        var data = {};
        data['order[payment_method]'] = method;
        if ($('speedy_exact_hour')) {
            data['speedy_exact_hour'] = $('speedy_exact_hour').getValue();
        }
        if ($('speedy_exact_minutes')) {
            data['speedy_exact_minutes'] = $('speedy_exact_minutes').getValue();
        }
        //if(data['speedy_exact_minutes'] && data['speedy_exact_hour']){
        data['collect_shipping_rates'] = 1;
        var selectedShippingMethod = $j('.shipment-methods input[type="radio"]:checked')

        if ($j(selectedShippingMethod).length) {
            data['order[shipping_method]'] = $j(selectedShippingMethod).val();
            data['shipping_method'] = $j(selectedShippingMethod).val();
            // data['collect_shipping_rates'] = 1;
        }
        //}
        this.loadArea(['card_validation', 'shipping_method', 'totals', 'billing_method'], true, data);
    }
    ,
    loadShippingRates: function(recalculate) {

        this.isShippingMethodReseted = false;

        var data = {};
        var speedyExactTime = null;
        var shippingMethod = null;


        var selectedShippingMethod = $j('.shipment-methods input[type="radio"]:checked')

        if ($j(selectedShippingMethod).length) {
            data['order[shipping_method]'] = $j(selectedShippingMethod).val();
            data['shipping_method'] = $j(selectedShippingMethod).val();

        }






        if ($('speedy_exact_hour')) {
            data['speedy_exact_hour'] = $('speedy_exact_hour').getValue();
        }
        if ($('speedy_exact_minutes')) {
            data['speedy_exact_minutes'] = $('speedy_exact_minutes').getValue();
        }


        if (data) {
            data['collect_shipping_rates'] = 1;


            this.loadArea(['shipping_method', 'totals', 'billing_method'], true, data);
        } else {


            this.loadArea(['shipping_method', 'totals', 'billing_method'], true, {collect_shipping_rates: 1});
        }

    }
});

$j('document').ready(function() {


    var currentMethodOptions = null;



    $j(document).on('change', 'input:radio[id*="speedyshippingmodule"]', function(evt) {

        var isExactHourAllowed = $j(this).nextAll('div.speedy_method_options').find('input:hidden.speedy_exacthour_allowed').length
        var isFreeMethod = $j(this).nextAll('input:hidden');

        if ($j(this).is(':checked') && isExactHourAllowed) {
            $j('input#speedy_exact_hour_enable').removeAttr('disabled')
            var finalSelector = $j(this).nextAll('div.speedy_method_options');
            var priceWithTax = $j(finalSelector).find('input:hidden.speedy_exacthour_allowed').val()
            var priceWithoutTax = $j(finalSelector).find('input:hidden.speedy_exacthour_withouttax').val()
            var price = '';
            if (!isFreeMethod.length) {

                if (includeTax) {
                    price = priceWithTax;
                }
                else if (excludeTax) {
                    price = priceWithoutTax;
                }
                else if (showBoth) {
                    price = priceWithoutTax + '/' + priceWithTax;
                }
            } else {
                price = '0.00';
            }
            price += ' лв.';
            $j('p#fixed_price_view').show().text('Надбавка "Фиксиран час": ' + price)
        } else {

            $j('#speedy_admin_form input#speedy_exact_hour_enable').attr('disabled', 'disabled').removeAttr('checked');
            $j('#speedy_admin_form input:text').attr('disabled', 'disabled').val('')
            $j('p#fixed_price_view').text('Надбавка "Фиксиран час": ');

        }


    })



    $j(document).on('change', 'input#speedy_exact_hour_enable', function() {



        if ($j(this).is(':checked')) {
            //get the current selected service
            var service = $j('#co-shipping-method-form input:radio:checked')
            var isExactHourAllowed = $j(service).nextAll('div.speedy_method_options').find('input:hidden.speedy_exacthour_allowed').length
            var isFreeMethod = $j(service).nextAll('input:hidden');
            var finalSelector = $j(service).nextAll('div.speedy_method_options');
            var priceWithTax = $j(finalSelector).find('input:hidden.speedy_exacthour_allowed').val()
            var priceWithoutTax = $j(finalSelector).find('input:hidden.speedy_exacthour_withouttax').val()
            var price = '';
            if (!isFreeMethod.length) {
                if (includeTax) {
                    price = priceWithTax;
                }
                else if (excludeTax) {
                    price = priceWithoutTax;
                }
                else if (showBoth) {
                    price = priceWithoutTax + '/' + priceWithTax;
                }
            } else {
                price = '0.00';
            }
            price += ' лв.';

            //$j('p#fixed_price_view').show().text('Добавка "фиксиран час:"'+price)
            $j('#speedy_admin_form input:text').removeAttr('disabled')
        } else {
            $j('#speedy_admin_form input:text').attr('disabled', 'disabled').val('')
            //$j('p#fixed_price_view').text('Добавка "фиксиран час:"')
        }
    })


})




