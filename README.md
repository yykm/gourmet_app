<h1 align="center" style="color: cornflowerblue">
Gourmet
</h1>

## Gourmetについて

 [Gourmet](https://gourmet-search.work)： ぐるな〇のようなグルメ情報サイトを模倣した転職活動用ポートフォリオ。

* フリーワード・現在地より範囲を指定して飲食店
* 写真投稿
* 口コミ投稿
* お気に入り
* 予約（開発環境のみ予約確認メール送信機能）
* Google Mapより店舗所在地の確認

などの機能を疑似的に実装しました。

## 簡易的な設計図

* [ネットワーク・インフラ設計図](https://gourmet-doc.s3-ap-northeast-1.amazonaws.com/gourmet-network.png)
* [ER図](https://gourmet-doc.s3-ap-northeast-1.amazonaws.com/gourmet-ER.png)
* [テーブル一覧](https://gourmet-doc.s3-ap-northeast-1.amazonaws.com/gourmet-tables.html)
* [機能一覧](https://gourmet-doc.s3-ap-northeast-1.amazonaws.com/gourmet-features.html)

## フォルダ構成

#### フロントエンド
<p align="left"><a href="https://gourmet-doc.s3-ap-northeast-1.amazonaws.com/gourmet-tree-frontend.png" target="_blank"><img src="https://gourmet-doc.s3-ap-northeast-1.amazonaws.com/gourmet-tree-frontend.png" width="480" height="878"></a></p>


#### バックエンド
<p align="left"><a href="https://gourmet-doc.s3-ap-northeast-1.amazonaws.com/gourmet-tree-backend.png" target="_blank"><img src="https://gourmet-doc.s3-ap-northeast-1.amazonaws.com/gourmet-tree-backend.png" width="631"　height="952"></a></p>


## ポートフォリオの技術的な構成要素
【言語】
PHP 7.3 / HTML5 and CSS3/ JavaScript

【フレームワーク】
Laravel 6.x / Vue.js / BootStrap4

【バージョン管理】
Git Hub

【インフラ・ネットワーク】
Webサーバ：AWS EC2(Amazon Linux2 / Apache)
DBサーバ：AWS EC2(Amazon Linux2 / MariaDB)
メールサーバ：AWS EC2(Amazone Linux2 / Postfix) *開発環境のみ

VPC(10.0.0.0/16) のうち、

公開サブネット(10.0.1.0/24)にWebサーバを配置

非公開サブネット(10.0.2.0/24)にDBサーバを配置

非公開サブネットに関してはNATを通して外側への通信のみ許可


またALBを通して通信をSSL化、

写真投稿機能で容量が大きい画像ファイルなどはS3に保存する...など

## 各種連携API
- [ Maps Javascript API (Google Map)](https://cloud.google.com/maps-platform/?hl=ja&utm_source=google&utm_medium=cpc&utm_campaign=FY18-Q2-global-demandgen-paidsearchonnetworkhouseads-cs-maps_contactsal_saf&utm_content=text-ad-none-none-DEV_c-CRE_320617583946-ADGP_Hybrid%20%7C%20AW%20SEM%20%7C%20BKWS%20~%20Google%20Maps%20API%20EXA-KWID_43700039913979214-aud-596763661393%3Akwd-335425467-userloc_1009332&utm_term=KW_google%20maps%20api-ST_google%20maps%20api&gclid=Cj0KCQiApsiBBhCKARIsAN8o_4iJEghPcMLW0qe5zH1HRW7cfpa55dZ2z9lH8QgGyxJuLNqDzK38mAkaAgzhEALw_wcB).
- [ホットペッパーグルメサーチAPI](https://webservice.recruit.co.jp/doc/hotpepper/reference.html)


## 追記

リポジトリを公開する前提が無かったため、かなりコミットやそのメッセージ等が大まかなのはご了承いただけますと幸いです。
