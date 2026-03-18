const detail = document.getElementById("detail");
const id = new URLSearchParams(location.search).get("id");

init();

async function init() {
  if (!id) {
    renderNotFound();
    return;
  }

  try {
    const res = await fetch(`api.php?id=${encodeURIComponent(id)}`);
    if (!res.ok) {
      renderNotFound();
      return;
    }

    const project = await res.json();
    detail.innerHTML = `
      <a class="back-link" href="index.php#portfolio">← 一覧へ戻る</a>
      <p class="project-year">${project.year}</p>
      <h2>${project.title}</h2>
      <p class="project-tags">#${(project.tags || []).join(" #")}</p>
      <h3>サマリー</h3>
      <p>${project.summary}</p>
      <h3>詳細</h3>
      <p>${project.body}</p>
    `;
  } catch {
    renderNotFound();
  }
}

function renderNotFound() {
  detail.innerHTML = `
    <a class="back-link" href="index.php#portfolio">← 一覧へ戻る</a>
    <h2>プロジェクトが見つかりませんでした。</h2>
    <p>削除済みか、URLが無効です。</p>
  `;
}
