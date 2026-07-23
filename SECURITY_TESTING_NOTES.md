# 資安測試筆記

這份筆記記錄這次怎麼找出並驗證修復了 `search.php` 和 `artist_paint.php` 的漏洞。目的是讓你之後自己也能重複測試，或套用到其他專案上。

---

## 背景：發現問題的過程

先用 `Read`/`Grep` 把所有會接觸 `$_GET`、`$_POST` 的地方找出來看：

```bash
grep -rn "_GET\[\|_POST\[" *.php
```

原則很簡單：**任何直接來自使用者輸入（網址參數、表單）、又被直接拼進 SQL 字串或直接印回 HTML 的地方，都是可疑點。** 逐一檢查後鎖定兩個檔案：

- `artist_paint.php`：`$_GET['id']` 直接拼進 SQL，也直接印進 HTML
- `search.php`：`$_POST['keyword']` 直接拼進 3 個 SQL 查詢，也直接印進 HTML

---

## 測試一：SQL Injection

### 概念

如果程式碼長這樣：
```php
$key = $_POST['keyword'];
$SQL = "SELECT * FROM Artist WHERE aname like '%$key%'";
```
使用者輸入的內容會**原封不動**變成 SQL 指令的一部分。正常輸入像 `Picasso` 沒問題，但如果輸入的內容裡包含 SQL 的特殊符號（單引號 `'`、`OR`、`UNION` 等），就可能讓查詢邏輯被竄改。

### 修法前：怎麼證明漏洞存在

最簡單的驗證方式是送出一個**只包含單引號**的輸入，觀察網站有沒有噴出資料庫錯誤訊息（代表你的輸入真的被當成 SQL 語法解析，而不是單純的文字）：

```bash
curl -s -X POST --data-urlencode "keyword=Picasso'" "http://localhost:8080/search.php" | grep -i "sql syntax\|mysqli"
```
如果看到 `You have an error in your SQL syntax` 這類訊息，就代表輸入被當成 SQL 指令的一部分執行了，漏洞成立（這正是 artist_paint.php 一開始意外曝露出來的那種錯誤畫面）。

### 修法後：怎麼驗證已經修好

改用 `mysqli_real_escape_string()` 把輸入裡的特殊字元（單引號等）自動加上反斜線跳脫，讓它變回「純文字」，不會被解讀成 SQL 語法。驗證方式：送出同樣帶有攻擊意圖的輸入，確認：
1. 網頁**正常回應**（HTTP 200），沒有噴錯誤
2. 沒有任何資料異常外洩的跡象

```bash
curl -s -X POST --data-urlencode "keyword=%' OR '1'='1" "http://localhost:8080/search.php" \
  -o /tmp/inject_test.html -w "HTTP狀態碼: %{http_code}\n"

grep -o "Fatal error\|Warning\|SQL syntax" /tmp/inject_test.html
```
預期結果：HTTP 200，且 `grep` 找不到任何錯誤字樣（代表輸入被當成單純文字去搜尋，自然搜尋不到東西，而不是讓 SQL 語法被竄改）。

### 瀏覽器手動測試（更直覺）

1. 打開 `http://localhost:8080/homepage.php`
2. 在右上角搜尋框輸入：`Picasso' OR '1'='1`
3. 按下搜尋
4. **修復前**：可能看到頁面出現一堆不相關的資料被撈出來（因為 `OR '1'='1'` 讓 WHERE 條件永遠成立），或是直接看到 PHP 錯誤訊息
5. **修復後**：應該只會看到「搜尋不到符合『Picasso' OR '1'='1』的結果」這種正常的空結果畫面

---

## 測試二：反射型 XSS（Cross-Site Scripting）

### 概念

如果程式碼長這樣：
```php
echo "<p>有關「".$key."」的所有搜尋結果</p>";
```
使用者輸入的內容會**原封不動**變成 HTML 的一部分。如果輸入的是一段 `<script>` 標籤，瀏覽器會直接執行它。

### 怎麼測試

送出一段無害但可觀察的 payload，例如 `<script>alert(1)</script>` 或更簡單的 `<b>test</b>`，看它是被瀏覽器「執行/渲染」，還是被當成純文字顯示出來：

```bash
curl -s -X POST --data-urlencode "keyword=<b>xsstest</b>" "http://localhost:8080/search.php" | grep -o "<b>xsstest</b>\|&lt;b&gt;xsstest&lt;/b&gt;"
```

