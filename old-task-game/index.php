<?php
// Main Script Header (index.php)

// 1. Import your modules (With the corrected /includes/ paths)
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/bootstrap-db.php';
require_once __DIR__ . '/includes/ajax-handlers.php';

// 2. Explicitly create the $pdo variable right here in this file's scope
$pdo = connectDatabase();

// 3. Initialize database schema & seeds (passing $pdo)
$db_error = bootstrapDatabase($pdo);

// 4. Process any AJAX operations early (passing $pdo)
handleAjaxActions($pdo);

// 5. Fetch Tasks for Initial Page Load
$tasks = [];
$done_count = 0;
if ($pdo) {
    $tasks      = $pdo->query("SELECT * FROM household_tasks ORDER BY id ASC")->fetchAll();
    $done_count = (int)$pdo->query("SELECT COUNT(*) FROM household_tasks WHERE done=TRUE")->fetchColumn();
}
$total = count($tasks);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>🏡 Weekly Task Game</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="TaskGameStyle.css">
</head>
<body>

<!-- ══ HEADER ══════════════════════════════════════════════════════════════════ -->
<div class="app-header">
  <div class="container">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
      <div>
        <h1>🏡 Weekly Task Game</h1>
        <p>Choose 2–4 tasks per day · Stop at 14 · Earn your reward ✨</p>
      </div>
      <div class="text-end" style="min-width:200px">
        <div class="progress-label mb-1">
          <span id="done-label"><?= $done_count ?></span> of
          <span id="total-label"><?= $total ?></span> tasks done
        </div>
        <div class="progress">
          <div class="progress-bar" id="prog-bar"
               style="width:<?= $total > 0 ? round($done_count/$total*100) : 0 ?>%"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ══ MAIN ════════════════════════════════════════════════════════════════════ -->
<div class="container pb-5">

  <?php if ($db_error): ?>
  <div class="alert db-error mb-4 p-3" role="alert">
    <strong>⚠️ Database connection failed.</strong><br>
    <code style="font-size:.85rem"><?= htmlspecialchars($db_error) ?></code><br>
    <small class="text-muted mt-1 d-block">Set <code>DB_HOST</code>, <code>DB_NAME</code>, <code>DB_USER</code>, <code>DB_PASS</code> environment variables and reload.</small>
  </div>
  <?php endif; ?>

  <!-- Toolbar -->
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
      <span class="fw-semibold text-secondary small">
        <i class="bi bi-list-check me-1"></i>
        <span id="total-count"><?= $total ?></span> tasks in list
      </span>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      <button class="btn btn-sm btn-reset" onclick="confirmReset()">
        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset All
      </button>
      <button class="btn btn-sm btn-add" onclick="openAddModal()">
        <i class="bi bi-plus-lg me-1"></i> Add Task
      </button>
    </div>
  </div>

  <!-- Table card -->
  <div class="card">
    <div class="table-responsive">
      <table class="table mb-0" id="task-table">
        <thead>
          <tr>
            <th style="width:40px"></th>
            <th>Task</th>
            <th style="width:110px">Difficulty</th>
            <th style="width:120px">Est. Time</th>
            <th>Notes</th>
            <th style="width:105px" class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody id="task-tbody">
          <?php foreach ($tasks as $t): ?>
          <?= renderRow($t) ?>
          <?php endforeach; ?>
          <?php if (empty($tasks)): ?>
          <tr id="empty-row"><td colspan="6" class="text-center text-muted py-4">No tasks yet — add one above!</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Rules card -->
  <div class="card mt-4 p-4">
    <h6 class="fw-bold mb-3" style="color:var(--purple-dark)">📋 The Rules</h6>
    <ul class="mb-0 ps-3" style="font-size:.9rem;color:#555;line-height:2">
      <li>Pick <strong>2–4 tasks</strong> per day — going over means you <strong style="color:var(--red)">owe a forfeit</strong>.</li>
      <li>Once all tasks are checked off for the week, <strong>you must stop</strong> — no more chores!</li>
      <li>Working over 14 tasks in a week triggers a <strong style="color:var(--red)">punishment</strong>.</li>
      <li>Complete within the rules and earn a <strong style="color:var(--green)">reward</strong> — dinner out, a book, shopping, and more!</li>
    </ul>
  </div>

</div><!-- /container -->

