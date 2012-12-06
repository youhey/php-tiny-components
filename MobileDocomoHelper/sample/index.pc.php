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

$http = new HTTPResponse;
$http->setContentType('text/html');
$http->setCharset('utf-8');
$http->enableOutputBuffer('ContentType');
$http->enableOutputBuffer('ContentLength');

?>
<?php echo '<?xml version="1.0" encoding="utf-8"?>' ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-language" content="ja" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <title>サンプル</title>
</head>
<body>
    PCページ
</body>
</html>
