<?php
// templates/navbar.php
?>
<nav class="sticky top-0 bg-purple-100/80 backdrop-blur-md border-b border-purple-200/60 py-4 px-6 shadow-xs z-40 transition-all">
    <div class="max-w-5xl mx-auto flex justify-between items-center flex-wrap gap-4">
        <div class="flex items-center gap-3">
            <span class="text-2xl">🐶</span>
            <div>
                <h1 class="text-xl font-bold text-purple-950 tracking-tight flex items-center gap-1.5">
                    Cozy Cabin Game
                </h1>
                <p class="text-purple-700/80 text-xs font-medium">Balance beautifully · Rest fully</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="openAddModal()" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-xl transition text-xs shadow-md shadow-purple-200">
                    ➕ Add Chore
                </button>
                <button onclick="confirmReset()" class="bg-white/80 hover:bg-white text-purple-800 font-medium py-2 px-3.5 rounded-xl transition border border-purple-200 text-xs shadow-xs">
                    🌅 Reset Status
                </button>
                <button onclick="openSettingsModal()" class="bg-white/80 hover:bg-white text-purple-800 font-medium py-2 px-3.5 rounded-xl transition border border-purple-200 text-xs shadow-xs flex items-center gap-1.5">
                    ⚙️ Settings
                </button>
        </div>
    </div>
</nav>