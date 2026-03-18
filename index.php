<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Portfolio Timeline</title>
    <link rel="stylesheet" href="reset.css" />
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <header class="site-header">
      <div class="header-top">
        <h1>My History & Portfolio</h1>
        <div class="section-indicator">
          <span class="label">SECTION</span>
          <span id="currentSection">00 TIMELINE</span>
        </div>
      </div>
      <nav class="header-nav" aria-label="セクションナビゲーション">
        <a href="#timeline" data-section="00 TIMELINE">00 TIMELINE</a>
        <a href="#portfolio" data-section="01 PORTFOLIO">01 PORTFOLIO</a>
      </nav>
      <div class="progress-wrap" aria-hidden="true">
        <div id="scrollProgress" class="progress-bar"></div>
      </div>
    </header>

    <main>
      <section id="timeline" class="section timeline-section">
        <p class="section-no">00</p>
        <h2>TIMELINE</h2>
        <p class="section-lead">来歴・価値観・転機を紹介するセクション（仮）</p>

        <div class="timeline-bg-year" id="timelineBgYear" aria-hidden="true">2016</div>

        <div class="timeline-layout">
          <div class="timeline-text sticky-panel">
            <article id="timelineTextCard" class="text-card"></article>
          </div>

          <div class="timeline-media" id="timelineMedia"></div>
        </div>
      </section>

      <section id="portfolio" class="section portfolio-section">
        <p class="section-no">01</p>
        <h2>PORTFOLIO</h2>
        <p class="section-lead">PM力が伝わるプロジェクト一覧（CRUD可能）</p>

        <div class="portfolio-grid">
          <section class="portfolio-form-wrap">
            <h3>プロジェクトを追加 / 更新</h3>
            <form id="projectForm" class="project-form">
              <input type="hidden" id="projectId" />

              <label for="projectTitle">タイトル</label>
              <input id="projectTitle" name="title" required maxlength="80" />

              <label for="projectYear">年</label>
              <input id="projectYear" name="year" required maxlength="10" placeholder="2025" />

              <label for="projectSummary">要約（カード表示）</label>
              <textarea id="projectSummary" name="summary" required maxlength="180"></textarea>

              <label for="projectBody">詳細（PM視点の説明）</label>
              <textarea id="projectBody" name="body" required rows="6"></textarea>

              <label for="projectTags">タグ（カンマ区切り）</label>
              <input id="projectTags" name="tags" placeholder="要件定義, 進行管理" />

              <div class="form-actions">
                <button type="submit" class="btn btn-primary">保存</button>
                <button type="button" id="cancelEdit" class="btn">編集をキャンセル</button>
              </div>
            </form>
            <p id="flashMessage" class="flash-message" aria-live="polite"></p>
          </section>

          <section class="portfolio-list-wrap">
            <div class="list-head">
              <h3>プロジェクト一覧</h3>
              <p id="projectCount"></p>
            </div>
            <ul id="projectList" class="project-list"></ul>
          </section>
        </div>
      </section>
    </main>

    <template id="projectItemTemplate">
      <li class="project-item">
        <button class="card-open" type="button">
          <p class="project-year"></p>
          <h4 class="project-title"></h4>
          <p class="project-summary"></p>
          <p class="project-tags"></p>
        </button>
        <div class="item-actions">
          <button class="btn edit-btn" type="button">編集</button>
          <button class="btn delete-btn" type="button">削除</button>
        </div>
      </li>
    </template>

    <script src="script.js"></script>
  </body>
</html>
