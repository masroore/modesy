<div class="row table-filter-container">
    <div class="col-sm-12">
        <?php echo form_open(admin_url() . "translations/" . $language->id, ['method' => 'GET']); ?>

        <div class="item-table-filter" style="width: 80px; min-width: 80px;">
            <label><?php echo trans("show"); ?></label>
            <select name="show" class="form-control">
                <option value="50" <?php echo ($this->input->get('show', true) == '50') ? 'selected' : ''; ?>>50</option>
                <option value="100" <?php echo ($this->input->get('show', true) == '100') ? 'selected' : ''; ?>>100</option>
                <option value="200" <?php echo ($this->input->get('show', true) == '200') ? 'selected' : ''; ?>>200</option>
                <option value="300" <?php echo ($this->input->get('show', true) == '300') ? 'selected' : ''; ?>>300</option>
                <option value="500" <?php echo ($this->input->get('show', true) == '500') ? 'selected' : ''; ?>>500</option>
            </select>
        </div>

        <div class="item-table-filter">
            <label><?php echo trans("search"); ?></label>
            <input name="q" class="form-control" placeholder="<?php echo trans("search"); ?>" type="search" value="<?php echo html_escape($this->input->get('q', true)); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
        </div>

        <div class="item-table-filter md-top-10" style="width: 65px; min-width: 65px;">
            <label style="display: block">&nbsp;</label>
            <button type="submit" class="btn bg-purple"><?php echo trans("filter"); ?></button>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
