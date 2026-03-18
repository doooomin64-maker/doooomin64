<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Project Detail</title>
    <link rel="stylesheet" href="reset.css" />
    <link rel="stylesheet" href="styles.css" />
    <style>
      .detail-wrap {
        width: min(900px, 92vw);
        margin: 120px auto 80px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 16px;
        padding: 1.4rem;
      }
      .back-link {
        display: inline-block;
        margin-bottom: 1rem;
        color: var(--muted);
      }
    </style>
  </head>
  <body>
    <header class="site-header">
      <div class="header-top"><h1>Project Detail</h1></div>
      <div class="progress-wrap"><div class="progress-bar" style="width: 100%"></div></div>
    </header>

    <main class="detail-wrap" id="detail"></main>

    <script src="detail.js"></script>
  </body>
</html>
