<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row" style="margin-bottom: 15px;">
    <div class="col-sm-12">
        <h3 style="font-size: 18px; font-weight: 600;"><?php echo trans('social_login'); ?></h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('facebook_login'); ?></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <?php echo form_open('admin_controller/facebook_login_post'); ?>

            <div class="box-body">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata('mes_social_facebook'))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>
                <div class="form-group">
                    <label class="label-sitemap"><?php echo trans('app_id'); ?></label>
                    <input type="text" class="form-control" name="facebook_app_id"
                           placeholder="<?php echo trans('app_id'); ?>"
                           value="<?php echo $general_settings->facebook_app_id; ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
                <div class="form-group">
                    <label class="label-sitemap"><?php echo trans('app_secret'); ?></label>
                    <input type="text" class="form-control" name="facebook_app_secret"
                           placeholder="<?php echo trans('app_secret'); ?>"
                           value="<?php echo $general_settings->facebook_app_secret; ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
    </div>
    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('google_login'); ?></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <?php echo form_open('admin_controller/google_login_post'); ?>
            <div class="box-body">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata('mes_social_google'))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>
                <div class="form-group">
                    <label class="label-sitemap"><?php echo trans('client_id'); ?></label>
                    <input type="text" class="form-control" name="google_client_id"
                           placeholder="<?php echo trans('client_id'); ?>"
                           value="<?php echo $general_settings->google_client_id; ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

                <div class="form-group">
                    <label class="label-sitemap"><?php echo trans('client_secret'); ?></label>
                    <input type="text" class="form-control" name="google_client_secret"
                           placeholder="<?php echo trans('client_secret'); ?>"
                           value="<?php echo $general_settings->google_client_secret; ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
    </div>
</div>