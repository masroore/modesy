<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if (!empty($countries)): ?>
    <div class="row">
        <div class="col-12 col-sm-4 m-b-15">
            <div class="selectdiv">
                <select id="select_countries" name="country_id" class="form-control" onchange="get_states(this.value, '<?php echo $map; ?>');" required>
                    <option value=""><?php echo trans('country'); ?></option>
                    <?php foreach ($this->countries as $item):
                        if (!empty($country_id)): ?>
                            <option value="<?php echo $item->id; ?>" <?php echo ($item->id == $country_id) ? 'selected' : ''; ?>><?php echo html_escape($item->name); ?></option>
                        <?php else: ?>
                            <option value="<?php echo $item->id; ?>"><?php echo html_escape($item->name); ?></option>
                        <?php endif;
                    endforeach; ?>
                </select>
            </div>
        </div>
        <div id="get_states_container" class="col-12 col-sm-4 m-b-15 <?php echo (!empty($state_id)) ? '' : 'display-none'; ?>">
            <div class="selectdiv">
                <select id="select_states" name="state_id" class="form-control" onchange="get_cities(this.value, '<?php echo $map; ?>');" <?php echo ($this->form_settings->product_location_required == 1) ? 'required' : ''; ?>>
                    <option value=""><?php echo trans('state'); ?></option>
                    <?php if (!empty($states)):
                        foreach ($states as $item):
                            if (!empty($state_id)): ?>
                                <option value="<?php echo $item->id; ?>" <?php echo ($item->id == $state_id) ? 'selected' : ''; ?>><?php echo html_escape($item->name); ?></option>
                            <?php else: ?>
                                <option value="<?php echo $item->id; ?>"><?php echo html_escape($item->name); ?></option>
                            <?php endif;
                        endforeach;
                    endif; ?>
                </select>
            </div>
        </div>
        <div id="get_cities_container" class="col-12 col-sm-4 m-b-15 <?php echo (!empty($cities)) ? '' : 'display-none'; ?>">
            <div class="selectdiv">
                <select id="select_cities" name="city_id" class="form-control" <?php echo (!empty($map)) ? 'onchange="update_product_map();"' : ''; ?>>
                    <option value=""><?php echo trans('city'); ?></option>
                    <?php if (!empty($cities)):
                        foreach ($cities as $item):
                            if (!empty($city_id)): ?>
                                <option value="<?php echo $item->id; ?>" <?php echo ($item->id == $city_id) ? 'selected' : ''; ?>><?php echo html_escape($item->name); ?></option>
                            <?php else: ?>
                                <option value="<?php echo $item->id; ?>"><?php echo html_escape($item->name); ?></option>
                            <?php endif;
                        endforeach;
                    endif; ?>
                </select>
            </div>
        </div>
    </div>
<?php endif; ?>
