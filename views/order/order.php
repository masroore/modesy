<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $title; ?></li>
                    </ol>
                </nav>

                <h1 class="page-title"><?php echo $title; ?></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-3">
                <div class="row-custom">
                    <!-- load profile nav -->
                    <?php $this->load->view("order/_order_tabs"); ?>
                </div>
            </div>

            <div class="col-sm-12 col-md-9">
                <div class="row">
                    <div class="col-12">
                        <!-- include message block -->
                        <?php $this->load->view('product/_messages'); ?>
                    </div>
                </div>

                <div class="order-details-container">
                    <div class="order-head">
                        <h2 class="title"><?php echo trans("order"); ?>:&nbsp;#<?php echo $order->order_number; ?></h2>
                    </div>
                    <div class="order-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row order-row-item">
                                    <div class="col-3">
                                        <?php echo trans("status"); ?>
                                    </div>
                                    <div class="col-9">
                                        <?php if ($order->status == 1): ?>
                                            <strong><?php echo trans("completed"); ?></strong>
                                        <?php else: ?>
                                            <strong><?php echo trans("order_processing"); ?></strong>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="row order-row-item">
                                    <div class="col-3">
                                        <?php echo trans("payment_status"); ?>
                                    </div>
                                    <div class="col-9">
                                        <?php echo trans($order->payment_status); ?>

                                        <?php if ($order->payment_method == "Bank Transfer" && $order->payment_status == "awaiting_payment"):

                                            if (isset($last_bank_transfer)):?>
                                                <?php if ($last_bank_transfer->status == "pending"): ?>
                                                    <span class="text-info">(<?php echo trans("pending"); ?>)</span>
                                                <?php elseif ($last_bank_transfer->status == "declined"): ?>
                                                    <span class="text-danger">(<?php echo trans("bank_transfer_declined"); ?>)</span>
                                                    <button type="button" class="btn btn-sm btn-secondary color-white m-l-15" data-toggle="modal" data-target="#reportPaymentModal"><?php echo trans("report_bank_transfer"); ?></button>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-secondary color-white m-l-15" data-toggle="modal" data-target="#reportPaymentModal"><?php echo trans("report_bank_transfer"); ?></button>
                                            <?php endif; ?>


                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="row order-row-item">
                                    <div class="col-3">
                                        <?php echo trans("payment_method"); ?>
                                    </div>
                                    <div class="col-9">
                                        <?php
                                        if ($order->payment_method == "Bank Transfer") {
                                            echo trans("bank_transfer");
                                        } else {
                                            echo $order->payment_method;
                                        } ?>
                                    </div>
                                </div>
                                <div class="row order-row-item">
                                    <div class="col-3">
                                        <?php echo trans("date"); ?>
                                    </div>
                                    <div class="col-9">
                                        <?php echo date("Y-m-d / h:i", strtotime($order->created_at)); ?>
                                    </div>
                                </div>
                                <div class="row order-row-item">
                                    <div class="col-3">
                                        <?php echo trans("updated"); ?>
                                    </div>
                                    <div class="col-9">
                                        <?php echo time_ago($order->updated_at); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php $shipping = get_order_shipping($order->id);
                        if (!empty($shipping)):?>
                            <div class="row shipping-container">
                                <div class="col-md-12 col-lg-6 m-b-sm-15">
                                    <h3 class="block-title"><?php echo trans("shipping_address"); ?></h3>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("first_name"); ?>
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->shipping_first_name; ?>
                                        </div>
                                    </div>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("last_name"); ?>
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->shipping_last_name; ?>
                                        </div>
                                    </div>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("email"); ?>
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->shipping_email; ?>
                                        </div>
                                    </div>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("phone_number"); ?>
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->shipping_phone_number; ?>
                                        </div>
                                    </div>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("address"); ?>&nbsp;1
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->shipping_address_1; ?>
                                        </div>
                                    </div>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("address"); ?>&nbsp;2
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->shipping_address_2; ?>
                                        </div>
                                    </div>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("country"); ?>
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->shipping_country; ?>
                                        </div>
                                    </div>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("state"); ?>
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->shipping_state; ?>
                                        </div>
                                    </div>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("city"); ?>
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->shipping_city; ?>
                                        </div>
                                    </div>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("zip_code"); ?>
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->shipping_zip_code; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <h3 class="block-title"><?php echo trans("billing_address"); ?></h3>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("first_name"); ?>
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->billing_first_name; ?>
                                        </div>
                                    </div>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("last_name"); ?>
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->billing_last_name; ?>
                                        </div>
                                    </div>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("email"); ?>
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->billing_email; ?>
                                        </div>
                                    </div>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("phone_number"); ?>
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->billing_phone_number; ?>
                                        </div>
                                    </div>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("address"); ?>&nbsp;1
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->billing_address_1; ?>
                                        </div>
                                    </div>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("address"); ?>&nbsp;2
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->billing_address_2; ?>
                                        </div>
                                    </div>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("country"); ?>
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->billing_country; ?>
                                        </div>
                                    </div>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("state"); ?>
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->billing_state; ?>
                                        </div>
                                    </div>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("city"); ?>
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->billing_city; ?>
                                        </div>
                                    </div>
                                    <div class="row shipping-row-item">
                                        <div class="col-5">
                                            <?php echo trans("zip_code"); ?>
                                        </div>
                                        <div class="col-7">
                                            <?php echo $shipping->billing_zip_code; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php $is_order_has_physical_product = false; ?>
                        <div class="row table-orders-container">
                            <div class="col-12">
                                <h3 class="block-title"><?php echo trans("products"); ?></h3>
                                <div class="table-responsive">
                                    <table class="table table-orders">
                                        <thead>
                                        <tr>
                                            <th scope="col"><?php echo trans("product"); ?></th>
                                            <th scope="col"><?php echo trans("status"); ?></th>
                                            <th scope="col"><?php echo trans("updated"); ?></th>
                                            <th scope="col"><?php echo trans("options"); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($order_products as $item):
                                            if ($item->product_type == 'physical') {
                                                $is_order_has_physical_product = true;
                                            } ?>
                                            <tr>
                                                <td>
                                                    <div class="product-item-table">
                                                        <div class="left">
                                                            <div class="img-table">
                                                                <a href="<?php echo base_url() . $item->product_slug; ?>" target="_blank">
                                                                    <img src="<?php echo get_product_image($item->product_id, 'image_small'); ?>" data-src="" alt="" class="lazyload img-responsive post-image"/>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="right">
                                                            <a href="<?php echo base_url() . $item->product_slug; ?>" target="_blank" class="table-product-title">
                                                                <?php echo html_escape($item->product_title); ?>
                                                            </a>
                                                            <p>
                                                                <span><?php echo trans("seller"); ?>:</span>
                                                                <?php $seller = get_user($item->seller_id); ?>
                                                                <?php if (!empty($seller)): ?>
                                                                    <a href="<?php echo base_url(); ?>profile/<?php echo $seller->slug; ?>" target="_blank" class="table-product-title">
                                                                        <strong class="font-600"><?php echo get_shop_name($seller); ?></strong>
                                                                    </a>
                                                                <?php endif; ?>
                                                            </p>
                                                            <p><?php echo trans("unit_price"); ?>:&nbsp;<?php echo print_price($item->product_unit_price, $item->product_currency); ?></p>
                                                            <p><?php echo trans("quantity"); ?>:&nbsp;<?php echo $item->product_quantity; ?></p>
                                                            <?php if ($item->product_type == 'physical'): ?>
                                                                <p><?php echo trans("shipping"); ?>:&nbsp;<?php echo print_price($item->product_shipping_cost, $item->product_currency); ?></p>
                                                            <?php endif; ?>
                                                            <p><?php echo trans("total"); ?>:&nbsp;<?php echo print_price($item->product_total_price, $item->product_currency); ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <strong><?php echo trans($item->order_status) ?></strong>
                                                </td>
                                                <td>
                                                    <?php if ($item->product_type == 'physical') {
                                                        echo time_ago($item->updated_at);
                                                    } ?>
                                                </td>
                                                <td style="min-width: 180px;">
                                                    <?php if ($item->product_type == 'digital'):
                                                        $digital_sale = get_digital_sale_by_order_id($item->buyer_id, $item->product_id, $item->order_id);
                                                        if (!empty($digital_sale)):?>
                                                            <div class="row-custom">
                                                                <?php echo form_open('file_controller/download_purchased_digital_file'); ?>
                                                                <input type="hidden" name="sale_id" value="<?php echo $digital_sale->id; ?>">
                                                                <button type="submit" class="btn btn-md btn-custom">
                                                                    <i class="icon-download-solid"></i>&nbsp;&nbsp;<?php echo trans("download"); ?>
                                                                </button>
                                                                <?php echo form_close(); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <?php if ($item->order_status == "completed"): ?>
                                                            <strong class="font-600"><i class="icon-check"></i>&nbsp;<?php echo trans("approved"); ?></strong>
                                                        <?php else: ?>
                                                            <?php if ($item->order_status == "shipped"): ?>
                                                                <button type="submit" class="btn btn-sm btn-custom" onclick="approve_order_product('<?php echo $item->id; ?>','<?php echo trans("confirm_approve_order"); ?>');"><?php echo trans("approve"); ?></button>
                                                                <small class="text-confirm-order-table"><?php echo trans("warning_buyer_approve_order_short"); ?></small>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php if (!empty($item->shipping_tracking_number)): ?>
                                            <tr class="tr-shipping">
                                                <td colspan="4">
                                                    <div class="order-shipping-tracking-number">
                                                        <p><strong><?php echo trans("shipping") ?></strong></p>
                                                        <p><?php echo trans("tracking_number") ?>:&nbsp;<?php echo html_escape($item->shipping_tracking_number); ?></p>
                                                        <p><?php echo trans("url") ?>:&nbsp;<?php echo html_escape($item->shipping_tracking_url); ?></p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="tr-shipping-seperator">
                                                <td colspan="4"></td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="order-total">
                                    <div class="row">
                                        <div class="col-6 col-left">
                                            <?php echo trans("subtotal"); ?>
                                        </div>
                                        <div class="col-6 col-right">
                                            <strong class="font-600"><?php echo print_price($order->price_subtotal, $order->price_currency); ?></strong>
                                        </div>
                                    </div>
                                    <?php if ($is_order_has_physical_product): ?>
                                        <div class="row">
                                            <div class="col-6 col-left">
                                                <?php echo trans("shipping"); ?>
                                            </div>
                                            <div class="col-6 col-right">
                                                <strong class="font-600"><?php echo print_price($order->price_shipping, $order->price_currency); ?></strong>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row-seperator"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 col-left">
                                            <?php echo trans("total"); ?>
                                        </div>
                                        <div class="col-6 col-right">
                                            <strong class="font-600"><?php echo print_price($order->price_total, $order->price_currency); ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (!empty($shipping)): ?>
                    <p class="text-confirm-order">*<?php echo trans("warning_buyer_approve_order"); ?></p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<!-- Wrapper End-->

