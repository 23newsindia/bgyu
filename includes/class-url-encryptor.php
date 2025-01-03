<?php
class SFD_URL_Encryptor {
    private static $encryption_key;
    
    public static function init() {
        self::$encryption_key = wp_salt('auth');
    }
    
    public static function encrypt_url($url) {
        if (empty($url)) {
            error_log('Empty URL provided for encryption');
            return false;
        }

        try {
            $encrypted = openssl_encrypt(
                $url, 
                'AES-256-CBC', 
                self::$encryption_key, 
                0, 
                substr(self::$encryption_key, 0, 16)
            );
            
            if ($encrypted === false) {
                error_log('Encryption failed: ' . openssl_error_string());
                return false;
            }

            return urlencode(base64_encode($encrypted));
        } catch (Exception $e) {
            error_log('Encryption error: ' . $e->getMessage());
            return false;
        }
    }
    
    public static function decrypt_url($encrypted_url) {
        if (empty($encrypted_url)) {
            error_log('Empty encrypted URL provided');
            return false;
        }

        try {
            $decoded = base64_decode(urldecode($encrypted_url));
            if ($decoded === false) {
                error_log('Base64 decode failed');
                return false;
            }

            $decrypted = openssl_decrypt(
                $decoded, 
                'AES-256-CBC', 
                self::$encryption_key, 
                0, 
                substr(self::$encryption_key, 0, 16)
            );

            if ($decrypted === false) {
                error_log('Decryption failed: ' . openssl_error_string());
                return false;
            }

            return $decrypted;
        } catch (Exception $e) {
            error_log('Decryption error: ' . $e->getMessage());
            return false;
        }
    }
}