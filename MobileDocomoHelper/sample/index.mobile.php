<?php
/**
 * サンプル
 * 
 * @author IKEDA Youhei <youhey.ikeda@gmail.com>
 */

if (!defined('DS')) {
    /** @ignore */
    define('DS', DIRECTORY_SEPARATOR);
}

/** HTTPResponse class */
require_once 'HTTPResponse'.DS.'HTTPResponse.php';

/** Mobile class */
require_once 'MobileDocomoHelper'.DS.'Mobile.php';

/** Mobile_XHTML class */
require_once 'MobileDocomoHelper'.DS.'Mobile_XHTML.php';

/** Mobile_Emoji class */
require_once 'MobileDocomoHelper'.DS.'Mobile_Emoji.php';

/** GoogleAnalytics class */
require_once 'GoogleAnalytics'.DS.'GoogleAnalytics.php';

$http = new HTTPResponse;
$http->setContentType(Mobile_XHTML::contentType());
$http->enableOutputBuffer('ContentType');
$http->enableOutputBuffer('ContentLength');
$http->registerOutputBuffer(array('Mobile_Emoji', 'toBinary'));
$http->enableOutputBuffer('Utf8toSjis');

?>
<?php echo Mobile_XHTML::doctype().PHP_EOL ?>
<?php echo Mobile_XHTML::tagHtml().PHP_EOL ?>

<head>
<?php echo Mobile_XHTML::tagMetaContentType().PHP_EOL ?>
<?php if (Mobile::isEZweb()) : ?>
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />
<?php endif ?>
<title>サンプリング</title>
<style type="text/css">  
<![CDATA[
a:link { color: #0587D0; }
a:visited { color: #0587D0; }
a:hover { color: #FFBE00; }
a:active { color: #FFBE00; }
]]>
</style>
</head>
<body style="background:#FFFFFF;" bgcolor="#FFFFFF" text="#222222" link="#0587D0" alink="#FFBE00" vlink="#0587D0">
<a name="pagetop" id="pagetop"></a>
<hr style="color:#B50A14;background-color:#B50A14;height:1px;border:0px solid #B50A14;margin:0.3em 0;" color="#B50A14" size="1" />
<div align="center" style="text-align:center;">ヘッダ</div>
<hr style="color:#B50A14;background-color:#B50A14;height:1px;border:0px solid #B50A14;margin:0.3em 0;" color="#B50A14" size="1" />

<div style="font-size:small;">
<?php echo Mobile_Emoji::create(109) ?>モバイルページ
</div>

<hr style="color:#999999;background-color:#999999;height:1px;border:0px solid #999999;margin:0.3em 0;" color="#999999" size="1" />
<div align="center" style="text-align:center;">フッタ</div>

<?php echo GoogleAnalytics::makeTag('MO-xxxxxxx-1') ?>
</body>
</html>
