<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('system_settings'); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('admin_controller/system_settings_post'); ?>

            <div class="box-body">
                <!-- include message block -->
                <?php $this->load->view('admin/includes/_messages'); ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12 m-b-10">
                            <label><strong><?php echo trans('physical_products'); ?></strong></label>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="physical_products_system" value="1" id="physical_products_1" class="square-purple" <?php echo ($general_settings->physical_products_system == 1) ? 'checked' : ''; ?>>
                            <label for="physical_products_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="physical_products_system" value="0" id="physical_products_2" class="square-purple" <?php echo ($general_settings->physical_products_system != 1) ? 'checked' : ''; ?>>
                            <label for="physical_products_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12 m-b-10">
                            <label><strong><?php echo trans('digital_products'); ?></strong></label>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="digital_products_system" value="1" id="digital_products_1" class="square-purple" <?php echo ($general_settings->digital_products_system == 1) ? 'checked' : ''; ?>>
                            <label for="digital_products_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="digital_products_system" value="0" id="digital_products_2" class="square-purple" <?php echo ($general_settings->digital_products_system != 1) ? 'checked' : ''; ?>>
                            <label for="digital_products_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12 m-b-10">
                            <label><strong><?php echo trans('marketplace_selling_product_on_the_site'); ?></strong></label>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="marketplace_system" value="1" id="marketplace_system_1" class="square-purple" <?php echo ($general_settings->marketplace_system == 1) ? 'checked' : ''; ?>>
                            <label for="marketplace_system_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="marketplace_system" value="0" id="marketplace_system_2" class="square-purple" <?php echo ($general_settings->marketplace_system != 1) ? 'checked' : ''; ?>>
                            <label for="marketplace_system_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12 m-b-10">
                            <label><strong><?php echo trans('classified_ads_adding_product_as_listing'); ?></strong></label>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="classified_ads_system" value="1" id="classified_ads_system_1" class="square-purple" <?php echo ($general_settings->classified_ads_system == 1) ? 'checked' : ''; ?>>
                            <label for="classified_ads_system_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="classified_ads_system" value="0" id="classified_ads_system_2" class="square-purple" <?php echo ($general_settings->classified_ads_system != 1) ? 'checked' : ''; ?>>
                            <label for="classified_ads_system_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12 m-b-10">
                            <label><?php echo trans('multi_vendor_system'); ?></label>
                            <small style="font-size: 13px;">(<?php echo trans("multi_vendor_system_exp"); ?>)</small>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="multi_vendor_system" value="1" id="multi_vendor_system_1" class="square-purple" <?php echo ($general_settings->multi_vendor_system == 1) ? 'checked' : ''; ?>>
                            <label for="multi_vendor_system_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="multi_vendor_system" value="0" id="multi_vendor_system_2" class="square-purple" <?php echo ($general_settings->multi_vendor_system != 1) ? 'checked' : ''; ?>>
                            <label for="multi_vendor_system_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo trans('commission_rate'); ?></label>
                    <input type="number" name="commission_rate" class="form-control" min="0" max="100" value="<?php echo $general_settings->commission_rate; ?>">
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('timezone'); ?></label>
                    <input type="text" class="form-control" name="timezone" placeholder="<?php echo trans('timezone'); ?>"
                           value="<?php echo html_escape($general_settings->timezone); ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
                    <a href="http://php.net/manual/en/timezones.php" target="_blank">Timeszones</a>
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