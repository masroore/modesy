<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('update_page'); ?></h3>
            </div><!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('page_controller/update_page_post'); ?>
            <input type="hidden" name="id" value="<?php echo $page->id; ?>">

            <div class="box-body">
                <!-- include message block -->
                <?php $this->load->view('admin/includes/_messages'); ?>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('title'); ?></label>
                    <input type="text" class="form-control" name="title" placeholder="<?php echo trans('title'); ?>"
                           value="<?php echo $page->title; ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?> required>
                </div>
                <?php if ($page->slug != "terms-conditions"): ?>
                    <div class="form-group">
                        <label class="control-label"><?php echo trans("slug"); ?>
                            <small>(<?php echo trans("slug_exp"); ?>)</small>
                        </label>
                        <input type="text" class="form-control" name="slug" placeholder="<?php echo trans("slug"); ?>"
                               value="<?php echo $page->slug; ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="slug" value="<?php echo $page->slug; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label class="control-label"><?php echo trans("description"); ?> (<?php echo trans('meta_tag'); ?>)</label>
                    <input type="text" class="form-control" name="description"
                           placeholder="<?php echo trans("description"); ?> (<?php echo trans('meta_tag'); ?>)" value="<?php echo $page->description; ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('keywords'); ?> (<?php echo trans('meta_tag'); ?>)</label>
                    <input type="text" class="form-control" name="keywords"
                           placeholder="<?php echo trans('keywords'); ?> (<?php echo trans('meta_tag'); ?>)" value="<?php echo $page->keywords; ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

                <div class="form-group">
                    <label><?php echo trans("language"); ?></label>
                    <select name="lang_id" class="form-control" style="max-width: 600px;">
                        <?php foreach ($languages as $language): ?>
                            <option value="<?php echo $language->id; ?>" <?php echo ($page->lang_id == $language->id) ? 'selected' : ''; ?>><?php echo $language->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label><?php echo trans('order'); ?></label>
                    <input type="number" class="form-control" name="page_order" placeholder="<?php echo trans('order'); ?>" value="<?php echo $page->page_order; ?>" min="1" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?> style="max-width: 600px;">
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3 col-xs-12">
                            <label><?php echo trans('location'); ?></label>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="location" value="quick_links" id="menu_quick_links" class="square-purple" <?php echo ($page->location == "quick_links") ? 'checked' : ''; ?>>
                            <label for="menu_quick_links" class="option-label"><?php echo trans('footer_quick_links'); ?></label>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="location" value="information" id="menu_information" class="square-purple" <?php echo ($page->location == "information") ? 'checked' : ''; ?>>
                            <label for="menu_information" class="option-label"><?php echo trans('footer_information'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3 col-xs-12">
                            <label><?php echo trans('visibility'); ?></label>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="visibility" value="1" id="page_enabled"
                                   class="square-purple" <?php echo ($page->visibility == 1) ? 'checked' : ''; ?>>
                            <label for="page_enabled" class="option-label"><?php echo trans('show'); ?></label>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="visibility" value="0" id="page_disabled"
                                   class="square-purple" <?php echo ($page->visibility == 0) ? 'checked' : ''; ?>>
                            <label for="page_disabled" class="option-label"><?php echo trans('hide'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3 col-xs-12">
                            <label><?php echo trans('show_title'); ?></label>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="title_active" value="1" id="title_enabled"
                                   class="square-purple" <?php echo ($page->title_active == 1) ? 'checked' : ''; ?>>
                            <label for="title_enabled" class="option-label"><?php echo trans('yes'); ?></label>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="title_active" value="0" id="title_disabled"
                                   class="square-purple" <?php echo ($page->title_active == 0) ? 'checked' : ''; ?>>
                            <label for="title_disabled" class="option-label"><?php echo trans('no'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 30px;">
                    <label><?php echo trans('content'); ?></label>
					<div class="row">
						<div class="col-sm-12 m-b-10">
							<button type="button" class="btn btn-sm bg-purple color-white btn_ck_add_image"><i class="icon-image"></i><?php echo trans("add_image"); ?></button>
							<button type="button" class="btn btn-sm btn-info color-white btn_ck_add_video"><i class="icon-image"></i><?php echo trans("add_video"); ?></button>
							<button type="button" class="btn btn-sm btn-warning color-white btn_ck_add_iframe"><i class="icon-image"></i><?php echo trans("add_iframe"); ?></button>
						</div>
					</div>
                    <textarea id="ckEditor" class="form-control" name="page_content"
                              placeholder="Content"><?php echo $page->page_content; ?></textarea>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->

            <?php echo form_close(); ?><!-- form end -->
        </div>
        <!-- /.box -->
    </div>
</div>
