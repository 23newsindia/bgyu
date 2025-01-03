<?php
class SFD_File_Path_Handler {
    public static function get_file_path($file_url) {
        $upload_dir = wp_upload_dir();
        return str_replace(
            [site_url('/'), $upload_dir['baseurl']],
            [ABSPATH, $upload_dir['basedir']],
            $file_url
        );
    }

    public static function validate_file($file_path) {
        if (!file_exists($file_path)) {
            wp_die('File not found.', 'Error', ['response' => 404]);
        }

        if (!is_readable($file_path)) {
            wp_die('File not readable.', 'Error', ['response' => 403]);
        }

        return true;
    }
}