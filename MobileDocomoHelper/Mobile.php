<?php
/**
 * モバイル - モバイルサイト対応の機能を提供
 * 
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 */

/** Net_UserAgent_Mobile */
require_once 'Net/UserAgent/Mobile.php';

/** MobileException */
require_once 
    dirname(__FILE__).DIRECTORY_SEPARATOR.'MobileException.php';

/**
 * モバイル
 *
 * @author IKEDA Youhei <youhey.ikeda@gmail.com>
 */
class Mobile
{

    /** Yahoo!ケータイのクローラUA */
    const YAHOO_SEARCH_CRAWLER = 'Vodafone/1.0/V705SH (compatible; Y!J-SRD/1.0; http://help.yahoo.co.jp/help/jp/search/indexing/indexing-27.html)';

    /** ユーザエージェント（SoftBankのV705SH）*/
    const USER_AGENT_V705SH = 'Vodafone/1.0/V705SH/SHJ001 Browser/VF-NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1';

    /** docomo公式サイトの契約状態（拡張ヘッダ） - パケホーダイ契約状態 */
    const DOCOMO_OFFICIAL_STATUS_PAKEHO = 'HTTP_X_DCM_PAKEHO';

    /** docomo公式サイトの契約状態（拡張ヘッダ） - パケホーダイフル契約状態 */
    const DOCOMO_OFFICIAL_STATUS_PAKEHOFL = 'HTTP_X_DCM_PAKEHOFL';

    /** docomo公式サイトの契約状態（拡張ヘッダ） - iコンシェル契約状態 */
    const DOCOMO_OFFICIAL_STATUS_ICONCIER = 'HTTP_X_DCM_ICO';

    /** docomo公式サイトの契約状態（値） - 状態＝契約あり */
    const DOCOMO_OFFICIAL_STATUS_ENABLED = '1';

    /** docomo公式サイトの契約状態（値） - 状態＝契約なし */
    const DOCOMO_OFFICIAL_STATUS_DISABLED = '0';

    /** docomo公式サイトの契約状態（値） - 状態＝おためし */
    const DOCOMO_OFFICIAL_STATUS_TRIAL = 'T';

    /** docomo公式サイトの契約状態（値） - 状態＝ローミング中 */
    const DOCOMO_OFFICIAL_STATUS_ROAMING = 'R';

    /** docomo公式サイトの契約状態（値） - ユーザ設定＝通知しない */
    const DOCOMO_OFFICIAL_STATUS_WITHHELD = 'REJ';

    /** docomo公式サイトの契約状態（値） - docomoのサーバでエラー発生 */
    const DOCOMO_OFFICIAL_STATUS_ERROR = 'ERR';

    /** docomo公式サイトの契約状態（GETパラメータ） - ユーザID */
    const DOCOMO_OFFICIAL_QUERY_UID = 'uid=NULLGWDOCOMO';

    /** docomo公式サイトの契約状態（GETパラメータ） - パケホーダイ契約状態 */
    const DOCOMO_OFFICIAL_QUERY_PAKEHO = 'DCMPAKEHO=ON';

    /** docomo公式サイトの契約状態（GETパラメータ） - iコンシェル契約状態 */
    const DOCOMO_OFFICIAL_QUERY_ICONCIER = 'DCMICO=ON';

