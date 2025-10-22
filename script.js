// script.js - minimal, no libraries

document.addEventListener('DOMContentLoaded', ()=>{
  const tasksEl = document.getElementById('tasks');
  const modal = document.getElementById('modal');
  const btnNew = document.getElementById('btn-new');
  const modalClose = document.getElementById('modal-close');
  const form = document.getElementById('task-form');

  function showModal(edit=false, data={}){
    modal.classList.remove('hidden');
    document.getElementById('modal-title').textContent = edit ? 'Edit Task' : 'New Task';
    document.getElementById('task-id').value = data.id || '';
    document.getElementById('title').value = data.title || '';
    document.getElementById('description').value = data.description || '';
    document.getElementById('priority').value = data.priority || 'Medium';
    document.getElementById('deadline').value = data.deadline || '';
    document.getElementById('progress').value = data.progress || 0;
    document.getElementById('status').value = data.status || 'pending';
  }
  function hideModal(){ modal.classList.add('hidden'); }

  btnNew?.addEventListener('click', ()=> showModal(false, {}));
  modalClose?.addEventListener('click', hideModal);

  form?.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const formData = new FormData(form);
    const id = formData.get('id');
    const action = id ? 'update' : 'create';
    formData.append('action', action);
    const res = await fetch('task_actions.php', {method:'POST', body: formData});
    const j = await res.json();
    if (j.success) {
      hideModal(); loadTasks();
    } else alert(j.error || 'Something went wrong');
  });

  async function loadTasks(){
    const res = await fetch('task_actions.php?action=list');
    const j = await res.json();
    tasksEl.innerHTML = '';
    if (!j.success) { tasksEl.textContent = j.error || 'Could not load'; return; }
    j.tasks.forEach(t=>{
      const div = document.createElement('div'); div.className='task';
      div.innerHTML = `
        <div style="display:flex;justify-content:space-between;align-items:center">
          <strong>${escapeHtml(t.title)}</strong>
          <div style="display:flex;gap:6px">
            <button data-id="${t.id}" class="btn edit">Edit</button>
            <button data-id="${t.id}" class="btn small del">Del</button>
          </div>
        </div>
        <div class="meta">
          <span>${t.priority} • ${t.status}</span>
          <span>Progress: ${t.progress}%</span>
        </div>
        <div style="margin-top:8px;color:#444">${escapeHtml(t.description||'')}</div>
      `;
      tasksEl.appendChild(div);
    });

    // attach handlers
    document.querySelectorAll('.btn.edit').forEach(b=>b.addEventListener('click', async (e)=>{
      const id = e.target.dataset.id;
      const res = await fetch('task_actions.php?action=list');
      const j = await res.json();
      const task = j.tasks.find(x=>String(x.id) === String(id));
      if (task) showModal(true, task);
    }));

    document.querySelectorAll('.btn.del').forEach(b=>b.addEventListener('click', async (e)=>{
      if (!confirm('Delete this task?')) return;
      const id = e.target.dataset.id;
      const fd = new FormData(); fd.append('action','delete'); fd.append('id', id);
      const res = await fetch('task_actions.php', {method:'POST', body:fd});
      const j = await res.json();
      if (j.success) loadTasks(); else alert(j.error||'Could not delete');
    }));
  }

  function escapeHtml(s){ if (!s) return ''; return s.replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;'); }

  // initial load
  if (document.getElementById('tasks')) loadTasks();
});