<!-- ══ ADD / EDIT MODAL ════════════════════════════════════════════════════════ -->
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="taskModalLabel">Add Task</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <input type="hidden" id="edit-id">
        <div class="mb-3">
          <label class="form-label">Task Name *</label>
          <input type="text" class="form-control" id="f-task" placeholder="e.g. Clean the Kitchen">
        </div>
        <div class="row g-3 mb-3">
          <div class="col-6">
            <label class="form-label">Difficulty *</label>
            <select class="form-select" id="f-difficulty">
              <option value="Easy">Easy</option>
              <option value="Medium" selected>Medium</option>
              <option value="Hard">Hard</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label">Est. Time</label>
            <input type="text" class="form-control" id="f-time" placeholder="e.g. 20–30 min">
          </div>
        </div>
        <div class="mb-1">
          <label class="form-label">Notes</label>
          <textarea class="form-control" id="f-notes" rows="2" placeholder="Optional details…"></textarea>
        </div>
        <div id="modal-error" class="text-danger small mt-2" style="display:none"></div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-add px-4" onclick="saveTask()">
          <i class="bi bi-check2 me-1"></i> Save
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ══ CONFIRM RESET MODAL ═════════════════════════════════════════════════════ -->
<div class="modal fade" id="resetModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content shadow-lg">
      <div class="modal-header" style="background:var(--red)">
        <h5 class="modal-title text-white fw-bold">Reset All?</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center py-4">
        <p class="mb-0">This will uncheck all tasks and start the week fresh.</p>
      </div>
      <div class="modal-footer border-0 justify-content-center">
        <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger" onclick="doReset()">Yes, reset</button>
      </div>
    </div>
  </div>
</div>

<div class="footer-note">Weekly Task Game · Built with <span>♥</span> · Data stored in PostgreSQL</div>

<!-- ══ SCRIPTS ═════════════════════════════════════════════════════════════════ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const taskModal  = new bootstrap.Modal(document.getElementById('taskModal'));
const resetModal = new bootstrap.Modal(document.getElementById('resetModal'));

