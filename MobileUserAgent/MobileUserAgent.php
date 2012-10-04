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

/** MobileUserAgentException */
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'MobileUserAgentException.php';

/** Net_UserAgent_Mobile */
require_once 'Net/UserAgent/Mobile.php';

/**
 * Net_UserAgent_Mobileのラッパー
 * 
 * <p>モバイルサイト対応のための携帯電話端末の判別モジュールです。<br />
 * ユーザエージェントで、フィーチャフォンとスマートフォンを判別します。<br />
 * また、日本国内のフィーチャフォン3キャリアを判別します。<br />
 * フィーチャフォン端末については、XHTMLへの対応可否も判別できます。</p>
 * <p>端末情報の判別ロジックは、Net_UserAgent_Mobileに依存しています。<br />
 * 同モジュールをラッピングして、必要最小限の機能だけを提供します。</p>
 * <p>独自機能として、簡易なスマートフォンの判別機能を実装しています。<br />
 * ユーザエージェントをもとに、iOS系とAndroid系の端末を判別します。</p>
 * 
 * @author IKEDA Youhei <youhey.ikeda@gmail.com>
 */
class MobileUserAgent
{

    /** Yahoo!ケータイのクローラ対策 */
    const 
        YAHOO_SEARCH_CRAWLER = 'Vodafone/1.0/V705SH (compatible; Y!J-SRD/1.0; http://help.yahoo.co.jp/help/jp/search/indexing/indexing-27.html)',
        USER_AGENT_V705SH    = 'Vodafone/1.0/V705SH/SHJ001 Browser/VF-NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1';

    /**
     * XHTMLに対応しているdocomoのHTMLバージョン
     * 
     * @var array
     */
    private static $docomoXHTMLVersions = array(
            '4.0',
            '5.0',
            '6.0',
            '7.0',
            '7.1',
            '7.2',
        );

    /**
     * XHTML未対応のdocomoのFOMA機種
     * 
     * <ul>
     * <li>FOMA 2101V系</li>
     * <li>FOMA 2001系</li>
     * <li>FOMA 2002系</li>
     * </ul>
     * 
     * @var array
     */
    private static $docomoChtmlFoma = array(
            /* 2101V系 */ 'D2101V', 'P2101V', 'SH2101V', 'T2101V',
            /* 2001系 */  'N2001',
            /* 2002系 */  'N2002', 'P2002',
        );

    /**
     * スマートフォンとみなすユーザエージェント
     * 
     * <p>BlackBerryやWindowsMobileなどはスマートフォン外と定義</p>
     * <p>厳密な判定は避け、簡易な正規表現で判定する。</p>
     * <ul>
     * <li>Apple iPhone / Apple iPad / Other iPhone browser</li>
     * <li>Apple iPod touch</li>
     * <li>1.5+ Android / Pre 1.5 Android</li>
     * </ul>
     * <p>BlackBerryやHPのwebOS（Palm）への対応はユーザエージェントを追加する。</p>
     * <code>
     * 'blackberry' => array('blackberry'),
     * 'webOS'      => array('webos'),
     * </code>
     * 
     * @var array
     */
    private static $SmartPhoneUserAgentGroups  = array(
            'iphone'  => array('iphone', 'incognito', 'webmate', 'ipad'), 
            'ipod'    => array('ipod'), 
            'android' => array('android', 'dream', 'cupcake'), 
        );

    /**
     * ユーザエージェントが日本国内3キャリアのフィーチャーフォンかをチェック
     * 
     * @return boolean 日本国内3キャリアのフィーチャーフォンであればTURE
     * @throws MobileUserAgentException ユーザエージェントのデータ操作に失敗したとき
     */
    public static function isFeaturePhone() {
        $isDocomo       = self::isDocomo();
        $isEZweb        = self::isEZweb();
        $isSoftBank     = self::isSoftBank();
        $isFeaturePhone = ($isDocomo || $isEZweb || $isSoftBank);

        return $isFeaturePhone;
    }

    /**
     * ユーザエージェントがスマートフォンっぽいかをチェック
     * 
     * @return boolean ユーザエージェントがスマートフォンっぽければTRUE
     */
    public static function isSmartPhone() {
        $ua    = self::getLowerUserAgent();
        $match = false;
        foreach (self::$SmartPhoneUserAgentGroups as $group) {
            foreach ($group as $keyword) {
                if (strpos($ua, $keyword) !== false) {
                    $match = true;
                    break;
                }
            }
        }

        return $match;
    }

    /**
     * ユーザエージェントがdocomoかをチェック
     * 
     * @return boolean ユーザエージェントがdocomoであればTURE
     * @throws MobileUserAgentException ユーザエージェントのデータ操作に失敗したとき
     */
    public static function isDocomo() {
        $isDocomo = self::getUserAgentMobile()->isDoCoMo();

        return $isDocomo;
    }

    /**
     * ユーザエージェントがEZwebかをチェック
     * 
     * @return boolean ユーザエージェントがEZwebであればTURE
     * @throws MobileUserAgentException ユーザエージェントのデータ操作に失敗したとき
     */
    public static function isEZweb() {
        $isEZweb = self::getUserAgentMobile()->isEZweb();

        return $isEZweb;
    }

