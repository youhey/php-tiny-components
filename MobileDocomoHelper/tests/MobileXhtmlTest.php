<?php
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'MobileTestCase.php';

require_once
    dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Mobile_XHTML.php';

/**
 * Test class for Mobile_XHTML.
 */
class MobileXhtmlTest extends MobileTestCase
{

    private $cacheServerEnv = array();

    public function setUp()
    {
        if (isset($_SERVER)) {
            $this->cacheServerEnv = $_SERVER;
        }
        $_SERVER = array();
    }

    public function tearDown()
    {
        $_SERVER = $this->cacheServerEnv;
    }

    /**
     * test of Mobile_XHTML::doctype() in User Agent for not XHTML device
     * 
     * @param  string $ua User Agent
     * @return void
     * @dataProvider providerNotXHTML
     */
    public function testDoctype_Empty($ua)
    {
        $_SERVER  = array('HTTP_USER_AGENT' => $ua);
        $result   = Mobile_XHTML::doctype();
        $expected = '';
        $this->assertSame($expected, $result, "UserAgent is '{$ua}'");
    }

    /**
     * test of Mobile_XHTML::doctype() in User Agent for docomo XHTML device
     * 
     * @param  string $ua User Agent
     * @return void
     * @dataProvider providerDocomoXHTML
     */
    public function testDoctype_DocomoFoma($ua)
    {
        $_SERVER  = array('HTTP_USER_AGENT' => $ua);
        $result   = Mobile_XHTML::doctype();
        $expected = '#<\?xml version="1\.0" encoding="Shift_JIS"\?>'
                  . PHP_EOL 
                  . '<!DOCTYPE html PUBLIC "-//i-mode group \(ja\)//DTD XHTML '
                  . 'i-XHTML\(Locale/Ver\.=ja/[12].[0123]\) 1\.0//EN" ' 
                  . '"i-xhtml_4ja_10\.dtd">#';
        $this->assertRegExp($expected, $result, "UserAgent is '{$ua}'");
    }

    /**
     * test of Mobile_XHTML::doctype() in User Agent for au XHTML device
     * 
     * @param  string $ua User Agent
     * @return void
     * @dataProvider providerEZwebXHTML
     */
    public function testDoctype_EZwebWap2($ua)
    {
        $_SERVER  = array('HTTP_USER_AGENT' => $ua);
        $result   = Mobile_XHTML::doctype();
        $expected = '<?xml version="1.0" encoding="Shift_JIS"?>' 
                  . PHP_EOL 
                  . '<!DOCTYPE html PUBLIC "-//OPENWAVE//DTD XHTML 1.0//EN" ' 
                  . '"http://www.openwave.com/DTD/xhtml-basic.dtd">';
        $this->assertSame($expected, $result, "UserAgent is '{$ua}'");
    }

    /**
     * test of Mobile_XHTML::doctype() in User Agent for au XHTML device
     * 
     * @param  string $ua User Agent
     * @return void
     * @dataProvider providerSoftBankXHTML
     */
    public function testDoctype_SoftBankXHTML($ua)
    {

        $_SERVER  = array('HTTP_USER_AGENT' => $ua);
        $result   = Mobile_XHTML::doctype();
        $expected = '<?xml version="1.0" encoding="Shift_JIS"?>' 
                  . PHP_EOL 
                  . '<!DOCTYPE html PUBLIC "-//J-PHONE//DTD ' 
                  . 'XHTML Basic 1.0 Plus//EN" "xhtml-basic10-plus.dtd">';
        $this->assertSame($expected, $result, "UserAgent is '{$ua}'");
    }

