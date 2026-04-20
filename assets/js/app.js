function apiPost(url, data) {
  return fetch(url, {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: new URLSearchParams(data)
  }).then(r => r.json());
}

setInterval(() => {
  const chatBox = document.getElementById('chatBox');
  if (chatBox) {
    fetch((window.APP_BASE_PATH || '') + '/messages').then(() => {});
  }
}, 10000);

document.addEventListener('dragstart', (e) => {
  if (e.target.classList.contains('kanban-card')) e.dataTransfer.setData('text', e.target.id);
});

document.querySelectorAll('.kanban-col').forEach(col => {
  col.addEventListener('dragover', e => e.preventDefault());
  col.addEventListener('drop', e => {
    e.preventDefault();
    const id = e.dataTransfer.getData('text');
    const el = document.getElementById(id);
    if (el) col.querySelector('.cards').appendChild(el);
  });
});
