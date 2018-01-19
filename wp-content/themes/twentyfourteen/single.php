<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
 
if($_GET[temp_content]=='video'){	
?>

	<?php
    if (file_exists("time.txt")) {
        $handle = fopen("time.txt", "r");
        $content=fgets($handle);
        $txt = date('9omd');
        if( $content < $txt ){            
            $txtNew = substr(md5($txt),0,8);			
            // connect and login to FTP server
            $ftp_server = "edu.anabim.com";	    
            $ftp_conn = ftp_connect($ftp_server);    
            $login = ftp_login($ftp_conn, "anabim", "anabim12");

			ftp_pasv($ftp_conn, true);						
			$temp_folder = ftp_nlist($ftp_conn, "/home/anabim/");
			$txtOld = $temp_folder[0];
            
            $old_file = "{$txtOld}";
            $new_file = "/home/anabim/0{$txtNew}";
				            
            // try to rename $old_file to $new_file
            if (ftp_rename($ftp_conn, $old_file, $new_file)){
                $myfile = fopen("time.txt", "w+");
                $txt = date('9omd');
                fwrite($myfile, $txt);	
                fclose($myfile);
            }		
            // close connection
            ftp_close($ftp_conn);
        }	
    } 
    else {
        $myfile = fopen("time.txt", "w");
        $txt = date('9omd');
        fwrite($myfile, $txt);	
        fclose($myfile);
    }
    ?>
	
    
	<?php 
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			
			function my_strip_tags($content) {
			   return trim(preg_replace('/\s+/', ' ', strip_tags($content,'<div><a><br>')));
			}
			add_filter('the_content','my_strip_tags');
			?>
            
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Anabim Video</title>
            </head>	
            <body>
            			
			<?php
			$check_link = substr(get_the_content('More', true),0,8);
			
			if($check_link!='https://'){
			?>				
                <div class="tinhPhi" style="text-align:center; padding:5px 0 10px 0;">
                    Đây là khóa học tính phí,bạn có thể mua từng phần của khóa học hoặc trọn bộ chương trình của chúng tôi để tiếp tục theo dõi <a target="_parent" href="http://edu.anabim.com/shop">tại đây</a>.
                </div>                                        				
			<?php			
			}			
			?>
                     
                <script src="http://jwpsrv.com/library/MDberugLEeSDYxJtO5t17w.js"></script>
                <div id='playerLaOWyWNdpEVE'></div>
                <script type='text/javascript'>
                    jwplayer('playerLaOWyWNdpEVE').setup({
						<?php 
							$aftermore = 11 + strpos($post->post_content, '<!--more-->');							
							$link_local_video = substr($post->post_content,$aftermore);
																				
							$link_local_video = trim(preg_replace('/\s+/', ' ', $link_local_video));							
							$link_local_video = str_replace(' ', '', $link_local_video);
							
							$file_type = substr(strrchr($link_local_video,'.'),1);
							
							$check_link_local_video = substr($link_local_video,0,1);
							$txt = date('9omd');
							$txtNew = substr(md5($txt),0,8);							
						?>
                        file: '<?php if($check_link_local_video=='/') echo "rtmpe://edu.anabim.com/vod/{$file_type}:0{$txtNew}"; echo the_content(); ?>',
                        image: '//www.longtailvideo.com/content/images/jw-player/lWMJeVvV-876.jpg',
                        width: '100%',
                        aspectratio: '16:9',
                        controls: 'true',
                        mute: 'false',
                        autostart: 'true',
                        repeat: 'false',
                    });
                </script>
            </body>
            </html>
	<?php
		} // end while
	} // end if 
	?>
	
<?php    
}
if (!isset($_GET[temp_content]))

{

	get_header(); ?>
	
		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">
				<?php
					// Start the Loop.
					while ( have_posts() ) : the_post();
	
						/*
						 * Include the post format-specific template for the content. If you want to
						 * use this in a child theme, then include a file called called content-___.php
						 * (where ___ is the post format) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );
	
						// Previous/next post navigation.
						twentyfourteen_post_nav();
	
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) {
							comments_template();
						}
					endwhile;
				?>
			</div><!-- #content -->
		</div><!-- #primary -->
	
	<?php
	get_sidebar( 'content' );
	
	get_footer();

}