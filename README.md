# Database-ArtWeb｜美術館資訊檢索系統

🔗 **Live Demo：[https://ericaartweb.duckdns.org](https://ericaartweb.duckdns.org)**

本專題以美術館資料庫為案例，內容分為四大部分：

1. 設計並驗證 ER 模型的邏輯合理性
2. 繪製 ER 模型
3. 將 ER Model 轉換為 Relational Model
4. 根據 Relational Model 建置資料庫，開發整合資料庫的網頁，實現瀏覽與檢索功能

在開發過程中，先學習如何規劃 ERD 繪製邏輯，並將正規化及實體間的關聯性納入考量。網頁實作部分，結合 SQL 語法與網頁開發語言（PHP、CSS、jQuery），成功提取並呈現資料，打造一個可供使用者瀏覽與搜尋的互動式資料庫網站。

原始開發完成後，另外重新整理了整套環境，把它從一個只能在校內伺服器執行的作業，改造成可以獨立部署、對外公開存取的網站，過程中也一併修補了原本存在的資安漏洞（詳見下方〈資安〉章節）。

---

## 畫面截圖

- 美術作品資訊

<img width="1117" height="897" alt="image" src="https://github.com/user-attachments/assets/9f7c963d-6820-41d1-a1da-d4927b454a2c" />


- 作者資訊

<img width="1120" height="742" alt="image" src="https://github.com/user-attachments/assets/4b5024f7-8e53-42ba-80db-03ac3e01d19c" />


- 搜尋資訊（以 Pablo 為例）

<img width="638" height="358" alt="image" src="https://github.com/user-attachments/assets/ebc81afa-ca91-4875-a0ba-556975700a45" />
<img width="780" height="696" alt="image" src="https://github.com/user-attachments/assets/3a0181b1-d5c4-440d-a530-00ad7b76fcb6" />


---

## 功能

| 頁面 | 說明 |
|---|---|
| `homepage.php` | 首頁介紹 |
| `artist.php` | 藝術家列表，點擊姓名可查看該藝術家的作品 |
| `artist_paint.php` | 特定藝術家的作品列表 |
| `painting.php` | 全站畫作列表 |
| `customer.php` | 顧客資訊與其偏好的藝術家/類型 |
| `search.php` | 跨藝術家 / 顧客 / 畫作的關鍵字搜尋 |

## 使用技術

- **後端**：PHP 8.2 + Apache（`mysqli`）
- **資料庫**：MySQL 5.7
- **前端**：HTML / CSS / jQuery
- **容器化**：Docker、Docker Compose
- **HTTPS**：Caddy 反向代理，自動申請並續約 Let's Encrypt 憑證
- **部署環境**：Oracle Cloud Always Free VM（Ubuntu 22.04）

## 系統架構

```
使用者
  │  HTTPS (443)
  ▼
Caddy（反向代理，自動 HTTPS）
  │  內部網路
  ▼
web 容器（PHP 8.2 + Apache）
  │  內部網路
  ▼
db 容器（MySQL 5.7）
```

本機開發環境不會啟動 Caddy（見下方設定說明），直接透過 `web` 容器對外的 port 存取。

---

## 本機開發環境設定

需求：已安裝 [Docker](https://www.docker.com/products/docker-desktop/) 與 Docker Compose。

```bash
# 1. Clone 專案
git clone https://github.com/Capybara1020/Database-ArtWeb.git
cd Database-ArtWeb

# 2. 建立環境變數檔案
cp .env.example .env
# 依需求修改 .env 內的資料庫帳密

# 3. 建置並啟動容器（第一次或改動 Dockerfile 後要加 --build）
docker compose up -d --build
```

啟動後：
- 網站：http://localhost:8080
- MySQL：`localhost:3306`（帳密見 `.env`）

資料庫會在第一次啟動時，自動從 `A1083305_checkpoint7.sql` 匯入資料表與初始資料。

常用指令：

```bash
docker compose ps              # 查看容器狀態
docker compose logs web        # 查看網頁伺服器 log
docker compose down            # 停止並移除容器（資料庫資料會保留）
docker compose down -v         # 連同資料庫資料一起清除，下次啟動會重新匯入
```

---

## 專案結構

```
Database-ArtWeb/
├── artist.php              # 藝術家列表頁
├── artist_paint.php        # 單一藝術家作品頁
├── painting.php            # 畫作列表頁
├── customer.php            # 顧客資訊頁
├── search.php              # 搜尋頁
├── homepage.php            # 首頁
├── index.php                # 根目錄導向 homepage.php
├── db.php                   # 共用資料庫連線（讀取環境變數）
├── A1083305_checkpoint7.sql # 資料庫結構與初始資料
├── Dockerfile                # PHP + Apache image 設定
├── docker-compose.yml        # db / web / caddy 服務定義
├── Caddyfile                 # 正式環境的反向代理與 HTTPS 設定
├── .env.example               # 環境變數範本
├── css/ js/ img/ webfonts/    # 靜態資源
```

---

## 資安

原始版本的程式碼直接把資料庫連線資訊寫死在程式碼裡，且部分頁面的使用者輸入（網址參數、表單）未經處理就直接組進 SQL 查詢與輸出到 HTML。重新整理環境的過程中一併處理了以下問題：

- **SQL Injection**：`search.php`、`artist_paint.php` 原本的查詢語法直接拼接使用者輸入，已改用 `mysqli_real_escape_string()` 跳脫
- **反射型 XSS**：使用者輸入回顯到頁面前，統一加上 `htmlspecialchars()`
- **錯誤訊息外洩**：正式環境關閉 `display_errors`，避免內部檔案路徑、SQL 片段暴露給訪客
- **機密資訊管理**：資料庫帳密改用環境變數（`.env`，未進版控），本機與正式環境使用不同組密碼

---

## 部署

正式環境部署在 Oracle Cloud Always Free VM，透過 Docker Compose 啟動與本機相同的服務（`db`、`web`），額外加上 `caddy` 服務處理 HTTPS：

```bash
docker compose --profile prod up -d --build
```

`caddy` 服務僅在加上 `--profile prod` 時才會啟動，本機開發不受影響。
