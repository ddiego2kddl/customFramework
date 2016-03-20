<?php

    $fileName = $_GET['fileName'];
    $url_ideatResources = '../Projects/';
    $file = $url_ideatResources.$fileName;

    echo $file ;

    if (file_exists($file)) {
        echo "ye";
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream; charset=UTF-8');
        header('Content-Disposition: attachment; filename="'.$fileName.'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        
        //estos dos comandos son necesarios en el servidor, aunque funcionaba bien sin ellos en local
        ob_clean();
        flush();
    
        readfile($file);
        exit;
    }

?>