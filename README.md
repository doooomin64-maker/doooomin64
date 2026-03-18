# CRUD対応ポートフォリオサイト（HTTP + CSS + JavaScript + PHP）

Camper のヒストリーページのような「スクロールで魅せる構成」を参考にした、
PHPでCRUD APIを持つポートフォリオサイトです。

## 技術スタック

- HTTP: PHP built-in server（`php -S`）
- フロント: HTML + CSS + JavaScript
- バックエンド: PHP（`api.php`）
- データ保存: JSONファイル（`data/projects.json`）

## 実装した内容

- ヘッダー
  - 現在閲覧中セクション（00 TIMELINE / 01 PORTFOLIO）表示
  - スクロール進捗バー表示
- 00 TIMELINE
  - 左：説明カード（`position: sticky`）
  - 右：画像プレースホルダー縦スクロール
  - スクロール位置に応じて背景に年代を表示
- 01 PORTFOLIO（CRUD）
  - Create / Read / Update / Delete
  - カードクリックで `detail.php` に遷移
  - PM視点の詳細を表示

## API仕様（`api.php`）

- `GET /api.php` : 全件取得
- `GET /api.php?id=xxx` : 1件取得
- `POST /api.php` : 作成
- `PUT /api.php?id=xxx` : 更新
- `DELETE /api.php?id=xxx` : 削除

### POST/PUT 例

```json
{
  "title": "新規PJ",
  "year": "2026",
  "summary": "要約",
  "body": "詳細説明",
  "tags": ["要件定義", "進行管理"]
}
```

## ファイル構成

- `index.php` : メインページ（Timeline + Portfolio CRUD）
- `detail.php` : プロジェクト詳細ページ
- `api.php` : CRUD API
- `data/projects.json` : 永続データ
- `styles.css` : 全体スタイル
- `script.js` : メインページ挙動
- `detail.js` : 詳細ページ表示
- `reset.css` : CSSリセット

## 起動方法

```bash
php -S 0.0.0.0:8000
```

ブラウザで `http://localhost:8000/index.php` を開いてください。
