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

/** MobileDisplayInfoException */
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'MobileDisplayInfoException.php';

/** Net_UserAgent_Mobile */
require_once 'Net/UserAgent/Mobile.php';

/**
 * Net_UserAgent_Mobile_Displayのラッパー
 * 
 * <p>フィーチャフォンの端末画面サイズを取得するモジュールです。</p>
 * <p>Net_UserAgent_Mobileが必須です。<br />
 * また、2009年以降のdocomo端末への対応には、付属パッチをあててください。</p>
 * <code>
 * $ patch Net/UserAgent/Mobile/DoCoMo/ScreenInfo.php < ScreenInfo.php.patch
 * </code>
 * 
 * @author IKEDA Youhei <youhey.ikeda@gmail.com>
 */
class MobileDisplayInfo
{

    /** 端末の画面サイズが不明であればFWVGA（フルワイドVGA）とする */
    const
        DEFAULT_KTAI_WIDTH  = 480,
        DEFAULT_KTAI_HEIGHT = 854;

    /**
     * 端末画面の横幅がサイズ以下かをチェック
     * 
     * @param integer $size 比較する横幅
     * @return boolean 端末画面の横幅がサイズ以下であればTRUE
     */
    public static function isWidthLessOrEqualTo($size) {
        $displayWidth  = self::width();
        $isLessOrEqual = (is_int($displayWidth) && ($displayWidth <= $size));

        return $isLessOrEqual;
    }

    /**
     * 携帯端末の端末情報から、画面サイズの横幅を返却
     * 
     * <p>日本国内3キャリアのフィーチャフォンのみに対応します。<br />
     * 非対応のユーザエージェントに対しては、NULLを返却します。</p>
     * <p>PCブラウザおよびスマートフォンには対応しません。<br />
     * HTTPリクエストから端末情報（画面サイズ）が取得できないためです。</p>
     * 
     * @return integer 画面サイズの横幅
     */
    public static function width() {
        $displayWidth = null;

        if (self::isKTai()) {
            try {
                $displayWidth = self::createDisplayInfo()->getWidth();
            } catch (MobileDisplayInfoException $e) {
                // ignore
            }
            if (empty($displayWidth)) {
                $displayWidth = self::DEFAULT_KTAI_WIDTH;
            }
        }

        return $displayWidth;
    }

    /**
     * 携帯端末の端末情報から、画面サイズの高さを返却
     * 
     * <p>日本国内3キャリアのフィーチャフォンのみに対応します。<br />
     * 非対応のユーザエージェントに対しては、NULLを返却します。</p>
     * <p>PCブラウザおよびスマートフォンには対応しません。<br />
     * HTTPリクエストから端末情報（画面サイズ）が取得できないためです。</p>
     * 
     * @return integer 画面サイズの高さ
     */
    public static function height() {
        $displayHeight = null;

        if (self::isKTai()) {
            try {
                $displayHeight = self::createDisplayInfo()->getHeight();
            } catch (MobileDisplayInfoException $e) {
                // ignore
            }
            if (empty($displayHeight)) {
                $displayHeight = self::DEFAULT_KTAI_HEIGHT;
            }
        }

        return $displayHeight;
    }

    /**
     * ユーザエージェントが日本国内3キャリアのフィーチャフォンかをチェック
     * 
     * @return boolean 日本国内3キャリアのフィーチャフォンであればTURE
     */
    private static function isKTai() {
        $isKtai = false;

        try {
            $userAgent  = self::createUserAgentMobile();
            $isDocomo   = $userAgent->isDoCoMo();
            $isEZweb    = $userAgent->isEZweb();
            $isSoftbank = $userAgent->isSoftbank();
            $isKtai     = ($isDocomo || $isEZweb || $isSoftbank);
        } catch (MobileDisplayInfoException $e) {
            // ignore
        }
        $userAgent = null;

        return $isKtai;
    }

    /**
     * Net_UserAgent_Mobile_Displayのインスタンスを生成
     * 
     * @return Net_UserAgent_Mobile_Display
     * @throws UserAgentException インスタンスの生成に失敗したとき
     */
    private static function createDisplayInfo() {
        // 未定義のdocomo端末で、INDEX外へのアクセスによるエラーを抑制
        $displayInfo = @self::createUserAgentMobile()->makeDisplay();
        if (Net_UserAgent_Mobile::isError($displayInfo)) {
            $message = 'Unable to create Net_UserAgent_Mobile_Display: '
                     . $agent->getMessage();
            throw new MobileDisplayInfoException($message);
        }

        return $displayInfo;
    }

    /**
     * Net_UserAgent_Mobileのインスタンスを生成
     * 
     * @return Net_UserAgent_Mobile_Common
     * @throws UserAgentException インスタンスの生成に失敗したとき
     */
    private static function createUserAgentMobile() {
        $userAgent = null;
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        }

        $userAgentMobile = Net_UserAgent_Mobile::singleton($userAgent);
        if (Net_UserAgent_Mobile::isError($userAgentMobile)) {
            $message = 'Unable to create Net_UserAgen_Mobile: '
                     . $userAgentMobile->getMessage();
            throw new MobileDisplayInfoException($message);
        }

        return $userAgentMobile;
    }
}
