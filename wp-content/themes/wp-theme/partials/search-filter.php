<?php

if(is_page_template('template-member-list.php')){
    // uncomment this when we have a taxonomy for members picked out
    // $filters = get_taxonomies(array('name' => 'industry'));

    // we have to use a custom name for the search value
    $search_field_name = 'member-s';
}else{
    // Figure filters for this post type
    $filters = get_object_taxonomies($wp_query->query['post_type'], 'objects');
    $search_field_name = 's';
}

?>

<div class="search-filter middlifier">
    <form method="get" class="search-filter-form">
        <?php if (!empty($filters)) : ?>
            <?php foreach ($filters as $taxonomy => $obj) : ?>
                <?php
                    $name = strtolower($taxonomy);
                    $query = 'filter-' . $name;
                    $terms = get_terms($taxonomy, array(
                        'hide_empty' => false
                    ));

                    if (empty($terms)) {
                        continue;
                    }

                    $default = 'default';
                    if (isset($_GET[$query]) && !empty($_GET[$query])) {
                        $default = $_GET[$query];
                    }

                ?>

                <div class="wrapper filter <?php echo $name; ?>">
                    <select name="filter-<?php echo $name; ?>" class="select filter">
                        <option value="default" <?php echo ($default == 'default') ? 'selected="selected"' : '' ?>>All <?php echo $obj->labels->all_items; ?></option>
                        <?php foreach ($terms as $term) : ?>
                            <option value="<?php echo $term->term_id; ?>" <?php echo ($default == $term->term_id) ? 'selected="selected"' : '' ?>><?php echo $term->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>

        <div class="wrapper search gform_fields gfield">
            <label for="s">Search</label>
            <div><input type="text" id="s" name="<?php echo $search_field_name ?>" class="search" value="<?php echo isset($_GET[$search_field_name]) ? $_GET[$search_field_name] : '' ?>" /></div>
        </div>
        <div class="wrapper submit">
            <input type="hidden" name="paged" value="1" />
            <input type="submit" value="Go" class="primary-button submit" />
        </div>
    </form>
</div>