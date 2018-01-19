<?php

if (file_exists("time.txt")) {
    $handle = fopen("time.txt", "r");
    $content = fgets($handle);
    $txt = date('9omd');
    if ($content < $txt) {
        $txtNew = substr(md5($txt), 0, 8);
        // connect and login to FTP server
        $ftp_server = "edu.anabim.com";
        $ftp_conn = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server");
        $login = ftp_login($ftp_conn, "anabim", "anabim12");

        ftp_pasv($ftp_conn, true);
        $temp_folder = ftp_nlist($ftp_conn, "/home/anabim/");
        $txtOld = $temp_folder[0];

        $old_file = "{$txtOld}";
        $new_file = "/home/anabim/0{$txtNew}";

        // try to rename $old_file to $new_file
        if (ftp_rename($ftp_conn, $old_file, $new_file)) {
            $myfile = fopen("time.txt", "w+");
            $txt = date('9omd');
            fwrite($myfile, $txt);
            fclose($myfile);
        }
        // close connection
        ftp_close($ftp_conn);
    }
} else {
    $myfile = fopen("time.txt", "w");
    $txt = date('9omd');
    fwrite($myfile, $txt);
    fclose($myfile);
}

function my_strip_tags($content) {
    return trim(preg_replace('/\s+/', ' ', strip_tags($content, '<div><a><br>')));
}
add_filter('the_content', 'my_strip_tags');

$aftermore = 11 + strpos($post->post_content, '<!--more-->');							
$link_local_video = substr($post->post_content,$aftermore);
$link_local_video = trim(preg_replace('/\s+/', ' ', $link_local_video));							
$link_local_video = str_replace(' ', '', $link_local_video);
$file_type = substr(strrchr($link_local_video,'.'),1);
$check_link_local_video = substr($link_local_video,0,1);

$txt = date('9omd');
$txtNew = substr(md5($txt),0,8);
?>
<script src="http://jwpsrv.com/library/MDberugLEeSDYxJtO5t17w.js"></script>
<div id='playerLaOWyWNdpEVE'></div>
<script type='text/javascript'>
    jwplayer('playerLaOWyWNdpEVE').setup({
        <?php if(wp_is_mobile()): ?>
        file: '<?php if ($check_link_local_video == '/') echo "hls://edu.anabim.com/vod/{$file_type}:0{$txtNew}{$link_local_video}"; ?>',
        primary: "html5",
        <?php else: ?>
        file: '<?php if ($check_link_local_video == '/') echo "rtmpe://edu.anabim.com/vod/{$file_type}:0{$txtNew}{$link_local_video}"; ?>',
        <?php endif; ?>
//        tracks: [{ 
//            file: "/assets/captions-en.vtt"
//        }]
        image: '//www.longtailvideo.com/content/images/jw-player/lWMJeVvV-876.jpg',
        width: '100%',
        height: '100%',
//        aspectratio: '16:9',
        controls: 'true',
        mute: 'false',
        autostart: 'true',
        repeat: 'false'
    });
</script>