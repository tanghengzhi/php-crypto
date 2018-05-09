<?php

trait Key {
    public static $key = "secret\0\0\0\0\0\0\0\0\0\0";
}

class Mcrypt {

    use Key;

    public static function encrypt($data) {
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
        $encryptText = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, self::$key, $data, MCRYPT_MODE_ECB, $iv);
        return base64_encode($encryptText);

    }

    public static function decrypt($data) {
        $encryptText = base64_decode($data);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
        $decryptText = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, self::$key, $encryptText, MCRYPT_MODE_ECB, $iv);
        return $decryptText;
    }
}

class OpenSSL {

    use Key;

    public static function encrypt($data) {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));
        $encryptText = openssl_encrypt($data, 'AES-256-CBC', self::$key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . "::" . $encryptText);
    }

    public static function decrypt($data) {
        list($iv, $encryptText) = explode('::', base64_decode($data));
        $decryptText = openssl_decrypt($encryptText, 'AES-256-CBC', self::$key, OPENSSL_RAW_DATA, $iv);
        return $decryptText;
    }
}

class Sodium {
    use Key;

    public static function encrypt($data) {
        $nonce = random_bytes(SODIUM_CRYPTO_AEAD_AES256GCM_NPUBBYTES);
        $encryptText = sodium_crypto_aead_aes256gcm_encrypt($data, '', $nonce, str_pad(self::$key, SODIUM_CRYPTO_AEAD_AES256GCM_KEYBYTES));
        return base64_encode($nonce . "::" . $encryptText);
    }

    public static function decrypt($data) {
        list($nonce, $encryptText) = explode('::', base64_decode($data));
        $decryptText = sodium_crypto_aead_aes256gcm_decrypt($encryptText, '', $nonce, str_pad(self::$key, SODIUM_CRYPTO_AEAD_AES256GCM_KEYBYTES));
        return $decryptText;
    }
}

$plainText = '你好，再见👋';

try {
    $encryptText = Mcrypt::encrypt($plainText);
    $decryptText = Mcrypt::decrypt($encryptText);
    echo $plainText, '->', $encryptText, '->', $decryptText, "\n";
} catch (Error $e) {
    echo $e->getMessage(), "\n";
}

try {
    $encryptText = OpenSSL::encrypt($plainText);
    $decryptText = OpenSSL::decrypt($encryptText);
    echo $plainText, '->', $encryptText, '->', $decryptText, "\n";
} catch (Error $e) {
    echo $e->getMessage();
}

try {
    $encryptText = Sodium::encrypt($plainText);
    $decryptText = Sodium::decrypt($encryptText);
    echo $plainText, '->', $encryptText, '->', $decryptText, "\n";
} catch (Error $e) {
    echo $e->getMessage(), "\n";
}