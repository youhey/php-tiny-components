<?php

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'MobileTestCase.php';

require_once
    dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Mobile.php';

/**
 * Test class for Mobile.
 */
class MobileTest extends MobileTestCase
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
     * test of User Agent for docomo
     * 
     * - Mobile::isDocomo()
     * - Mobile::isEZweb()
     * - Mobile::isSoftBank()
     * 
     * @param  string $ua User Agent
     * @return void
     * @dataProvider providerDocomo
     */
    public function testIsDocomo($ua)
    {
        $_SERVER  = array('HTTP_USER_AGENT' => $ua);
        $this->assertTrue(Mobile::isDocomo(), "UserAgent is '{$ua}'");
        $this->assertFalse(Mobile::isEZweb(), "UserAgent is '{$ua}'");
        $this->assertFalse(Mobile::isSoftBank(), "UserAgent is '{$ua}'");
    }

    /**
     * test of User Agent for EZweb
     * 
     * - Mobile::isDocomo()
     * - Mobile::isEZweb()
     * - Mobile::isSoftBank()
     * 
     * @param  string $ua User Agent
     * @return void
     * @dataProvider providerEZweb
     */
    public function testIsEZweb($ua)
    {
        $_SERVER  = array('HTTP_USER_AGENT' => $ua);
        $this->assertFalse(Mobile::isDocomo(), "UserAgent is '{$ua}'");
        $this->assertTrue(Mobile::isEZweb(), "UserAgent is '{$ua}'");
        $this->assertFalse(Mobile::isSoftBank(), "UserAgent is '{$ua}'");
    }

    /**
     * test of User Agent for SoftBank
     * 
     * - Mobile::isDocomo()
     * - Mobile::isEZweb()
     * - Mobile::isSoftBank()
     * 
     * @param  string $ua User Agent
     * @return void
     * @dataProvider providerSoftBank
     */
    public function testSoftBank($ua)
    {
        $_SERVER  = array('HTTP_USER_AGENT' => $ua);
        $this->assertFalse(Mobile::isDocomo(), "UserAgent is '{$ua}'");
        $this->assertFalse(Mobile::isEZweb(), "UserAgent is '{$ua}'");
        $this->assertTrue(Mobile::isSoftBank(), "UserAgent is '{$ua}'");
    }

    /**
     * test of User Agent for XHTML device
     * 
     * - Mobile::isXhtml()
     * 
     * @param  string $ua User Agent
     * @return void
     * @dataProvider providerXHTML
     */
    public function testIsXhtml($ua)
    {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);
        $this->assertTrue(Mobile::isXhtml(), "UserAgent is '{$ua}'");
    }

    /**
     * test of User Agent for not XHTML device
     * 
     * - Mobile::isXhtml()
     * 
     * @param  string $ua User Agent
     * @return void
     * @dataProvider providerNotXHTML
     */
    public function testIsNotXhtml($ua)
    {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);
        $this->assertFalse(Mobile::isXhtml(), "UserAgent is '{$ua}'");
    }

    /**
     * test of bug id 17197 in Net_UserAgent_Mobile
     * 
     * <p>YahooケータイのクローラがはくUAに対応するためのbugfix</p>
     * 
     * @retunr void
     */
    public function testBugfix_Net_UserAgent_Mobile_bug17197()
    {
        $_SERVER = array('HTTP_USER_AGENT' => 'Vodafone/1.0/V705SH (compatible; Y!J-SRD/1.0; http://help.yahoo.co.jp/help/jp/search/indexing/indexing-27.html)');
        $this->assertTrue(Mobile::isSoftBank());
    }

    /**
     * MOVAの機種、FOMAのXHTML非対応機種、FOMAのXHTML対応機種
     * 
     * @return array
     */
    public static function providerDocomo()
    {
        $agents = array_merge(self::_docomoMovaUserAgents(), 
                              self::_docomoChtmlFomaUserAgents(), 
                              self::_docomoXhtmlFomaUserAgents());

        $provider = array();
        foreach ($agents as $agent) {
            $provider[] = array($agent);
        }

        return $provider;
    }

    /**
     * auのWAP1.x機種、auのWAP2.0機種
     * 
     * @return array
     */
    public static function providerEZWeb()
    {
        $agents = array_merge(self::_ezwebWap1UserAgents(), 
                              self::_ezwebWap2UserAgents());

        $provider = array();
        foreach ($agents as $agent) {
            $provider[] = array($agent);
        }

        return $provider;
    }

    /**
     * SoftBankのTypeC機種、TypeP機種、TypeW機種、3GC機種
     * 
     * @return array
     */
    public static function providerSoftBank()
    {
        $agents = array_merge(self::_softBankTypeCUserAgents(), 
                              self::_softBankTypePUserAgents(), 
                              self::_softBankTypeWUserAgents(), 
                              self::_softBank3GCUserAgents());

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
    public static function providerXHTML()
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
     * MOVAの機種はXHTMLに非対応
     * FOMAでも、XHTMLに非対応の機種
     * auのWAP1.x機種はXHTMLに非対応
     * SoftBankのTypeC機種はXHTMLに非対応
     * SoftBankのTypeP機種はXHTMLに非対応
     * 
     * @return array
     */
    public static function providerNotXHTML()
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
}
