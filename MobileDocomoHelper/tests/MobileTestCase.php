<?php


/**
 * Abstract MobileTestCase
 */
class MobileTestCase extends PHPUnit_Framework_TestCase
{
    const DATA_DIR = 'data';

    protected static function _docomoMovaUserAgents()
    {
        $files = array(
                'mova.html-1.0.txt', 
                'mova.html-2.0.txt', 
                'mova.html-3.0.txt', 
                'mova.html-4.0.txt', 
                'mova.html-5.0.txt', 
            );
        $agents = self::_loadTextFiles($files);

        return $agents;
    }

    protected static function _docomoChtmlFomaUserAgents()
    {
        $files = array(
                'foma.chtml.txt', 
            );
        $agents = self::_loadTextFiles($files);

        return $agents;
    }

    protected static function _docomoXhtmlFomaUserAgents()
    {
        $files = array(
                'foma.html-4.0.txt', 
                'foma.html-5.0.txt', 
                'foma.html-6.0.txt', 
                'foma.html-7.0.txt', 
                'foma.html-7.1.txt', 
                'foma.html-7.2.txt', 
                'foma.imode-2.0.txt', 
            );
        $agents = self::_loadTextFiles($files);

        return $agents;
    }

    protected static function _ezwebWap1UserAgents()
    {
        $files = array(
                'ezweb.wap1.txt', 
            );
        $agents = self::_loadTextFiles($files);

        return $agents;
    }

    protected static function _ezwebWap2UserAgents()
    {
        $files = array(
                'ezweb.wap2.txt', 
            );
        $agents = self::_loadTextFiles($files);

        return $agents;
    }

    protected static function _softBankTypeCUserAgents()
    {
        $files = array(
                'softbank.type-c.txt', 
            );
        $agents = self::_loadTextFiles($files);

        return $agents;
    }

    protected static function _softBankTypePUserAgents()
    {
        $files = array(
                'softbank.type-p.txt', 
            );
        $agents = self::_loadTextFiles($files);

        return $agents;
    }

    protected static function _softBankTypeWUserAgents()
    {
        $files = array(
                'softbank.type-w.txt', 
            );
        $agents = self::_loadTextFiles($files);

        return $agents;
    }

    protected static function _softBank3GCUserAgents()
    {
        $files = array(
                'softbank.3gc.txt', 
            );
        $agents = self::_loadTextFiles($files);

        return $agents;
    }

    protected static function _loadTextFiles(Array $files)
    {
        $dir  = dirname(__FILE__).DIRECTORY_SEPARATOR.self::DATA_DIR;
        $list = array();
        foreach ($files as $f) {
            $path = $dir.DIRECTORY_SEPARATOR.$f;
            $data = file($path, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
            $list = array_merge($list, $data);
        }

        return $list;
    }

    protected static function _importPhpArray($file)
    {
        $dir  = dirname(__FILE__).DIRECTORY_SEPARATOR.self::DATA_DIR;
        $path = $dir.DIRECTORY_SEPARATOR.$file;
        $data = @include $path;

        return (array)$data;
    }
}
