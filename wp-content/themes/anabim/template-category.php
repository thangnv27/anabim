<section>
    <?php
    $i = 0;
    $category = get_queried_object();
    foreach (get_categories(array('parent' => $category->term_id, 'orderby' => 'slug', 'hide_empty' => 0)) as $cat) :
        $i++;
        if ($i % 3 == 1) {
            echo '<ul class="kwicks-horizontal clearfix kwicks">';
            $k = 0;
        }
        ?>        	
        <li id='panel-<?php echo $i; ?>' class='panel-<?php echo $i; ?>'>
            <span class="po-fix">
                <span class="title-slide-fix fl">
                    <span class="a_fix"><?php echo $cat->cat_name; ?></span>
                    <br />
                    <div class="des"><?php echo $cat->description; ?></div>
                </span>
                <span class="content-slide-fix fl">                                         	                                    
                    <?php foreach (get_categories(array('parent' => $cat->term_id, 'orderby' => 'slug', 'hide_empty' => 0)) as $cat_test) : ?>
                        <a href="<?php echo get_category_link($cat_test->term_id); ?>"><?php echo $cat_test->cat_name; ?></a>
                    <?php endforeach; ?>
                </span>
                <style type='text/css'>
                    .panel-<?php echo $i; ?>{
                        background:#efefef left bottom no-repeat url(<?php echo z_taxonomy_image_url($cat->term_id); ?>)
                    }
                </style>
            </span>
        </li>
        <?php 
        if ($i % 3 == 0) echo '</ul>';

        $k++;
    endforeach;
if ($k != 0) echo '</ul>';
?>
</section>