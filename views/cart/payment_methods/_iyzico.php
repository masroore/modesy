<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($cart_payment_method->payment_option == "iyzico"): ?>

    <?php
    $options = initialize_iyzico();
    $conversation_id = generate_short_unique_id();

    if (!$this->auth_check) {
        $user = new  stdClass();
        $user->id = 0;
        $user->username = @$shipping_address->shipping_first_name . " " . $shipping_address->shipping_last_name;
        if (empty($user->username)) {
            $user->username = trans("guest");
        }
        $user->phone_number = @$shipping_address->shipping_phone_number;
        if (empty($user->phone_number)) {
            $user->phone_number = "11111111";
        }
        $user->email = @$shipping_address->shipping_email;
        if (empty($user->email)) {
            $user->email = "unknown@domain.com";
        }
    } else {
        $user = $this->auth_user;
    }

    $ci =& get_instance();
    $country_name = "Turkey";
    if ($mds_payment_type == 'promote') {
        $item_basket_name = $promoted_plan->purchased_plan;
        $item_basket_category = trans("promote_plan");
        $item_basket_price = get_price($total_amount, 'decimal');
        $callback_url = base_url() . "iyzico-payment-post?payment_type=promote&lang=" . $this->selected_lang->short_form . "&conversation_id=" . $conversation_id;
    } else {
        $product_ids = "";
        $i = 0;
        if (!empty($cart_items)) {
            foreach ($cart_items as $cart_item) {
                if ($i != 0) {
                    $product_ids .= ", ";
                }
                $product_ids .= $cart_item->product_id;
                $i++;
            }
        }
        $item_basket_name = trans("product") . " (" . $product_ids . ")";
        $item_basket_category = trans("sale");
        $item_basket_price = get_price($cart_total->total, 'decimal');
        $callback_url = base_url() . "iyzico-payment-post?payment_type=sale&lang=" . $this->selected_lang->short_form . "&conversation_id=" . $conversation_id;
        $country = get_country($shipping_address->shipping_country_id);
        if (!empty($country)) {
            $country_name = $country->name;
        }
    }

    $buyer_id = "guest_" . uniqid();
    if ($this->auth_check) {
        $buyer_id = $this->auth_user->id;
    }
    $ip = $this->input->ip_address();
    if (empty($ip)) {
        $ip = "85.34.78.112";
    }
    # create request class
    $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
    $request->setLocale(\Iyzipay\Model\Locale::TR);
    $request->setConversationId($conversation_id);
    $request->setPrice($item_basket_price);
    $request->setPaidPrice($item_basket_price);
    $request->setCurrency(\Iyzipay\Model\Currency::TL);
    $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
    $request->setCallbackUrl($callback_url);
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
    $BasketItem = new \Iyzipay\Model\BasketItem();
    $BasketItem->setId("0");
    $BasketItem->setName($item_basket_name);
    $BasketItem->setCategory1($item_basket_category);
    $BasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
    $BasketItem->setPrice($item_basket_price);
    if ($this->payment_settings->iyzico_type == "marketplace") {
        $BasketItem->setSubMerchantKey($this->payment_settings->iyzico_submerchant_key);
        $BasketItem->setSubMerchantPrice($item_basket_price);
    }
    $basketItems[0] = $BasketItem;

    $request->setBasketItems($basketItems);
    # make request
    $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);

    if ($checkoutFormInitialize->getStatus() == "failure") {
        $this->session->set_flashdata('error', $checkoutFormInitialize->getErrorMessage());
    } else {
        echo $checkoutFormInitialize->getcheckoutFormContent();
    } ?>

    <div class="row">
        <div class="col-12">
            <!-- include message block -->
            <?php $this->load->view('product/_messages'); ?>
        </div>
    </div>

    <div id="iyzipay-checkout-form" class="responsive"></div>
<?php endif; ?>
<?php reset_flash_data(); ?>