    /**
     * XHTMLに対応しているdocomoのHTMLバージョン
     * 
     * @var array
     */
    private static $_docomoXHTMLVersions = array(
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
     * @var array
     */
    private static $_docomoChtmlFoma = array(
            /* 2101V系 */ 'D2101V', 'P2101V', 'SH2101V', 'T2101V', 
            /* 2001系 */  'N2001', 
            /* 2002系 */  'N2002', 'P2002', 
        );

    /**
     * ユーザエージェントがdocomoかをチェック
     * 
     * @return boolean ユーザエージェントがdocomoであればTURE
     * @throws MobileException ユーザエージェントのデータ操作に失敗したとき
     */
    public function isDocomo()
    {
        return 
            self::_getUserAgent()->isDoCoMo();
    }

    /**
     * ユーザエージェントがEZwebかをチェック
     * 
     * @return boolean ユーザエージェントがEZwebであればTURE
     * @throws MobileException ユーザエージェントのデータ操作に失敗したとき
     */
    public function isEZweb()
    {
        return 
            self::_getUserAgent()->isEZweb();
    }

    /**
     * ユーザエージェントがSoftBankかをチェック
     * 
     * @return boolean ユーザエージェントがSoftBankであればTURE
     * @throws MobileException ユーザエージェントのデータ操作に失敗したとき
     */
    public function isSoftBank()
    {
        return 
            self::_getUserAgent()->isSoftbank();
    }

    /**
     * ユーザエージェントの機種がXHTMLに対応しているかをチェック
     * 
     * @return boolean XHTMLに対応した機種であればTRUE
     */
    public static function isXhtml()
    {
        return 
            self::_isXhtmlDocomo() || 
            self::_isXhtmlEZweb() || 
            self::_isXhtmlSoftBank();
    }

    /**
     * iモードのHTMLバージョンを返却
     * 
     * @return string HTMLバージョン、docomoでなければNULL
     * @throws MobileException ユーザエージェントのデータ操作に失敗したとき
     * @throws MobileException ユーザエージェントがdocomoでないとき
     */
    public static function getDocomoHtmlVersion()
    {
        if (!self::isDoCoMo()) {
            throw new MobileException('UserAgent is not docomo');
        }

        $agent   = self::_getUserAgent();
        $version = $agent->getHTMLVersion();
        if (empty($version)) {
            // マッピングが未定義なのはライブラリが未対応の機種
            // ≒新しい機種なので、最新バージョンとして処理する
            $version = '7.2';
        }

        return $version;
    }

    /**
     * ユーザエージェントがdocomoのXHTML対応端末かをチェック
     * 
     * @return boolean XHTMLに対応した機種であればTRUE
     * @throws MobileException ユーザエージェントのデータ操作に失敗したとき
     */
    private static function _isXhtmlDocomo()
    {
        $agent   = self::_getUserAgent();
        $model   = $agent->getModel();
        $version = null;
        if ($agent->isDoCoMo()) {
            $version = self::getDocomoHtmlVersion();
        }

        return 
            $agent->isDoCoMo() && 
            $agent->isFoma() && 
            !in_array($model, self::$_docomoChtmlFoma, true) && 
            in_array($version, self::$_docomoXHTMLVersions);
    }

    /**
     * ユーザエージェントがEZwebのXHTML対応端末かをチェック
     * 
     * @return boolean XHTMLに対応した機種であればTRUE
     * @throws MobileException ユーザエージェントのデータ操作に失敗したとき
     */
    private static function _isXhtmlEZweb()
    {
        $agent = self::_getUserAgent();

        return 
            $agent->isEZweb() && 
            $agent->isWAP2();
    }

    /**
     * ユーザエージェントがSoftBankのXHTML対応端末かをチェック
     * 
     * @return boolean XHTMLに対応した機種であればTRUE
     * @throws MobileException ユーザエージェントのデータ操作に失敗したとき
     */
    private static function _isXhtmlSoftBank()
    {
        $agent = self::_getUserAgent();

        return 
            $agent->isSoftBank() && 
            ($agent->isTypeW() || $agent->isType3GC());
    }

    /**
     * Net_UserAgent_Mobileのインスタンスを返却
     * 
     * @return Net_UserAgent_Mobile_Common
     * @throws MobileException インスタンスの生成に失敗したとき
     */
    private static function _getUserAgent()
    {
        $ua = null;
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $ua = (string)$_SERVER['HTTP_USER_AGENT'];
            // bugfix for http://pear.php.net/bugs/bug.php?id=17197
            if ($ua === self::YAHOO_SEARCH_CRAWLER) {
                $ua = self::USER_AGENT_V705SH;
            }
        }

        $agent = Net_UserAgent_Mobile::singleton($ua);
        if (Net_UserAgent_Mobile::isError($agent)) {
            throw new MobileException('Unable to create Net_UserAgen_Mobile: ' . 
                                      $agent->getMessage());
        }

        return $agent;
    }
}
