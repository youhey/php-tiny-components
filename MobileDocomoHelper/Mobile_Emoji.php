<?php
/**
 * モバイル - モバイルサイト対応の機能を提供
 * 
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 */

/** Text_Pictogram_Mobile */
require_once 'Text/Pictogram/Mobile.php';

/** Mobile */
require_once  dirname(__FILE__).DIRECTORY_SEPARATOR.'Mobile.php';

/** MobileException */
require_once  dirname(__FILE__).DIRECTORY_SEPARATOR.'MobileException.php';

/**
 * モバイルサイトの絵文字
 *
 * @author IKEDA Youhei <youhey.ikeda@gmail.com>
 */
class Mobile_Emoji
{

    /**
     * バイナリ絵文字に置換する変数のフォーマット
     */
    const 
        EMOJI_FORMAT = '%%emoji_%1$d%%', 
        EMOJI_REGEXP = '%emoji_(\d+)%';

    /**
     * 絵文字のフォント色フォーマット
     */
    const COLORING_TAG = '<span style="color:#%1$s;">%2$s</span>';

    /**
     * docomo絵文字のフォント色
     * 
     * @var array
     */
    private static $_docomoPictogramColors = array(
               1 => 'FF0000', 
               2 => '0000FF', 
               3 => '0000FF', 
               4 => '0000FF', 
               5 => 'FF8000', 
               6 => 'FF0000', 
               7 => '0000FF', 
               8 => '0000FF', 
               9 => 'FF0000', 
              10 => 'FF8000', 
              11 => '00FF00', 
              12 => '0000FF', 
              13 => 'FF0000', 
              14 => 'FF8000', 
              15 => '00FF00', 
              16 => '0000FF', 
              17 => 'FF0000', 
              18 => 'FF8000', 
              19 => '00FF00', 
              20 => '0000FF', 
              21 => 'FF00FF', 
              22 => '000000', 
              23 => '0000FF', 
              24 => '00FF00', 
              25 => '000000', 
              26 => '0000FF', 
              27 => 'FF8000', 
              28 => '000000', 
              29 => 'FF00FF', 
              30 => '00FF00', 
              31 => 'FF8000', 
              32 => '0000FF', 
              33 => '000000', 
              34 => '00FF00', 
              35 => 'FF0000', 
              36 => '0000FF', 
              37 => '0000FF', 
              38 => 'FF0000', 
              39 => '0000FF', 
              40 => 'FF0000', 
              41 => 'FF0000', 
              42 => 'FF00FF', 
              43 => 'FF0000', 
              44 => '00FF00', 
              45 => '0000FF', 
              46 => 'FF00FF', 
              47 => '0000FF', 
              48 => '000000', 
              49 => '000000', 
              50 => '000000', 
              51 => '00FF00', 
              52 => 'FF00FF', 
              53 => 'FF8000', 
              54 => 'FF8000', 
              55 => 'FF0000', 
              56 => '0000FF', 
              57 => '000000', 
              58 => '000000', 
              59 => '000000', 
              60 => 'FF8000', 
              61 => '0000FF', 
              62 => 'FF00FF', 
              63 => '000000', 
              64 => 'FF0000', 
              65 => 'FF8000', 
              66 => '000000', 
              67 => 'FF0000', 
              68 => '000000', 
              69 => 'FF0000', 
              70 => 'FF8000', 
              71 => 'FF0000', 
              72 => 'FF0000', 
              73 => 'FF0000', 
              74 => '000000', 
              75 => '000000', 
              76 => 'FF8000', 
              77 => '0000FF', 
              78 => '000000', 
              79 => '0000FF', 
              80 => 'FF0000', 
              81 => '000000', 
              82 => 'FF0000', 
              83 => '000000', 
              84 => '000000', 
              85 => 'FF8000', 
              86 => 'FF8000', 
              87 => 'FF8000', 
              88 => 'FF8000', 
              89 => '000000', 
              90 => '000000', 
              91 => 'FF8000', 
              92 => '000000', 
              93 => '000000', 
              94 => '0000FF', 
              95 => '000000', 
              96 => '000000', 
              97 => '000000', 
              98 => '000000', 
              99 => '000000', 
             100 => 'FF8000', 
             101 => 'FF8000', 
             102 => '0000FF', 
             103 => '00FF00', 
             104 => '000000', 
             105 => '000000', 
             106 => '000000', 
             107 => '000000', 
             108 => 'FF8000', 
             109 => 'FF8000', 
             110 => '000000', 
             111 => '000000', 
             112 => '000000', 
             113 => 'FF0000', 
             114 => 'FF0000', 
             115 => 'FF0000', 
             116 => 'FF0000', 
             117 => 'FF0000', 
             118 => 'FF0000', 
             119 => '0000FF', 
             120 => 'FF0000', 
             121 => 'FF0000', 
             122 => '000000', 
             123 => '000000', 
             124 => '000000', 
             125 => '000000', 
             126 => '000000', 
             127 => '000000', 
             128 => '000000', 
             129 => '000000', 
             130 => '000000', 
             131 => '000000', 
             132 => '000000', 
             133 => '000000', 
             134 => '000000', 
             135 => 'FF0000', 
             136 => 'FF0000', 
             137 => 'FF0000', 
             138 => 'FF0000', 
             139 => 'FF0000', 
             140 => 'FF00FF', 
             141 => 'FF0000', 
             142 => '0000FF', 
             143 => '00FF00', 
             144 => '0000FF', 
             145 => 'FF0000', 
             146 => 'FF0000', 
             147 => 'FF0000', 
             148 => 'FF00FF', 
             149 => 'FF0000', 
             150 => 'FF8000', 
             151 => 'FF8000', 
             152 => '000000', 
             153 => 'FF0000', 
             154 => '000000', 
             155 => 'FF0000', 
             156 => '0000FF', 
             157 => '0000FF', 
             158 => 'FF0000', 
             159 => 'FF00FF', 
             160 => 'FF0000', 
             161 => 'FF0000', 
             162 => '000000', 
             163 => '000000', 
             164 => '000000', 
             165 => '000000', 
             166 => '000000', 
             167 => '000000', 
             168 => '000000', 
             169 => '000000', 
             170 => '000000', 
             171 => '000000', 
             172 => '000000', 
             173 => '000000', 
             174 => '000000', 
             175 => '000000', 
             176 => '000000', 
            1001 => 'FF9900', 
            1002 => 'FF9900', 
            1003 => '0000FF', 
            1004 => '000000', 
            1005 => 'FF0000', 
            1006 => '000080', 
            1007 => '0000FF', 
            1008 => 'FF9900', 
            1009 => '800000', 
            1010 => '800000', 
            1011 => '000000', 
            1012 => 'FF0000', 
            1013 => '000000', 
            1014 => '00FF00', 
            1015 => 'FF9900', 
            1016 => 'FF00FF', 
            1017 => '000000', 
            1018 => '000000', 
            1019 => '00FF00', 
            1020 => '000000', 
            1021 => '00FF00', 
            1022 => 'FF00FF', 
            1023 => '0000FF', 
            1024 => '0000FF', 
            1025 => 'FF0000', 
            1026 => '800080', 
            1027 => 'FF00FF', 
            1028 => 'FF9900', 
            1029 => 'FF0000', 
            1030 => 'FF00FF', 
            1031 => 'FF00FF', 
            1032 => '000080', 
            1033 => 'FF9900', 
            1034 => '0000FF', 
            1035 => '0000FF', 
            1036 => 'FF0000', 
            1037 => '0000FF', 
            1038 => '000000', 
            1039 => '000000', 
            1040 => '000000', 
            1041 => 'FF0000', 
            1042 => '00FF00', 
            1043 => '000000', 
            1044 => 'FF9900', 
            1045 => 'FF0000', 
            1046 => '0000FF', 
            1047 => 'FF0000', 
            1048 => 'FF0000', 
            1049 => '000000', 
            1050 => '000000', 
            1051 => '00FF00', 
            1052 => '0000FF', 
            1053 => '0000FF', 
            1054 => '00FF00', 
            1055 => 'FF0000', 
            1056 => 'FF0000', 
            1057 => 'FF9900', 
            1058 => 'FF0000', 
            1059 => '00FF00', 
            1060 => 'FF0000', 
            1061 => 'FF00FF', 
            1062 => '000000', 
            1063 => 'FF0000', 
            1064 => '800000', 
            1065 => 'FF9900', 
            1066 => '800000', 
            1067 => '800000', 
            1068 => 'FF9900', 
            1069 => '000080', 
            1070 => '0000FF', 
            1071 => 'FF9900', 
            1072 => 'FF9900', 
            1073 => '800000', 
            1074 => 'FF9900', 
            1075 => '800080', 
            1076 => '800080', 
        );

