<?php
/*****************************************************************
 * 文件名称：qrcode.php
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-03-06 09:36
 * 描    述：
 *****************************************************************/


/**
 * 生成二维码
 * @param string $text 生成二维码用的文本
 * @return string 返回二维码图片链接
 */
public static function genQrcode($text) {
    $imgPath		= self::getImagePath('tmp');
    $pos			= strrpos($imgPath, '/');
    $fileName		= substr($imgPath, $pos + 1);
    $relatPath		= substr($imgPath, 0, $pos).'/'.date('Ymd').'/';
    $fullPath		= BASE_DIR.$relatPath;
    if (!is_dir($fullPath)) {
        if (!mkdir($fullPath, 0755, true)) {
            return '';
        }
    }
    $fileName		= $relatPath.$fileName.".png";
    $fullFileName	= BASE_DIR.$fileName;

    /* 生成二维码 */
    $errLevel = 'L';        /* 容错级别 */
    $size = 6;              /* 生成图片大小 */
    QRcode::png($text, $fullFileName, $errLevel, $size, 2); /* 生成二维码图片  */
    if (!is_file($fullFileName)) {
        return '';
    }

    /* 上传二维码图片至七牛 */
    $qiniu = new FsQiniuUploadManage();
    $res = $qiniu->uploadOrgPic($fullFileName, $fileName); /* 上传七牛 */
    if ($res['code'] == 200) {
        return $res['imgUrl'];
    } else {
        return '';
    }
}
