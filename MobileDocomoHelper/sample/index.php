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

/** Mobile */
require_once 'MobileDocomoHelper'.DS.'Mobile.php';

if (Mobile::isDocomo() || Mobile::isSoftBank() || Mobile::isEZweb()) {
    require_once dirname(__FILE__).DS.'index.mobile.php';
} else {
    require_once dirname(__FILE__).DS.'index.pc.php';
}
