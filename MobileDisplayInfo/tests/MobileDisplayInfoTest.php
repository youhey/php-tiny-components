<?php
/**
 * フィーチャフォンの端末画面サイズを取得するモジュール
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

/** MobileDisplayInfo */
require_once dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'MobileDisplayInfo.php';

/**
 * テストケース - MobileDisplayInfo
 * 
 * @author IKEDA youhei <youhey.ikeda@gmail.com>
 */
class MobileDisplayInfoTest extends PHPUnit_Framework_TestCase
{

    private $cacheServerEnv = array();

    public function setUp() {
        if (isset($_SERVER)) {
            $this->cacheServerEnv = $_SERVER;
        }
        $_SERVER = array();
    }
    public function tearDown() {
        $_SERVER = $this->cacheServerEnv;
    }

    public function test：isWidthLessOrEqualToメソッド、端末の画面横幅が「引数」より小さい() {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'KDDI-KC4B UP.Browser/6.2.0.17.2.1.3 (GUI) MMP/2.0',
                'HTTP_X_UP_DEVCAP_SCREENPIXELS' => '480,854'
            );

        $result = MobileDisplayInfo::isWidthLessOrEqualTo(600);
        $this->assertTrue($result);
   }

    public function test：isWidthLessOrEqualToメソッド、端末の画面横幅が「引数」と等しい() {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'KDDI-KC4B UP.Browser/6.2.0.17.2.1.3 (GUI) MMP/2.0',
                'HTTP_X_UP_DEVCAP_SCREENPIXELS' => '480,854'
            );

        $result = MobileDisplayInfo::isWidthLessOrEqualTo(480);
        $this->assertTrue($result);
   }

    public function test：isWidthLessOrEqualToメソッド、端末の画面横幅が「引数」より大きい() {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'KDDI-KC4B UP.Browser/6.2.0.17.2.1.3 (GUI) MMP/2.0',
                'HTTP_X_UP_DEVCAP_SCREENPIXELS' => '480,854'
            );

        $result = MobileDisplayInfo::isWidthLessOrEqualTo(240);
        $this->assertFalse($result);
   }

    public function test：isWidthLessOrEqualToメソッド、端末の画面横幅が不明であれば常にFALSE() {
        $_SERVER = array(
                // IE6
                'HTTP_USER_AGENT' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; GTB6.6; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)',
            );

        // フィーチャフォン以外は、横幅が「NULL」で計算できないため常にFALSE
        foreach (array('最小値' => 1, '最大値' => PHP_INT_MAX) as $testNumber) {
            $result = MobileDisplayInfo::isWidthLessOrEqualTo($testNumber);
            $this->assertFalse($result);
        }
   }

    public function test：docomo端末（P902i）の画面横サイズ() {
        $_SERVER = array('HTTP_USER_AGENT' => 'DoCoMo/2.0 P902i(c100;TB;W24H12)');

        $result   = MobileDisplayInfo::width();
        $expected = 240;
        $this->assertEquals($expected, $result, "「docomo P902i」の画面横幅");
   }

    public function test：docomo端末（P902i）の画面縦サイズ() {
        $_SERVER = array('HTTP_USER_AGENT' => 'DoCoMo/2.0 P902i(c100;TB;W24H12)');

        $result   = MobileDisplayInfo::height();
        $expected = 270;
        $this->assertEquals($expected, $result, "「docomo P902i」の画面高さ");
   }

    /** @dataProvider provider：2009年度下期から2011年度下期までのdocomo端末 */
    public function test：2009年度下期から2011年度下期までのdocomo端末の画面横サイズ($deviceIndex, $deviceInfo) {
        $_SERVER = array('HTTP_USER_AGENT' => $deviceInfo['user-agent']);

        $result   = MobileDisplayInfo::width();
        $expected = $deviceInfo['width'];
        $this->assertEquals($expected, $result, "「{$deviceIndex}」の画面横幅");
   }

    /** @dataProvider provider：2009年度下期から2011年度下期までのdocomo端末 */
    public function test：2009年度下期から2011年度下期までのdocomo端末の画面縦サイズ($deviceIndex, $deviceInfo) {
        $_SERVER = array('HTTP_USER_AGENT' => $deviceInfo['user-agent']);

        $result   = MobileDisplayInfo::height();
        $expected = $deviceInfo['height'];
        $this->assertEquals($expected, $result, "「{$deviceIndex}」の画面高さ");
   }

    /** @dataProvider provider：画面サイズのパターン */
    public function test：au端末の画面横サイズ($width, $height) {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'KDDI-KC4B UP.Browser/6.2.0.17.2.1.3 (GUI) MMP/2.0',
                'HTTP_X_UP_DEVCAP_SCREENPIXELS' => "{$width},{$height}"
            );

        $result   = MobileDisplayInfo::width();
        $expected = $width;
        $this->assertEquals($expected, $result, "au端末「{$width}x{$height}」の画面横幅を取得");
   }

    /** @dataProvider provider：画面サイズのパターン */
    public function test：au端末の画面縦サイズ($width, $height) {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'KDDI-KC4B UP.Browser/6.2.0.17.2.1.3 (GUI) MMP/2.0',
                'HTTP_X_UP_DEVCAP_SCREENPIXELS' => "{$width},{$height}"
            );

        $result   = MobileDisplayInfo::height();
        $expected = $height;
        $this->assertEquals($expected, $result, "au端末「{$width}x{$height}」の画面高さを取得");
   }

    /** @dataProvider provider：画面サイズのパターン */
    public function test：SoftBank端末の画面横サイズ($width, $height) {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'SoftBank/1.0/105SH/SHJ001 Browser/NetFront/3.5 Profile/MIDP-2.0 Configuration/CLDC-1.1',
                'HTTP_X_JPHONE_DISPLAY' => "{$width}*{$height}"
            );

        $result   = MobileDisplayInfo::width();
        $expected = $width;
        $this->assertEquals($expected, $result, "SoftBank端末「{$width}x{$height}」の画面横幅を取得");
   }

    /** @dataProvider provider：画面サイズのパターン */
    public function test：SoftBank端末の画面縦サイズ($width, $height) {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'SoftBank/1.0/105SH/SHJ001 Browser/NetFront/3.5 Profile/MIDP-2.0 Configuration/CLDC-1.1',
                'HTTP_X_JPHONE_DISPLAY' => "{$width}*{$height}"
            );

        $result   = MobileDisplayInfo::height();
        $expected = $height;
        $this->assertEquals($expected, $result, "SoftBank端末「{$width}x{$height}」の画面高さを取得");
   }

    public function test：未知ののdocomo端末の画面横サイズ() {
        $_SERVER = array('HTTP_USER_AGENT' => 'DoCoMo/2.0 XX042X(c500;TB;W24H16)');

        $result   = MobileDisplayInfo::width();
        $expected = 480;
        $this->assertEquals($expected, $result, "「未知のdocomo端末」の画面横幅はフルワイドVGA");
   }

    public function test：未知ののdocomo端末の画面縦サイズ() {
        $_SERVER = array('HTTP_USER_AGENT' => 'DoCoMo/2.0 XX042X(c500;TB;W24H16)');

        $result   = MobileDisplayInfo::height();
        $expected = 854;
        $this->assertEquals($expected, $result, "「未知のdocomo端末」の画面高さはフルワイドVGA");
   }

    public function test：サイズ不明なau端末の画面横サイズ() {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'KDDI-KC4B UP.Browser/6.2.0.17.2.1.3 (GUI) MMP/2.0',
            );

        $result   = MobileDisplayInfo::width();
        $expected = 480;
        $this->assertEquals($expected, $result, "「サイズ不明なau端末」の画面横幅はフルワイドVGA");
   }

    public function test：サイズ不明なau端末の画面縦サイズ() {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'KDDI-KC4B UP.Browser/6.2.0.17.2.1.3 (GUI) MMP/2.0',
            );

        $result   = MobileDisplayInfo::height();
        $expected = 854;
        $this->assertEquals($expected, $result, "「サイズ不明なau端末」の画面高さはフルワイドVGA");
   }

    public function test：サイズ不明なSoftBank端末の画面横サイズ() {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'SoftBank/1.0/105SH/SHJ001 Browser/NetFront/3.5 Profile/MIDP-2.0 Configuration/CLDC-1.1',
            );

        $result   = MobileDisplayInfo::width();
        $expected = 480;
        $this->assertEquals($expected, $result, "「サイズ不明なSoftBank端末」の画面横幅はフルワイドVGA");
   }

    public function test：サイズ不明なSoftBank端末の画面縦サイズ() {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'SoftBank/1.0/105SH/SHJ001 Browser/NetFront/3.5 Profile/MIDP-2.0 Configuration/CLDC-1.1',
            );

        $result   = MobileDisplayInfo::height();
        $expected = 854;
        $this->assertEquals($expected, $result, "「サイズ不明なSoftBank端末」の画面高さはフルワイドVGA");
   }

    public function test：IE9の画面横サイズ() {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C)',
            );

        $result = MobileDisplayInfo::width();
        $this->assertNull($result, "「IE9」の画面高さはNULL");
   }

    public function test：IE9の画面縦サイズ() {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C)',
            );

        $result = MobileDisplayInfo::height();
        $this->assertNull($result, "「IE9」の画面高さはNULL");
   }

    public function test：Chromeの画面横サイズ() {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.187 Safari/535.1NEW',
            );

        $result = MobileDisplayInfo::width();
        $this->assertNull($result, "「Chrome」の画面高さはNULL");
   }

    public function test：Chromeの画面縦サイズ() {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.187 Safari/535.1NEW',
            );

        $result = MobileDisplayInfo::height();
        $this->assertNull($result, "「Chrome」の画面高さはNULL");
   }

    public function test：iPhoneの画面横サイズ() {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 5_0 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A334 Safari/7534.48.3',
            );

        $result = MobileDisplayInfo::width();
        $this->assertNull($result, "「iPhone」の画面高さはNULL");
   }

    public function test：iPhoneの画面縦サイズ() {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 5_0 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A334 Safari/7534.48.3',
            );

        $result = MobileDisplayInfo::height();
        $this->assertNull($result, "「iPhone」の画面高さはNULL");
   }

    public function test：Nexusの画面横サイズ() {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'Mozilla/5.0 (Linux; U; Android 2.3.4; ja-jp; Nexus S Build/GRJ22) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
            );

        $result = MobileDisplayInfo::width();
        $this->assertNull($result, "「Nexus」の画面高さはNULL");
   }

    public function test：Nexusの画面縦サイズ() {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'Mozilla/5.0 (Linux; U; Android 2.3.4; ja-jp; Nexus S Build/GRJ22) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
            );

        $result = MobileDisplayInfo::height();
        $this->assertNull($result, "「Nexus」の画面高さはNULL");
   }

    public static function provider：画面サイズのパターン() {
        $displays = array(
                array(230, 240),
                array(230, 320),
                array(240, 240),
                array(240, 252),
                array(240, 256),
                array(240, 266),
                array(240, 267),
                array(240, 268),
                array(240, 269),
                array(240, 270),
                array(240, 280),
                array(240, 282),
                array(240, 295),
                array(240, 298),
                array(240, 320),
                array(240, 350),
                array(240, 352),
                array(240, 364),
                array(240, 368),
                array(240, 400),
                array(240, 427),
                array(256, 240),
                array(480, 592),
                array(480, 640),
                array(480, 648),
                array(480, 662),
                array(480, 800),
                array(480, 854),
                array(480, 960),
                array(600, 1024),
                array(800, 600),
            );

        return $displays;
    }

    public static function provider：2009年度下期から2011年度下期までのdocomo端末() {
        $devices = array(
                'F-02B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 F02B(c500;TB;W24H16)'
                    ),
                'F-03B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 F03B(c500;TB;W24H16)'
                    ),
                'L-01B' => array(
                        'width'      => 480,
                        'height'     => 800,
                        'user-agent' => 'DoCoMo/2.0 L01B(c500;TB;W24H16) '
                    ),
                'L-02B' => array(
                        'width'      => 480,
                        'height'     => 800,
                        'user-agent' => 'DoCoMo/2.0 L02B(c100;TB;W24H16)'
                    ),
                'F-01B' => array(
                        'width'      => 480,
                        'height'     => 960,
                        'user-agent' => 'DoCoMo/2.0 F01B(c500;TB;W24H16)'
                    ),
                'F-04B' => array(
                        'width'      => 480,
                        'height'     => 960,
                        'user-agent' => 'DoCoMo/2.0 F04B(c500;TB;W24H16)'
                    ),
                'L-03B' => array(
                        'width'      => 240,
                        'height'     => 400,
                        'user-agent' => 'DoCoMo/2.0 L03B(c100;TB;W24H16)'
                    ),
                'N-02B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 N02B(c500;TB;W24H16)'
                    ),
                'N-03B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 N03B(c500;TB;W24H16)'
                    ),
                'P-02B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 P02B(c500;TB;W24H16)'
                    ),
                'N-01B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 N01B(c500;TB;W24H16)'
                    ),
                'P-01B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 P01B(c500;TB;W24H16)'
                    ),
                'SH-01B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH01B(c500;TB;W24H16)'
                    ),
                'SH-04B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH04B(c500;TB;W24H14)'
                    ),
                'P-03B' => array(
                        'width'      => 240,
                        'height'     => 427,
                        'user-agent' => 'DoCoMo/2.0 P03B(c500;TB;W24H16)'
                    ),
                'SH-02B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH02B(c500;TB;W24H16)'
                    ),
                'SH-03B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH03B(c500;TB;W30H18)'
                    ),
                'SH-05B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH05B(c500;TB;W24H16)'
                    ),
                'SH-06B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH06B(c500;TB;W20H13) '
                    ),
                'F-08B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 F08B(c500;TB;W24H16)'
                    ),
                'F-06B' => array(
                        'width'      => 480,
                        'height'     => 960,
                        'user-agent' => 'DoCoMo/2.0 F06B(c500;TB;W24H16) '
                    ),
                'F-09B' => array(
                        'width'      => 480,
                        'height'     => 800,
                        'user-agent' => 'DoCoMo/2.0 F09B(c100;TB;W20H09)'
                    ),
                'F-10B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 F10B(c500;TB;W24H16)'
                    ),
                'F-07B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 F07B(c500;TB;W24H16)'
                    ),
                'L-04B' => array(
                        'width'      => 240,
                        'height'     => 320,
                        'user-agent' => 'DoCoMo/2.0 L04B(c100;TB;W24H12)'
                    ),
                'N-04B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 N04B(c500;TB;W24H16)'
                    ),
                'N-06B' => array(
                        'width'      => 240,
                        'height'     => 427,
                        'user-agent' => 'DoCoMo/2.0 N06B(c100;TB;W24H16)'
                    ),
                'N-07B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 N07B(c500;TB;W24H16)'
                    ),
                'N-05B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 N05B(c500;TB;W24H16)'
                    ),
                'N-08B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 N08B(c500;TB;W30H15)'
                    ),
                'P-04B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 P04B(c500;TB;W24H16)'
                    ),
                'P-05B' => array(
                        'width'      => 240,
                        'height'     => 427,
                        'user-agent' => 'DoCoMo/2.0 P05B(c500;TB;W24H16)'
                    ),
                'P-06B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 P06B(c500;TB;W24H16)'
                    ),
                'P-07B' => array(
                        'width'      => 240,
                        'height'     => 427,
                        'user-agent' => 'DoCoMo/2.0 P07B(c500;TB;W24H16)'
                    ),
                'SH-07B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH07B(c500;TB;W24H16)'
                    ),
                'SH-08B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH08B(c500;TB;W24H16)'
                    ),
                'SH-09B' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH09B(c500;TB;W24H16)'
                    ),
                'L-01C' => array(
                        'width'      => 240,
                        'height'     => 400,
                        'user-agent' => 'DoCoMo/2.0 L01C(c100;TB;W16H11)'
                    ),
                'F-01C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 F01C(c500;TB;W24H16)'
                    ),
                'F-02C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 F02C(c500;TB;W24H16)'
                    ),
                'F-05C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 F05C(c500;TB;W24H16)'
                    ),
                'F-03C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 F03C(c500;TB;W24H16)'
                    ),
                'F-04C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 F04C(c500;TB;W24H16)'
                    ),
                'L-03C' => array(
                        'width'      => 480,
                        'height'     => 800,
                        'user-agent' => 'DoCoMo/2.0 L03C(c500;TB;W24H16)'
                    ),
                'N-02C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 N02C(c500;TB;W24H16)'
                    ),
                'P-01C' => array(
                        'width'      => 240,
                        'height'     => 427,
                        'user-agent' => 'DoCoMo/2.0 P01C(c500;TB;W24H16)'
                    ),
                'N-03C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 N03C(c500;TB;W24H16)'
                    ),
                'P-02C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 P02C(c500;TB;W24H16)'
                    ),
                'N-01C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 N01C(c500;TB;W24H16)'
                    ),
                'P-03C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 P03C(c500;TB;W24H16)'
                    ),
                'SH-01C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH01C(c500;TB;W24H16)'
                    ),
                'SH-02C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH02C(c500;TB;W24H16)'
                    ),
                'SH-04C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH04C(c500;TB;W24H16)'
                    ),
                'SH-05C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH05C(c500;TB;W30H18)'
                    ),
                'SH-06C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH06C(c500;TB;W30H18)'
                    ),
                'SH-08C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH08C(c500;TB;W24H14)'
                    ),
                'SH-09C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH09C(c500;TB;W24H16)'
                    ),
                'F-09C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 F09C(c500;TB;W24H16)'
                    ),
                'F-10C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 F10C(c500;TB;W24H16)'
                    ),
                'CA-01C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 CA01C(c500;TB;W24H16)'
                    ),
                'F-11C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 F11C(c500;TB;W24H16)'
                    ),
                'F-07C' => array(
                        'width'      => 600,
                        'height'     => 1024,
                        'user-agent' => 'DoCoMo/2.0 F07C(c500;TB;W24H15)'
                    ),
                'F-08C' => array(
                        'width'      => 240,
                        'height'     => 400,
                        'user-agent' => 'DoCoMo/2.0 F08C(c100;TB;W20H09)'
                    ),
                'P-04C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 P04C(c500;TB;W24H16)'
                    ),
                'L-10C' => array(
                        'width'      => 240,
                        'height'     => 400,
                        'user-agent' => 'DoCoMo/2.0 L10C(c100;TB;W24H16)'
                    ),
                'SH-10C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH10C(c500;TB;W24H16)'
                    ),
                'SH-11C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH11C(c500;TB;W24H16)'
                    ),
                'N-05C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 N05C(c500;TB;W24H16)'
                    ),
                'P-05C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 P05C(c500;TB;W24H16)'
                    ),
                'P-06C' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 P06C(c500;TB;W24H16)'
                    ),
                'N-02D' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 N02D(c500;TB;W24H16)'
                    ),
                'F-06D' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 F06D(c500;TB;W24H16)'
                    ),
                'N-03D' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 N03D(c500;TB;W24H16)'
                    ),
                'P-03D' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 P03D(c500;TB;W24H16)'
                    ),
                'F-02D' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 F02D(c500;TB;W24H16)'
                    ),
                'F-04D' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 F04D(c500;TB;W24H16)'
                    ),
                'SH-03D' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH03D(c500;TB;W24H16)'
                    ),
                'SH-05D' => array(
                        'width'      => 480,
                        'height'     => 854,
                        'user-agent' => 'DoCoMo/2.0 SH05D(c500;TB;W24H16)'
                    ),
            );

        $provider = array();
        foreach ($devices as $deviceIndex => $deviceInfo) {
            $provider[] = array($deviceIndex, $deviceInfo);
        }

        return $provider;
    }
}
