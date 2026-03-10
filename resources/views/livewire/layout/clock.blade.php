<?php

use Livewire\Volt\Component;

new class extends Component {
};

?>

<div x-data="{ 
    time: new Date().toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit', second: '2-digit' }),
    date: new Date().toLocaleDateString('es-MX', { day: 'numeric', month: 'long', year: 'numeric' }),
    init() {
        setInterval(() => {
            const now = new Date();
            this.time = now.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            this.date = now.toLocaleDateString('es-MX', { day: 'numeric', month: 'long', year: 'numeric' });
        }, 1000);
    }
}" @init="init()" class="text-right">
    <div class="text-sm font-bold text-gray-900 dark:text-slate-100" x-text="time"></div>
    <p class="text-[10px] text-gray-500 dark:text-slate-400 mt-0.5" x-text="date"></p>
</div>