    /**
     * docomoの絵文字番号から、絵文字に置換する特殊変数を生成
     *
     * @param  integer $number
     * @return string  絵文字に置換する特殊変数
     * @throws InvalidArgumentException 引数のデータ型が期待値と一致しないとき
     */
    public static function create($number)
    {
        if (!is_integer($number)) {
            throw new InvalidArgumentException('Argument #1 must be an integer');
        }
        $variable = sprintf(self::EMOJI_FORMAT, $number);
        $color    = self::_getPictogramColor($number);
        if (!empty($color)) {
            $variable = sprintf(self::COLORING_TAG, $color, $variable);
        }

        return $variable;
    }

    /**
     * テキストの特殊変数をバイナリ絵文字に変換
     * 
     * <p>バイナリ絵文字に変換する変数は独自フォーマット<br />
     * "%emoji_{n}%"という書式で{n]は可変の絵文字番号</p>
     * 
     * @param  string $text バイナリ絵文字に変換するテキスト
     * @return string バイナリ絵文字に変換した文字列
     * @throws InvalidArgumentException 引数のデータ型が期待値と一致しないとき
     * @throws MobileException キャリアの絵文字データが取得できないとき
     * @throws MobileException ユーザエージェントのデータ操作に失敗したとき
     */
    public static function toBinary($text)
    {
        if (!is_string($text)) {
            throw new InvalidArgumentException('Argument #1 must be an string');
        }
        $pictograms = self::_getPictograms();

        return 
            preg_replace('/'.self::EMOJI_REGEXP.'/e', "@\$pictograms[\\1]", $text);
    }