- 如果看到輸出是 **`&lt;b&gt;xsstest&lt;/b&gt;`**（尖括號被轉成 HTML 實體）→ 代表 `htmlspecialchars()` 生效，安全，瀏覽器只會顯示一串純文字 `<b>xsstest</b>`，不會真的把文字變粗體或執行任何程式碼
- 如果看到輸出是 **`<b>xsstest</b>`** 原封不動 → 代表沒有跳脫，瀏覽器會真的把它當 HTML 執行，是漏洞

### 瀏覽器手動測試

1. 在搜尋框輸入：`<script>alert('xss')</script>`
2. 送出搜尋
3. **修復前**：瀏覽器會跳出一個 alert 彈窗（證明你的輸入被當成可執行的程式碼）
4. **修復後**：畫面上應該會**照字面**顯示出 `<script>alert('xss')</script>` 這串文字，不會有任何彈窗跳出來

---

## 測試三：錯誤訊息是否外洩內部資訊

### 概念

PHP 預設（尤其是 Docker 官方 image）在發生錯誤時，會把完整的**伺服器內部檔案路徑**、**SQL 語法片段**、**程式呼叫堆疊**直接印到網頁上給訪客看。這對攻擊者來說等於白送情報（知道你的檔案結構、資料庫查詢邏輯），對面試官來說也觀感不佳。

### 怎麼測試：故意觸發一個錯誤

最簡單的方式是**訪問一個需要參數、但故意不帶參數**的頁面：

```bash
curl -s "http://localhost:8080/artist_paint.php" | grep -o "Warning\|Fatal error\|/var/www/html"
```
- **修復前**：會看到類似 `Warning: Undefined array key "id" in /var/www/html/artist_paint.php on line 58` 這種訊息，暴露了容器內部的實際檔案路徑
- **修復後**（`Dockerfile` 裡設定 `display_errors = Off`）：畫面上什麼錯誤訊息都不會顯示（但錯誤還是有被記錄下來，只是記到 log 裡給你自己看，不會顯示給訪客）

### 如果想確認錯誤真的有被記錄（而不是憑空消失）

```bash
docker compose logs web --tail 20
```
應該還是能在 log 裡看到對應的 PHP Warning，代表只是「不顯示在網頁上」，不是「完全沒被記錄」，之後排查問題還是找得到線索。

---

## 測試四：確認修復沒有破壞原本功能（迴歸測試）

改完資安漏洞後，很容易不小心把正常功能也改壞（例如把使用者輸入跳脫得太徹底，導致正常的中文名字、空格都查不到）。所以每次修完都要用**正常輸入**再測一次：

```bash
curl -s -X POST -d "keyword=Picasso" "http://localhost:8080/search.php" | grep -o "tm-person-name\|tm-gallery-title" | sort | uniq -c
```
預期：搜尋「Picasso」還是要能正常搜到 Pablo Picasso 本人的資料，以及他的畫作——證明跳脫機制沒有誤傷正常搜尋。

---

## 測試方法總結表

| 要測的東西 | 送出的內容 | 修復前的異常現象 | 修復後的正常現象 |
|---|---|---|---|
| SQL Injection | 輸入含 `'`、`OR '1'='1` 等 | 資料庫錯誤訊息、或撈出不該出現的資料 | 正常回應、當作純文字搜尋（通常查無資料） |
| XSS | 輸入 `<script>alert(1)</script>` | 瀏覽器跳出 alert，代表程式碼被執行 | 畫面上原樣顯示這串文字，不會執行 |
| 錯誤訊息外洩 | 訪問缺少必要參數的頁面 | 顯示內部檔案路徑、SQL 片段 | 畫面乾淨，log 裡還查得到 |
| 迴歸測試 | 正常關鍵字（如 `Picasso`） | — | 功能沒有被誤傷，結果一樣正確 |

## 通用心法

1. **凡是使用者能控制的輸入（`$_GET`、`$_POST`、cookie、header），進 SQL 前一定要跳脫**（`mysqli_real_escape_string()`，或更好的做法是改用 prepared statement）。
2. **凡是要印回 HTML 的動態內容，一定要用 `htmlspecialchars()`**，不管資料來源是使用者輸入還是資料庫。
3. **正式環境永遠關閉 `display_errors`**，錯誤只記錄到 log，不顯示給訪客。
4. 測試漏洞的邏輯永遠是「送一個刻意搞怪的輸入 → 看系統的反應像不像被騙了」；測完漏洞也要送一個正常輸入，確認沒有把好的功能也弄壞。
