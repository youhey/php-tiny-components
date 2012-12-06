<?php

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'MobileTestCase.php';

require_once
    dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Mobile_Emoji.php';

/**
 * Test class for Mobile_Emoji.
 */
class MobileEmojiTest extends MobileTestCase
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
     * test Mobile_Emoji::create() in User Agent for docomo
     * 
     * @param  integer $id       Emoji ID
     * @param  string  $expected Creation Emoji string
     * @retunr void
     * @dataProvider providerEmojiID
     */
    public function testCreateEmoji_docomo($id, $expected)
    {
        $_SERVER = array('HTTP_USER_AGENT' => 'DoCoMo/2.0 P903i');
        $colors = self::_docomoEmojiColors();
        $color  = (isset($colors[$id]) ? $colors[$id] : null);
        if (!empty($color)) {
            $expected = sprintf('<span style="color:#%s;">%s</span>', 
                                $color, 
                                $expected);
        }
        $result = Mobile_Emoji::create($id);
        $this->assertSame($expected, $result, "Emoji ID is '{$id}'");
    }

    /**
     * test Mobile_Emoji::create() in User Agent for au
     * 
     * @param  integer $id       Emoji ID
     * @param  string  $expected Creation Emoji string
     * @retunr void
     * @dataProvider providerEmojiID
     */
    public function testCreateEmoji_EZweb($id, $expected)
    {
        $_SERVER = array('HTTP_USER_AGENT' => 'KDDI-CA39 UP.Browser/6.2.0.13.1.5 (GUI) MMP/2.0');
        $result  = Mobile_Emoji::create($id);
        $this->assertSame($expected, $result, "Emoji ID is '{$id}'");
    }

    /**
     * test Mobile_Emoji::create() in User Agent for SoftBank
     * 
     * @param  integer $id       Emoji ID
     * @param  string  $expected Creation Emoji string
     * @retunr void
     * @dataProvider providerEmojiID
     */
    public function testCreateEmoji_SoftBank($id, $expected)
    {
        $_SERVER = array('HTTP_USER_AGENT' => 'SoftBank/1.0/930SH/SHJ001 Browser/NetFront/3.4 Profile/MIDP-2.0 Configuration/CLDC-1.1');
        $result  = Mobile_Emoji::create($id);
        $this->assertSame($expected, $result, "Emoji ID is '{$id}'");
    }

    /**
     * test Mobile_Emoji::toBinary() in User Agent for docomo
     * 
     * @param  integer $id       Emoji ID
     * @param  string  $keyword  Emoji keyword
     * @param  string  $expected Emoji binary
     * @retunr void
     * @dataProvider providerDocomoEmoji
     */
    public function testToBinary_docomo($id, $keyword, $expected)
    {
        $_SERVER = array('HTTP_USER_AGENT' => 'DoCoMo/2.0 P903i');
        $result  = Mobile_Emoji::toBinary($keyword);
        $this->assertSame($expected, $result, "Emoji ID is '{$id}'");
    }

    /**
     * test Mobile_Emoji::toBinary() in User Agent for au
     * 
     * @param  integer $id       Emoji ID
     * @param  string  $keyword  Emoji keyword
     * @param  string  $expected Emoji binary
     * @retunr void
     * @dataProvider providerEZwebEmoji
     */
    public function testToBinary_EZweb($id, $keyword, $expected)
    {
        $_SERVER = array('HTTP_USER_AGENT' => 'KDDI-CA39 UP.Browser/6.2.0.13.1.5 (GUI) MMP/2.0');
        $result  = Mobile_Emoji::toBinary($keyword);
        $this->assertSame($expected, $result, "Emoji ID is '{$id}'");
    }

    /**
     * test Mobile_Emoji::toBinary() in User Agent for SoftBank
     * 
     * @param  integer $id       Emoji ID
     * @param  string  $keyword  Emoji keyword
     * @param  string  $expected Emoji binary
     * @retunr void
     * @dataProvider providerSoftBankEmoji
     */
    public function testToBinary_SoftBank($id, $keyword, $expected)
    {
        $_SERVER = array('HTTP_USER_AGENT' => 'SoftBank/1.0/930SH/SHJ001 Browser/NetFront/3.4 Profile/MIDP-2.0 Configuration/CLDC-1.1');
        $result  = Mobile_Emoji::toBinary($keyword);
        $this->assertSame($expected, $result, "Emoji ID is '{$id}'");
    }

    /**
     * auとSoftBankの複合絵文字（2文字以上の組み合わせがある絵文字）をテスト
     * 
     * - Mobile_Emoji::toBinary()
     * 
     * @retunr void
     */
    public function testToBinary_MultiplePictogram()
    {
        // EZweb（1023）：{笑顔}+{汗}
        $_SERVER  = array('HTTP_USER_AGENT' => 'KDDI-CA39 UP.Browser/6.2.0.13.1.5 (GUI) MMP/2.0');
        $result   = Mobile_Emoji::toBinary('%emoji_1023%');
        $expected = pack('H*', 'F649').pack('H*', 'F7CE');
        $this->assertSame($expected, $result, "Emoji ID is '1023' in au");

        // SofBank（1012）：{メール}+{ハート}
        $_SERVER = array('HTTP_USER_AGENT' => 'SoftBank/1.0/930SH/SHJ001 Browser/NetFront/3.4 Profile/MIDP-2.0 Configuration/CLDC-1.1');
        $result   = Mobile_Emoji::toBinary('%emoji_1012%');
        $expected = pack('H*', 'F743').pack('H*', 'F9C8');
        $this->assertSame($expected, $result, "Emoji ID is '1023' in au");

        // SofBank（1023）：{笑顔}+{汗}
        $_SERVER = array('HTTP_USER_AGENT' => 'SoftBank/1.0/930SH/SHJ001 Browser/NetFront/3.4 Profile/MIDP-2.0 Configuration/CLDC-1.1');
        $result   = Mobile_Emoji::toBinary('%emoji_1023%');
        $expected = pack('H*', 'FB55').pack('H*', 'F9D1');
        $this->assertSame($expected, $result, "Emoji ID is '1023' in au");
    }

    /**
     * docomo基準の絵文字ID
     * 
     * @retunr array
     */
    public static function providerEmojiID()
    {
        $provider = array();
        for ($i = 1; $i <= 176; ++$i) {
            $provider[] = array($i, "%emoji_{$i}%");
        }
        for ($i = 1001; $i <= 1076; ++$i) {
            $provider[] = array($i, "%emoji_{$i}%");
        }

        return $provider;
    }

    public function providerDocomoEmoji()
    {
        $provider = array();
        $emoji    = self::_docomoSjisEmoji();
        for ($i = 1; $i <= 176; ++$i) {
            $binary     = pack('H*', $emoji[$i]);
            $provider[] = array($i, "%emoji_{$i}%", $binary);
        }
        for ($i = 1001; $i <= 1076; ++$i) {
            $binary     = pack('H*', $emoji[$i]);
            $provider[] = array($i, "%emoji_{$i}%", $binary);
        }
        for ($i = 177; $i < 1001; ++$i) {
            $provider[] = array($i, "%emoji_{$i}%", '');
        }
        for ($i = 1077; $i < 2000; ++$i) {
            $provider[] = array($i, "%emoji_{$i}%", '');
        }

        return $provider;
    }

    public static function providerEZwebEmoji()
    {
        $provider = array();
        $emoji    = self::_ezwebSjisEmoji();
        for ($i = 1; $i <= 176; ++$i) {
            // docomoと互換性のない絵文字は変換しない（to Empty）
            $binary = '';
            if (!empty($emoji[$i])) {
                $binary = pack('H*', $emoji[$i]);
            }
            $provider[] = array($i, "%emoji_{$i}%", $binary);
        }
        for ($i = 1001; $i <= 1076; ++$i) {
            // docomoと互換性のない絵文字は変換しない（to Empty）
            $binary = '';
            if (!empty($emoji[$i])) {
                $binary = pack('H*', $emoji[$i]);
            }
            $provider[] = array($i, "%emoji_{$i}%", $binary);
        }
        for ($i = 177; $i < 1001; ++$i) {
            $provider[] = array($i, "%emoji_{$i}%", '');
        }
        for ($i = 1077; $i < 2000; ++$i) {
            $provider[] = array($i, "%emoji_{$i}%", '');
        }

        return $provider;
    }

    public static function providerSoftBankEmoji()
    {
        $provider = array();
        $emoji    = self::_softBankSjisEmoji();
        for ($i = 1; $i <= 176; ++$i) {
            // docomoと互換性のない絵文字は変換しない（to Empty）
            $binary = '';
            if (!empty($emoji[$i])) {
                $binary = pack('H*', $emoji[$i]);
            }
            $provider[] = array($i, "%emoji_{$i}%", $binary);
        }
        for ($i = 1001; $i <= 1076; ++$i) {
            // docomoと互換性のない絵文字は変換しない（to Empty）
            $binary = '';
            if (!empty($emoji[$i])) {
                $binary = pack('H*', $emoji[$i]);
            }
            $provider[] = array($i, "%emoji_{$i}%", $binary);
        }
        for ($i = 177; $i < 1001; ++$i) {
            $provider[] = array($i, "%emoji_{$i}%", '');
        }
        for ($i = 1077; $i < 2000; ++$i) {
            $provider[] = array($i, "%emoji_{$i}%", '');
        }

        return $provider;
    }

    private static function _docomoEmojiColors()
    {
        return 
            self::_importPhpArray('docomo_emoji_colors.php');
    }

    private static function _docomoSjisEmoji()
    {
        return 
            self::_importPhpArray('docomo_sjis_emoji.php');
    }

    private static function _ezwebSjisEmoji()
    {
        return 
            self::_importPhpArray('ezweb_sjis_emoji.php');
    }

    private static function _softBankSjisEmoji()
    {
        return 
            self::_importPhpArray('softbank_sjis_emoji.php');
    }
}
