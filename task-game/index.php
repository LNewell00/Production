<?php
// index.php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/bootstrap-db.php';
require_once __DIR__ . '/includes/ajax-handlers.php';
require_once __DIR__ . '/templates/task-row.php';

$pdo = connectDatabase();
$db_error = bootstrapDatabase($pdo);
handleAjaxActions($pdo);

$active_tasks = [];
$completed_tasks = [];
$total_points_earned = 0;

if ($pdo) {
    $all_tasks = $pdo->query("SELECT * FROM household_tasks ORDER BY sort_order ASC, id ASC")->fetchAll();
    foreach ($all_tasks as $t) {
        $isDone = ($t['done'] === 't' || $t['done'] === true || $t['done'] === 1 || $t['done'] === 'true');
        if ($isDone) {
            $completed_tasks[] = $t;
            $total_points_earned += (int)$t['points'];
        } else {
            $active_tasks[] = $t;
        }
    }
}
$total_tasks = count($active_tasks) + count($completed_tasks);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🐾 Pastel Cabin Task Game</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        .dragging { opacity: 0.4; scale: 0.99; background-color: #f3e8ff !important; box-shadow: 0 4px 12px rgba(147, 51, 234, 0.08); }
        
        @keyframes cozyPop {
            0% { transform: scale(1); }
            40% { transform: scale(1.01); background-color: #ecfdf5; }
            100% { transform: scale(1); }
        }
        .animate-cozy-pop { animation: cozyPop 0.4s ease-in-out forwards; }
    </style>
</head>
<body class="bg-slate-50/60 text-slate-700 min-h-screen font-sans antialiased selection:bg-purple-200">

    <?php require_once __DIR__ . '/templates/navbar.php'; ?>

    <main class="max-w-7xl mx-auto p-4 md:p-6 mt-4 space-y-6">

        <?php if ($db_error): ?>
        <div class="bg-rose-50 border-l-4 border-rose-400 text-rose-900 p-4 rounded-xl shadow-xs" role="alert">
            <p class="font-bold text-sm">⚠️ Database link unavailable.</p>
            <code><?= htmlspecialchars($db_error) ?></code>
        </div>
        <?php endif; ?>
        
        <section class="bg-white p-6 rounded-2xl shadow-xs border border-slate-100">
            <div class="flex justify-between items-center mb-2.5">
                <h2 class="font-bold text-base text-purple-950 flex items-center gap-2 tracking-tight">
                    🐕 Weekly Energy Level Gauge
                </h2>
                <span id="score-text" class="font-bold text-sm text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg"><?= $total_points_earned ?> / <span class="max-target-label">12</span> Points</span>
            </div>
            <div class="w-full bg-slate-100 h-5 rounded-full overflow-hidden relative border border-slate-200/40 shadow-inner">
                <div id="progress-bar" class="h-full bg-gradient-to-r from-purple-400 to-purple-500 transition-all duration-500" style="width: 0%"></div>
            </div>
            <div id="warning-box" class="hidden mt-4 p-4 bg-amber-50/80 border-l-4 border-amber-400 text-amber-950 rounded-xl flex items-start gap-3">
                <span class="text-lg mt-0.5">🐾</span>
                <div>
                    <p class="font-bold text-sm text-amber-900">Cap Threshold Achieved!</p>
                    <p class="text-xs text-amber-800 mt-0.5">You've reached your daily limit. Drop the broom and rest! 📚🌿</p>
                </div>
            </div>
        </section>

        <div class="bg-transparent md:bg-white rounded-2xl md:shadow-xs md:border md:border-slate-100 overflow-hidden">
            <div class="p-4 bg-slate-50/50 border-b border-slate-100 flex justify-between items-center rounded-t-2xl md:rounded-none">
                <span class="text-xs font-bold uppercase tracking-wider text-purple-900 flex items-center gap-2">
                    🧹 Active Focus List (<span id="active-count"><?= count($active_tasks) ?></span> pending)
                </span>
                <span class="hidden sm:inline-block text-[10px] text-purple-500 font-medium">💡 Hint: Tap on a card to mark it complete!</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse block md:table">
                    <thead class="hidden md:table-header-group">
                        <tr class="bg-slate-50/40 border-b border-slate-100 text-slate-400 font-bold text-xs uppercase tracking-wider">
                            <th class="p-4 w-12 text-center">Move</th>
                            <th class="p-4">Task Assignment</th>
                            <th class="p-4 w-32">Difficulty</th>
                            <th class="p-4 w-32">Est. Time</th>
                            <th class="p-4">Notes</th>
                            <th class="p-4 w-44 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="active-tbody" class="block md:table-row-group divide-y divide-slate-100 text-sm space-y-3 md:space-y-0">
                        <?php foreach ($active_tasks as $t): ?>
                            <?= renderRow($t) ?>
                        <?php endforeach; ?>
                        <tr id="active-empty-row" class="<?= !empty($active_tasks) ? 'hidden' : '' ?>">
                            <td colspan="6" class="text-center text-slate-400 py-10 italic block md:table-cell bg-white rounded-xl border border-slate-100">All caught up! Grab a sweet treat or add a fresh assignment. ✨</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-transparent md:bg-white rounded-2xl md:shadow-xs md:border md:border-slate-100 overflow-hidden opacity-95">
            <div class="p-4 bg-emerald-50/40 border-b border-emerald-100/60 flex justify-between items-center rounded-t-2xl md:rounded-none">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-800 flex items-center gap-2">
                    ✅ Completed Today (<span id="completed-count"><?= count($completed_tasks) ?></span> completed)
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse block md:table">
                    <tbody id="completed-tbody" class="block md:table-row-group divide-y divide-slate-100 text-sm bg-slate-50/20 space-y-3 md:space-y-0">
                        <?php foreach ($completed_tasks as $t): ?>
                            <?= renderRow($t) ?>
                        <?php endforeach; ?>
                        <tr id="completed-empty-row" class="<?= !empty($completed_tasks) ? 'hidden' : '' ?>">
                            <td colspan="6" class="text-center text-slate-400 py-8 italic text-xs block md:table-cell bg-white/60 rounded-xl border border-slate-100">No entries processed done yet for this cycle.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <footer class="text-center py-10 text-xs text-slate-400 tracking-wide mt-12 border-t border-slate-100 max-w-7xl mx-auto w-full">
        Created by Logan Newell · ❤️ · Powered with PHP & PostgreSQL backend
    </footer>

    <div id="taskModal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden border border-slate-100 transform transition-all scale-95 duration-200">
            <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 id="taskModalLabel" class="font-bold text-purple-950 text-sm tracking-tight">Add Task Assignment</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 text-sm font-bold p-1 cursor-pointer">✕</button>
            </div>
            <form onsubmit="saveTask(e=event)" class="p-5 space-y-4">
                <input type="hidden" id="edit-id">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Task Name *</label>
                    <input type="text" id="f-task" required placeholder="e.g., Dust off reading bookshelf" class="w-full mt-1.5 p-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-400 focus:outline-none bg-slate-50 text-sm transition">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Difficulty *</label>
                        <select id="f-difficulty" class="w-full mt-1.5 p-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-400 focus:outline-none bg-slate-50 text-sm cursor-pointer">
                            <option value="Easy">🥰 Easy (1pt)</option>
                            <option value="Medium" selected>😐 Medium (2pt)</option>
                            <option value="Hard">🫠 Hard (3pt)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Est. Time</label>
                        <input type="text" id="f-time" placeholder="e.g., 20 mins" class="w-full mt-1.5 p-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-400 focus:outline-none bg-slate-50 text-sm transition">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Notes / Details</label>
                    <textarea id="f-notes" rows="2" placeholder="Optional notes details..." class="w-full mt-1.5 p-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-400 focus:outline-none bg-slate-50 text-sm resize-none transition"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium py-2 px-4 rounded-xl transition text-xs cursor-pointer">Cancel</button>
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-5 rounded-xl transition text-xs shadow-xs cursor-pointer">Save Assignment</button>
                </div>
            </form>
        </div>
    </div>

    <div id="settingsModal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full overflow-hidden border border-slate-100 transform transition-all scale-95 duration-200">
            <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-purple-950 text-sm tracking-tight">⚙️ Game Parameters</h3>
                <button onclick="closeSettingsModal()" class="text-slate-400 hover:text-slate-600 text-sm font-bold p-1 cursor-pointer">✕</button>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Daily Target Cap Points</label>
                    <input type="number" id="settings-points" min="1" max="50" class="w-full mt-1.5 p-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-400 focus:outline-none bg-slate-50 text-sm font-semibold text-slate-800">
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                    <button onclick="closeSettingsModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium py-2 px-4 rounded-xl transition text-xs cursor-pointer">Cancel</button>
                    <button onclick="saveSettings()" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-5 rounded-xl transition text-xs cursor-pointer">Save Settings</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const taskCache = {};
        let maxAllowedTargetPoints = parseInt(localStorage.getItem('cabinMaxPoints') || '12');
        let dragSrcEl = null;

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('tr[id^="row-"]').forEach(row => {
                const id = row.id.replace('row-', '');
                const doneAttr = row.getAttribute('data-done');
                const isTaskDone = (doneAttr === 'true' || doneAttr === '1' || doneAttr === 't');
                
                const badgeEl = row.querySelector('td:nth-child(3) span');
                let difficulty = 'Medium';
                if (badgeEl) {
                    if (badgeEl.innerText.includes('Easy')) difficulty = 'Easy';
                    if (badgeEl.innerText.includes('Hard')) difficulty = 'Hard';
                }

                const timeText = row.querySelector('td:nth-child(4)').innerText.trim().replace('⏰ ', '');
                const notesText = row.querySelector('td:nth-child(5)').innerText.trim();

                taskCache[id] = {
                    id: parseInt(id),
                    task: row.querySelector('.task-title-cell').innerText.trim(),
                    points: parseInt(row.getAttribute('data-points') || '0'),
                    done: isTaskDone,
                    difficulty: difficulty,
                    est_time: timeText === '—' || timeText === '' ? '' : timeText,
                    notes: notesText
                };
            });

            applyTargetPointsLabels();
            recalculatePointsGauge();
            initializeDragAndDropEngine();
        });

        function post(data) {
            return fetch(window.location.href, {
                method: 'POST',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded' 
                },
                body: new URLSearchParams(data)
            }).then(r => r.json());
        }

        function diffBadge(difficulty, points) {
            const map = { 
                Easy: 'bg-emerald-100/80 text-emerald-800 border-emerald-200', 
                Medium: 'bg-purple-100/80 text-purple-800 border-purple-200', 
                Hard: 'bg-amber-100/80 text-amber-800 border-amber-200' 
            };
            return `<span class="px-2.5 py-0.5 text-xs font-semibold rounded-md border ${map[difficulty] || 'bg-slate-50'}">${difficulty} (${points}p)</span>`;
        }

        function buildRowHtml(t) {
            const isDone = t.done === true || t.done === 't' || t.done === 'true' || t.done == 1;
            const rowClass = isDone 
                ? 'bg-emerald-50/40 border-emerald-100/60 opacity-85' 
                : 'hover:bg-purple-50/40 text-slate-700 border-slate-100';
                
            const textMuteClass = isDone ? 'line-through text-slate-400 italic font-medium' : 'text-slate-800 font-semibold';

            return `
                <tr id="row-${t.id}" onclick="toggleTaskInline(${t.id}, event)" class="block md:table-row transition-all duration-200 border-b cursor-pointer select-none relative p-4 pb-14 md:p-0 mb-3 md:mb-0 rounded-xl md:rounded-none bg-white md:bg-transparent shadow-xs md:shadow-none border border-slate-100 md:border-none ${rowClass}" data-points="${t.points}" data-done="${isDone}">
                    <td class="inline-block md:table-cell p-0 md:p-4 w-8 md:w-12 text-center text-slate-300 font-mono text-base drag-handle cursor-grab active:cursor-grabbing hover:text-purple-500 transition-colors align-middle">
                        ☰
                    </td>
                    <td class="block md:table-cell p-0 px-2 md:p-4 text-base md:text-sm tracking-tight task-title-cell mt-1 md:mt-0 ${textMuteClass}">${esc(t.task)}</td>
                    <td class="inline-block md:table-cell p-0 pl-2 pr-4 md:p-4 mt-2 md:mt-0">${diffBadge(t.difficulty, t.points)}</td>
                    <td class="inline-block md:table-cell p-0 md:p-4 text-xs font-semibold text-slate-400 bg-slate-100 md:bg-transparent px-2 py-0.5 rounded-md md:rounded-none mt-2 md:mt-0">⏰ ${esc(t.est_time || '—')}</td>
                    <td class="block md:table-cell p-0 px-2 md:p-4 text-slate-400 italic text-xs max-w-full md:max-w-md truncate mt-2 md:mt-0">${esc(t.notes || '')}</td>
                    <td class="absolute bottom-3 right-3 md:static md:table-cell p-0 md:p-4 text-center space-x-1.5 whitespace-nowrap">
                        <button onclick="openEditModal(${t.id})" class="inline-flex items-center bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold text-[11px] md:text-xs px-2.5 py-1 md:px-3 md:py-1.5 rounded-lg border border-purple-200/40 cursor-pointer transition active:scale-95 shadow-2xs">✏️ Edit</button>
                        <button onclick="deleteTask(${t.id})" class="inline-flex items-center bg-slate-50 hover:bg-rose-50 text-slate-500 hover:text-rose-700 font-bold text-[11px] md:text-xs px-2.5 py-1 md:px-3 md:py-1.5 rounded-lg border border-slate-200/40 cursor-pointer transition active:scale-95 shadow-2xs">🗑️ Delete</button>
                    </td>
                </tr>
            `;
        }

        function esc(s) {
            return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        function toggleTaskInline(id, event) {
            if (event) {
                const clickedElement = event.target;
                if (clickedElement.closest('.drag-handle') || clickedElement.closest('button')) {
                    return; 
                }
            }

            post({ action: 'toggle', id }).then(res => {
                if (!res.ok) return;
                
                const isDoneNow = (res.done === true || res.done === 't' || res.done === 'true' || res.done == 1);
                if (taskCache[id]) taskCache[id].done = isDoneNow;
                
                const targetRow = document.getElementById('row-' + id);
                if (!targetRow) return;

                targetRow.classList.add('animate-cozy-pop');

                setTimeout(() => {
                    targetRow.remove();
                    
                    const updatedRowHtml = buildRowHtml(taskCache[id]);
                    const destinationContainer = document.getElementById(isDoneNow ? 'completed-tbody' : 'active-tbody');
                    
                    destinationContainer.insertAdjacentHTML('beforeend', updatedRowHtml);
                    
                    updateSectionPlaceholders();
                    recalculatePointsGauge();
                    initializeDragAndDropEngine();
                }, 350);
            });
        }

        // FIX: Replaced complete object rewrites with targeted token toggles 
        function updateSectionPlaceholders() {
            const activeCount = document.querySelectorAll('#active-tbody tr[id^="row-"]').length;
            const completedCount = document.querySelectorAll('#completed-tbody tr[id^="row-"]').length;
            
            document.getElementById('active-count').innerText = activeCount;
            document.getElementById('completed-count').innerText = completedCount;
            
            document.getElementById('active-empty-row').classList.toggle('hidden', activeCount > 0);
            document.getElementById('completed-empty-row').classList.toggle('hidden', completedCount > 0);
        }

        function openAddModal() {
            document.getElementById('edit-id').value = '';
            document.getElementById('f-task').value = '';
            document.getElementById('f-difficulty').value = 'Medium';
            document.getElementById('f-time').value = '';
            document.getElementById('f-notes').value = '';
            document.getElementById('taskModalLabel').innerText = "Add Task Assignment";
            document.getElementById('taskModal').classList.remove('hidden');
        }

        function openEditModal(id) {
            const t = taskCache[id];
            if (!t) return;
            document.getElementById('edit-id').value = t.id;
            document.getElementById('f-task').value = t.task;
            document.getElementById('f-difficulty').value = t.difficulty || 'Medium';
            document.getElementById('f-time').value = t.est_time || '';
            document.getElementById('f-notes').value = t.notes || '';
            document.getElementById('taskModalLabel').innerText = "Modify Chore Profile";
            document.getElementById('taskModal').classList.remove('hidden');
        }

        function closeModal() { document.getElementById('taskModal').classList.add('hidden'); }
        function openSettingsModal() {
            document.getElementById('settings-points').value = maxAllowedTargetPoints;
            document.getElementById('settingsModal').classList.remove('hidden');
        }
        function closeSettingsModal() { document.getElementById('settingsModal').classList.add('hidden'); }

        function saveSettings() {
            const val = parseInt(document.getElementById('settings-points').value || '12');
            maxAllowedTargetPoints = val > 0 ? val : 12;
            localStorage.setItem('cabinMaxPoints', maxAllowedTargetPoints.toString());
            applyTargetPointsLabels();
            recalculatePointsGauge();
            closeSettingsModal();
        }

        function applyTargetPointsLabels() {
            document.querySelectorAll('.max-target-label').forEach(el => el.innerText = maxAllowedTargetPoints);
        }

        function saveTask(e) {
            e.preventDefault();
            const id = document.getElementById('edit-id').value;
            const payload = {
                action: id ? 'update' : 'add',
                id: id,
                task: document.getElementById('f-task').value.trim(),
                difficulty: document.getElementById('f-difficulty').value,
                est_time: document.getElementById('f-time').value.trim(),
                notes: document.getElementById('f-notes').value.trim()
            };

            post(payload).then(res => {
                if (!res.ok) return alert('Save operation issue: ' + res.error);
                
                const isDone = res.row.done === true || res.row.done === 't' || res.row.done === 'true' || res.row.done == 1;
                taskCache[res.row.id] = res.row;
                
                const rowHtml = buildRowHtml(res.row);
                
                if (id) {
                    const existingRow = document.getElementById('row-' + id);
                    if (existingRow) existingRow.outerHTML = rowHtml;
                } else {
                    const container = document.getElementById(isDone ? 'completed-tbody' : 'active-tbody');
                    container.insertAdjacentHTML('beforeend', rowHtml);
                }
                closeModal();
                updateSectionPlaceholders();
                recalculatePointsGauge();
                initializeDragAndDropEngine();
            });
        }

        function deleteTask(id) {
            if (!confirm('Remove this assignment completely?')) return;
            post({ action: 'delete', id }).then(res => {
                if (!res.ok) return alert('Remove error.');
                const row = document.getElementById('row-' + id);
                if (row) row.remove();
                delete taskCache[id];
                
                updateSectionPlaceholders();
                recalculatePointsGauge();
            });
        }

        function confirmReset() {
            if (confirm('Reset tracking data status flags?')) {
                post({ action: 'reset' }).then(res => {
                    if (res.ok) window.location.reload();
                });
            }
        }

        // REVERSED DRAIN LOGIC SYSTEM
        function recalculatePointsGauge() {
            let sum = 0;
            document.querySelectorAll('tr[id^="row-"]').forEach(row => {
                const doneAttr = row.getAttribute('data-done');
                if (doneAttr === 'true' || doneAttr === '1' || doneAttr === 't') {
                    sum += parseInt(row.getAttribute('data-points') || 0);
                }
            });

            const bar = document.getElementById('progress-bar');
            const alertCard = document.getElementById('warning-box');
            
            const remainingPoints = Math.max(0, maxAllowedTargetPoints - sum);
            document.getElementById('score-text').innerHTML = `${remainingPoints} / ${maxAllowedTargetPoints} Energy Points Left`;

            let energyPercentRemaining = ((maxAllowedTargetPoints - sum) / maxAllowedTargetPoints) * 100;
            energyPercentRemaining = Math.max(0, Math.min(energyPercentRemaining, 100));
            
            bar.style.width = `${energyPercentRemaining}%`;

            if (sum >= maxAllowedTargetPoints) {
                bar.className = "h-full bg-gradient-to-r from-amber-500 to-rose-500 transition-all duration-500";
                alertCard.classList.remove('hidden');
            } else {
                bar.className = "h-full bg-gradient-to-r from-purple-500 to-indigo-500 transition-all duration-500";
                alertCard.classList.add('hidden');
            }
        }

        function initializeDragAndDropEngine() {
            document.querySelectorAll('tr[id^="row-"]').forEach(row => {
                const handle = row.querySelector('.drag-handle');
                if (!handle) return;

                handle.onmousedown = () => row.setAttribute('draggable', 'true');
                handle.onmouseup = () => row.removeAttribute('draggable');

                row.addEventListener('dragstart', handleDragStart, false);
                row.addEventListener('dragover', handleDragOver, false);
                row.addEventListener('dragenter', handleDragEnter, false);
                row.addEventListener('dragleave', handleDragLeave, false);
                row.addEventListener('drop', handleDrop, false);
                row.addEventListener('dragend', handleDragEnd, false);
            });
        }

        function handleDragStart(e) {
            dragSrcEl = this;
            this.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', this.id);
        }

        function handleDragOver(e) {
            if (e.preventDefault) e.preventDefault();
            return false;
        }

        function handleDragEnter(e) { this.classList.add('bg-purple-50/50'); }
        function handleDragLeave(e) { this.classList.remove('bg-purple-50/50'); }

        function handleDrop(e) {
            if (e.stopPropagation) e.stopPropagation();
            e.preventDefault();

            const targetRow = this;
            const sourceId = e.dataTransfer.getData('text/plain');
            const sourceRow = document.getElementById(sourceId);

            if (dragSrcEl !== targetRow && sourceRow && sourceRow.parentNode === targetRow.parentNode) {
                const rect = targetRow.getBoundingClientRect();
                const next = (e.clientY - rect.top) / (rect.bottom - rect.top) > 0.5;
                targetRow.parentNode.insertBefore(sourceRow, next ? targetRow.nextSibling : targetRow);
                saveDraggedSequenceOrder(targetRow.parentNode);
            }
            return false;
        }

        function handleDragEnd(e) {
            document.querySelectorAll('tr[id^="row-"]').forEach(row => {
                row.removeAttribute('draggable');
                row.classList.remove('dragging', 'bg-purple-50/50');
            });
        }

        function saveDraggedSequenceOrder(parentTbody) {
            const currentIds = [];
            parentTbody.querySelectorAll('tr[id^="row-"]').forEach(row => {
                currentIds.push(row.id.replace('row-', ''));
            });

            post({
                action: 'update_order',
                ids: currentIds.join(',')
            }).then(res => {
                if(!res.ok) console.error("Sequence layout array save error.");
            });
        }

        window.addEventListener('keydown', e => {
            if (e.key === 'Escape') { closeModal(); closeSettingsModal(); }
        });
    </script>
</body>
</html>