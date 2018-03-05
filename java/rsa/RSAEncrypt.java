import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.security.InvalidKeyException;
import java.security.KeyFactory;
import java.security.KeyPair;
import java.security.KeyPairGenerator;
import java.security.NoSuchAlgorithmException;
import java.security.SecureRandom;
import java.security.interfaces.RSAPrivateKey;
import java.security.interfaces.RSAPublicKey;
import java.security.spec.InvalidKeySpecException;
import java.security.spec.PKCS8EncodedKeySpec;
import java.security.spec.X509EncodedKeySpec;
import java.util.Arrays;

import javax.crypto.BadPaddingException;
import javax.crypto.Cipher;
import javax.crypto.IllegalBlockSizeException;
import javax.crypto.NoSuchPaddingException;

import sun.misc.BASE64Decoder;

public class RSAEncrypt {

 //	private static final String DEFAULT_PUBLIC_KEY= 
 //		"MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQChDzcjw/rWgFwnxunbKp7/4e8w" + "\r" +
 //		"/UmXx2jk6qEEn69t6N2R1i/LmcyDT1xr/T2AHGOiXNQ5V8W4iCaaeNawi7aJaRht" + "\r" +
 //		"Vx1uOH/2U378fscEESEG8XDqll0GCfB1/TjKI2aitVSzXOtRs8kYgGU78f7VmDNg" + "\r" +
 //		"XIlk3gdhnzh+uoEQywIDAQAB" + "\r";
    private static final String DEFAULT_PUBLIC_KEY =
		"MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA3xjz5JyrMtHaayUSWd4/" + "\r" +
		"jaRKWrgcHmy4NnLvR88sxL0xl+8G20oRSrQhYJSyjedF2HGxJoQAt4HEAX+syzX0" + "\r" +
		"W29LTIa3bG+CfswNfYR3Kd8253hG4QfHguKMrD9eD9R0zA22OcJ1hgaKCvDSeMcT" + "\r" +
		"C87Pgv0rx2U77TdoNEHXOncVz5tu8lWaX2VUGFC2c9tikaocAvLvvdduO90icrk0" + "\r" +
		"d6hrZSCSokkoyYoXYfiFF+0MMyOeFwloWlVNxjCb0/iG19I6HT1f6SwhDly50nSd" + "\r" +
		"+KoMFHygj6CLorGzmKFbTNq8W8xgzQ7m7bnN9L0Ia67YmhLMXrExsxBNMOFOLNbV" + "\r" +
		"yQIDAQAB" + "\r";

