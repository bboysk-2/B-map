
![スクリーンショット 2024-05-13 193720](https://github.com/bboysk-2/B-map/assets/140153753/51a5e682-215a-4a94-80e8-ccd24749ca22)

## サービス[URL](https://b-map-749a2d979506.herokuapp.com/)
## サービス概要
**ブレイクダンスの練習可能なスポットを共有するサービス**

## 開発したきっかけ
私は現在歴12年ほどのブレイクダンス愛好家なのですが、活動の中で長年悩みの種となっていたのが“練習場所探し問題”です。ブレイクダンスはその性質上、床の滑り具合やキャパシティに大きく左右されるため、練習場所がかなり絞られてしまう実情があります。特に初心者の頃は、情報を共有できる仲間もおらず血眼で練習場所を探したという経験はブレイクダンサーなら誰しも通る道。それなら練習場所をweb上で共有できる仕組みを作ったらいいのでは？という思いから「B-MAP」を開発するに至りました。

## ターゲットユーザー
- 身の周りでダンサーの繋がりがほとんどない方（ダンス初心者、越してきた方）
- できるだけお金がかからず自分のタイミングで気軽に練習できる環境を探している方

**<details><summary>ユーザーが抱える問題</summary>**
    
    ブレイクダンサーなら誰しも一度は経験したであろう問題
    
    ・「ブレイクダンスは広さや床の材質に大きく左右されるため、ブレイクダンスに適した練習場所がなかなか見つからない」
    
    ・「ブレイクダンスに適した広さのスタジオを一人で予約しようものなら料金が嵩んでしまう」

    ・「練習場所は身近にあるが、自分の練習したいタイミングで使用できない（予約制の施設など）」
</details>
    
**<details><summary>このサービスで解決できること</summary>**
    
    「B-MAP」は、上記のような問題に対応すべくブレイクダンスの練習場所探しに特化したサービスとなっております。
    
    -----------------------------------------

    このサービスではユーザー同士が自分のおススメする練習場所を自由に投稿することができます。その際、複数の設定項目から
    その練習場所に関する詳細情報を設定することが可能です。
    「床の材質」、「広さ」、「料金」、「使用可能な時間帯」、「予約の有無」等の練習場所を探す上では欠かせない情報を設定し、
    閲覧ユーザーはそれらの情報から自分の理想に合った練習場所を探すことが可能です。
  
    -----------------------------------------
    
</details>

## 主な機能
#### 【すべてのユーザー】
- スポット検索機能
    - Google Map上で投稿されたスポットを表示
    - リストで投稿されたスポット一覧表示
    - ワード検索
 
- 認証機能
    - メールアドレスでのログイン機能
    - ソーシャルログイン機能

#### 【登録ユーザーのみ】
- スポットをレビュー
    - 星5段階の評価、コメント

- 新しいスポットを追加
    - 住所、スポット名、※以上必須項目　スポットカテゴリー(駅、体育館、スタジオetc...)、床の材質、床の滑り、広さ、料金、利用可能な時間帯、
    音出し、予約の有無、備考、スポット画像の項目が設定可能

- スポットをお気に入りに追加

- マイページの編集
    - 自分の投稿したスポットおよびレビューコメントの編集削除
    - ユーザネーム、メールアドレス、パスワード、プロフィール画像の編集
    - アカウントの削除


## 使用技術
### バックエンド
- Laravel 9.52.16
- PHP 8.0.30
- Laravel Breeze

### フロントエンド
- Vue.js 3.2.41
- Tailwindcss

### インフラ
- Docker(開発環境)
- Heroku(デプロイ)

### サードパーティAPI
- Google API
  - Geocoding API
  - Maps JavaScript API


## 画面遷移図
URLは[こちら](https://www.figma.com/file/xmpQV2hp7EkaM1jdLY8d8W/B-MAP?type=design&node-id=0%3A1&mode=design&t=TQQQbFtUOsM9izlH-1)

## ER図
![B-MAP ER 図 ](https://github.com/bboysk-2/B-map/assets/140153753/5541164f-4cbd-4089-9923-178e8a7c0250)


## 使用しているアイコン素材のダウンロード元
- [ICOOON-MONO](https://icooon-mono.com/)
- [Icon-rainbow](https://icon-rainbow.com/)
- [icon-icons](https://icon-icons.com/)