<!-- Modal -->
<div class="modal fade" id="reportPaymentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-custom">
            <!-- form start -->
            <?php echo form_open_multipart('order_controller/bank_transfer_payment_report_post'); ?>
            <div class="modal-header">
                <h5 class="modal-title"><?php echo trans("report_bank_transfer"); ?></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true"><i class="icon-close"></i> </span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="order_number" class="form-control form-input" value="<?php echo $order->order_number; ?>">
                <div class="form-group">
                    <label><?php echo trans("payment_note"); ?></label>
                    <textarea name="payment_note" class="form-control form-textarea" maxlength="499"></textarea>
                </div>
                <div class="form-group">
                    <label><?php echo trans("receipt"); ?>
                        <small>(.png, .jpg, .jpeg)</small>
                    </label>
                    <p>
                        <a class='btn btn-md btn-secondary btn-file-upload'>
                            <?php echo trans('select_image'); ?>
                            <input type="file" name="file" size="40" accept=".png, .jpg, .jpeg" onchange="$('#upload-file-info').html($(this).val());">
                        </a><br>
                        <span class='badge badge-info' id="upload-file-info"></span>
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-md btn-red" data-dismiss="modal"><?php echo trans("close"); ?></button>
                <button type="submit" class="btn btn-md btn-custom"><?php echo trans("submit"); ?></button>
            </div>
            <?php echo form_close(); ?><!-- form end -->
        </div>
    </div>
</div>

<?php if (!empty($this->session->userdata('mds_send_email_order_summary'))): ?>
    <script>
        $(document).ready(function () {
            var data = {
                "order_id": '<?php echo $order->id; ?>',
                'lang_folder': lang_folder,
                'form_lang_base_url': '<?php echo lang_base_url(); ?>'
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "ajax_controller/send_email_order_summary",
                data: data,
                success: function (response) {
                }
            });
        });
    </script>
    <?php
    $this->session->unset_userdata('mds_send_email_order_summary');
endif; ?>