 //	private static final String DEFAULT_PRIVATE_KEY=
 //		"MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAKEPNyPD+taAXCfG" + "\r" +
 //		"6dsqnv/h7zD9SZfHaOTqoQSfr23o3ZHWL8uZzINPXGv9PYAcY6Jc1DlXxbiIJpp4" + "\r" +
 //		"1rCLtolpGG1XHW44f/ZTfvx+xwQRIQbxcOqWXQYJ8HX9OMojZqK1VLNc61GzyRiA" + "\r" +
 //		"ZTvx/tWYM2BciWTeB2GfOH66gRDLAgMBAAECgYBp4qTvoJKynuT3SbDJY/XwaEtm" + "\r" +
 //		"u768SF9P0GlXrtwYuDWjAVue0VhBI9WxMWZTaVafkcP8hxX4QZqPh84td0zjcq3j" + "\r" +
 //		"DLOegAFJkIorGzq5FyK7ydBoU1TLjFV459c8dTZMTu+LgsOTD11/V/Jr4NJxIudo" + "\r" +
 //		"MBQ3c4cHmOoYv4uzkQJBANR+7Fc3e6oZgqTOesqPSPqljbsdF9E4x4eDFuOecCkJ" + "\r" +
 //		"DvVLOOoAzvtHfAiUp+H3fk4hXRpALiNBEHiIdhIuX2UCQQDCCHiPHFd4gC58yyCM" + "\r" +
 //		"6Leqkmoa+6YpfRb3oxykLBXcWx7DtbX+ayKy5OQmnkEG+MW8XB8wAdiUl0/tb6cQ" + "\r" +
 //		"FaRvAkBhvP94Hk0DMDinFVHlWYJ3xy4pongSA8vCyMj+aSGtvjzjFnZXK4gIjBjA" + "\r" +
 //		"2Z9ekDfIOBBawqp2DLdGuX2VXz8BAkByMuIh+KBSv76cnEDwLhfLQJlKgEnvqTvX" + "\r" +
 //		"TB0TUw8avlaBAXW34/5sI+NUB1hmbgyTK/T/IFcEPXpBWLGO+e3pAkAGWLpnH0Zh" + "\r" +
 //		"Fae7oAqkMAd3xCNY6ec180tAe57hZ6kS+SYLKwb4gGzYaCxc22vMtYksXHtUeamo" + "\r" +
 //		"1NMLzI2ZfUoX" + "\r";
    private static final String DEFAULT_PRIVATE_KEY =
        "MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDfGPPknKsy0dpr" + "\r" +
        "JRJZ3j+NpEpauBwebLg2cu9HzyzEvTGX7wbbShFKtCFglLKN50XYcbEmhAC3gcQB" + "\r" +
        "f6zLNfRbb0tMhrdsb4J+zA19hHcp3zbneEbhB8eC4oysP14P1HTMDbY5wnWGBooK" + "\r" +
        "8NJ4xxMLzs+C/SvHZTvtN2g0Qdc6dxXPm27yVZpfZVQYULZz22KRqhwC8u+91247" + "\r" +
        "3SJyuTR3qGtlIJKiSSjJihdh+IUX7QwzI54XCWhaVU3GMJvT+IbX0jodPV/pLCEO" + "\r" +
        "XLnSdJ34qgwUfKCPoIuisbOYoVtM2rxbzGDNDubtuc30vQhrrtiaEsxesTGzEE0w" + "\r" +
        "4U4s1tXJAgMBAAECggEAYTLU59BeQkjtuWqwYRkUZVij0HyQO60wYQCYQZgAsEKh" + "\r" +
        "fcl1Gktd10c9l3+Rf4O4iYGXJemzCkBuLhz7IpoCOWf2zYOQHJ1EpIuwgPQamtKW" + "\r" +
        "cCiV8OrbwKmFknIVQB5XOKPstaOEYn0s3XFosZhCMw3KmQ5GaZwwjLxsGQBqjhyN" + "\r" +
        "mEJpN+XiBh7yusgtxQ6UaZFDIP9HniZa4RxdRMr0m4gHNf99nSazOAjwDMv2xIbD" + "\r" +
        "5eF4Xrmhr30XzIUq9QwruLWbEejQ4HEIeyAE72PUR2tpg1YGYzJ4kHqSG1cm7d6W" + "\r" +
        "HIGZ+qBWUNiEe4ctxAfrRSbxIOkTPsZZycABzahqAQKBgQD6rO1wrchXehsBh5Ue" + "\r" +
        "2Y0d6DY9FpgWrcfneI34+75ajwHIJifx/BHVzhm11lqi5NDeXxygpjg8ciX/ccOi" + "\r" +
        "49tglM+Prv2UtNzy2Npbn8JCeNo+HhMFxNQsnTJ6KPQdbrcd8rNHhYu5lM14fbEA" + "\r" +
        "67Yv423zV1RHOWeIifeokCvfQQKBgQDj1hEmh8RyR6CVaqo2KRd3E0B0apkwwNJB" + "\r" +
        "BdwsIpnUMXcfEC5z3k/sRcXcLDrJ6C2hr+/NX5W3OaZednN8e2Me2R8gB7xG0DK7" + "\r" +
        "lsrfq1MQJgpFYUw57ILM02v/GMBKooMWLLUEsR4WpPRa86EsJm7Q/v7zjQXkg6sv" + "\r" +
        "Ey9wyeFciQKBgDITgYtU7AStm99+WkfDZfFnhg5GCTPem2SeLJ5ki+5DSzPUi35H" + "\r" +
        "wLhZZ8FvhMOtuhvyHCHrkqhglT6mV1Ke+iAdGim152phhxHsSBKto3zr740hLOOm" + "\r" +
        "IkyXSpIkxD9s8p/E8BOFlhgIpdNAKK/qZobChCBsfaWMziX0icjepCUBAoGAGyh1" + "\r" +
        "OuaMICMxnR3t1C7pwSPmVvot8IXLijyslgY51/VdgbJoFx+03zEh+LC9ATxP1Coo" + "\r" +
        "p45xRjn0/uWXtoruscqnzyWc58QBiCLdY/QEHrSqHMMQVwc+QDQjd3D32u2sOoRx" + "\r" +
        "v3FtaL2Y8w5/c8fRBdCfi2CQB0E6so3S5drqpiECgYAmqCZ3jjorOn5OxWanW54M" + "\r" +
        "NomNO6DYqHZzUPr3Haw6a1Ls+RiYXxfje/Ih8Ua5SCQ75gD5pPQ/olh3siF/yi51" + "\r" +
        "dE7mQTl+PvwP1D4x9J1NhueI2+wbl6efM2R2YTQJmzugj2YTsApk1dFuWY2y54dn" + "\r" +
        "UHzR4LWvkG94bbFh5n18Qg==" + "\r";

