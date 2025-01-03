<?php
class SFD_Download_Handler {
    public static function send_file($file_path) {
        // Clear output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }

        $filename = basename($file_path);
        $filesize = filesize($file_path);

        // Set download headers
        nocache_headers();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . $filesize);
        header('Content-Transfer-Encoding: binary');
        header('Connection: close');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        // Send file
        readfile($file_path);
        exit;
    }
}