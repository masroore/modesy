<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($cart_payment_method->payment_option == "paypal"): ?>

	<script src="https://www.paypal.com/sdk/js?client-id=<?php echo $payment_settings->paypal_client_id; ?>&currency=<?php echo $currency; ?>"></script>

	<div class="row">
		<div class="col-12">
			<?php $this->load->view('product/_messages'); ?>
		</div>
	</div>
	<div id="payment-button-container" class="paypal-button-cnt">
		<div id="paypal-button-container"></div>
	</div>

	<script>
        $(document).ready(function () {
            paypal.Buttons({
                createOrder: function (data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '<?php echo price_format_decimal($total_amount); ?>',
                            }
                        }]
                    });
                },
                style: {
                    color: 'gold',
                },
                onApprove: function (data, actions) {
                    // Capture the funds from the transaction
                    return actions.order.capture().then(function (details) {
                        var data_array = {
                            'payment_id': details.id,
                            'currency': '<?php echo $currency; ?>',
                            'payment_amount': '<?php echo price_format_decimal($total_amount); ?>',
                            'payment_status': details.status,
                            'mds_payment_type': '<?php echo $mds_payment_type; ?>',
                            'lang_folder': lang_folder,
                            'form_lang_base_url': '<?php echo lang_base_url(); ?>'
                        };
                        data_array[csfr_token_name] = $.cookie(csfr_cookie_name);
                        $.ajax({
                            type: "POST",
                            url: base_url + "cart_controller/paypal_payment_post",
                            data: data_array,
                            success: function (response) {
                                var obj = JSON.parse(response);
                                if (obj.result == 1) {
                                    window.location.href = obj.redirect;
                                } else {
                                    location.reload();
                                }
                            }
                        });
                    });
                },
                onError: function (error) {
                    alert(error);
                }
            }).render('#paypal-button-container');
        });
	</script>
<?php endif; ?>
