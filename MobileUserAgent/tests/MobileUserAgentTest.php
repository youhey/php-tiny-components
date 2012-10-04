<?php
/**
 * モバイルサイト対応のための携帯電話端末の判別モジュール
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 * 
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/** MobileUserAgent */
require_once dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'MobileUserAgent.php';

/**
 * テストケース - MobileUserAgent
 * 
 * @author IKEDA Youhei <youhey.ikeda@gmail.com>
 */
class MobileUserAgentTest extends PHPUnit_Framework_TestCase
{

    private $cacheServerEnv = array();

    public function setUp() {
        if (isset($_SERVER)) {
            $this->cacheServerEnv = $_SERVER;
        }
        $_SERVER = array();
    }
    public function tearDown() {
        $_SERVER       = $this->cacheServerEnv;
        $this->request = null;
    }

    /** @dataProvider provider：docomo「MOVA」のユーザエージェント */
    public function test：「isDocomo」メソッドの正常系、docomoのMOVA機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isDocomo();
        $this->assertTrue($result, "docomoのMOVA「{$ua}」はドコモの端末です");
    }

    /** @dataProvider provider：docomo「MOVA」のユーザエージェント */
    public function test：「isEZweb」メソッドの正常系、docomoのMOVA機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isEZweb();
        $this->assertFalse($result, "docomoのMOVA「{$ua}」はauの端末ではない");
    }

    /** @dataProvider provider：docomo「MOVA」のユーザエージェント */
    public function test：「isSoftBank」メソッドの正常系、docomoのMOVA機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isSoftBank();
        $this->assertFalse($result, "docomoのMOVA「{$ua}」はSoftBankの端末ではない");
    }

    /** @dataProvider provider：docomo「XHTMl非対応FOMA」のユーザエージェント */
    public function test：「isDocomo」メソッドの正常系、docomoのXHTMl非対応FOMA($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isDocomo();
        $this->assertTrue($result, "docomoのXHTMl非対応FOMA「{$ua}」はドコモの端末です");
    }

    /** @dataProvider provider：docomo「XHTMl非対応FOMA」のユーザエージェント */
    public function test：「isEZweb」メソッドの正常系、docomoのXHTMl非対応FOMA($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isEZweb();
        $this->assertFalse($result, "docomoのXHTMl非対応FOMA「{$ua}」はauの端末ではない");
    }

    /** @dataProvider provider：docomo「XHTMl非対応FOMA」のユーザエージェント */
    public function test：「isSoftBank」メソッドの正常系、docomoのXHTMl非対応FOMA($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isSoftBank();
        $this->assertFalse($result, "docomoのXHTMl非対応FOMA「{$ua}」はSoftBankの端末ではない");
    }

    /** @dataProvider provider：docomo「XHTML対応FOMA」のユーザエージェント */
    public function test：「isDocomo」メソッドの正常系、docomoのXHTML対応FOMA機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isDocomo();
        $this->assertTrue($result, "docomoのXHTML対応FOMA「{$ua}」はドコモの端末です");
    }

    /** @dataProvider provider：docomo「XHTML対応FOMA」のユーザエージェント */
    public function test：「isEZweb」メソッドの正常系、docomoのXHTML対応FOMA機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isEZweb();
        $this->assertFalse($result, "docomoのXHTML対応FOMA「{$ua}」はauの端末ではない");
    }

    /** @dataProvider provider：docomo「XHTML対応FOMA」のユーザエージェント */
    public function test：「isSoftBank」メソッドの正常系、docomoのXHTML対応FOMA機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isSoftBank();
        $this->assertFalse($result, "docomoのXHTML対応FOMA「{$ua}」はSoftBankの端末ではない");
    }

    /** @dataProvider provider：au「XHTML非対応端末」のユーザエージェント */
    public function test：「isDocomo」メソッドの正常系、auのXHTML非対応機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isDocomo();
        $this->assertFalse($result, "auのXHTML非対応端末「{$ua}」はドコモの端末ではない");
    }

    /** @dataProvider provider：au「XHTML非対応端末」のユーザエージェント */
    public function test：「isEZweb」メソッドの正常系、auのXHTML非対応機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isEZweb();
        $this->assertTrue($result, "auのXHTML非対応端末「{$ua}」はauの端末です");
    }

    /** @dataProvider provider：au「XHTML非対応端末」のユーザエージェント */
    public function test：「isSoftBank」メソッドの正常系、auのXHTML非対応機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isSoftBank();
        $this->assertFalse($result, "auのXHTML非対応端末「{$ua}」はSoftBankの端末ではない");
    }

    /** @dataProvider provider：au「XHTML対応端末」のユーザエージェント */
    public function test：「isDocomo」メソッドの正常系、auのXHTML対応機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isDocomo();
        $this->assertFalse($result, "auのXHTML対応端末「{$ua}」はドコモの端末ではない");
    }

    /** @dataProvider provider：au「XHTML対応端末」のユーザエージェント */
    public function test：「isEZweb」メソッドの正常系、auのXHTML対応機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isEZweb();
        $this->assertTrue($result, "auのXHTML対応端末「{$ua}」はauの端末です");
    }

    /** @dataProvider provider：au「XHTML対応端末」のユーザエージェント */
    public function test：「isSoftBank」メソッドの正常系、auのXHTML対応機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isSoftBank();
        $this->assertFalse($result, "auのXHTML対応端末「{$ua}」はSoftBankの端末ではない");
    }

    /** @dataProvider provider：SoftBank「XHTML非対応端末」のユーザエージェント */
    public function test：「isDocomo」メソッドの正常系、SoftBankのXHTML非対応機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isDocomo();
        $this->assertFalse($result, "SoftBankのXHTML対応端末「{$ua}」はドコモの端末ではない");
    }

    /** @dataProvider provider：SoftBank「XHTML非対応端末」のユーザエージェント */
    public function test：「isEZweb」メソッドの正常系、SoftBankのXHTML非対応機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isEZweb();
        $this->assertFalse($result, "SoftBankのXHTML対応端末「{$ua}」はauの端末ではない");
    }

    /** @dataProvider provider：SoftBank「XHTML非対応端末」のユーザエージェント */
    public function test：「isSoftBank」メソッドの正常系、SoftBankのXHTML非対応機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isSoftBank();
        $this->assertTrue($result, "SoftBankのXHTML対応端末「{$ua}」はSoftBankの端末です");
    }

    /** @dataProvider provider：SoftBank「XHTML対応端末」のユーザエージェント */
    public function test：「isDocomo」メソッドの正常系、SoftBankのXHTML対応機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isDocomo();
        $this->assertFalse($result, "SoftBankのXHTML対応端末「{$ua}」はドコモの端末ではない");
    }

    /** @dataProvider provider：SoftBank「XHTML対応端末」のユーザエージェント */
    public function test：「isEZweb」メソッドの正常系、SoftBankのXHTML対応機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isEZweb();
        $this->assertFalse($result, "SoftBankのXHTML対応端末「{$ua}」はauの端末ではない");
    }

    /** @dataProvider provider：SoftBank「XHTML対応端末」のユーザエージェント */
    public function test：「isSoftBank」メソッドの正常系、SoftBankのXHTML対応機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isSoftBank();
        $this->assertTrue($result, "SoftBankのXHTML対応端末「{$ua}」はSoftBankの端末です");
    }

    /** @dataProvider provider：docomo「MOVA」のユーザエージェント */
    public function test：「isXhtml」メソッドの正常系、docomoのMOVA機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isXhtml();
        $this->assertFalse($result, "ドコモのMOVA「{$ua}」はXHTML対応端末ではない");
    }

    /** @dataProvider provider：docomo「XHTMl非対応FOMA」のユーザエージェント */
    public function test：「isXhtml」メソッドの正常系、docomoのXHTML非対応FOMA機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isXhtml();
        $this->assertFalse($result, "docomoのXHTML非対応FOMA端末「{$ua}」はXHTML対応端末ではない");
    }

    /** @dataProvider provider：au「XHTML非対応端末」のユーザエージェント */
    public function test：「isXhtml」メソッドの正常系、auのXHTML非対応機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isXhtml();
        $this->assertFalse($result, "auのXHTML非対応端末「{$ua}」はXHTML対応端末ではない");
    }

    /** @dataProvider provider：SoftBank「XHTML非対応端末」のユーザエージェント */
    public function test：「isXhtml」メソッドの正常系、SoftBankのXHTML非対応機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isXhtml();
        $this->assertFalse($result, "SoftBankのXHTML非対応端末「{$ua}」はXHTML対応端末ではない");
    }

    /** @dataProvider provider：docomo「XHTML対応FOMA」のユーザエージェント */
    public function test：「isXhtml」メソッドの正常系、docomoのXHTML対応FOMA機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isXhtml();
        $this->assertTrue($result, "ドコモのXHTML対応FOMA端末「{$ua}」はXHTML対応端末です");
    }

    /** @dataProvider provider：au「XHTML対応端末」のユーザエージェント */
    public function test：「isXhtml」メソッドの正常系、ezWebのXHTML対応機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        // auのWAP2.0機種はXHTMLに対応しているのでTRUE
        $result = MobileUserAgent::isXhtml();
        $this->assertTrue($result, "auのXHTML対応端末「{$ua}」はXHTML対応端末です");
    }

    /** @dataProvider provider：SoftBank「XHTML対応端末」のユーザエージェント */
    public function test：「isXhtml」メソッドの正常系、SoftBankのXHTML対応機種($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        // SoftBankのTypeW機種はXHTMLに対応しているのでTRUE
        // SoftBankの3GC機種はXHTMLに対応しているのでTRUE
        $result = MobileUserAgent::isXhtml();
        $this->assertTrue($result, "SoftBankのXHTML対応端末「{$ua}」はXHTML対応端末です");
    }

    /** @dataProvider provider：国内3キャリア携帯電話のユーザエージェント */
    public function test：「isFeaturePhone」メソッドの正常系、UAが国内3キャリアの携帯電話($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isFeaturePhone();
        $this->assertTrue($result, "国内3キャリアの携帯電話「{$ua}」はフィーチャフォンです");
    }

    /** @dataProvider provider：PCブラウザのユーザエージェント */
    public function test：「isFeaturePhone」メソッドの正常系、UAがPCブラウザ($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);
        $result  = MobileUserAgent::isFeaturePhone();
        $this->assertFalse($result, "PCブラウザ「{$ua}」はフィーチャフォンではない");
    }

    /** @dataProvider provider：iOS系のユーザエージェント */
    public function test：「isFeaturePhone」メソッドの正常系、UAがiOS系($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);
        $result  = MobileUserAgent::isFeaturePhone();
        $this->assertFalse($result, "iOS系の「{$ua}」はフィーチャフォンではない");
    }

    /** @dataProvider provider：Android系のユーザエージェント */
    public function test：「isFeaturePhone」メソッドの正常系、UAがAndroid系($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);
        $result  = MobileUserAgent::isFeaturePhone();
        $this->assertFalse($result, "Android系の「{$ua}」はフィーチャフォンではない");
    }

    /** @dataProvider provider：WindowsMobile系のユーザエージェント */
    public function test：「isFeaturePhone」メソッドの正常系、UAがWindowsMobile系($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);
        $result  = MobileUserAgent::isFeaturePhone();
        $this->assertFalse($result, "Windows Mobile系の「{$ua}」はフィーチャフォンではない");
    }

    /** @dataProvider provider：BlackBerry系のユーザエージェント */
    public function test：「isFeaturePhone」メソッドの正常系、UAがBlackBerry系($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isFeaturePhone();
        $this->assertFalse($result, "Black Berry系の「{$ua}」はフィーチャフォンではない");
    }

    /** @dataProvider provider：iOS系のユーザエージェント */
    public function test：「isSmartPhone」メソッドの正常系、UAがiOS系($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isSmartPhone();
        $this->assertTrue($result, "iOS系の「{$ua}」はスマートフォンあつかい");
    }

    /** @dataProvider provider：Android系のユーザエージェント */
    public function test：「isSmartPhone」メソッドの正常系、UAがAndroid系($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isSmartPhone();
        $this->assertTrue($result, "Android系の「{$ua}」はスマートフォンあつかい");
    }

    /** @dataProvider provider：国内3キャリア携帯電話のユーザエージェント */
    public function test：「isSmartPhone」メソッドの正常系、UAが国内3キャリアの携帯電話($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isSmartPhone();
        $this->assertFalse($result, "国内3キャリアの携帯電話「{$ua}」はフィーチャフォンではない");
    }

    /** @dataProvider provider：PCブラウザのユーザエージェント */
    public function test：「isSmartPhone」メソッドの正常系、UAがPCブラウザ($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isSmartPhone();
        $this->assertFalse($result, "PCブラウザ「{$ua}」はスマートフォンではない");
    }

    /** @dataProvider provider：WindowsMobile系のユーザエージェント */
    public function test：「isSmartPhone」メソッドの正常系、UAがWindowsMobile系($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isSmartPhone();
        $this->assertFalse($result, "Windows Mobile系の「{$ua}」は非スマートフォンあつかい");
    }

    /** @dataProvider provider：BlackBerry系のユーザエージェント */
    public function test：「isSmartPhone」メソッドの正常系、UAがBlackBerry系($ua) {
        $_SERVER = array('HTTP_USER_AGENT' => $ua);

        $result = MobileUserAgent::isSmartPhone();
        $this->assertFalse($result, "Black Berry系の「{$ua}」は非スマートフォンあつかい");
    }

    public function test：「isDocomo」メソッドのBUGFIX、Yahoo！ケイタイのクローラ対応() {
        $_SERVER = array('HTTP_USER_AGENT' => 'Vodafone/1.0/V705SH (compatible; Y!J-SRD/1.0; http://help.yahoo.co.jp/help/jp/search/indexing/indexing-27.html)');

        $result = MobileUserAgent::isDocomo();
        $this->assertFalse($result);
    }

    public function test：「isEZweb」メソッドのBUGFIX、Yahoo！ケイタイのクローラ対応() {
        $_SERVER = array('HTTP_USER_AGENT' => 'Vodafone/1.0/V705SH (compatible; Y!J-SRD/1.0; http://help.yahoo.co.jp/help/jp/search/indexing/indexing-27.html)');

        $result = MobileUserAgent::isEZweb();
        $this->assertFalse($result);
    }

    public function test：「isSoftBank」メソッドのBUGFIX、Yahoo！ケイタイのクローラ対応() {
        $_SERVER = array('HTTP_USER_AGENT' => 'Vodafone/1.0/V705SH (compatible; Y!J-SRD/1.0; http://help.yahoo.co.jp/help/jp/search/indexing/indexing-27.html)');

        $result = MobileUserAgent::isSoftBank();
        $this->assertTrue($result);
    }

    public function test：「isXhtml」メソッドのBUGFIX、Yahoo！ケイタイのクローラ対応() {
        $_SERVER = array('HTTP_USER_AGENT' => 'Vodafone/1.0/V705SH (compatible; Y!J-SRD/1.0; http://help.yahoo.co.jp/help/jp/search/indexing/indexing-27.html)');

        $result = MobileUserAgent::isXhtml();
        $this->assertTrue($result);
    }

    public static function provider：PCブラウザのユーザエージェント() {
        $files = array(
                'win.ie.txt', 
                'win.chrome.txt', 
                'win.firefox.txt', 
                'win.safari.txt', 
                'win.opera.txt', 
                'mac.safari.txt', 
                'mac.chrome.txt', 
                'mac.firefox.txt', 
                'mac.opera.txt', 
            );
        $userAgents = self::loadTextFiles($files);

        $provider = array();
        foreach ($userAgents as $ua) {
            $provider[] = array($ua);
        }

        return $provider;
    }

    public static function provider：国内3キャリア携帯電話のユーザエージェント() {
        $files = array(
                'mova.html-1.0.txt', 
                'mova.html-2.0.txt', 
                'mova.html-3.0.txt', 
                'mova.html-4.0.txt', 
                'mova.html-5.0.txt', 
                'foma.html-4.0.txt', 
                'foma.html-5.0.txt', 
                'foma.html-6.0.txt', 
                'foma.html-7.0.txt', 
                'foma.html-7.1.txt', 
                'foma.html-7.2.txt', 
                'foma.imode-2.0.txt', 
                'ezweb.wap1.txt', 
                'ezweb.wap2.txt', 
                'softbank.type-c.txt', 
                'softbank.type-p.txt', 
                'softbank.type-w.txt', 
                'softbank.3gc.txt', 
            );
        $userAgents = self::loadTextFiles($files);

        $provider = array();
        foreach ($userAgents as $ua) {
            $provider[] = array($ua);
        }

        return $provider;
    }

    public static function provider：docomo「XHTMl非対応FOMA」のユーザエージェント() {
        $files = array(
                'foma.chtml.txt', 
            );
        $userAgents = self::loadTextFiles($files);

        $provider = array();
        foreach ($userAgents as $ua) {
            $provider[] = array($ua);
        }

        return $provider;
    }

    public static function provider：docomo「XHTML対応FOMA」のユーザエージェント() {
        $files = array(
                'foma.html-4.0.txt', 
                'foma.html-5.0.txt', 
                'foma.html-6.0.txt', 
                'foma.html-7.0.txt', 
                'foma.html-7.1.txt', 
                'foma.html-7.2.txt', 
                'foma.imode-2.0.txt', 
            );
        $userAgents = self::loadTextFiles($files);

        $provider = array();
        foreach ($userAgents as $ua) {
            $provider[] = array($ua);
        }

        return $provider;
    }

    public static function provider：docomo「MOVA」のユーザエージェント() {
        $files = array(
                'mova.html-1.0.txt', 
                'mova.html-2.0.txt', 
                'mova.html-3.0.txt', 
                'mova.html-4.0.txt', 
                'mova.html-5.0.txt', 
            );
        $userAgents = self::loadTextFiles($files);

        $provider = array();
        foreach ($userAgents as $ua) {
            $provider[] = array($ua);
        }

        return $provider;
    }

    public static function provider：au「XHTML非対応端末」のユーザエージェント() {
        $files = array(
                'ezweb.wap1.txt', 
            );
        $userAgents = self::loadTextFiles($files);

        $provider = array();
        foreach ($userAgents as $ua) {
            $provider[] = array($ua);
        }

        return $provider;
    }

    public static function provider：au「XHTML対応端末」のユーザエージェント() {
        $files = array(
                'ezweb.wap2.txt', 
            );
        $userAgents = self::loadTextFiles($files);

        $provider = array();
        foreach ($userAgents as $ua) {
            $provider[] = array($ua);
        }

        return $provider;
    }

    public static function provider：SoftBank「XHTML非対応端末」のユーザエージェント() {
        $files = array(
                'softbank.type-c.txt', 
                'softbank.type-p.txt', 
            );
        $userAgents = self::loadTextFiles($files);

        $provider = array();
        foreach ($userAgents as $ua) {
            $provider[] = array($ua);
        }

        return $provider;
    }

    public static function provider：SoftBank「XHTML対応端末」のユーザエージェント() {
        $files = array(
                'softbank.type-w.txt', 
                'softbank.3gc.txt', 
            );
        $userAgents = self::loadTextFiles($files);

        $provider = array();
        foreach ($userAgents as $ua) {
            $provider[] = array($ua);
        }

        return $provider;
    }

    public static function provider：iOS系のユーザエージェント() {
        $files = array(
                'ios.iphone.txt', 
                'ios.ipad.txt', 
                'ios.ipod.txt', 
            );
        $userAgents = self::loadTextFiles($files);

        $provider = array();
        foreach ($userAgents as $ua) {
            $provider[] = array($ua);
        }

        return $provider;
    }

    public static function provider：Android系のユーザエージェント() {
        $files = array(
                'android.txt', 
                'docomo.android.txt', 
            );
        $userAgents = self::loadTextFiles($files);

        $provider = array();
        foreach ($userAgents as $ua) {
            $provider[] = array($ua);
        }

        return $provider;
    }

    public static function provider：WindowsMobile系のユーザエージェント() {
        $files = array(
                'windowsmobile.txt', 
            );
        $userAgents = self::loadTextFiles($files);

        $provider = array();
        foreach ($userAgents as $ua) {
            $provider[] = array($ua);
        }

        return $provider;
    }

    public static function provider：BlackBerry系のユーザエージェント() {
        $files = array(
                'blackberry.txt', 
            );
        $userAgents = self::loadTextFiles($files);

        $provider = array();
        foreach ($userAgents as $ua) {
            $provider[] = array($ua);
        }

        return $provider;
    }

    private static function loadTextFiles(Array $files) {
        $dataDir = dirname(__FILE__).DIRECTORY_SEPARATOR.'data';

        $list = array();
        foreach ($files as $f) {
            $path = $dataDir.DIRECTORY_SEPARATOR.$f;
            $data = file($path, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
            $list = array_merge($list, $data);
        }

        return $list;
    }
}
