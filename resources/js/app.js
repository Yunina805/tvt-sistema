import './bootstrap';

import Collapse from '@alpinejs/collapse';
Alpine.plugin(Collapse);

// ─── SweetAlert2 ──────────────────────────────────────────────────────────────
import Swal from 'sweetalert2';
window.Swal = Swal;

// Defaults compartidos
const swalToast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    },
});

const swalConfirm = Swal.mixin({
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#6b7280',
    cancelButtonText: 'Cancelar',
    reverseButtons: true,
    focusCancel: true,
    customClass: {
        popup: 'rounded-2xl',
        title: 'text-base font-black text-gray-900',
        htmlContainer: 'text-sm text-gray-500',
        confirmButton: 'rounded-xl text-xs font-black uppercase tracking-wider px-5 py-2.5',
        cancelButton: 'rounded-xl text-xs font-black uppercase tracking-wider px-5 py-2.5',
    },
});

// ─── Alpine magic: $confirm ──────────────────────────────────────────────────
document.addEventListener('alpine:init', () => {
    Alpine.magic('confirm', () => async (message, callback, options = {}) => {
        const result = await swalConfirm.fire({
            title: options.title ?? '¿Estás seguro?',
            html: message,
            confirmButtonText: options.confirmText ?? 'Sí, continuar',
            icon: options.icon ?? 'warning',
        });
        if (result.isConfirmed) callback();
    });
});

// ─── Livewire: escuchar eventos 'toast' desde PHP ─────────────────────────────
document.addEventListener('livewire:initialized', () => {
    Livewire.on('toast', ({ type, message }) => {
        const iconMap = { success: 'success', info: 'info', error: 'error', warning: 'warning' };
        swalToast.fire({
            icon: iconMap[type] ?? 'info',
            title: message,
        });
    });
});
