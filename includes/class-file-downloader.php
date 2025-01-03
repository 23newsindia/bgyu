<?php
class SFD_File_Downloader {
    public static function init() {
        add_action('init', [self::class, 'handle_download_request']);
        add_filter('the_content', [self::class, 'add_download_buttons']);
    }
    
    public static function handle_download_request() {
        if (!isset($_GET['sfd_download'], $_GET['file'], $_GET['post'])) {
            return;
        }

        $post_id = intval($_GET['post']);
        
        if (!self::can_download($post_id)) {
            wp_die('Login required to download this file.', 'Access Denied', ['response' => 403]);
        }

        $encrypted_url = sanitize_text_field($_GET['file']);
        $file_url = SFD_URL_Encryptor::decrypt_url($encrypted_url);

        if (!$file_url) {
            wp_die('Invalid file URL.', 'Error', ['response' => 400]);
        }

        $upload_dir = wp_upload_dir();
        $file_path = str_replace(
            [$upload_dir['baseurl'], site_url('/')],
            [$upload_dir['basedir'], ABSPATH],
            $file_url
        );

        if (!file_exists($file_path)) {
            wp_die('File not found.', 'Error', ['response' => 404]);
        }

        // Clear any output buffering
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Set headers for download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        
        // Read file and exit
        readfile($file_path);
        exit;
    }
    
    public static function can_download($post_id) {
        $requires_login = get_post_meta($post_id, '_file_requires_login', true);
        return !$requires_login || is_user_logged_in();
    }
    
    public static function add_download_buttons($content) {
        global $post;
        
        if (!is_singular() || !is_main_query()) {
            return $content;
        }
        
        $download_html = '';
        
        for ($i = 1; $i <= 2; $i++) {
            $encrypted_url = get_post_meta($post->ID, "_secure_file_url_{$i}", true);
            if ($encrypted_url) {
                $requires_login = get_post_meta($post->ID, "_file_{$i}_requires_login", true);
                
                if (!$requires_login || is_user_logged_in()) {
                    $download_url = add_query_arg([
                        'sfd_download' => '1',
                        'file' => $encrypted_url,
                        'post' => $post->ID
                    ], site_url());
                    
                    $download_html .= sprintf(
                        '<div class="sfd-download-button">
                            <a href="%s" class="button" onclick="handleDownloadClick(event, this)">
                                Download File %d
                            </a>
                            <div class="robot-message">🤖 Hey, welcome! Thanks for visiting comfyuiblog.com</div>
                            <div class="firework"></div>
                        </div>',
                        esc_url($download_url),
                        $i
                    );
                } elseif ($requires_login) {
                    $download_html .= sprintf(
                        '<div class="sfd-login-required">
                            <p>Please <a href="%s">login</a> to download File %d</p>
                        </div>',
                        esc_url(wp_login_url(get_permalink())),
                        $i
                    );
                }
            }
        }
        
        return $content . $download_html;
    }
}