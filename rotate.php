<?php
    // 
    // This script update the Xtream Loadbalancer server IP with new automatically 
    // crontab command 
    //
    //  */5 * * * *     sudo /usr/bin/php   /path/to/rotate.php
    //
    //
    // 

    $api_id = "";

    // Xtream database Information
    $db_host = "";
    $db_user = "user_iptvpro";
    $db_pass = "";
    $db_name = "xtream_iptvpro";
    $db_port = "7999";
    
    $xtream_table = "streaming_servers";
    
    // Create connection
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name , $db_port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $url = "http://3.36.94.98/list.php?api_id=".$api_id;
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = json_decode(curl_exec($ch)) ;

    foreach( $result->records as $r ){
        $new_ip = $r->fields->IP_API[0];
        $old_ip = $r->fields->Old_IP;

        if( $old_ip && $new_ip && $old_ip != $new_ip ){

            $sql = "UPDATE ".$xtream_table." SET server_ip='".$new_ip."' WHERE server_ip='".$old_ip."'";

            if ($conn->query($sql) === TRUE) {
            echo "IP updated ".$old_ip."->".$new_ip." \r\n";
            } else {
            echo "Error updating record: " . $conn->error;
            }
        }
    }

    $conn->close();


?>