	/**
	 * 私钥
	 */
	private RSAPrivateKey privateKey;

	/**
	 * 公钥
	 */
	private RSAPublicKey publicKey;

	/**
	 * 字节数据转字符串专用集合
	 */
	private static final char[] HEX_CHAR= {'0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f'};


	/**
	 * 获取私钥
	 * @return 当前的私钥对象
	 */
	public RSAPrivateKey getPrivateKey() {
		return privateKey;
	}

	/**
	 * 获取公钥
	 * @return 当前的公钥对象
	 */
	public RSAPublicKey getPublicKey() {
		return publicKey;
	}

	/**
	 * 随机生成密钥对
	 */
	public void genKeyPair(){
		KeyPairGenerator keyPairGen= null;
		try {
			keyPairGen= KeyPairGenerator.getInstance("RSA");
		} catch (NoSuchAlgorithmException e) {
			e.printStackTrace();
		}
		keyPairGen.initialize(1024, new SecureRandom());
		KeyPair keyPair= keyPairGen.generateKeyPair();
		this.privateKey= (RSAPrivateKey) keyPair.getPrivate();
		this.publicKey= (RSAPublicKey) keyPair.getPublic();
	}

	/**
	 * 从文件中输入流中加载公钥
	 * @param in 公钥输入流
	 * @throws Exception 加载公钥时产生的异常
	 */
	public void loadPublicKey(InputStream in) throws Exception{
		try {
			BufferedReader br= new BufferedReader(new InputStreamReader(in));
			String readLine= null;
			StringBuilder sb= new StringBuilder();
			while((readLine= br.readLine())!=null){
				if(readLine.charAt(0)=='-'){
					continue;
				}else{
					sb.append(readLine);
					sb.append('\r');
				}
			}
			loadPublicKey(sb.toString());
		} catch (IOException e) {
			throw new Exception("公钥数据流读取错误");
		} catch (NullPointerException e) {
			throw new Exception("公钥输入流为空");
		}
	}


	/**
	 * 从字符串中加载公钥
	 * @param publicKeyStr 公钥数据字符串
	 * @throws Exception 加载公钥时产生的异常
	 */
	public void loadPublicKey(String publicKeyStr) throws Exception{
		try {
			BASE64Decoder base64Decoder= new BASE64Decoder();
			byte[] buffer= base64Decoder.decodeBuffer(publicKeyStr);
			KeyFactory keyFactory= KeyFactory.getInstance("RSA");
			X509EncodedKeySpec keySpec= new X509EncodedKeySpec(buffer);
			this.publicKey= (RSAPublicKey) keyFactory.generatePublic(keySpec);
		} catch (NoSuchAlgorithmException e) {
			throw new Exception("无此算法");
		} catch (InvalidKeySpecException e) {
			throw new Exception("公钥非法");
		} catch (IOException e) {
			throw new Exception("公钥数据内容读取错误");
		} catch (NullPointerException e) {
			throw new Exception("公钥数据为空");
		}
	}

	/**
	 * 从文件中加载私钥
	 * @param keyFileName 私钥文件名
	 * @return 是否成功
	 * @throws Exception 
	 */
	public void loadPrivateKey(InputStream in) throws Exception{
		try {
			BufferedReader br= new BufferedReader(new InputStreamReader(in));
			String readLine= null;
			StringBuilder sb= new StringBuilder();
			while((readLine= br.readLine())!=null){
				if(readLine.charAt(0)=='-'){
					continue;
				}else{
					sb.append(readLine);
					sb.append('\r');
				}
			}
			loadPrivateKey(sb.toString());
		} catch (IOException e) {
			throw new Exception("私钥数据读取错误");
		} catch (NullPointerException e) {
			throw new Exception("私钥输入流为空");
		}
	}

	public void loadPrivateKey(String privateKeyStr) throws Exception{
		try {
			BASE64Decoder base64Decoder= new BASE64Decoder();
			byte[] buffer= base64Decoder.decodeBuffer(privateKeyStr);
			PKCS8EncodedKeySpec keySpec= new PKCS8EncodedKeySpec(buffer);
			KeyFactory keyFactory= KeyFactory.getInstance("RSA");
			this.privateKey= (RSAPrivateKey) keyFactory.generatePrivate(keySpec);
		} catch (NoSuchAlgorithmException e) {
			throw new Exception("无此算法");
		} catch (InvalidKeySpecException e) {
			throw new Exception("私钥非法");
		} catch (IOException e) {
			throw new Exception("私钥数据内容读取错误");
		} catch (NullPointerException e) {
			throw new Exception("私钥数据为空");
		}
	}