    /**
     * フォント色が必要な絵文字であれば色指定を返却
     * 
     * @param  integer $id 絵文字ID
     * @return string  色指定、必要なければNULL
     * @throws InvalidArgumentException 引数のデータ型が期待値と一致しないとき
     */
    private static function _getPictogramColor($id)
    {
        if (!is_integer($id)) {
            throw new InvalidArgumentException('Argument #1 must be an integer');
        }

        $color = null;
        if (Mobile::isDocomo() && isset(self::$_docomoPictogramColors[$id])) {
            $color = self::$_docomoPictogramColors[$id];
        }

        return $color;
    }

    /**
     * ユーザエージェントのキャリアに応じた絵文字データを返却
     * 
     * <p>キャリアごとの絵文字データは、対応する絵文字バイナリの配列です。</p>
     * 
     * @return array キャリア絵文字データ
     * @throws MobileException キャリアの絵文字データ取得に失敗したとき
     */
    public static function _getPictograms()
    {
        $formatted_pictograms = array();
        try {
            $carrier   = self::_getCarrier();
            $pictogram = Text_Pictogram_Mobile::factory($carrier, 'sjis');
            $data      = $pictogram->getFormattedPictogramsArray('docomo');
            if (!empty($data)) {
                foreach ($data as $id => $emoji) {
                    $binary = '';
                    // 複合絵文字に対応
                    $strlen = mb_strlen($emoji, 'SJIS-win');
                    for ($i = 0; $i < $strlen; ++$i) {
                        $char = mb_substr($emoji, $i, 1, 'SJIS-win');
                        if ($pictogram->isPictogram($char)) {
                            $binary .= $char;
                        }
                    }
                    $formatted_pictograms[$id] = $binary;
                }
            }
        } catch (Text_Pictogram_Mobile_Exception $e) {
            throw new MobileException('Unable to get the formatted pictograms: ' . 
                                      $e->getMessage());
        }
        $pictogram = null;

        return $formatted_pictograms;
    }

    /**
     * ユーザエージェントのキャリアを返却
     * 
     * @return string キャリア（nonmobile|docomo|softbank|ezweb）
     */
    private static function _getCarrier()
    {
        $carrier = 'nonmobile';
        if (Mobile::isDoCoMo()) {
            $carrier = 'docomo';
        } elseif (Mobile::isSoftBank()) {
            $carrier = 'softbank';
        } elseif (Mobile::isEZweb()) {
            $carrier = 'ezweb';
        }

        return $carrier;
    }
}
