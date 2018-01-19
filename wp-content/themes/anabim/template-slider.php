<!--BEGIN SLIDER-->
<section>
    <?php
    $slider_id = intval(get_option('home_slider'));
    if ($slider_id > 0):
    ?>
    <div class="slider">
        <?php echo do_shortcode('[layerslider id="' . $slider_id . '"]'); ?>
    </div>
    <?php endif; ?>
</section>
<!--END SLIDER-->