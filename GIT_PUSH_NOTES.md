# Git 推送流程筆記（Docker 化改動 → dev 分支）

## 情境

本機的 Docker 化修改（`db.php`、5 個 PHP 檔案、`Dockerfile`、`docker-compose.yml` 等）已經在本機測試成功，接下來要存進版本控制、推上 GitHub，避免電腦出狀況導致改動遺失。

當時的狀態：
- 本機已經在 `dev` 分支上
- 遠端 GitHub 只有 `main` 分支，還沒有 `dev`（`git branch -a` 看不到 `remotes/origin/dev`）

---

## 步驟一：確認目前狀態（先看，不動手）

```bash
git status
git branch -a
git remote -v
```

- `git status`：列出哪些檔案改了、哪些是新檔案（untracked）。**先看過一遍，確認沒有不該進版控的東西**（例如密碼、暫存檔）。
- `git branch -a`：確認目前在哪個分支，以及遠端有哪些分支存在。
- `git remote -v`：確認遠端倉庫網址是不是你預期的那個。

這次確認到一個關鍵細節：`.env`（含真實密碼）**沒有**出現在 `git status` 的 untracked 清單裡，因為前一步驟已經把它寫進 `.gitignore` 了——代表密碼不會被誤傳上 GitHub。

---

## 步驟二：只加入該加入的檔案（`git add`）

```bash
git add artist.php artist_paint.php customer.php painting.php search.php \
        db.php Dockerfile docker-compose.yml .env.example .gitignore \
        DOCKER_MIGRATION.md
```

**為什麼指名檔案，不用 `git add -A` 或 `git add .`**：
`-A`/`.` 會把當下資料夾所有改動（包含你沒注意到的檔案）全部加入，萬一有不該進版控的東西（`.env`、暫存檔、大型二進位檔）會被一起帶走。明確列出檔名，等於再檢查一次「這次到底要 commit 什麼」，比較安全。

加入之後可以再跑一次 `git status` 確認：
- `Changes to be committed` 底下列出的檔案，就是等一下會被 commit 進去的內容
- 如果看到不該出現的檔案，用 `git restore --staged <檔名>` 取消暫存

---

## 步驟三：建立 Commit

```bash
git commit -m "Dockerize database for local deployment

Replace hardcoded school-server DB connection with a shared db.php
that reads credentials from environment variables, and add
Dockerfile/docker-compose.yml to run PHP + MySQL containers with
automatic .sql import."
```

Commit message 的寫法：
- **第一行**：一句話摘要，做了什麼（不是列檔案清單，是講這次改動的目的）
- **空一行後的內容**：補充「為什麼」這樣改，方便未來自己或別人回頭看的時候理解動機，而不是只看到一堆檔案 diff

---

## 步驟四：推送到遠端（`git push`）

```bash
git push -u origin dev
```

- `origin`：遠端倉庫的名字（`git remote -v` 看到的那個）
- `dev`：要推送的分支名稱
- `-u`（等同 `--set-upstream`）：**只有第一次推某個新分支時需要加**。作用是把本機 `dev` 跟遠端 `origin/dev` 綁在一起，之後同一台電腦上只要打 `git push` / `git pull`（不用再打分支名）就知道要對應到哪個遠端分支。

因為遠端原本沒有 `dev` 分支，這個指令執行後 GitHub 會**自動建立**一個新的 `dev` 分支，並顯示一個建立 Pull Request 的連結（如果之後想把 `dev` 合併回 `main`，可以點那個連結開 PR，不用馬上做）。

---

## 步驟五：確認乾淨

```bash
git status
```

看到 `nothing to commit, working tree clean` 且 `Your branch is up to date with 'origin/dev'`，代表：
- 本機所有改動都已經進了 commit
- 本機 commit 都已經成功送到遠端，兩邊完全同步

---

## 這次流程的重點回顧

| 動作 | 目的 |
|---|---|
| 先 `git status` 再動手 | 確保清楚知道要 commit 什麼，不會誤帶敏感檔案 |
| 明確列檔名 `git add`，不用 `-A` | 避免不小心把不該進版控的檔案加進去 |
| Commit message 寫「為什麼」而不只是「做了什麼」 | 之後回頭看歷史紀錄才看得懂當初的動機 |
| `.gitignore` 先排除 `.env` | 密碼類的敏感資訊不該進版本控制系統，即使之後刪除，Git 歷史裡還是查得到 |
| `git push -u origin dev` | 第一次推新分支要建立本機↔遠端的追蹤關係，之後才能簡化成單純 `git push` |
