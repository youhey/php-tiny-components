◇◇
◇このライブラリは？
--------------------------------------------------------------------------
モバイルサイト（ガラケーXHTML）に対応するためのライブラリ群です。

◇◇
◇依存関係は？
--------------------------------------------------------------------------
PEARライブラリの「Net_UserAgent_Mobile」に依存しています。
> $ pear install Net_UserAgent_Mobile

HTTPResponseというライブラリと組み合わせると、便利になるかもしれません。

【1】Mobileクラス
（携帯電話の）デバイス情報を取得します。
PEARライブラリの「Net_UserAgent_Mobile」をラッピングしています。

- PHP5.2系に特化して、静的な機能呼び出しで小回りが利きます。
- docomo固有の契約情報を取得する機能を実装しています。

> <?php if (Mobile::isDocomo()) : ?>
>     あなたはdocomoユーザです。
>     <?php if (!Mobile::isPakehoContract()) : ?>
>         ﾊﾟｹﾎｰﾀﾞｲ！の契約が確認できないので、高額に……。
>     <?php endif ?>
> <?php endif ?>


【2】Mobile_XHTMLクラス
携帯電話のXHTML対応に必要な、DOCTYPE宣言の出力などに対応します。

【3】Mobile_Emojiクラス
3キャリアの絵文字出力に対応します。
絵文字はdocomo絵文字を基準に、その絵文字番号で指定します。
出力はキャリアごとのバイナリ（シフトJIS符合）で出力します。
自動でdocomoの絵文字に<SPAN>タグで色付けをしたりします。

変換のためのマーカー作成と、バイナリに変換する機能に分かれています。

> <?php echo Mobile_Emoji::toBinary(Mobile_Emoji::create(1)) ?>