    /**
     * test Mobile_XHTML::contentType() in User Agent for XHTML device
     * 
     * @param  string $ua User Agent
     * @retunr void
     * @dataProvider providerXHTML
     */
    public function testContentType_XHTML($ua)
    {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);
        $result   = Mobile_XHTML::contentType();
        $expected = 'application/xhtml+xml';
        $this->assertSame($expected, $result, "UserAgent is '{$ua}'");
    }

    /**
     * test Mobile_XHTML::contentType() in User Agent for Not XHTML device
     * 
     * @param  string $ua User Agent
     * @retunr void
     * @dataProvider providerNotXHTML
     */
    public function testContentType_NotXHTML($ua)
    {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);
        $result   = Mobile_XHTML::contentType();
        $expected = 'text/html; charset=Shift_JIS';
        $this->assertSame($expected, $result, "UserAgent is '{$ua}'");
    }

    /**
     * test Mobile_XHTML::tagHtml() in User Agent for XHTML device
     * 
     * @param  string $ua User Agent
     * @retunr void
     * @dataProvider providerXHTML
     */
    public function testTagHtml_XHTML($ua)
    {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);
        $result   = Mobile_XHTML::tagHtml();
        $expected = '<html xmlns="http://www.w3.org/1999/xhtml">';
        $this->assertSame($expected, $result, "UserAgent is '{$ua}'");
    }

    /**
     * test Mobile_XHTML::tagHtml() in User Agent for Not XHTML device
     * 
     * @param  string $ua User Agent
     * @retunr void
     * @dataProvider providerNotXHTML
     */
    public function testTagHtml_NotXHTML($ua)
    {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);
        $result   = Mobile_XHTML::tagHtml();
        $expected = '<html>';
        $this->assertSame($expected, $result, "UserAgent is '{$ua}'");
    }

    /**
     * test Mobile_XHTML::tagMetaContentType() in User Agent for XHTML device
     * 
     * @param  string $ua User Agent
     * @retunr void
     * @dataProvider providerXHTML
     */
    public function testTagMetaContentType_XHTML($ua)
    {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);
        $result   = Mobile_XHTML::tagMetaContentType();
        $expected = '<meta http-equiv="content-Type" content="application/xhtml+xml; charset=Shift_JIS" />';
        $this->assertSame($expected, $result, "UserAgent is '{$ua}'");
    }

    /**
     * test Mobile_XHTML::tagMetaContentType() in User Agent for Not XHTML device
     * 
     * @param  string $ua User Agent
     * @retunr void
     * @dataProvider providerNotXHTML
     */
    public function testTagMetaContentType_NotXHTML($ua)
    {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);
        $result   = Mobile_XHTML::tagMetaContentType();
        $expected = '<meta http-equiv="content-Type" content="text/html; charset=Shift_JIS" />';
        $this->assertSame($expected, $result, "UserAgent is '{$ua}'");
    }

    /**
     * MOVAの機種はXHTMLに非対応
     * FOMAでも、XHTMLに非対応の機種
     * auのWAP1.x機種はXHTMLに非対応
     * SoftBankのTypeC機種はXHTMLに非対応
     * SoftBankのTypeP機種はXHTMLに非対応
     * 
     * @return array
     */
    public function providerNotXHTML()
    {
        $agents = array_merge(self::_docomoMovaUserAgents(), 
                              self::_docomoChtmlFomaUserAgents(), 
                              self::_ezwebWap1UserAgents(), 
                              self::_softBankTypeCUserAgents(), 
                              self::_softBankTypePUserAgents());

        $provider = array();
        foreach ($agents as $agent) {
            $provider[] = array($agent);
        }

        return $provider;
    }

    /**
     *  FOMAのXHTML対応機種
     *  auのWAP2.0機種（XHTMLに対応）
     *  SoftBankのTypeW機種（XHTMLに対応）
     *  SoftBankの3GC機種（XHTMLに対応）
     * 
     * @return array
     */
    public function providerXHTML()
    {
        $agents = array_merge(self::_docomoXhtmlFomaUserAgents(), 
                              self::_ezwebWap2UserAgents(), 
                              self::_softBankTypeWUserAgents(), 
                              self::_softBank3GCUserAgents());

        $provider = array();
        foreach ($agents as $agent) {
            $provider[] = array($agent);
        }

        return $provider;
    }

    /**
     * FOMAのXHTML対応機種
     * 
     * @return array
     */
    public function providerDocomoXHTML()
    {
        $agents = self::_docomoXhtmlFomaUserAgents();

        $provider = array();
        foreach ($agents as $agent) {
            $provider[] = array($agent);
        }

        return $provider;
    }

    /**
     * auのWAP2.0機種（XHTMLに対応）
     * 
     * @return array
     */
    public function providerEZwebXHTML()
    {
        $agents = self::_ezwebWap2UserAgents();

        $provider = array();
        foreach ($agents as $agent) {
            $provider[] = array($agent);
        }

        return $provider;
    }

    /**
     * SoftBankのTypeW機種（XHTMLに対応）
     * SoftBankの3GC機種（XHTMLに対応）
     * 
     * @return array
     */
    public function providerSoftBankXHTML()
    {
        $agents = array_merge(self::_softBankTypeWUserAgents(), 
                              self::_softBank3GCUserAgents());

        $provider = array();
        foreach ($agents as $agent) {
            $provider[] = array($agent);
        }

        return $provider;
    }
}