// ── Helpers ──────────────────────────────────────────────────────────────────
function post(data) {
  return fetch(location.href, {
    method: 'POST',
    headers: { 'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams(data)
  }).then(r => r.json());
}

function diffBadge(d) {
  const map = { Easy:'easy', Medium:'medium', Hard:'hard' };
  return `<span class="badge-${map[d]||'easy'}">${d}</span>`;
}

function buildRow(t) {
  const doneClass = t.done === 'true' || t.done === true || t.done === 't' ? 'done-row' : '';
  const checked   = doneClass ? 'checked' : '';
  return `<tr id="row-${t.id}" class="${doneClass}">
    <td><input type="checkbox" class="form-check-input" ${checked} onchange="toggleDone(${t.id}, this)"></td>
    <td class="fw-semibold">${esc(t.task)}</td>
    <td>${diffBadge(t.difficulty)}</td>
    <td class="text-muted small">${esc(t.est_time||'')}</td>
    <td class="text-muted small fst-italic">${esc(t.notes||'')}</td>
    <td class="actions-cell text-center">
      <button class="btn-icon edit"   title="Edit"   onclick="openEditModal(${t.id})"><i class="bi bi-pencil"></i></button>
      <button class="btn-icon delete" title="Remove" onclick="deleteTask(${t.id})"><i class="bi bi-trash3"></i></button>
    </td>
  </tr>`;
}

function esc(s) {
  return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function updateProgress() {
  const rows  = document.querySelectorAll('#task-tbody tr[id^="row-"]');
  const done  = document.querySelectorAll('#task-tbody tr[id^="row-"].done-row');
  const total = rows.length;
  const d     = done.length;
  document.getElementById('done-label').textContent  = d;
  document.getElementById('total-label').textContent = total;
  document.getElementById('total-count').textContent = total;
  document.getElementById('prog-bar').style.width    = total ? Math.round(d/total*100)+'%' : '0%';
}

function checkEmpty() {
  const rows = document.querySelectorAll('#task-tbody tr[id^="row-"]');
  const emp  = document.getElementById('empty-row');
  if (rows.length === 0 && !emp) {
    document.getElementById('task-tbody').innerHTML =
      '<tr id="empty-row"><td colspan="6" class="text-center text-muted py-4">No tasks yet — add one above!</td></tr>';
  } else if (rows.length > 0 && emp) {
    emp.remove();
  }
}

// ── Stored task data for edit modal ──────────────────────────────────────────
const taskCache = {};
<?php foreach ($tasks as $t): ?>
taskCache[<?= $t['id'] ?>] = <?= json_encode($t) ?>;
<?php endforeach; ?>

// ── Actions ───────────────────────────────────────────────────────────────────
function toggleDone(id, cb) {
  post({ action:'toggle', id }).then(res => {
    if (!res.ok) { cb.checked = !cb.checked; return; }
    const row = document.getElementById('row-'+id);
    if (res.done === true || res.done === 't' || res.done === 'true') {
      row.classList.add('done-row');
      cb.checked = true;
    } else {
      row.classList.remove('done-row');
      cb.checked = false;
    }
    updateProgress();
  });
}

function deleteTask(id) {
  if (!confirm('Remove this task?')) return;
  post({ action:'delete', id }).then(res => {
    if (!res.ok) return alert('Error: ' + res.error);
    const row = document.getElementById('row-'+id);
    row && row.remove();
    delete taskCache[id];
    checkEmpty();
    updateProgress();
  });
}

function openAddModal() {
  document.getElementById('edit-id').value = '';
  document.getElementById('f-task').value        = '';
  document.getElementById('f-difficulty').value  = 'Medium';
  document.getElementById('f-time').value        = '';
  document.getElementById('f-notes').value       = '';
  document.getElementById('modal-error').style.display = 'none';
  document.getElementById('taskModalLabel').textContent = 'Add Task';
  taskModal.show();
}

function openEditModal(id) {
  const t = taskCache[id];
  if (!t) return alert('Could not load task data.');
  document.getElementById('edit-id').value       = t.id;
  document.getElementById('f-task').value        = t.task;
  document.getElementById('f-difficulty').value  = t.difficulty;
  document.getElementById('f-time').value        = t.est_time  || '';
  document.getElementById('f-notes').value       = t.notes || '';
  document.getElementById('modal-error').style.display = 'none';
  document.getElementById('taskModalLabel').textContent = 'Edit Task';
  taskModal.show();
}

function saveTask() {
  const id   = document.getElementById('edit-id').value;
  const task = document.getElementById('f-task').value.trim();
  const errEl = document.getElementById('modal-error');
  if (!task) { errEl.textContent='Task name is required.'; errEl.style.display='block'; return; }
  errEl.style.display = 'none';

  const payload = {
    action:     id ? 'update' : 'add',
    id,
    task,
    difficulty: document.getElementById('f-difficulty').value,
    est_time:   document.getElementById('f-time').value.trim(),
    notes:      document.getElementById('f-notes').value.trim(),
  };

  post(payload).then(res => {
    if (!res.ok) { errEl.textContent = res.error || 'Save failed.'; errEl.style.display='block'; return; }
    taskCache[res.row.id] = res.row;
    const tbody = document.getElementById('task-tbody');
    if (id) {
      const existing = document.getElementById('row-'+id);
      existing && (existing.outerHTML = buildRow(res.row));
    } else {
      const emp = document.getElementById('empty-row');
      emp && emp.remove();
      tbody.insertAdjacentHTML('beforeend', buildRow(res.row));
    }
    checkEmpty();
    updateProgress();
    taskModal.hide();
  });
}

function confirmReset() { resetModal.show(); }

function doReset() {
  post({ action:'reset' }).then(res => {
    if (!res.ok) return alert('Error: ' + res.error);
    document.querySelectorAll('#task-tbody tr[id^="row-"]').forEach(row => {
      row.classList.remove('done-row');
      const cb = row.querySelector('input[type=checkbox]');
      if (cb) cb.checked = false;
    });
    updateProgress();
    resetModal.hide();
  });
}

// Allow Enter key in modal to save
document.getElementById('taskModal').addEventListener('keydown', e => {
  if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') saveTask();
});
</script>
</body>
</html>
<?php
// ─── PHP helper: render a table row (used for initial load) ───────────────────
function renderRow($t) {
    $done  = ($t['done'] === 't' || $t['done'] === true || $t['done'] === 1) ? 'done-row' : '';
    $chk   = $done ? 'checked' : '';
    
    // 1. Properly escape values before putting them into the string
    $task  = htmlspecialchars($t['task']);
    $diff  = htmlspecialchars($t['difficulty']);
    $time  = htmlspecialchars($t['est_time'] ?? '');
    $notes = htmlspecialchars($t['notes'] ?? '');
    
    $dmap  = ['Easy'=>'easy','Medium'=>'medium','Hard'=>'hard'];
    $badge = '<span class="badge-'.($dmap[$diff]??'easy').'">'.$diff.'</span>';
    $id    = (int)$t['id'];
    
    // 2. Inject clean variables directly into HEREDOC
    return <<<HTML
    <tr id="row-{$id}" class="{$done}">
      <td><input type="checkbox" class="form-check-input" {$chk} onchange="toggleDone({$id}, this)"></td>
      <td class="fw-semibold">{$task}</td>
      <td>{$badge}</td>
      <td class="text-muted small">{$time}</td>
      <td class="text-muted small fst-italic">{$notes}</td>
      <td class="actions-cell text-center">
        <button class="btn-icon edit"   title="Edit"   onclick="openEditModal({$id})"><i class="bi bi-pencil"></i></button>
        <button class="btn-icon delete" title="Remove" onclick="deleteTask({$id})"><i class="bi bi-trash3"></i></button>
      </td>
    </tr>
    HTML;
}
?>