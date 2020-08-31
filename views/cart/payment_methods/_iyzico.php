<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($cart_payment_method->payment_option == "iyzico"): ?>

	<div class="row">
		<div class="col-12">
			<!-- include message block -->
			<?php $this->load->view('product/_messages'); ?>
		</div>
	</div>
	<!--PROMOTE SALES-->
	<?php if ($mds_payment_type == 'promote'):
		$ci =& get_instance();
		$options = $ci->initialize_iyzico();
		$user = user();
		$ip = $this->input->ip_address();
		if (empty($ip)) {
			$ip = "85.34.78.112";
		}
		# create request class
		$request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
		$request->setLocale(\Iyzipay\Model\Locale::TR);
		$request->setConversationId("123456");
		$request->setPrice(price_format_decimal($total_amount));
		$request->setPaidPrice(price_format_decimal($total_amount));
		$request->setCurrency(\Iyzipay\Model\Currency::TL);
		$request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
		$request->setCallbackUrl(base_url() . "cart_controller/iyzico_payment_post?payment_type=promote&lang_base_url=" . lang_base_url());
		$request->setEnabledInstallments(array(2, 3, 6, 9));

		$buyer = new \Iyzipay\Model\Buyer();
		$buyer->setId($user->id);
		$buyer->setName($user->username);
		$buyer->setSurname($user->username);
		$buyer->setGsmNumber($user->phone_number);
		$buyer->setEmail($user->email);
		$buyer->setIdentityNumber("11111111111");
		$buyer->setRegistrationAddress("not_set");
		$buyer->setIp($ip);
		$buyer->setCity("not_set");
		$buyer->setCountry("not_set");
		$buyer->setZipCode("not_set");
		$request->setBuyer($buyer);

		$shippingAddress = new \Iyzipay\Model\Address();
		$shippingAddress->setContactName("not_set");
		$shippingAddress->setCity("not_set");
		$shippingAddress->setCountry("not_set");
		$shippingAddress->setAddress("not_set");
		$shippingAddress->setZipCode("");
		$request->setShippingAddress($shippingAddress);

		$billingAddress = new \Iyzipay\Model\Address();
		$billingAddress->setContactName("not_set");
		$billingAddress->setCity("not_set");
		$billingAddress->setCountry("not_set");
		$billingAddress->setAddress("not_set");
		$billingAddress->setZipCode("");
		$request->setBillingAddress($billingAddress);

		$basketItems = array();
		//shipping
		$BasketItem = new \Iyzipay\Model\BasketItem();
		$BasketItem->setId("0");
		$BasketItem->setName($promoted_plan->purchased_plan);
		$BasketItem->setCategory1(trans("promote_plan"));
		$BasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
		$BasketItem->setPrice(price_format_decimal($total_amount));
		$basketItems[0] = $BasketItem;
		//cart items
		$request->setBasketItems($basketItems);
		# make request
		$checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);
		echo $checkoutFormInitialize->getcheckoutFormContent(); ?>
		<div id="iyzipay-checkout-form" class="responsive"></div>
	<?php else: ?>
		<!--PRODUCT SALES-->
		<?php
		$ci =& get_instance();
		$options = $ci->initialize_iyzico();
		$buyer_id = "guest_" . uniqid();
		if (auth_check()) {
			$buyer_id = user()->id;
		}
		$ip = $this->input->ip_address();
		if (empty($ip)) {
			$ip = "85.34.78.112";
		}
		$country_name = "Turkey";
		$country = get_country($shipping_address->shipping_country_id);
		if (!empty($country)) {
			$country_name = $country->name;
		}
		# create request class
		$request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
		$request->setLocale(\Iyzipay\Model\Locale::TR);
		$request->setConversationId("123456");
		$request->setPrice(price_format_decimal($cart_total->total));
		$request->setPaidPrice(price_format_decimal($cart_total->total));
		$request->setCurrency(\Iyzipay\Model\Currency::TL);
		$request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
		$request->setCallbackUrl(base_url() . "cart_controller/iyzico_payment_post?payment_type=sale&lang_base_url=" . lang_base_url());
		$request->setEnabledInstallments(array(2, 3, 6, 9));

		$buyer = new \Iyzipay\Model\Buyer();
		$buyer->setId($buyer_id);
		$buyer->setName($shipping_address->shipping_first_name);
		$buyer->setSurname($shipping_address->shipping_last_name);
		$buyer->setGsmNumber($shipping_address->shipping_phone_number);
		$buyer->setEmail($shipping_address->shipping_email);
		$buyer->setIdentityNumber("11111111111");
		$buyer->setRegistrationAddress($shipping_address->shipping_address_1);
		$buyer->setIp($ip);
		$buyer->setCity($shipping_address->shipping_city);
		$buyer->setCountry($country_name);
		$buyer->setZipCode($shipping_address->shipping_zip_code);
		$request->setBuyer($buyer);

		$shippingAddress = new \Iyzipay\Model\Address();
		$shippingAddress->setContactName($shipping_address->shipping_first_name . " " . $shipping_address->shipping_last_name);
		$shippingAddress->setCity($shipping_address->shipping_city);
		$shippingAddress->setCountry($country_name);
		$shippingAddress->setAddress($shipping_address->shipping_address_1);
		$shippingAddress->setZipCode($shipping_address->shipping_zip_code);
		$request->setShippingAddress($shippingAddress);

		$billingAddress = new \Iyzipay\Model\Address();
		$billingAddress->setContactName($shipping_address->billing_first_name . " " . $shipping_address->billing_last_name);
		$billingAddress->setCity($shipping_address->billing_city);
		$billingAddress->setCountry($country_name);
		$billingAddress->setAddress($shipping_address->billing_address_1);
		$billingAddress->setZipCode($shipping_address->billing_zip_code);
		$request->setBillingAddress($billingAddress);

		$basketItems = array();
		//shipping
		$count = 1;
		if ($cart_total->shipping_cost > 0) {
			$BasketItem = new \Iyzipay\Model\BasketItem();
			$BasketItem->setId("0");
			$BasketItem->setName(trans("shipping"));
			$BasketItem->setCategory1(trans("shipping"));
			$BasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
			$BasketItem->setPrice(price_format_decimal($cart_total->shipping_cost));
			$basketItems[0] = $BasketItem;
			//cart items
			$count = 1;
		} else {
			$count = 0;
		}
		if (!empty($cart_items)):
			foreach ($cart_items as $cart_item):
				$product = get_available_product($cart_item->product_id);
				if (!empty($product)) {
					$BasketItem = new \Iyzipay\Model\BasketItem();
					$BasketItem->setId($product->id);
					$BasketItem->setName($product->title);
					$BasketItem->setCategory1(trans("order"));
					$BasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
					$BasketItem->setPrice(price_format_decimal($cart_item->total_price));
					$basketItems[$count] = $BasketItem;
					$count++;
				}
			endforeach;
		endif;
		$request->setBasketItems($basketItems);
		# make request
		$checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);
		echo $checkoutFormInitialize->getcheckoutFormContent(); ?>
		<div id="iyzipay-checkout-form" class="responsive"></div>
	<?php endif; ?>

<?php endif; ?>
