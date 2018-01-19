<?php
    if (file_exists("time.txt")) {
        $handle = fopen("time.txt", "r");
        $content=fgets($handle);
        $txt = date('9omd');
        if( $content < $txt ){            
            $txtNew = substr(md5($txt),0,8);			
            // connect and login to FTP server
            $ftp_server = "anabimmedia.ddns.net";
            $ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
            $login = ftp_login($ftp_conn, "upload", "vuongbe12");
						
			$temp_folder = ftp_nlist($ftp_conn, "/opt/red5/webapps/oflaDemo/streams/");
			$txtOld = $temp_folder[0];
            
            $old_file = "{$txtOld}";
            $new_file = "/opt/red5/webapps/oflaDemo/streams/0{$txtNew}";
            
            // try to rename $old_file to $new_file
            if (ftp_rename($ftp_conn, $old_file, $new_file)){
                $myfile = fopen("time.txt", "w+") or die("Unable to open file!");
                $txt = date('9omd');
                fwrite($myfile, $txt);	
                fclose($myfile);
            }		
            // close connection
            ftp_close($ftp_conn);
        }	
    } 
    else {
        $myfile = fopen("time.txt", "w") or die("Unable to open file!");
        $txt = date('9omd');
        fwrite($myfile, $txt);	
        fclose($myfile);
    }
    ?>