    /**
     * ユーザエージェントがSoftBankかをチェック
     * 
     * @return boolean ユーザエージェントがSoftBankであればTURE
     * @throws MobileUserAgentException ユーザエージェントのデータ操作に失敗したとき
     */
    public static function isSoftBank() {
        $isSoftBank = self::getUserAgentMobile()->isSoftbank();

        return $isSoftBank;
    }

    /**
     * ユーザエージェントの機種がXHTMLに対応しているかをチェック
     * 
     * @return boolean XHTMLに対応した機種であればTRUE
     */
    public static function isXhtml() {
        $isXhtmlDocomo   = self::isXhtmlDocomo();
        $isXhtmlEZweb    = self::isXhtmlEZweb();
        $isXhtmlSoftBank = self::isXhtmlSoftBank();
        $isXhtml         = ($isXhtmlDocomo || $isXhtmlEZweb || $isXhtmlSoftBank);

        return $isXhtml;
    }

    /**
     * ユーザエージェントがdocomoのXHTML対応端末かをチェック
     * 
     * @return boolean XHTMLに対応した機種であればTRUE
     * @throws MobileUserAgentException ユーザエージェントのデータ操作に失敗したとき
     */
    private static function isXhtmlDocomo() {
        $isDocomo = false;
        $isFoma   = false;
        $model    = null;
        $version  = null;

        $agent = self::getUserAgentMobile();
        if ($agent->isDoCoMo()) {
            $isDocomo = true;
            $isFoma   = $agent->isFoma();
            $model    = $agent->getModel();
            $version  = self::getDocomoHtmlVersion();
        }
        $agent = null;

        $inChtml  = in_array($model, self::$docomoChtmlFoma, true);
        $inXhtml  = in_array($version, self::$docomoXHTMLVersions, true);
        $isXhtml  = ($isDocomo && $isFoma && !$inChtml && $inXhtml);

        return $isXhtml;
    }

    /**
     * ユーザエージェントがEZwebのXHTML対応端末かをチェック
     * 
     * @return boolean XHTMLに対応した機種であればTRUE
     * @throws MobileUserAgentException ユーザエージェントのデータ操作に失敗したとき
     */
    private static function isXhtmlEZweb() {
        $isEZweb = false;
        $isWAP2  = false;

        $agent = self::getUserAgentMobile();
        if ($agent->isEZweb()) {
            $isEZweb = true;
            $isWAP2  = $agent->isWAP2();
        }
        $agent = null;

        $isXhtml = ($isEZweb && $isWAP2);

        return $isXhtml;
    }

    /**
     * ユーザエージェントがSoftBankのXHTML対応端末かをチェック
     * 
     * @return boolean XHTMLに対応した機種であればTRUE
     * @throws MobileUserAgentException ユーザエージェントのデータ操作に失敗したとき
     */
    private static function isXhtmlSoftBank() {
        $isSoftBank = false;
        $isTypeW    = false;
        $isType3GC  = false;

        $agent = self::getUserAgentMobile();
        if ($agent->isSoftBank()) {
            $isSoftBank = true;
            $isTypeW    = $agent->isTypeW();
            $isType3GC  = $agent->isType3GC();
        }
        $agent = null;

        $isXhtml = ($isSoftBank && ($isTypeW || $isType3GC));

        return $isXhtml;
    }

    /**
     * iモードのHTMLバージョンを返却
     * 
     * <p>マッピングが未定義なのはライブラリが未対応の機種<br />
     * ≒新しい機種なので、最新バージョンとして処理する</p>
     * 
     * @return string iモードのHTMLバージョン
     * @throws MobileUserAgentException ユーザエージェントのデータ操作に失敗したとき
     * @throws MobileUserAgentException ユーザエージェントがdocomoでないとき
     */
    private static function getDocomoHtmlVersion() {
        $userAgentMobile = self::getUserAgentMobile();
        if (!$userAgentMobile->isDoCoMo()) {
            $message = 'UserAgent is not docomo';
            throw new MobileUserAgentException($message);
        }

        $version = $userAgentMobile->getHTMLVersion();
        if (empty($version)) {
            $version = '7.2';
        }

        return $version;
    }

    /**
     * Net_UserAgent_Mobileのインスタンスを返却
     * 
     * @return Net_UserAgent_Mobile_Common
     * @throws MobileUserAgentException インスタンスの生成に失敗したとき
     */
    private static function getUserAgentMobile() {
        $userAgent = null;
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        }
        if ($userAgent === self::YAHOO_SEARCH_CRAWLER) {
            $userAgent = self::USER_AGENT_V705SH;
        }

        $userAgentMobile = Net_UserAgent_Mobile::singleton($userAgent);
        if (Net_UserAgent_Mobile::isError($userAgentMobile)) {
            $message = 'Unable to create Net_UserAgen_Mobile: '
                     . $userAgentMobile->getMessage();
            throw new MobileUserAgentException($message);
        }

        return $userAgentMobile;
    }

    /**
     * ユーザエージェントの文字列を小文字で返却
     * 
     * @return string 小文字のユーザエージェント
     */
    private static function getLowerUserAgent() {
        $userAgent = '';
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
        }

        return $userAgent;
    }
}