	/**
	 * 加密过程
	 * @param publicKey 公钥
	 * @param plainTextData 明文数据
	 * @return
	 * @throws Exception 加密过程中的异常信息
	 */
	public byte[] encrypt(RSAPublicKey publicKey, byte[] plainTextData) throws Exception{
		if(publicKey== null){
			throw new Exception("加密公钥为空, 请设置");
		}
		Cipher cipher= null;
		try {
			cipher= Cipher.getInstance("RSA");
			cipher.init(Cipher.ENCRYPT_MODE, publicKey);
			byte[] output= cipher.doFinal(plainTextData);
			return output;
		} catch (NoSuchAlgorithmException e) {
			throw new Exception("无此加密算法");
		} catch (NoSuchPaddingException e) {
			e.printStackTrace();
			return null;
		}catch (InvalidKeyException e) {
			throw new Exception("加密公钥非法,请检查");
		} catch (IllegalBlockSizeException e) {
			throw new Exception("明文长度非法");
		} catch (BadPaddingException e) {
			throw new Exception("明文数据已损坏");
		}
	}

	/**
	 * 解密过程
	 * @param privateKey 私钥
	 * @param cipherData 密文数据
	 * @return 明文
	 * @throws Exception 解密过程中的异常信息
	 */
	public byte[] decrypt(RSAPrivateKey privateKey, byte[] cipherData) throws Exception{
		if (privateKey== null){
			throw new Exception("解密私钥为空, 请设置");
		}
		Cipher cipher= null;
		try {
			cipher= Cipher.getInstance("RSA");
			cipher.init(Cipher.DECRYPT_MODE, privateKey);
			byte[] output= cipher.doFinal(cipherData);
			return output;
		} catch (NoSuchAlgorithmException e) {
			throw new Exception("无此解密算法");
		} catch (NoSuchPaddingException e) {
			e.printStackTrace();
			return null;
		}catch (InvalidKeyException e) {
			throw new Exception("解密私钥非法,请检查");
		} catch (IllegalBlockSizeException e) {
			throw new Exception("密文长度非法");
		} catch (BadPaddingException e) {
			throw new Exception("密文数据已损坏");
		}		
	}

	/**
	 * 私钥加密
	 * @param privateKey 私钥
	 * @param plainTextData 明文数据
	 * @return
	 * @throws Exception 加密过程中的异常信息
	 */
	public byte[] privateKeyEncrypt(RSAPrivateKey privateKey, byte[] plainTextData) throws Exception{

		if(privateKey== null){
			throw new Exception("加密私钥为空, 请设置");
		}

		try {
            Cipher cipher = Cipher.getInstance("RSA");
            cipher.init(Cipher.ENCRYPT_MODE, privateKey);
            int keyLen  = 256;
            int step    = keyLen - 11;
            byte[] output = new byte[(int)Math.ceil(plainTextData.length / (float)step) * keyLen];
            int outputLen = 0;
            for (int i = 0; i < plainTextData.length; i += step) {
                byte[] _output= cipher.doFinal(Arrays.copyOfRange(plainTextData, i, i + step));
                System.arraycopy(_output, 0, output, outputLen, _output.length);
                outputLen += _output.length;
            }
            return output;
		} catch (NoSuchAlgorithmException e) {
			throw new Exception("无此加密算法");
		} catch (NoSuchPaddingException e) {
			e.printStackTrace();
			throw new Exception("Error");
			//return null;
		}catch (InvalidKeyException e) {
			throw new Exception("加密私钥非法,请检查");
		} catch (IllegalBlockSizeException e) {
			throw new Exception("明文长度非法");
		} catch (BadPaddingException e) {
			throw new Exception("明文数据已损坏");
		}
	}

	/**
	 * 公钥解密
	 * @param publicKey 私钥
	 * @param cipherData 密文数据
	 * @return 明文
	 * @throws Exception 解密过程中的异常信息
	 */
	public byte[] publicKeyDecrypt(RSAPublicKey publicKey, byte[] cipherData) throws Exception{
		if (publicKey== null){
			throw new Exception("解密公钥为空, 请设置");
		}
        try {
            Cipher cipher = Cipher.getInstance("RSA");
            cipher.init(Cipher.DECRYPT_MODE, publicKey);
            byte[] output = new byte[cipherData.length];
            int outputLen = 0;
            int step = 256;
            for (int i = 0; i < cipherData.length; i += step) {
                byte[] _output= cipher.doFinal(Arrays.copyOfRange(cipherData, i, i + step));
                System.arraycopy(_output, 0, output, outputLen, _output.length);
                outputLen += _output.length;
            }
            return output;
		} catch (NoSuchAlgorithmException e) {
			throw new Exception("无此解密算法");
		} catch (NoSuchPaddingException e) {
			e.printStackTrace();
			return null;
		}catch (InvalidKeyException e) {
			throw new Exception("解密公钥非法,请检查");
		} catch (IllegalBlockSizeException e) {
			throw new Exception("密文长度非法");
		} catch (BadPaddingException e) {
			throw new Exception("密文数据已损坏");
		}		
	}


