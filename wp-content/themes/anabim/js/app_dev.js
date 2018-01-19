var viewport_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
var viewport_height = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);

jQuery(document).ready(function ($) {
    if (jQuery('.kwicks').length > 0) {
        jQuery("#course").show();
        jQuery('.kwicks').kwicks({
            maxSize: '80%',
            behavior: 'menu',
            delayMouseIn: 5000000
        });
    }
    
    if(is_home){
        jQuery(window).bind('load resize', function (){
            viewport_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
            viewport_height = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);

            jQuery("#quote .quote-left .quote-content ul").show().bxSlider({
                mode: 'fade',
                pager: false,
                controls: false,
                auto: true,
                pause: 5000
            });
            jQuery("#feedbacks .feedback-user ul").show().bxSlider({
                mode: 'fade',
                pager: false,
                controls: false,
                auto: true,
                pause: 5000
            });
            
            if(!is_mobile && viewport_width > 991){
                jQuery("#quote .quote-right").show().height(jQuery("#quote .quote-left").outerHeight());
            }
            if(viewport_width > 991){
                var feedback_user_height = jQuery("#feedbacks .feedback-user").height();
                var feedback_lib_height = jQuery("#feedbacks .feedback-lib").height();
                if(feedback_user_height > feedback_lib_height){
                    jQuery("#feedbacks .feedback-lib").height(jQuery("#feedbacks .feedback-user").height());
                } else {
                    jQuery("#feedbacks .feedback-user").height(jQuery("#feedbacks .feedback-lib").height());
                }
            }
        });
    } else {
        if(jQuery(".project-slider ul li").length > 0){
            jQuery(".project-slider ul").bxSlider({
                mode: 'fade',
                auto: true,
                pause: 5000,
                adaptiveHeight:true
            });
        }
        
        jQuery(window).bind('load resize', function (){
            viewport_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
            viewport_height = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);

            if(viewport_width > 767){
                jQuery('.page-upgrade .packages .item').height(jQuery('.page-upgrade .packages').height());
            }
        });
    }
    
    jQuery("#commentform textarea, #commentform input[type=text]").addClass('form-control');
    jQuery("#commentform input[type=submit]").addClass('btn btn-warning');
    
    if(jQuery("#course_tabs").length > 0){
        jQuery("#course_tabs").show().tabs();
    }
    if(jQuery("#course_online").length > 0){
        jQuery("#course_online").show().tabs();
    }
    if(jQuery( ".accordion" ).length > 0){
        jQuery( ".accordion" ).accordion();
    }
    
    jQuery(".colorfix:contains('Free')").each(function () {
        jQuery(this).html(jQuery(this).html().replace("Free", '<span style="color:#F08800">Free</span>'));
    });
//    jQuery("#playlist").append("<ul>" + jQuery(".list-fix.all-video").html() + "</ul>");
    jQuery("#playlist a, .list-fix.all-video a, .list-fix.free-video a").click(function(){
        var currentVideoTitle = jQuery(this).html().replace('<i class="fa fa-youtube-play"></i>', '');
        jQuery("#player .video-title").html(currentVideoTitle);
        
        // Set current video status
        jQuery("#playlist a, .list-fix.all-video a, .list-fix.free-video a").removeClass('current-video');
        jQuery("#playlist a, .list-fix.all-video a, .list-fix.free-video a").each(function (){
            if(currentVideoTitle.toString().trim() === jQuery(this).html().replace('<i class="fa fa-youtube-play"></i>', '').toString().trim()){
                jQuery(this).addClass('current-video');
            }
        });
    });
    
    // Menu mobile
    $(".menu-mobile").show().simpleSidebar({
        settings: {
            opener: ".menu-fix",
            wrapper: "#wrapper",
            animation: {
                easing: "easeOutQuint"
            }
        },
        sidebar: {
            align: "left",
            width: 240,
            closingLinks: '.btn-close-menu'
        },
        mask: {
            //STYLE holds all CSS rules. Use this feature to stylize the mask.
            style: {
                //Default options.
                backgroundColor: 'transparent', //if you do not want any color use 'transparent'.
                opacity: 0.5, //if you do not want any opacity use 0.
                filter: 'Alpha(opacity=50)' //IE8 and earlier - If you do not want any opacity use 0.
                        //You can add more options.
            }
        }
    });
    
    jQuery('.entry-content img, .taxonomy-description img').each(function(){
        jQuery(this).attr('href', jQuery(this).attr('src')).css({
            'cursor': 'pointer'
        }).parent().attr('href', 'javascript://');
    }).addClass('single-group-img').colorbox({
        rel:'single-group-img',
        fixed:true
    });
    
//    jQuery(".open-lostpassword-form").click(function (){
//        if(jQuery("#user-lostpassword").is(":hidden")){
//            jQuery("#user-lostpassword").fadeIn('slow');
//        } else {
//            jQuery("#user-lostpassword").fadeOut('slow');
//        }
//    });
    
//    jQuery(".imagefit").imagefit({
//        mode: 'outside',
//        halign : 'center',
//        valign : 'middle'
//    });
});