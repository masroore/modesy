<div class="row table-filter-container">
    <div class="col-sm-12">
        <?php echo form_open($form_action, ['method' => 'GET']); ?>

        <div class="item-table-filter" style="width: 80px; min-width: 80px;">
            <label><?php echo trans("show"); ?></label>
            <select name="show" class="form-control">
                <option value="15" <?php echo ($this->input->get('show', true) == '15') ? 'selected' : ''; ?>>15</option>
                <option value="30" <?php echo ($this->input->get('show', true) == '30') ? 'selected' : ''; ?>>30</option>
                <option value="60" <?php echo ($this->input->get('show', true) == '60') ? 'selected' : ''; ?>>60</option>
                <option value="100" <?php echo ($this->input->get('show', true) == '100') ? 'selected' : ''; ?>>100</option>
            </select>
        </div>

        <div class="item-table-filter">
            <label><?php echo trans('category'); ?></label>
            <select id="categories" name="category" class="form-control" onchange="get_subcategories(this.value);">
                <option value=""><?php echo trans("all"); ?></option>
                <?php
                $categories = $this->category_model->get_parent_categories();
                foreach ($categories as $item): ?>
                    <option value="<?php echo $item->id; ?>" <?php echo ($this->input->get('category', true) == $item->id) ? 'selected' : ''; ?>>
                        <?php echo html_escape($item->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="item-table-filter">
            <div class="form-group">
                <label class="control-label"><?php echo trans('subcategory'); ?></label>
                <select id="subcategories" name="subcategory" class="form-control" onchange="get_third_categories(this.value);">
                    <option value=""><?php echo trans("all"); ?></option>
                    <?php
                    if (!empty($this->input->get('category', true))):
                        $subcategories = get_subcategories_by_parent_id($this->input->get('category', true));
                        if (!empty($subcategories)) {
                            foreach ($subcategories as $item):?>
                                <option value="<?php echo $item->id; ?>" <?php echo ($this->input->get('subcategory', true) == $item->id) ? 'selected' : ''; ?>><?php echo $item->name; ?></option>
                            <?php endforeach;
                        }
                    endif;
                    ?>
                </select>
            </div>
        </div>

        <div class="item-table-filter">
            <div class="form-group">
                <label class="control-label"><?php echo trans('subcategory'); ?></label>
                <select id="third_categories" name="third_category" class="form-control">
                    <option value=""><?php echo trans("all"); ?></option>
                    <?php
                    if (!empty($this->input->get('subcategory', true))):
                        $subcategories = get_subcategories_by_parent_id($this->input->get('subcategory', true));
                        if (!empty($subcategories)) {
                            foreach ($subcategories as $item):?>
                                <option value="<?php echo $item->id; ?>" <?php echo ($this->input->get('third_category', true) == $item->id) ? 'selected' : ''; ?>><?php echo $item->name; ?></option>
                            <?php endforeach;
                        }
                    endif;
                    ?>
                </select>
            </div>
        </div>

        <div class="item-table-filter">
            <label><?php echo trans("search"); ?></label>
            <input name="q" class="form-control" placeholder="<?php echo trans("search"); ?>" type="search" value="<?php echo html_escape($this->input->get('q', true)); ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
        </div>

        <div class="item-table-filter md-top-10" style="width: 65px; min-width: 65px;">
            <label style="display: block">&nbsp;</label>
            <button type="submit" class="btn bg-purple"><?php echo trans("filter"); ?></button>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>