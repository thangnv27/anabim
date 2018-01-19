<section id="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3">
                <?php if ( is_active_sidebar( 'footer1' ) ) { dynamic_sidebar( 'footer1' ); } ?>
            </div>
            <div class="col-sm-3">
                <?php if ( is_active_sidebar( 'footer2' ) ) { dynamic_sidebar( 'footer2' ); } ?>
                <div class="social">
                    <ul>
                        <?php
                        $fbURL = get_option(SHORT_NAME . "_fbURL");
                        $twitterURL = get_option(SHORT_NAME . "_twitterURL");
                        $linkedInURL = get_option(SHORT_NAME . "_linkedInURL");
                        $googlePlusURL = get_option(SHORT_NAME . "_googlePlusURL");
                        $youtubeURL = get_option(SHORT_NAME . "_youtubeURL");
                        $pinterestURL = get_option(SHORT_NAME . "_pinterestURL");
                        $instagramURL = get_option(SHORT_NAME . "_instagramURL");
                        ?>
                        <?php if (!empty($fbURL)): ?>
                        <li><a class="btn btn-primary" href="<?php echo $fbURL; ?>"><i class="fa fa-facebook"></i></a></li>
                        <?php endif; ?>
                        <?php if (!empty($twitterURL)): ?>
                        <li><a class="btn btn-info" href="<?php echo $twitterURL; ?>"><i class="fa fa-twitter"></i></a></li>
                        <?php endif; ?>
                        <?php if (!empty($linkedInURL)): ?>
                        <li><a class="btn btn-primary" href="<?php echo $linkedInURL; ?>"><i class="fa fa-linkedin"></i></a></li>
                        <?php endif; ?>
                        <?php if (!empty($googlePlusURL)): ?>
                        <li><a class="btn btn-danger" href="<?php echo $googlePlusURL; ?>"><i class="fa fa-google-plus"></i></a></li>
                        <?php endif; ?>
                        <?php if (!empty($youtubeURL)): ?>
                        <li><a class="btn btn-danger" href="<?php echo $youtubeURL; ?>"><i class="fa fa-youtube"></i></i></a></li>
                        <?php endif; ?>
                        <?php if (!empty($pinterestURL)): ?>
                        <li><a class="btn btn-danger" href="<?php echo $pinterestURL; ?>"><i class="fa fa-pinterest"></i></a></li>
                        <?php endif; ?>
                        <?php if (!empty($instagramURL)): ?>
                        <li><a class="btn btn-primary" href="<?php echo $instagramURL; ?>"><i class="fa fa-instagram"></i></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="col-sm-3">
                <?php if ( is_active_sidebar( 'footer3' ) ) { dynamic_sidebar( 'footer3' ); } ?>
            </div>
            <div class="col-sm-3">
                <?php if ( is_active_sidebar( 'footer4' ) ) { dynamic_sidebar( 'footer4' ); } ?>
            </div>
        </div>
    </div>
    <div class="copyright">
        <span>Copyright &copy; ANABIM. All rights reserved.</span>
    </div>
</section>

<!-- script references -->
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery-migrate.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.simplesidebar.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/colorbox/jquery.colorbox-min.js"></script>
<!--<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.imagefit.min.js"></script>-->
<!--<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/disable-copy.js"></script>-->
<?php if(is_home() or is_front_page() or is_archive() or is_singular('project') or is_singular('photo')): ?>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.kwicks.min.js"></script>
<?php endif; ?>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/custom.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/app.js"></script>
<?php wp_footer(); ?>
</body>
</html>