	/**
	 * 字节数据转十六进制字符串
	 * @param data 输入数据
	 * @return 十六进制内容
	 */
	public static String byteArrayToString(byte[] data){
		StringBuilder stringBuilder= new StringBuilder();
		for (int i=0; i<data.length; i++){
			//取出字节的高四位 作为索引得到相应的十六进制标识符 注意无符号右移
			stringBuilder.append(HEX_CHAR[(data[i] & 0xf0)>>> 4]);
			//取出字节的低四位 作为索引得到相应的十六进制标识符
			stringBuilder.append(HEX_CHAR[(data[i] & 0x0f)]);
			if (i<data.length-1){
				stringBuilder.append(' ');
			}
		}
		return stringBuilder.toString();
	}


	public static void main(String[] args){
		RSAEncrypt rsaEncrypt= new RSAEncrypt();
		//rsaEncrypt.genKeyPair();

		//加载公钥
		try {
			rsaEncrypt.loadPublicKey(RSAEncrypt.DEFAULT_PUBLIC_KEY);
			System.out.println("加载公钥成功");
		} catch (Exception e) {
			System.err.println(e.getMessage());
			System.err.println("加载公钥失败");
		}

		//加载私钥
		try {
			rsaEncrypt.loadPrivateKey(RSAEncrypt.DEFAULT_PRIVATE_KEY);
			System.out.println("加载私钥成功");
		} catch (Exception e) {
			System.err.println(e.getMessage());
			System.err.println("加载私钥失败");
		}

		//测试字符串
		String plainText= "{\"appid\":\"wxdedbed370678ea02\",\"noncestr\":\"9axp3pus9ozns4jpz5o6axawfeua9r9j\",\"package\":\"Sign=WXPay\",\"partnerid\":\"1491544602\",\"prepayid\":\"wx2017111416324490e62109c80067824932\",\"timestamp\":\"1510648364\",\"sign\":\"CCE3ACA8F4AEC0A18DC6328C45BD52KJIOIUKWEIORUOSDFSDFIOW#EUOIRWUEIORWEUIORIWOEIRWEROWERDFDF29\"}";
		System.out.println("原始明文:"+plainText);
		System.out.println("原始明文长度:"+plainText.length());

		try {
			//加密
             byte[] encryptByte = rsaEncrypt.privateKeyEncrypt(rsaEncrypt.getPrivateKey(), plainText.getBytes());
             System.out.println("密文:"+rsaEncrypt.byteArrayToString(encryptByte));
             System.out.println("密文长度:"+encryptByte.length);


//            String encryptStr = "FZTKLxsnb12oXE3tiLk1MPRt923KNBnqQL3U40cCNJGtzl6uV4o0Q81KhH+2SiqWTTsoZvmcqHcDpxpcHjR/8ZnEUzaSk71UPGBPmU1TLCdX/TTEbKpNCNmaBgPKOTKnLLzSmkAbunvzus5DrWOnouViECI0l7Css3JyAi4aXs/q7ati9ezT/mNZyNIIEFwhtk0xGFCOliP3Puywv68tfHRtrFtgVjs9DfW8xo8Tkd3NvCseNQ42y7WlWRFLId3aHrfd0VM/CS+BkU8LxgX+yq1D5yRhdi0/1Tf4wC+1Pj+IYERvcXMPzmq1t7DpUc2x4VmX6qOcUiuW2q7DX/rHKg==";
 //           byte[] encryptByte = Base64.decodeBase64(encryptStr.getBytes("UTF-8"));
 
			//解密
			//byte[] decryptByte = rsaEncrypt.publicKeyDecrypt(rsaEncrypt.getPublicKey(), encryptByte);
			byte[] decryptByte = rsaEncrypt.publicKeyDecrypt(rsaEncrypt.getPublicKey(), encryptByte);
			System.out.println("明文:"+rsaEncrypt.byteArrayToString(decryptByte));
			System.out.println("明文长度:"+ decryptByte.length);
			System.out.println(new String(decryptByte));
		} catch (Exception e) {
			System.err.println(e.getMessage());
		}
	}
}
