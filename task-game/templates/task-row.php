<?php
// templates/task-row.php

function renderRow($t) {
    $isDone = ($t['done'] === 't' || $t['done'] === true || $t['done'] === 1 || $t['done'] === 'true');
    $rowClass = $isDone 
        ? 'bg-emerald-50/40 border-emerald-100/60 opacity-85' 
        : 'hover:bg-purple-50/40 text-slate-700 border-slate-100';
        
    $textMuteClass = $isDone ? 'line-through text-slate-400 italic font-medium' : 'text-slate-800 font-semibold';
    
    $diff = $t['difficulty'] ?? 'Medium';
    $points = $t['points'] ?? 2;
    $map = [
        'Easy'   => 'bg-emerald-100/80 text-emerald-800 border-emerald-200',
        'Medium' => 'bg-purple-100/80 text-purple-800 border-purple-200',
        'Hard'   => 'bg-amber-100/80 text-amber-800 border-amber-200'
    ];
    $badgeStyle = $map[$diff] ?? 'bg-slate-50';
    $badgeHtml = "<span class='px-2.5 py-0.5 text-xs font-semibold rounded-md border {$badgeStyle}'>{$diff} ({$points}p)</span>";

    $task = htmlspecialchars($t['task'] ?? '');
    $time = htmlspecialchars($t['est_time'] ?: '—');
    $notes = htmlspecialchars($t['notes'] ?? '');
    $id = (int)$t['id'];

    return "
        <tr id='row-{$id}' onclick='toggleTaskInline({$id}, event)' class='block md:table-row transition-all duration-200 border-b cursor-pointer select-none relative p-4 pl-12 pb-14 md:p-0 mb-3 md:mb-0 rounded-xl md:rounded-none bg-white md:bg-transparent shadow-xs md:shadow-none border border-slate-100 md:border-none {$rowClass}' data-points='{$points}' data-done='{$isDone}'>
            <td class='absolute left-3 top-4 md:static md:table-cell p-0 md:p-4 text-center text-slate-300 hover:text-purple-500 font-bold text-lg cursor-grab active:cursor-grabbing select-none drag-handle-zone' title='Drag to reorder'>☰</td>
            <td class='block md:table-cell p-0 px-2 md:p-4 text-base md:text-sm tracking-tight task-title-cell mt-1 md:mt-0 {$textMuteClass}'>{$task}</td>
            <td class='inline-block md:table-cell p-0 pl-2 pr-4 md:p-4 mt-2 md:mt-0 diff-badge-cell'>{$badgeHtml}</td>
            <td class='inline-block md:table-cell p-0 md:p-4 text-xs font-semibold text-slate-400 bg-slate-100 md:bg-transparent px-2 py-0.5 rounded-md md:rounded-none mt-2 md:mt-0 time-cell'>{$time}</td>
            <td class='block md:table-cell p-0 px-2 md:p-4 text-slate-400 italic text-xs max-w-full md:max-w-md truncate mt-2 md:mt-0 notes-cell'>{$notes}</td>
            <td class='absolute bottom-3 right-3 md:static md:table-cell p-0 md:p-4 text-center space-x-1.5 whitespace-nowrap'>
                <button onclick='openEditModal({$id})' class='inline-flex items-center bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold text-[11px] md:text-xs px-2.5 py-1 md:px-3 md:py-1.5 rounded-lg border border-purple-200/40 cursor-pointer transition active:scale-95 shadow-2xs'>✏️ Edit</button>
                <button onclick='deleteTask({$id})' class='inline-flex items-center bg-slate-50 hover:bg-rose-50 text-slate-500 hover:text-rose-700 font-bold text-[11px] md:text-xs px-2.5 py-1 md:px-3 md:py-1.5 rounded-lg border border-slate-200/40 cursor-pointer transition active:scale-95 shadow-2xs'>🗑️ Delete</button>
            </td>
        </tr>
    ";
}