<?php

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'MobileTestCase.php';

require_once
    dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Mobile_Docomo.php';

/**
 * Test class for Mobile_Docomo.
 */
class MobileDocomoTest extends MobileTestCase
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
     * test Mobile_Docomo::hasUidConfirmation() method.
     * 
     * <p>URLのGETパラメータで、パケホーダイ契約を確認しているか</p>
     * 
     * @param  string $query_string GETパラメータ
     * @retunr void
     * @dataProvider providerExistUidQueryString
     */
    public function testHasUidConfirmation_ExistQueryString($query_string)
    {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'DoCoMo/2.0 P903i', 
                'QUERY_STRING'    => $query_string, // GETパラメータあり
            );
        $this->assertTrue(Mobile_Docomo::hasUidConfirmation());
    }
    /**
     * @ignore
     * @param string $query_string GETパラメータ
     * @dataProvider providerNotExistUidQueryString
     */
    public function testHasUidConfirmation_NotExistQueryString($query_string)
    {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'DoCoMo/2.0 P903i', 
                'QUERY_STRING'    => $query_string, // GETパラメータなし
            );
        $this->assertFalse(Mobile_Docomo::hasUidConfirmation());
    }
    /**
     * @ignore
     * @param string $ua ユーザーエージェント
     * @dataProvider providerNotDocomoUserAgent
     */
    public function testHasUidConfirmation_NotDocomoUA($ua)
    {
        try {
            $_SERVER = array('HTTP_USER_AGENT' => $ua);
            Mobile_Docomo::hasUidConfirmation();
        } catch (Exception $e) {
            $this->assertInstanceOf('MobileException', $e);
            $error    = $e->getMessage();
            $expected = 'UserAgent is not docomo';
            $this->assertSame($expected, $error);
        }
    }

    /**
     * test Mobile_Docomo::hasPakehoConfirmation() method.
     * 
     * <p>URLのGETパラメータで、パケホーダイ契約を確認しているか</p>
     * 
     * @param  string $query_string GETパラメータ
     * @retunr void
     * @dataProvider providerExistPakehoQueryString
     */
    public function testHasPakehoConfirmation_ExistQueryString($query_string)
    {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'DoCoMo/2.0 P903i', 
                'QUERY_STRING'    => $query_string, // GETパラメータあり
            );
        $this->assertTrue(Mobile_Docomo::hasPakehoConfirmation());
    }
    /**
     * @ignore
     * @param string $query_string GETパラメータ
     * @dataProvider providerNotExistPakehoQueryString
     */
    public function testHasPakehoConfirmation_NotExistQueryString($query_string)
    {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'DoCoMo/2.0 P903i', 
                'QUERY_STRING'    => $query_string, // GETパラメータなし
            );
        $this->assertFalse(Mobile_Docomo::hasPakehoConfirmation());
    }
    /**
     * @ignore
     * @param string $ua ユーザーエージェント
     * @dataProvider providerNotDocomoUserAgent
     */
    public function testHasPakehoConfirmation_NotDocomoUA($ua)
    {
        try {
            $_SERVER = array('HTTP_USER_AGENT' => $ua);
            Mobile_Docomo::hasPakehoConfirmation();
        } catch (Exception $e) {
            $this->assertInstanceOf('MobileException', $e);
            $error    = $e->getMessage();
            $expected = 'UserAgent is not docomo';
            $this->assertSame($expected, $error);
        }
    }

    /**
     * test Mobile_Docomo::hasPakehoStatus() method.
     * 
     * <p>docomoのパケホーダイ契約状態が通知されているか</p>
     * 
     * @param string $status 状態
     * @retunr void
     * @dataProvider providerSendPakehoStatus
     */
    public function testHasPakehoStatus_SendStatus($status)
    {
        $_SERVER = array(
                'HTTP_USER_AGENT'   => 'DoCoMo/2.0 P903i', 
                'HTTP_X_DCM_PAKEHO' => $status, 
            );
        $this->assertTrue(Mobile_Docomo::hasPakehoStatus());
    }
    /**
     * @ignore
     * @param string $status 状態
     * @dataProvider providerNoSendPakehoStatus
     */
    public function testHasPakehoStatus_NoSendStatus($status)
    {
        $_SERVER = array(
                'HTTP_USER_AGENT'   => 'DoCoMo/2.0 P903i', 
                'HTTP_X_DCM_PAKEHO' => $status, 
            );
        $this->assertFalse(Mobile_Docomo::hasPakehoStatus());
    }
    /** @ignore */
    public function testHasPakehoStatus_DocomoServerError()
    {
        // docomoのサーバでエラーが発生していれば例外
        try {
            $_SERVER = array(
                    'HTTP_USER_AGENT'   => 'DoCoMo/2.0 P903i', 
                    'HTTP_X_DCM_PAKEHO' => 'ERR', 
                );
            Mobile_Docomo::hasPakehoStatus();
        } catch (Exception $e) {
            $this->assertInstanceOf('MobileException', $e);
            $error    = $e->getMessage();
            $expected = 'docomo server error has occurred';
            $this->assertSame($expected, $error);
        }
    }
    /**
     * @ignore
     * @param string $ua ユーザーエージェント
     * @dataProvider providerNotDocomoUserAgent
     */
    public function testHasPakehoStatus_NotDocomoUA($ua)
    {
        try {
            $_SERVER = array('HTTP_USER_AGENT' => $ua);
            Mobile_Docomo::hasPakehoStatus();
        } catch (Exception $e) {
            $this->assertInstanceOf('MobileException', $e);
            $error    = $e->getMessage();
            $expected = 'UserAgent is not docomo';
            $this->assertSame($expected, $error);
        }
    }

    /**
     * test Mobile_Docomo::isPakehoContract() method.
     * 
     * <p>docomoのパケホーダイ契約状態から、契約が有効かをチェック</p>
     * 
     * @param  string $status 契約状態
     * @retunr void
     * @dataProvider providerEnablePakehoContract
     */
    public function testIsPakehoContract_EnableContract($status)
    {
        $_SERVER = array(
                'HTTP_USER_AGENT'   => 'DoCoMo/2.0 P903i', 
                'HTTP_X_DCM_PAKEHO' => $status, 
            );
        $this->assertTrue(Mobile_Docomo::isPakehoContract());
    }
    /**
     * @ignore
     * @param string $status 契約状態
     * @dataProvider providerDisablePakehoContract
     */
    public function testIsPakehoContract_DisableContract($status)
    {
        $_SERVER = array(
                'HTTP_USER_AGENT'   => 'DoCoMo/2.0 P903i', 
                'HTTP_X_DCM_PAKEHO' => $status, 
            );
        $this->assertFalse(Mobile_Docomo::isPakehoContract());
    }
    /** @ignore */
    public function testIsPakehoContract_DocomoServerError()
    {
        // docomoのサーバでエラーが発生していれば例外
        try {
            $_SERVER = array(
                    'HTTP_USER_AGENT'   => 'DoCoMo/2.0 P903i', 
                    'HTTP_X_DCM_PAKEHO' => 'ERR', 
                );
            Mobile_Docomo::isPakehoContract();
        } catch (Exception $e) {
            $this->assertInstanceOf('MobileException', $e);
            $error    = $e->getMessage();
            $expected = 'docomo server error has occurred';
            $this->assertSame($expected, $error);
        }
    }
    /**
     * @ignore
     * @param string $ua ユーザーエージェント
     * @dataProvider providerNotDocomoUserAgent
     */
    public function testIsPakehoContract_NotDocomoUA($ua)
    {
        try {
            $_SERVER = array('HTTP_USER_AGENT' => $ua);
            Mobile_Docomo::isPakehoContract();
        } catch (Exception $e) {
            $this->assertInstanceOf('MobileException', $e);
            $error    = $e->getMessage();
            $expected = 'UserAgent is not docomo';
            $this->assertSame($expected, $error);
        }
    }

    /**
     * test Mobile_Docomo::hasIconcierConfirmation() method.
     * 
     * <p>URLのGETパラメータで、iコンシェル契約を確認しているか</p>
     * 
     * @param string $query_string GETパラメータ
     * @retunr void
     * @dataProvider providerExistIconcierQueryString
     */
    public function testHasIconcierConfirmation_ExistQueryString($query_string)
    {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'DoCoMo/2.0 P903i', 
                'QUERY_STRING'    => $query_string, 
            );
        $this->assertTrue(Mobile_Docomo::hasIconcierConfirmation());
    }
    /**
     * @ignore
     * @param string $query_string GETパラメータ
     * @dataProvider providerNotExistIconcierQueryString
     */
    public function testHasIconcierConfirmation_NotExistQueryString($query_string)
    {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'DoCoMo/2.0 P903i', 
                'QUERY_STRING'    => $query_string, 
            );
        $this->assertFalse(Mobile_Docomo::hasIconcierConfirmation());
    }
    /**
     * @ignore
     * @param string $ua ユーザーエージェント
     * @dataProvider providerNotDocomoUserAgent
     */
    public function testHasIconcierConfirmation_NotDocomoUA($ua)
    {
        try {
            $_SERVER = array('HTTP_USER_AGENT' => $ua);
            Mobile_Docomo::hasIconcierConfirmation();
        } catch (Exception $e) {
            $this->assertInstanceOf('MobileException', $e);
            $error    = $e->getMessage();
            $expected = 'UserAgent is not docomo';
            $this->assertSame($expected, $error);
        }
    }

    /**
     * test Mobile_Docomo::hasIconcierStatus() method.
     * 
     * <p>docomoのiコンシェル契約状態が通知されているか</p>
     * 
     * @param  string $status iコンシェルの契約状態
     * @retunr void
     * @dataProvider providerSendIconcierStatus
     */
    public function testHasIconcierStatus_SendStatus($status)
    {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'DoCoMo/2.0 P903i', 
                'HTTP_X_DCM_ICO'  => $status, 
            );
        $this->assertTrue(Mobile_Docomo::hasIconcierStatus());
    }
    /**
     * @ignore
     * @param string $status iコンシェルの契約状態
     * @dataProvider providerNoSendIconcierStatus
     */
    public function testHasIconcierStatus_NoSendStatus($status)
    {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'DoCoMo/2.0 P903i', 
                'HTTP_X_DCM_ICO'  => $status, 
            );
        $this->assertFalse(Mobile_Docomo::hasIconcierStatus());
    }
    /** @ignore */
    public function testHasIconcierStatus_DocomoServerError()
    {
        // docomoのサーバでエラーが発生していれば例外
        try {
            $_SERVER = array(
                    'HTTP_USER_AGENT' => 'DoCoMo/2.0 P903i', 
                    'HTTP_X_DCM_ICO'  => 'ERR', 
                );
            Mobile_Docomo::hasIconcierStatus();
        } catch (Exception $e) {
            $this->assertInstanceOf('MobileException', $e);
            $error    = $e->getMessage();
            $expected = 'docomo server error has occurred';
            $this->assertSame($expected, $error);
        }
    }
    /**
     * @ignore
     * @param string $ua ユーザーエージェント
     * @dataProvider providerNotDocomoUserAgent
     */
    public function testHasIconcierStatus_NotDocomoUA($ua)
    {
        try {
            $_SERVER = array('HTTP_USER_AGENT' => $ua);
            Mobile_Docomo::hasIconcierStatus();
        } catch (Exception $e) {
            $this->assertInstanceOf('MobileException', $e);
            $error    = $e->getMessage();
            $expected = 'UserAgent is not docomo';
            $this->assertSame($expected, $error);
        }
    }

    /**
     * test Mobile_Docomo::isIconcierContract() method.
     * 
     * <p>docomoのiコンシェル契約状態から、契約が有効かをチェック</p>
     * 
     * @param  string $status iコンシェル契約状態
     * @retunr void
     * @dataProvider providerEnableIconcierContract
     */
    public function testIsIconcierContract_EnableContract($status)
    {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'DoCoMo/2.0 P903i', 
                'HTTP_X_DCM_ICO'  => $status , 
            );
        $this->assertTrue(Mobile_Docomo::isIconcierContract());
    }
    /**
     * @ignore
     * @param string $status iコンシェル契約状態
     * @dataProvider providerDisableIconcierContract
     */
    public function testIsIconcierContract_DisableContract($status)
    {
        $_SERVER = array(
                'HTTP_USER_AGENT' => 'DoCoMo/2.0 P903i', 
                'HTTP_X_DCM_ICO'  => $status , 
            );
        $this->assertFalse(Mobile_Docomo::isIconcierContract());
    }
    /** @ignore */
    public function testIsIconcierContract_DocomoServerError()
    {
        // docomoのサーバでエラーが発生していれば例外
        try {
            $_SERVER = array(
                    'HTTP_USER_AGENT' => 'DoCoMo/2.0 P903i', 
                    'HTTP_X_DCM_ICO'  => 'ERR', 
                );
            Mobile_Docomo::isIconcierContract();
        } catch (Exception $e) {
            $this->assertInstanceOf('MobileException', $e);
            $error    = $e->getMessage();
            $expected = 'docomo server error has occurred';
            $this->assertSame($expected, $error);
        }
    }
    /**
     * @ignore
     * @param string $ua ユーザーエージェント
     * @dataProvider providerNotDocomoUserAgent
     */
    public function testIsIconcierContract_NotDocomoUA($ua)
    {
        try {
            $_SERVER = array('HTTP_USER_AGENT' => $ua);
            Mobile_Docomo::isIconcierContract();
        } catch (Exception $e) {
            $this->assertInstanceOf('MobileException', $e);
            $error    = $e->getMessage();
            $expected = 'UserAgent is not docomo';
            $this->assertSame($expected, $error);
        }
    }

    /**
     * docomo、GETパラメータに「uid」あり
     * 
     * @return array
     */
    public static function providerExistUidQueryString()
    {
        return array(
                array('uid=NULLGWDOCOMO'), 
                array('foo=bar&uid=NULLGWDOCOMO&42'), 
            );
    }

    /**
     * docomo、GETパラメータに「uid」なし
     * 
     * @return array
     */
    public static function providerNotExistUidQueryString()
    {
        return array(
                array(''), 
                array('foo=bar&42'), 
                array(null), 
            );
    }

    /**
     * docomo、GETパラメータに「DCMPAKEH」あり
     * 
     * @return array
     */
    public static function providerExistPakehoQueryString()
    {
        return array(
                array('DCMPAKEHO=ON'), 
                array('foo=bar&DCMPAKEHO=ON&42'), 
            );
    }

    /**
     * docomo、GETパラメータに「DCMPAKEH」なし
     * 
     * @return array
     */
    public static function providerNotExistPakehoQueryString()
    {
        return array(
                array(''), 
                array('foo=bar&42'), 
                array(null), 
            );
    }

    /**
     * docomoのパケホーダイ契約状態→通知あり
     * 
     * @return array
     */
    public static function providerSendPakehoStatus()
    {
        return array(
                array('1'), // 契約あり
                array('R'), // 契約あり（ローミング中）
                array('0'), // 契約なし
            );
    }

    /**
     * docomoのパケホーダイ契約状態→通知なし
     * 
     * @return array
     */
    public static function providerNoSendPakehoStatus()
    {
        return array(
                array('REJ'), // 通知しない
                array(''),    // 契約状態の値がない
                array(null),  // 契約状態の値がない
            );
    }

    /**
     * docomoのパケホーダイの契約状態→契約が有効
     * 
     * @return array
     */
    public static function providerEnablePakehoContract()
    {
        return array(
                array('1'), // 契約あり
                array('R'), //  契約あり（ローミング中）
            );
    }

    /**
     * docomoのパケホーダイの契約状態→契約が無効
     * 
     * @return array
     */
    public static function providerDisablePakehoContract()
    {
        return array(
                array('0'),   // 契約なし
                array('REJ'), // 通知しない
                array(''),    // 契約状態の値がない
                array(null),  // 契約状態の値がない
            );
    }

    /**
     * docomo、GETパラメータに「DCMICO」あり
     * 
     * @return array
     */
    public static function providerExistIconcierQueryString()
    {
        return array(
                array('DCMICO=ON'), 
                array('foo=bar&DCMICO=ON&42'), 
            );
    }

    /**
     * docomo、GETパラメータに「DCMICO」なし
     * 
     * @return array
     */
    public static function providerNotExistIconcierQueryString()
    {
        return array(
                array('foo=bar&42'), 
                array(''), 
                array(null), 
            );
    }

    /**
     * docomoのiコンシェル契約状態→通知あり
     * 
     * @return array
     */
    public static function providerSendIconcierStatus()
    {
        return array(
                array('1'), // 契約あり
                array('T'), // 契約あり（おためし）
                array('0'), // 契約なし
            );
    }

    /**
     * docomoのiコンシェル契約状態→通知なし
     * 
     * @return array
     */
    public static function providerNoSendIconcierStatus()
    {
        return array(
                array('REJ'), // 通知しない
                array(''),    // 契約状態の値がない
                array(null),  // 契約状態の値がない
            );
    }

    /**
     * docomoのiコンシェル契約状態→契約が有効
     * 
     * @return array
     */
    public static function providerEnableIconcierContract()
    {
        return array(
                array('1'), // 契約あり
                array('T'), // 契約あり（おためし）
            );
    }

    /**
     * docomoのiコンシェル契約状態→契約が無効
     * 
     * @return array
     */
    public static function providerDisableIconcierContract()
    {
        return array(
                array('0'),   // 契約なし
                array('REJ'), // 通知しない
                array(''),    // 契約状態の値がない
                array(null),  // 契約状態の値がない
            );
    }

    /**
     * docomo以外のキャリアのユーザーエージェント
     * 
     * @return array
     */
    public static function providerNotDocomoUserAgent()
    {
        return array(
                array('HTTP_USER_AGENT' => 'KDDI-CA39 UP.Browser/6.2.0.13.1.5 (GUI) MMP/2.0'), 
                array('HTTP_USER_AGENT' => 'SoftBank/1.0/930SH/SHJ001 Browser/NetFront/3.4 Profile/MIDP-2.0 Configuration/CLDC-1.1'), 
            );
    }
}
