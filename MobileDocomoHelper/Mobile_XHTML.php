<?php
/**
 * モバイル - モバイルサイト対応の機能を提供
 * 
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 */

/** Mobile */
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'Mobile.php';

/** MobileException */
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'MobileException.php';

/**
 * モバイルサイトのXHTML
 *
 * @author IKEDA Youhei <youhey.ikeda@gmail.com>
 */
class Mobile_XHTML
{

    /** XML宣言、DOCTYPE宣言 */
    const 
        XML_DECLARATION  = '<?xml version="1.0" encoding="Shift_JIS"?>', 
        DOCTYPE_I_10     = '<!DOCTYPE html PUBLIC "-//i-mode group (ja)//DTD XHTML i-XHTML(Locale/Ver.=ja/1.0) 1.0//EN" "i-xhtml_4ja_10.dtd">', 
        DOCTYPE_I_11     = '<!DOCTYPE html PUBLIC "-//i-mode group (ja)//DTD XHTML i-XHTML(Locale/Ver.=ja/1.1) 1.0//EN" "i-xhtml_4ja_10.dtd">', 
        DOCTYPE_I_20     = '<!DOCTYPE html PUBLIC "-//i-mode group (ja)//DTD XHTML i-XHTML(Locale/Ver.=ja/2.0) 1.0//EN" "i-xhtml_4ja_10.dtd">', 
        DOCTYPE_I_21     = '<!DOCTYPE html PUBLIC "-//i-mode group (ja)//DTD XHTML i-XHTML(Locale/Ver.=ja/2.1) 1.0//EN" "i-xhtml_4ja_10.dtd">', 
        DOCTYPE_I_22     = '<!DOCTYPE html PUBLIC "-//i-mode group (ja)//DTD XHTML i-XHTML(Locale/Ver.=ja/2.2) 1.0//EN" "i-xhtml_4ja_10.dtd">', 
        DOCTYPE_I_23     = '<!DOCTYPE html PUBLIC "-//i-mode group (ja)//DTD XHTML i-XHTML(Locale/Ver.=ja/2.3) 1.0//EN" "i-xhtml_4ja_10.dtd">', 
        DOCTYPE_OPENWAVE = '<!DOCTYPE html PUBLIC "-//OPENWAVE//DTD XHTML 1.0//EN" "http://www.openwave.com/DTD/xhtml-basic.dtd">', 
        DOCTYPE_JPHONE   = '<!DOCTYPE html PUBLIC "-//J-PHONE//DTD XHTML Basic 1.0 Plus//EN" "xhtml-basic10-plus.dtd">';

    /** Content-Type */
    const 
        CONTENT_TYPE_HTML  = 'text/html', 
        CONTENT_TYPE_XHTML = 'application/xhtml+xml';

    /** <html>, <meta ...> */
    const 
        TAG_HTML_WITH_XMLNS   = '<html xmlns="http://www.w3.org/1999/xhtml">', 
        TAG_HTML_SIMPLE       = '<html>', 
        TAG_META_CONTENT_TYPE = '<meta http-equiv="content-Type" content="%1$s; charset=%2$s" />';
    /**
     * HTTPヘッダで応答するコンテンツの文字コード
     */
    const CONTENT_CHARSET = 'Shift_JIS';


    /**
     * ユーザエージェントの機種に応じたDOCTYPE宣言を返却
     * 
     * @return string DOCTYPE宣言、XHTML非対応端末であればEmpty
     * @see    Mobile::isDocomo()
     * @see    Mobile::isXhtml()
     */
    public function doctype()
    {
        $doctype = '';

        if (Mobile::isDocomo() && Mobile::isXhtml()) {
            $version = Mobile::getDocomoHtmlVersion();
            switch ($version) {
            case '4.0' : 
                $doctype = self::XML_DECLARATION.PHP_EOL.self::DOCTYPE_I_10;
                break;
            case '5.0' : 
                $doctype = self::XML_DECLARATION.PHP_EOL.self::DOCTYPE_I_11;
                break;
            case '6.0' : 
                $doctype = self::XML_DECLARATION.PHP_EOL.self::DOCTYPE_I_20;
                break;
            case '7.0' : 
                $doctype = self::XML_DECLARATION.PHP_EOL.self::DOCTYPE_I_21;
                break;
            case '7.1' : 
                $doctype = self::XML_DECLARATION.PHP_EOL.self::DOCTYPE_I_22;
                break;
            case '7.2' : 
                $doctype = self::XML_DECLARATION.PHP_EOL.self::DOCTYPE_I_23;
                break;
            default : 
                throw new MobileException("unknown HTML version: {$version}");
                break;
            }
        } elseif (Mobile::isEZweb() && Mobile::isXhtml()) {
            $doctype = self::XML_DECLARATION.PHP_EOL.self::DOCTYPE_OPENWAVE;
        } elseif (Mobile::isSoftBank() && Mobile::isXhtml()) {
            $doctype = self::XML_DECLARATION.PHP_EOL.self::DOCTYPE_JPHONE;
        }

        return $doctype;
    }

    /**
     * ユーザエージェントの機種に応じたContent-Typeを返却
     * 
     * @return string Content-Type
     */
    public static function contentType()
    {
        $html         = self::CONTENT_TYPE_HTML;
        $xhtml        = self::CONTENT_TYPE_XHTML;
        $charset      = self::CONTENT_CHARSET;
        $content_type = Mobile::isXhtml() ? $xhtml : "{$html}; charset={$charset}";

        return $content_type;
    }

    /**
     * ユーザエージェントの機種に応じた<html>タグを返却
     * 
     * @return string <html>
     */
    public static function tagHtml()
    {
        $tag = Mobile::isXhtml() ? 
               self::TAG_HTML_WITH_XMLNS : self::TAG_HTML_SIMPLE;

        return $tag;
    }

    /**
     * ユーザエージェントの機種に応じた<meta>タグのContent-Typeを返却
     * 
     * @return string <meta> of Content-Type
     */
    public static function tagMetaContentType()
    {
        $content = Mobile::isXhtml() ? 
                   self::CONTENT_TYPE_XHTML : self::CONTENT_TYPE_HTML;
        $charset = self::CONTENT_CHARSET;
        $tag     = sprintf(self::TAG_META_CONTENT_TYPE, $content, $charset);

        return $tag;
    }
}
