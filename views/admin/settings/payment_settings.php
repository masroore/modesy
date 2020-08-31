<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row" style="margin-bottom: 15px;">
    <div class="col-sm-12">
        <h3 style="font-size: 18px; font-weight: 600;margin-top: 10px;"><?php echo trans('payment_settings'); ?></h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('paypal'); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('settings_controller/paypal_settings_post'); ?>
            <div class="box-body">
                <img src="<?php echo base_url(); ?>assets/img/payment/paypal.svg" alt="paypal" class="img-payment-logo">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata("mes_paypal"))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans("status"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="paypal_enabled" value="1" id="paypal_enabled_1"
                                   class="square-purple" <?php echo ($this->payment_settings->paypal_enabled == 1) ? 'checked' : ''; ?>>
                            <label for="paypal_enabled_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="paypal_enabled" value="0" id="paypal_enabled_2"
                                   class="square-purple" <?php echo ($this->payment_settings->paypal_enabled != 1) ? 'checked' : ''; ?>>
                            <label for="paypal_enabled_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans("mode"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="paypal_mode" value="live" id="paypal_mode_1"
                                   class="square-purple" <?php echo ($this->payment_settings->paypal_mode == 'live') ? 'checked' : ''; ?>>
                            <label for="paypal_mode_1" class="option-label"><?php echo trans("production"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="paypal_mode" value="sandbox" id="paypal_mode_2"
                                   class="square-purple" <?php echo ($this->payment_settings->paypal_mode == 'sandbox') ? 'checked' : ''; ?>>
                            <label for="paypal_mode_2" class="option-label"><?php echo trans("sandbox"); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('client_id'); ?></label>
                    <input type="text" class="form-control" name="paypal_client_id" placeholder="<?php echo trans('client_id'); ?>"
                           value="<?php echo $this->payment_settings->paypal_client_id; ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('secret_key'); ?></label>
                    <input type="text" class="form-control" name="paypal_secret_key" placeholder="<?php echo trans('secret_key'); ?>"
                           value="<?php echo $this->payment_settings->paypal_secret_key; ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <!-- /.box -->
            <?php echo form_close(); ?><!-- form end -->
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('paystack'); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('settings_controller/paystack_settings_post'); ?>
            <div class="box-body">
                <img src="<?php echo base_url(); ?>assets/img/payment/paystack.png" alt="paystack" class="img-payment-logo">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata("mes_paystack"))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans("status"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="paystack_enabled" value="1" id="paystack_enabled_1"
                                   class="square-purple" <?php echo ($this->payment_settings->paystack_enabled == 1) ? 'checked' : ''; ?>>
                            <label for="paystack_enabled_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="paystack_enabled" value="0" id="paystack_enabled_2"
                                   class="square-purple" <?php echo ($this->payment_settings->paystack_enabled != 1) ? 'checked' : ''; ?>>
                            <label for="paystack_enabled_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('secret_key'); ?></label>
                    <input type="text" class="form-control" name="paystack_secret_key" placeholder="<?php echo trans('secret_key'); ?>"
                           value="<?php echo $this->payment_settings->paystack_secret_key; ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('public_key'); ?></label>
                    <input type="text" class="form-control" name="paystack_public_key" placeholder="<?php echo trans('public_key'); ?>"
                           value="<?php echo $this->payment_settings->paystack_public_key; ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <!-- /.box -->
            <?php echo form_close(); ?><!-- form end -->
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('pagseguro'); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('settings_controller/pagseguro_settings_post'); ?>
            <div class="box-body">
                <img src="<?php echo base_url(); ?>assets/img/payment/pagseguro.png" alt="pagseguro" class="img-payment-logo">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata("mes_pagseguro"))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans("status"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="pagseguro_enabled" value="1" id="pagseguro_enabled_1"
                                   class="square-purple" <?php echo ($this->payment_settings->pagseguro_enabled == 1) ? 'checked' : ''; ?>>
                            <label for="pagseguro_enabled_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="pagseguro_enabled" value="0" id="pagseguro_enabled_2"
                                   class="square-purple" <?php echo ($this->payment_settings->pagseguro_enabled != 1) ? 'checked' : ''; ?>>
                            <label for="pagseguro_enabled_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans("mode"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="pagseguro_mode" value="production" id="pagseguro_mode_1"
                                   class="square-purple" <?php echo ($this->payment_settings->pagseguro_mode == 'production') ? 'checked' : ''; ?>>
                            <label for="pagseguro_mode_1" class="option-label"><?php echo trans("production"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="pagseguro_mode" value="sandbox" id="pagseguro_mode_2"
                                   class="square-purple" <?php echo ($this->payment_settings->pagseguro_mode == 'sandbox') ? 'checked' : ''; ?>>
                            <label for="pagseguro_mode_2" class="option-label"><?php echo trans("sandbox"); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('email'); ?></label>
                    <input type="email" class="form-control" name="pagseguro_email" placeholder="<?php echo trans('email'); ?>"
                           value="<?php echo $this->payment_settings->pagseguro_email; ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('token'); ?></label>
                    <input type="text" class="form-control" name="pagseguro_token" placeholder="<?php echo trans('token'); ?>"
                           value="<?php echo $this->payment_settings->pagseguro_token; ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <!-- /.box -->
            <?php echo form_close(); ?><!-- form end -->
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('bank_transfer'); ?></h3><br>
                <small><?php echo trans("bank_transfer_exp"); ?></small>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('settings_controller/bank_transfer_settings_post'); ?>
            <div class="box-body">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata("mes_bank_transfer"))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans("status"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="bank_transfer_enabled" value="1" id="bank_transfer_enabled_1"
                                   class="square-purple" <?php echo ($this->payment_settings->bank_transfer_enabled == 1) ? 'checked' : ''; ?>>
                            <label for="bank_transfer_enabled_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="bank_transfer_enabled" value="0" id="bank_transfer_enabled_2"
                                   class="square-purple" <?php echo ($this->payment_settings->bank_transfer_enabled != 1) ? 'checked' : ''; ?>>
                            <label for="bank_transfer_enabled_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('bank_accounts'); ?></label>
                    <textarea id="ckEditorBankAccounts" class="form-control" name="bank_transfer_accounts"><?php echo $this->payment_settings->bank_transfer_accounts; ?></textarea>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <!-- /.box -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
    </div>
    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('stripe'); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('settings_controller/stripe_settings_post'); ?>
            <div class="box-body">
                <img src="<?php echo base_url(); ?>assets/img/payment/stripe.svg" alt="stripe" class="img-payment-logo">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata("mes_stripe"))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans("status"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="stripe_enabled" value="1" id="stripe_enabled_1"
                                   class="square-purple" <?php echo ($this->payment_settings->stripe_enabled == 1) ? 'checked' : ''; ?>>
                            <label for="stripe_enabled_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="stripe_enabled" value="0" id="stripe_enabled_2"
                                   class="square-purple" <?php echo ($this->payment_settings->stripe_enabled != 1) ? 'checked' : ''; ?>>
                            <label for="stripe_enabled_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('publishable_key'); ?></label>
                    <input type="text" class="form-control" name="stripe_publishable_key" placeholder="<?php echo trans('publishable_key'); ?>"
                           value="<?php echo $this->payment_settings->stripe_publishable_key; ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('secret_key'); ?></label>
                    <input type="text" class="form-control" name="stripe_secret_key" placeholder="<?php echo trans('secret_key'); ?>"
                           value="<?php echo $this->payment_settings->stripe_secret_key; ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <!-- /.box -->
            <?php echo form_close(); ?><!-- form end -->
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Razorpay</h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('settings_controller/razorpay_settings_post'); ?>
            <div class="box-body">
                <img src="<?php echo base_url(); ?>assets/img/payment/razorpay.svg" alt="razorpay" class="img-payment-logo">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata("mes_razorpay"))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans("status"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="razorpay_enabled" value="1" id="razorpay_enabled_1"
                                   class="square-purple" <?php echo ($this->payment_settings->razorpay_enabled == 1) ? 'checked' : ''; ?>>
                            <label for="razorpay_enabled_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="razorpay_enabled" value="0" id="razorpay_enabled_2"
                                   class="square-purple" <?php echo ($this->payment_settings->razorpay_enabled != 1) ? 'checked' : ''; ?>>
                            <label for="razorpay_enabled_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('api_key'); ?></label>
                    <input type="text" class="form-control" name="razorpay_key_id" placeholder="<?php echo trans('api_key'); ?>"
                           value="<?php echo $this->payment_settings->razorpay_key_id; ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('secret_key'); ?></label>
                    <input type="text" class="form-control" name="razorpay_key_secret" placeholder="<?php echo trans('secret_key'); ?>"
                           value="<?php echo $this->payment_settings->razorpay_key_secret; ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <!-- /.box -->
            <?php echo form_close(); ?><!-- form end -->
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('iyzico'); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('settings_controller/iyzico_settings_post'); ?>
            <div class="box-body">
                <img src="<?php echo base_url(); ?>assets/img/payment/iyzico.svg" alt="iyzico" class="img-payment-logo">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata("mes_iyzico"))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans("status"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="iyzico_enabled" value="1" id="iyzico_enabled_1"
                                   class="square-purple" <?php echo ($this->payment_settings->iyzico_enabled == 1) ? 'checked' : ''; ?>>
                            <label for="iyzico_enabled_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="iyzico_enabled" value="0" id="iyzico_enabled_2"
                                   class="square-purple" <?php echo ($this->payment_settings->iyzico_enabled != 1) ? 'checked' : ''; ?>>
                            <label for="iyzico_enabled_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans("mode"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="iyzico_mode" value="live" id="iyzico_mode_1"
                                   class="square-purple" <?php echo ($this->payment_settings->iyzico_mode == 'live') ? 'checked' : ''; ?>>
                            <label for="iyzico_mode_1" class="option-label"><?php echo trans("production"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="iyzico_mode" value="sandbox" id="iyzico_mode_2"
                                   class="square-purple" <?php echo ($this->payment_settings->iyzico_mode == 'sandbox') ? 'checked' : ''; ?>>
                            <label for="iyzico_mode_2" class="option-label"><?php echo trans("sandbox"); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans("type"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="iyzico_type" value="checkout_form" id="checkout_form_1"
                                   class="square-purple" <?php echo ($this->payment_settings->iyzico_type == "checkout_form") ? 'checked' : ''; ?>>
                            <label for="checkout_form_1" class="option-label"><?php echo trans('checkout_form'); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="iyzico_type" value="marketplace" id="marketplace_1"
                                   class="square-purple" <?php echo ($this->payment_settings->iyzico_type == "marketplace") ? 'checked' : ''; ?>>
                            <label for="marketplace_1" class="option-label"><?php echo trans('marketplace'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('api_key'); ?></label>
                    <input type="text" class="form-control" name="iyzico_api_key" placeholder="<?php echo trans('api_key'); ?>"
                           value="<?php echo $this->payment_settings->iyzico_api_key; ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('secret_key'); ?></label>
                    <input type="text" class="form-control" name="iyzico_secret_key" placeholder="<?php echo trans('secret_key'); ?>"
                           value="<?php echo $this->payment_settings->iyzico_secret_key; ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

                <div id="form_submerchant" <?php echo ($this->payment_settings->iyzico_type == "marketplace") ? "style='display: block'" : "style='display: none'"; ?>>
                    <div class="form-group">
                        <label><?php echo trans('submerchant_key'); ?></label>
                        <input type="text" class="form-control" name="iyzico_submerchant_key" placeholder="<?php echo trans('submerchant_key'); ?>" value="<?php echo $this->payment_settings->iyzico_submerchant_key; ?>">
                    </div>
                    <button type="button" class="btn btn-success" data-toggle="collapse" data-target="#iyzico_collapse"><?php echo trans("create_key"); ?></button>
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="iyzico_collapse" class="m-t-30 collapse <?php echo (!empty($this->session->flashdata("iyzico_show_form"))) ? 'in' : ''; ?>">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                            <label><?php echo trans('submerchant') . " " . trans("type"); ?></label>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12 col-option">
                                            <input type="radio" name="submerchant_type" value="PERSONAL" id="submerchant_type_1"
                                                   class="square-purple" <?php echo (empty(old("submerchant_type")) || old("submerchant_type") == 'PERSONAL') ? 'checked' : ''; ?>>
                                            <label for="submerchant_type_1" class="option-label">PERSONAL</label>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12 col-option">
                                            <input type="radio" name="submerchant_type" value="PRIVATE_COMPANY" id="submerchant_type_2"
                                                   class="square-purple" <?php echo (old("submerchant_type") == 'PRIVATE_COMPANY') ? 'checked' : ''; ?>>
                                            <label for="submerchant_type_2" class="option-label">PRIVATE_COMPANY</label>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-option">
                                            <input type="radio" name="submerchant_type" value="LIMITED_OR_JOINT_STOCK_COMPANY" id="submerchant_type_3"
                                                   class="square-purple" <?php echo (old("submerchant_type") == 'LIMITED_OR_JOINT_STOCK_COMPANY') ? 'checked' : ''; ?>>
                                            <label for="submerchant_type_3" class="option-label">LIMITED_OR_JOINT_STOCK_COMPANY</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                            <label><?php echo trans("first_name"); ?></label>
                                            <input type="text" class="form-control" name="first_name" placeholder="John" value="<?php echo old("first_name"); ?>">
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <label><?php echo trans("last_name"); ?></label>
                                            <input type="text" class="form-control" name="last_name" placeholder="Doe" value="<?php echo old("last_name"); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                            <label><?php echo trans("email"); ?></label>
                                            <input type="text" class="form-control" name="email" placeholder="email@submerchantemail.com" value="<?php echo old("email"); ?>">
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <label><?php echo trans("phone_number"); ?></label>
                                            <input type="text" class="form-control" name="phone_number" placeholder="+905350000000" value="<?php echo old("phone_number"); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                            <label><?php echo trans("identity_number"); ?></label>
                                            <input type="text" class="form-control" name="identity_number" placeholder="31300864726" value="<?php echo old("identity_number"); ?>">
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <label><?php echo trans("iban"); ?></label>
                                            <input type="text" class="form-control" name="iban" placeholder="TR180006200119000006672315" value="<?php echo old("iban"); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                            <label><?php echo trans('submerchant') . " " . trans("id"); ?></label>
                                            <input type="text" class="form-control" name="submerchant_id" placeholder="B49224" value="<?php echo old("submerchant_id"); ?>">
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <label><?php echo trans('submerchant') . " " . trans("name"); ?></label>
                                            <input type="text" class="form-control" name="submerchant_name" placeholder="John's market" value="<?php echo old("submerchant_name"); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4 col-xs-12">
                                            <label><?php echo trans("tax_office"); ?></label>
                                            <input type="text" class="form-control" name="tax_office" placeholder="<?php echo trans("optional"); ?>" value="<?php echo old("tax_office"); ?>">
                                        </div>
                                        <div class="col-sm-4 col-xs-12">
                                            <label><?php echo trans("tax_number"); ?></label>
                                            <input type="text" class="form-control" name="tax_number" placeholder="<?php echo trans("optional"); ?>" value="<?php echo old("tax_number"); ?>">
                                        </div>
                                        <div class="col-sm-4 col-xs-12">
                                            <label><?php echo trans("company_title"); ?></label>
                                            <input type="text" class="form-control" name="company_title" placeholder="<?php echo trans("optional"); ?>" value="<?php echo old("company_title"); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                            <label><?php echo trans("address"); ?></label>
                                            <input type="text" class="form-control" name="address" placeholder="<?php echo trans('address'); ?>" value="<?php echo old("address"); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <!-- /.box -->
            <?php echo form_close(); ?><!-- form end -->
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('cash_on_delivery'); ?></h3><br>
                <small><?php echo trans("cash_on_delivery_exp"); ?></small>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('settings_controller/cash_on_delivery_settings_post'); ?>
            <div class="box-body">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata("mes_cash_on_delivery"))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans("status"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="cash_on_delivery_enabled" value="1" id="cash_on_delivery_enabled_1"
                                   class="square-purple" <?php echo ($this->payment_settings->cash_on_delivery_enabled == 1) ? 'checked' : ''; ?>>
                            <label for="cash_on_delivery_enabled_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="cash_on_delivery_enabled" value="0" id="cash_on_delivery_enabled_2"
                                   class="square-purple" <?php echo ($this->payment_settings->cash_on_delivery_enabled != 1) ? 'checked' : ''; ?>>
                            <label for="cash_on_delivery_enabled_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <!-- /.box -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
    </div>
</div>

<style>
    .img-payment-logo {
        height: 28px;
        position: absolute;
        right: 15px;
        top: 15px;
    }
</style>

<script>
    $('input[name=iyzico_type]').on('ifChecked', function (event) {
        var value = $(this).val();
        if (value == "marketplace") {
            $("#form_submerchant").show();
        } else {
            $("#form_submerchant").hide();
        }
    });
</script>
