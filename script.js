const API_URL = "api.php";

const timelineEntries = [
  { year: "2016", title: "キャリアの起点", description: "ここに最初の転機やキャリア開始の背景を記載予定。", caption: "placeholder image 01" },
  { year: "2019", title: "挑戦領域の拡大", description: "関わった領域拡大や役割の変化を記載予定。", caption: "placeholder image 02" },
  { year: "2022", title: "PMとしての確立", description: "PM視点で成果を出した出来事を記載予定。", caption: "placeholder image 03" },
  { year: "2025", title: "次のステージ", description: "今後の方向性や目指す姿を記載予定。", caption: "placeholder image 04" }
];

const dom = {
  timelineMedia: document.getElementById("timelineMedia"),
  timelineBgYear: document.getElementById("timelineBgYear"),
  timelineTextCard: document.getElementById("timelineTextCard"),
  currentSection: document.getElementById("currentSection"),
  progress: document.getElementById("scrollProgress"),
  navLinks: Array.from(document.querySelectorAll(".header-nav a")),
  form: document.getElementById("projectForm"),
  projectId: document.getElementById("projectId"),
  title: document.getElementById("projectTitle"),
  year: document.getElementById("projectYear"),
  summary: document.getElementById("projectSummary"),
  body: document.getElementById("projectBody"),
  tags: document.getElementById("projectTags"),
  cancelEdit: document.getElementById("cancelEdit"),
  list: document.getElementById("projectList"),
  count: document.getElementById("projectCount"),
  template: document.getElementById("projectItemTemplate"),
  flashMessage: document.getElementById("flashMessage")
};

let projects = [];

init();

async function init() {
  renderTimeline();
  updateActiveTimelineCard(0);
  attachEvents();
  onScroll();
  await refreshProjects();
}

function renderTimeline() {
  const fragment = document.createDocumentFragment();
  timelineEntries.forEach((entry, index) => {
    const wrapper = document.createElement("article");
    wrapper.className = "timeline-item";
    wrapper.dataset.index = String(index);

    const img = document.createElement("div");
    img.className = "img";

    const caption = document.createElement("p");
    caption.textContent = `${entry.year} · ${entry.caption}`;

    wrapper.append(img, caption);
    fragment.append(wrapper);
  });

  dom.timelineMedia.innerHTML = "";
  dom.timelineMedia.append(fragment);
}

function updateActiveTimelineCard(index) {
  const entry = timelineEntries[index];
  if (!entry) return;
  dom.timelineBgYear.textContent = entry.year;
  dom.timelineTextCard.innerHTML = `
    <p class="project-year">${entry.year}</p>
    <h3>${entry.title}</h3>
    <p>${entry.description}</p>
  `;
}

function attachEvents() {
  window.addEventListener("scroll", onScroll);
  dom.form.addEventListener("submit", handleSubmit);
  dom.cancelEdit.addEventListener("click", resetForm);
}

function onScroll() {
  updateSectionState();
  updateTimelineProgress();
}

function updateSectionState() {
  const sections = [
    { id: "timeline", label: "00 TIMELINE" },
    { id: "portfolio", label: "01 PORTFOLIO" }
  ];

  let current = sections[0];
  sections.forEach((section) => {
    const el = document.getElementById(section.id);
    if (window.scrollY >= el.offsetTop - 180) current = section;
  });

  dom.currentSection.textContent = current.label;
  dom.navLinks.forEach((link) => {
    link.classList.toggle("active", link.dataset.section === current.label);
  });
}

function updateTimelineProgress() {
  const timeline = document.getElementById("timeline");
  const rect = timeline.getBoundingClientRect();
  const viewport = window.innerHeight;
  const total = rect.height + viewport;
  const scrolled = viewport - rect.top;
  const progress = Math.max(0, Math.min(scrolled / total, 1));
  dom.progress.style.width = `${Math.round(progress * 100)}%`;

  const cards = Array.from(dom.timelineMedia.querySelectorAll(".timeline-item"));
  let activeIndex = 0;
  cards.forEach((card, index) => {
    if (card.getBoundingClientRect().top <= viewport * 0.45) activeIndex = index;
  });
  updateActiveTimelineCard(activeIndex);
}

async function refreshProjects() {
  try {
    const res = await fetch(API_URL);
    projects = await res.json();
    renderProjects();
  } catch (error) {
    showMessage("読み込みに失敗しました", true);
  }
}

function renderProjects() {
  dom.list.innerHTML = "";
  projects.forEach((project) => {
    const node = dom.template.content.firstElementChild.cloneNode(true);
    node.querySelector(".project-year").textContent = project.year;
    node.querySelector(".project-title").textContent = project.title;
    node.querySelector(".project-summary").textContent = project.summary;
    node.querySelector(".project-tags").textContent = project.tags?.length ? `#${project.tags.join(" #")}` : "";

    node.querySelector(".card-open").addEventListener("click", () => {
      location.href = `detail.php?id=${project.id}`;
    });

    node.querySelector(".edit-btn").addEventListener("click", () => {
      dom.projectId.value = project.id;
      dom.title.value = project.title;
      dom.year.value = project.year;
      dom.summary.value = project.summary;
      dom.body.value = project.body;
      dom.tags.value = (project.tags || []).join(", ");
    });

    node.querySelector(".delete-btn").addEventListener("click", async () => {
      await fetch(`${API_URL}?id=${encodeURIComponent(project.id)}`, { method: "DELETE" });
      await refreshProjects();
      resetForm();
      showMessage("削除しました", false);
    });

    dom.list.append(node);
  });

  dom.count.textContent = `${projects.length} projects`;
}

async function handleSubmit(event) {
  event.preventDefault();
  const id = dom.projectId.value.trim();
  const payload = {
    title: dom.title.value.trim(),
    year: dom.year.value.trim(),
    summary: dom.summary.value.trim(),
    body: dom.body.value.trim(),
    tags: dom.tags.value.split(",").map((tag) => tag.trim()).filter(Boolean)
  };

  const method = id ? "PUT" : "POST";
  const url = id ? `${API_URL}?id=${encodeURIComponent(id)}` : API_URL;

  const res = await fetch(url, {
    method,
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload)
  });

  if (!res.ok) {
    showMessage("保存に失敗しました", true);
    return;
  }

  await refreshProjects();
  resetForm();
  showMessage("保存しました", false);
}

function resetForm() {
  dom.form.reset();
  dom.projectId.value = "";
}

function showMessage(message, isError) {
  dom.flashMessage.textContent = message;
  dom.flashMessage.style.color = isError ? "#ff7c7c" : "#87f5be";
}
