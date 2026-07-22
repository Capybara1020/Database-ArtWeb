# 資料庫容器化改造筆記

## 背景與問題

原本 5 個 PHP 檔案（`artist.php`、`painting.php`、`customer.php`、`search.php`、`artist_paint.php`）裡都各自寫死一行：

```php
$link = mysqli_connect('140.127.220.233','a1083305','a1083305Checkpoint7','a1083305');
```

`140.127.220.233` 是學校當時的 MySQL 主機 IP，現在已經不存在，所以網站連不上資料庫。目標是：
1. 用 Docker 建一個 MySQL 容器取代學校主機。
2. 把 `.sql` 資料重新匯入這個容器。
3. 讓 PHP 網頁能連得到這個新的資料庫。

---

## 步驟一：把連線資訊集中到一個檔案（`db.php`）

**問題**：5 個檔案各自寫死連線字串，之後只要 host/帳密變了就要改 5 個地方，很容易漏改。

**做法**：新增 `db.php`，用 `getenv()` 讀取環境變數，若沒有設定才 fallback 成預設值：

```php
<?php
$link = mysqli_connect(
    getenv('DB_HOST') ?: 'db',
    getenv('DB_USER') ?: 'a1083305',
    getenv('DB_PASS') ?: 'a1083305Checkpoint7',
    getenv('DB_NAME') ?: 'a1083305'
);

if (!$link) {
    die("資料庫連線失敗: " . mysqli_connect_error());
}
```

**為什麼用環境變數**：這樣同一份程式碼可以在不同環境（本機測試、正式上架）套用不同的帳密/host，不用改程式碼，也不用把密碼寫死進 git。

---

## 步驟二：修改 5 個 PHP 檔案

把每個檔案裡的 `$link = mysqli_connect(...)` 那一行，換成：

```php
require_once 'db.php';
```

`require_once` 會執行 `db.php` 裡的程式碼並把 `$link` 變數帶進來給後面的 SQL 查詢用，效果跟原本寫死連線是一樣的，只是連線邏輯只存在一個地方。

修改的檔案：
- `artist.php`
- `painting.php`
- `customer.php`
- `search.php`
- `artist_paint.php`

---

## 步驟三：撰寫 `Dockerfile`（PHP 執行環境）

```dockerfile
FROM php:8.2-apache

RUN docker-php-ext-install mysqli

COPY . /var/www/html/
```

逐行說明：
- `FROM php:8.2-apache`：用官方現成的 PHP 8.2 + Apache 映像檔當基底，不用自己從頭裝 Apache/PHP。
- `RUN docker-php-ext-install mysqli`：PHP 官方映像檔預設**不含** `mysqli` 擴充套件，程式裡用到 `mysqli_connect()`，所以要手動裝，不然會出現 `Call to undefined function mysqli_connect()`。
- `COPY . /var/www/html/`：把整個專案複製進容器的網站根目錄（Apache 預設會從這裡讀網頁）。

---

## 步驟四：撰寫 `docker-compose.yml`（兩個容器怎麼串起來）

```yaml
services:
  db:
    image: mysql:5.7
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASS}
      MYSQL_DATABASE: ${DB_NAME}
      MYSQL_USER: ${DB_USER}
      MYSQL_PASSWORD: ${DB_PASS}
    volumes:
      - db_data:/var/lib/mysql
      - ./A1083305_checkpoint7.sql:/docker-entrypoint-initdb.d/init.sql:ro
    ports:
      - "3306:3306"

  web:
    build: .
    restart: unless-stopped
    depends_on:
      - db
    environment:
      DB_HOST: db
      DB_USER: ${DB_USER}
      DB_PASS: ${DB_PASS}
      DB_NAME: ${DB_NAME}
    ports:
      - "8080:80"
    volumes:
      - ./:/var/www/html

volumes:
  db_data:
```

幾個關鍵設計：

| 設定 | 說明 |
|---|---|
| `image: mysql:5.7` | 沿用跟原本資料庫相近的版本（原 dump 檔是 5.7.33），避免版本落差造成語法不相容 |
| `MYSQL_DATABASE` / `MYSQL_USER` / `MYSQL_PASSWORD` | MySQL 官方映像檔的內建機制：容器第一次啟動時，會自動建立這個資料庫和這個使用者，不用手動 `CREATE DATABASE` |
| `./A1083305_checkpoint7.sql:/docker-entrypoint-initdb.d/init.sql` | MySQL 官方映像檔的另一個內建機制：**容器第一次啟動、資料庫是空的時候**，會自動執行 `/docker-entrypoint-initdb.d/` 目錄下的 `.sql` 檔案，等於自動幫你 import 資料，不用手動下指令匯入 |
| `db_data` volume | 把資料庫檔案存到 Docker 的具名 volume，這樣容器重啟/重建時資料不會不見（只有整個 volume 被砍掉資料才會消失） |
| `web` 的 `DB_HOST: db` | **這是最關鍵的一行**：`db` 是 compose 裡 MySQL 那個 service 的名字。Docker Compose 會自動幫同一個 compose 專案裡的容器建立內部網路，容器之間可以直接用 service 名稱互相連線（DNS 解析），完全不需要知道實際 IP，這也是為什麼不用再寫死 `140.127.220.233` 這種 IP |
| `depends_on: db` | 確保 `web` 容器會在 `db` 容器**啟動之後**才開始啟動（但不保證 MySQL 已經準備好接受連線，只是保證容器順序） |
| `web` 的 `volumes: ./:/var/www/html` | 把本機專案資料夾直接掛進容器，這樣改程式碼不用重新 build image 就能生效，方便開發階段測試。**正式上架時建議拿掉這行**，改成完全依賴 `Dockerfile` 的 `COPY`，這樣才是「這個 image 打包了完整、固定版本的程式碼」，比較符合正式環境的做法 |

