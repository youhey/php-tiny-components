<?php
/**
 * モバイル - モバイルサイト対応の機能を提供
 * 
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 */

/**
 * Mobile class.
 */
require_once 
    dirname(__FILE__).DIRECTORY_SEPARATOR.'Mobile.php';

/**
 * MobileException class.
 */
require_once 
    dirname(__FILE__).DIRECTORY_SEPARATOR.'MobileException.php';

/**
 * docomoキャリア固有の情報取得
 *
 * @author IKEDA Youhei <youhey.ikeda@gmail.com>
 */
class Mobile_Docomo
{

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
     * HTTPリクエスト（URL）で、ユーザIDを取得しているか
     * 
     * @return boolean 取得していればTRUE
     * @throws MobileException ユーザエージェントがdocomoでないとき
     */
    public static function hasUidConfirmation()
    {
        if (!Mobile::isDoCoMo()) {
            throw new MobileException('UserAgent is not docomo');
        }

        $queries = self::_getQueries();

        return
            in_array(self::DOCOMO_OFFICIAL_QUERY_UID, $queries);
    }

    /**
     * HTTPリクエスト（URL）で、パケット定額サービス契約の状態を確認しているか
     * 
     * @return boolean 確認していればTRUE
     * @throws MobileException ユーザエージェントがdocomoでないとき
     */
    public static function hasPakehoConfirmation()
    {
        if (!Mobile::isDoCoMo()) {
            throw new MobileException('UserAgent is not docomo');
        }

        $queries = self::_getQueries();

        return
            in_array(self::DOCOMO_OFFICIAL_QUERY_PAKEHO, $queries);
    }

    /**
     * docomoのユーザが、パケット定額サービス契約の状態を通知しているか
     * 
     * @return boolean 通知していればTRUE
     * @throws MobileException ユーザエージェントがdocomoでないとき
     * @throws MobileException docomoのサーバでエラーが発生したとき
     */
    public static function hasPakehoStatus()
    {
        if (!Mobile::isDoCoMo()) {
            throw new MobileException('UserAgent is not docomo');
        }

        $env = self::_getEnv(self::DOCOMO_OFFICIAL_STATUS_PAKEHO);
        if ($env === self::DOCOMO_OFFICIAL_STATUS_ERROR) {
            throw new MobileException('docomo server error has occurred');
        }

        return 
            ($env !== null) && 
            ($env !== '') && 
            ($env !== self::DOCOMO_OFFICIAL_STATUS_WITHHELD);
    }

    /**
     * docomoのユーザが、パケット定額サービスを契約しているか
     * 
     * @return boolean 契約していればTRUE
     * @throws MobileException ユーザエージェントがdocomoでないとき
     * @throws MobileException docomoのサーバでエラーが発生したとき
     */
    public static function isPakehoContract()
    {
        if (!Mobile::isDoCoMo()) {
            throw new MobileException('UserAgent is not docomo');
        }

        $env = self::_getEnv(self::DOCOMO_OFFICIAL_STATUS_PAKEHO);
        if ($env === self::DOCOMO_OFFICIAL_STATUS_ERROR) {
            throw new MobileException('docomo server error has occurred');
        }

        return 
            ($env === self::DOCOMO_OFFICIAL_STATUS_ENABLED) ||
            ($env === self::DOCOMO_OFFICIAL_STATUS_ROAMING);
    }

    /**
     * HTTPリクエスト（URL）で、iコンシェルサービス契約の状態を確認しているか
     * 
     * @return boolean 確認していればTRUE
     * @throws MobileException ユーザエージェントがdocomoでないとき
     */
    public static function hasIconcierConfirmation()
    {
        if (!Mobile::isDoCoMo()) {
            throw new MobileException('UserAgent is not docomo');
        }

        $queries = self::_getQueries();

        return
            in_array(self::DOCOMO_OFFICIAL_QUERY_ICONCIER, $queries);
    }

    /**
     * docomoのユーザが、iコンシェルサービス契約の状態を通知しているか
     * 
     * @return boolean 通知していればTRUE
     * @throws MobileException ユーザエージェントがdocomoでないとき
     * @throws MobileException docomoのサーバでエラーが発生したとき
     */
    public static function hasIconcierStatus()
    {
        if (!Mobile::isDoCoMo()) {
            throw new MobileException('UserAgent is not docomo');
        }

        $env = self::_getEnv(self::DOCOMO_OFFICIAL_STATUS_ICONCIER);
        if ($env === self::DOCOMO_OFFICIAL_STATUS_ERROR) {
            throw new MobileException('docomo server error has occurred');
        }

        return 
            ($env !== null) && 
            ($env !== '') && 
            ($env !== self::DOCOMO_OFFICIAL_STATUS_WITHHELD);
    }

    /**
     * docomoのユーザが、iコンシェルサービスを契約しているか
     * 
     * @return boolean 契約していればTRUE
     * @throws MobileException ユーザエージェントがdocomoでないとき
     * @throws MobileException docomoのサーバでエラーが発生したとき
     */
    public static function isIconcierContract()
    {
        if (!Mobile::isDoCoMo()) {
            throw new MobileException('UserAgent is not docomo');
        }

        $env = self::_getEnv(self::DOCOMO_OFFICIAL_STATUS_ICONCIER);
        if ($env === self::DOCOMO_OFFICIAL_STATUS_ERROR) {
            throw new MobileException('docomo server error has occurred');
        }

        return 
            ($env === self::DOCOMO_OFFICIAL_STATUS_ENABLED) ||
            ($env === self::DOCOMO_OFFICIAL_STATUS_TRIAL);
    }

    /**
     * サーバ環境変数からデータを取得
     * 
     * @param  string $key 変数名
     * @return string 環境変数の値
     * @throws InvalidArgumentException 引数のデータ型が期待値と一致しないとき
     */
    private static function _getEnv($key)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException('Argument #1 must be an string');
        }

        $env = null;
        if (isset($_SERVER[$key])) {
            $env = self::_stripCtrlChar($_SERVER[$key]);
        }

        return $env;
    }

    /**
     * 文字列から制御文字を削除
     * 
     * @param  string $string 生データ
     * @return string 制御文字を削除した文字列
     * @throws InvalidArgumentException 引数のデータ型が期待値と一致しないとき
     */
    private static function _stripCtrlChar($string)
    {
        if (!is_string($string)) {
            throw new InvalidArgumentException('Argument #1 must be an string');
        }

        $ctrl_char = array();
        for ($i = 0; $i <= 0x1F; ++$i) {
            $ctrl_char[] = chr($i);
        }
        $ctrl_char[] = "\x7F";

        return 
            str_replace($ctrl_char, '', (string)$string);
    }

    /**
     * QUERY STRING を配列で返却
     * 
     * @return array QUERY STRING
     */
    private static function _getQueries()
    {
        $queries = array();
        if (isset($_SERVER['QUERY_STRING'])) {
            foreach (explode('&', (string)$_SERVER['QUERY_STRING']) as $query) {
                $queries[] = self::_stripCtrlChar($query);
            }
        }

        return $queries;
    }
}
