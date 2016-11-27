<?php
/**
*
* PHP版3DES加解密类
*
* 可与java的3DES(DESede)加密方式兼容
*
*/
class Common_Crypt3Des {
	/**
	 * 使用pkcs7进行填充
	 * @param unknown $input
	 * @return string
	 */
	static function PaddingPKCS7($input) {
		$srcdata = $input;
		$block_size = mcrypt_get_block_size ( 'tripledes', 'ecb' );
		$padding_char = $block_size - (strlen ( $input ) % $block_size);
		$srcdata .= str_repeat ( chr ( $padding_char ), $padding_char );
		return $srcdata;
	}
	
	/**
	 * 3des加密
	 * @param  $string 待加密的字符串
	 * @param  $key 加密用的密钥
	 * @return string
	 */
	static function encrypt($string, $key) {
		$string = self::PaddingPKCS7 ( $string );
		
		// 加密方法
		$cipher_alg = MCRYPT_TRIPLEDES;
		// 初始化向量来增加安全性
		$iv = mcrypt_create_iv ( mcrypt_get_iv_size ( $cipher_alg, MCRYPT_MODE_ECB ), MCRYPT_RAND );
		
		$encrypted_string = mcrypt_encrypt ( $cipher_alg, $key, $string, MCRYPT_MODE_ECB, $iv );
		$des3 = bin2hex ( $encrypted_string ); // 转化成16进制
		
		//echo $des3 . "</br>";
		return $des3;
	}
}

// 开始64位编码
// $base64=base64_encode($spid."$".$des3);
// echo "base64:".$base64."<br>";
?>