---

## 步驟五：環境變數與敏感資訊管理

新增 `.env.example`（放進 git，當範本）：

```
DB_ROOT_PASS=changeme_root_password
DB_NAME=a1083305
DB_USER=a1083305
DB_PASS=a1083305Checkpoint7
```

複製一份成 `.env`（實際使用的檔案，`docker-compose.yml` 裡的 `${DB_NAME}` 這種語法會自動從 `.env` 讀值）。

新增 `.gitignore`：
```
.env
```

**為什麼要這樣分兩個檔案**：`.env` 裡通常會放正式環境的真實密碼，一旦傳到 GitHub 上就等於公開密碼。`.env.example` 只放範例值，讓其他人（或你自己重灌電腦後）知道需要哪些變數，但不含真正機密。

---

## 測試步驟

### 1. 驗證設定檔語法（不會真的啟動容器）

```bash
docker compose config
```
這個指令會把 `docker-compose.yml` + `.env` 合併解析後印出來，可以檢查變數有沒有正確代入、YAML 格式有沒有寫錯。

### 2. Build image 並啟動所有容器

```bash
docker compose up -d --build
```
- `--build`：強制重新 build `web` 的 image（改了 Dockerfile 或第一次跑一定要加）
- `-d`：background 執行，不佔用終端機

### 3. 確認容器都在跑

```bash
docker compose ps
```
要看到 `db` 跟 `web` 兩個 service 的 STATUS 都是 `Up`。

### 4. 看資料庫容器的啟動 log，確認初始化完成

```bash
docker compose logs db --tail 30
```
看到 `mysqld: ready for connections` 就代表 MySQL 已經啟動完成、可以接受連線了。

### 5. 直接進資料庫容器查資料，確認 `.sql` 真的匯入成功

```bash
docker compose exec db mysql -u a1083305 -pa1083305Checkpoint7 a1083305 -e "SHOW TABLES; SELECT COUNT(*) FROM Artist;"
```
- `docker compose exec db ...`：在 `db` 這個正在跑的容器裡執行指令
- 預期會看到 `Artist`、`Artwork`、`Customer` 等資料表，以及 `Artist` 有 20 筆資料

### 6. 測試網頁本身能不能正常運作

```bash
curl -s -o /dev/null -w "HTTP狀態碼: %{http_code}\n" http://localhost:8080/homepage.php
curl -s "http://localhost:8080/artist.php" | grep -o "tm-person-name" | wc -l
```
第一行確認網頁回應 200；第二行確認 `artist.php` 頁面裡真的渲染出了 20 筆藝術家（跟資料庫筆數對上，證明 PHP 有成功連上資料庫並撈到資料，不是空白頁或連線失敗）。

也可以直接打開瀏覽器看 `http://localhost:8080/homepage.php` 用眼睛確認畫面正常。

---

## 常用維運指令

| 指令 | 用途 |
|---|---|
| `docker compose down` | 停止並移除容器，但**保留** `db_data` volume，資料不會不見 |
| `docker compose down -v` | 連同 volume 一起刪除，等於資料庫**清空重來**（改了 `.sql` 檔想重新匯入時會用到，因為初始化腳本只在資料庫是空的時候才會執行） |
| `docker compose logs web --tail 50` | 看 PHP/Apache 容器的 log，排查網頁錯誤用 |
| `docker compose restart web` | 只重啟網頁容器，不動資料庫 |

---

## 概念總結（為什麼要這樣設計）

1. **不要把連線資訊寫死在程式碼裡** → 集中到 `db.php`，用環境變數注入，換環境不用改程式碼。
2. **容器之間用 service name 溝通，不要用 IP** → Docker Compose 自動處理內部 DNS，`DB_HOST=db` 永遠有效，不會因為換機器、換 IP 就失效。
3. **資料庫初始化交給 MySQL image 內建機制**（`MYSQL_DATABASE` + `docker-entrypoint-initdb.d/`）→ 不用手動下 `mysql -u root -p < xxx.sql` 這種指令，容器一啟動就自動處理好。
4. **機密資訊（密碼）跟設定檔（compose/Dockerfile）分開，並排除在 git 之外** → 避免帳密外洩到版本控制系統。
5. **本機開發用 volume mount 方便改完即生效，正式上架則應該讓 image 自帶完整程式碼**，兩者的取捨在於「方便性」vs「環境一致性/可重現性」。
