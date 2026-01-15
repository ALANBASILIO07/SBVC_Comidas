/**
 * Nombre del archivo        : app.js
 * Descripción               : JavaScript principal de la aplicación.
 *                            Punto de entrada principal que:
 *                              - Inicializa utilidades globales (SweetAlert2, Toasts).
 *                              - Implementa guardias de navegación protegida (wire:navigate).
 *                              - Carga información de autenticación inyectada por Blade.
 *                              - Inicializa validaciones de formulario (establecimiento).
 * Fecha de creación         : 06/01/2026
 * Elaboró                   : Alan Osvaldo Basilio Delgado
 * Fecha de liberación       : 06/01/2026
 * Autorizó                  : Maileth Patiño Ensastegui
 * Versión                   : 1.4
 * Fecha de mantenimiento    : 18/01/2026
 * Folio de mantenimiento    :
 * Tipo de mantenimiento     : UX / Compatibilidad Livewire + Modularización validación establecimiento
 * Descripción del mantenimiento: Guard para evitar loading infinito con wire:navigate en rutas protegidas + logs,
 *                                y modularización validación formulario establecimiento.
 * Responsable               : Alan Osvaldo Basilio Delgado
 * Revisor                   : Maileth Patiño Ensastegui
 */

/* PROLOGUE: Entrada JS principal - importa bootstrap, SweetAlert2 y módulos app. */
/* NOTA: No eliminar los métodos ni la lógica existente; se ha mantenido todo funcional. */

// ===== IMPORTAR SWEETALERT2 =====
import Swal from 'sweetalert2';

// Hacer SweetAlert2 disponible globalmente
window.Swal = Swal;

// Importar validación y toggle de establecimiento
import initEstablecimientoForm from './establecimiento.js';

// ===============================
// [SBVC][FIX] Guard anti-loading wire:navigate + logs (mejorado)
// ===============================
window.SBVC_DEBUG = true;
function sbvcLog(...args) { if (!window.SBVC_DEBUG) return; console.log('[SBVC]', ...args); }
function sbvcWarn(...args) { if (!window.SBVC_DEBUG) return; console.warn('[SBVC]', ...args); }

/**
 * Carga la info de auth inyectada por Blade desde:
 * <script id="sbvc-auth" type="application/json">...</script>
 */
function loadSbvcAuth() {
    if (window.__SBVC_AUTH__ && typeof window.__SBVC_AUTH__ === 'object') {
        sbvcLog('loadSbvcAuth: ya existe window.__SBVC_AUTH__');
        return window.__SBVC_AUTH__;
    }

    const el = document.getElementById('sbvc-auth') || document.querySelector('script[type="application/json"][data-sbvc-auth], script[type="application/json"]#sbvc-auth');
    if (el && el.textContent) {
        try {
            const parsed = JSON.parse(el.textContent);
            window.__SBVC_AUTH__ = parsed;
            sbvcLog('loadSbvcAuth: cargado desde #sbvc-auth', parsed);
            return parsed;
        } catch (err) {
            sbvcWarn('loadSbvcAuth: error parseando #sbvc-auth JSON', err);
            window.__SBVC_AUTH__ = null;
            return null;
        }
    }

    sbvcWarn('loadSbvcAuth: no se encontró #sbvc-auth ni window.__SBVC_AUTH__');
    window.__SBVC_AUTH__ = null;
    return null;
}

function getSbvcAuth() { return window.__SBVC_AUTH__ || loadSbvcAuth(); }

/**
 * Ejecuta SweetAlert desde <script data-swal>... 
 * NO elimina el script (se conservará en el DOM). 
 * Para evitar dobles disparos marcamos dataset.swalFired = "1".
 */
function runSwalFromDom() {
    const el = document.querySelector('script[data-swal]');
    if (!el) {
        sbvcLog('runSwalFromDom: no hay script[data-swal]');
        return;
    }

    // Si ya fue disparado antes, no volver a mostrar (pero dejamos el script en DOM)
    if (el.dataset.swalFired === '1') {
        sbvcLog('runSwalFromDom: script[data-swal] ya fue disparado antes; se conserva en DOM');
        return;
    }

    try {
        const text = (el.textContent || el.innerText || '').trim();
        if (!text) {
            sbvcWarn('runSwalFromDom: script[data-swal] vacío (no se remueve por petición)');
            return;
        }

        const payload = JSON.parse(text);
        sbvcLog('runSwalFromDom payload:', payload);

        if (payload && window.Swal) {
            // Mostrar el SweetAlert con el payload inyectado por backend
            Swal.fire(payload);
            // marcamos como disparado para evitar rebotes
            try { el.dataset.swalFired = '1'; } catch (err) { /* noop */ }
            sbvcLog('runSwalFromDom: SweetAlert disparado y script marcado como fired (sin eliminar)');
        } else {
            sbvcWarn('runSwalFromDom: Swal no está disponible o payload vacío');
        }
    } catch (e) {
        sbvcWarn('runSwalFromDom: SWAL JSON inválido:', e);
    }
}

/**
 * Elimina indicadores de progreso típicos (nprogress) para evitar que
 * la barra quede visible cuando interceptamos la navegación.
 */
function clearProgressIndicators() {
    try {
        document.documentElement.classList.remove('nprogress-busy');
        document.body.classList.remove('nprogress-busy');
        if (window.NProgress && typeof window.NProgress.done === 'function') {
            window.NProgress.done();
        }
    } catch (e) {
        // noop
    }
}

/**
 * Icono rojo 'i' sin anillo negro: fondo blanco, borde rojo, sin sombra.
 */
const SBVC_ICON_HTML_RED_INFO = '<div style="width:80px;height:80px;border-radius:50%;border:5px solid #E11;background:#ffffff;color:#E11;display:flex;align-items:center;justify-content:center;font-size:42px;font-weight:700;box-shadow:none;">i</div>';

/**
 * Guard para links protegidos:
 * - Intercepta en pointerdown (más temprano que click) y keydown (Enter/Space).
 * - Llama stopImmediatePropagation() para detener listeners de Livewire antes de que arranquen.
 * - Muestra Swal con 2 botones: "Completar ahora" (verde) y "Cancelar" (rojo).
 * - Solo al confirmar hace window.location.href; en otro caso no navega.
 */
function setupProtectedNavGuard() {
    if (window.__SBVC_GUARD_INIT__) return;
    window.__SBVC_GUARD_INIT__ = true;

    // Async handler
    async function handleProtectedActivation(targetEl) {
        const guard = targetEl.getAttribute('data-sbvc-guard');
        let href = targetEl.getAttribute('href') || targetEl.dataset.href || null;
        href = href || (targetEl.querySelector && targetEl.querySelector('a') ? targetEl.querySelector('a').href : null);

        sbvcLog('handleProtectedActivation:', { guard, href });

        const auth = getSbvcAuth();
        if (!auth) {
            const reloaded = loadSbvcAuth();
            if (!reloaded) {
                sbvcWarn('auth no disponible -> fallback navegacion');
                if (href) window.location.href = href;
                return;
            }
        }

        const safeAuth = getSbvcAuth();
        if (!safeAuth) {
            sbvcWarn('safeAuth null -> fallback navegacion');
            if (href) window.location.href = href;
            return;
        }

        if (guard === 'plan-activo') {
            // Sin cliente -> completar registro
            if (!safeAuth.hasCliente) {
                sbvcLog('Usuario sin cliente: mostrar SweetAlert completar registro');
                clearProgressIndicators();

                const res = await Swal.fire({
                    iconHtml: SBVC_ICON_HTML_RED_INFO,
                    customClass: { icon: 'sbvc-swal-icon' },
                    title: 'Completa tu registro',
                    html: '<p style="margin:0;">Por favor completa tu información de cliente para continuar.</p>',
                    showCancelButton: true,
                    showConfirmButton: true,
                    confirmButtonText: 'Completar ahora',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#28a745', // verde
                    cancelButtonColor: '#dc3545',  // rojo (ahora cancelar es rojo según tu petición)
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showCloseButton: false,
                    // Mantener la alerta visible hasta la acción explícita del usuario
                });

                if (res && res.isConfirmed) {
                    window.location.href = safeAuth.routes.registroCompletar;
                    return;
                }

                // Cancel -> no navegar
                sbvcLog('Usuario canceló la alerta de completar registro; no se realiza navegación.');
                return;
            }

            // Tiene cliente pero no plan -> ir a subscripcion
            if (!safeAuth.hasPlan) {
                sbvcLog('Usuario sin plan: mostrar SweetAlert subscripcion');
                clearProgressIndicators();

                const res = await Swal.fire({
                    iconHtml: SBVC_ICON_HTML_RED_INFO,
                    customClass: { icon: 'sbvc-swal-icon' },
                    title: 'Plan requerido',
                    html: '<p style="margin:0;">Necesitas un plan activo para acceder a esta sección.</p>',
                    showCancelButton: true,
                    showConfirmButton: true,
                    confirmButtonText: 'Completar ahora',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#28a745', // verde
                    cancelButtonColor: '#dc3545',  // rojo
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showCloseButton: false,
                });

                if (res && res.isConfirmed) {
                    window.location.href = safeAuth.routes.subscripcion;
                    return;
                }

                sbvcLog('Usuario canceló la alerta de plan requerido; no se realiza navegación.');
                return;
            }

            // Si cumple requisitos: permitir navegación normal (usamos href para forzar)
            sbvcLog('Usuario cumple requisitos -> navegar', { href });
            if (href) {
                window.location.href = href;
            }
        }
    }

    // pointerdown: interceptar antes que click y Livewire
    document.addEventListener('pointerdown', (e) => {
        const el = e.target?.closest?.('[data-sbvc-guard]');
        if (!el) return;

        try {
            e.preventDefault();
            if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();
            e.stopPropagation();
        } catch (err) {}

        sbvcLog('pointerdown interceptado en elemento protegido');
        clearProgressIndicators();

        handleProtectedActivation(el).catch(err => sbvcWarn('handleProtectedActivation error', err));
    }, { capture: true, passive: false });

    // keydown accessibility (Enter / Space)
    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Enter' && e.key !== ' ') return;
        const active = document.activeElement;
        const el = active?.closest?.('[data-sbvc-guard]') || (active && active.getAttribute && active.getAttribute('data-sbvc-guard') ? active : null);
        if (!el) return;

        try {
            e.preventDefault();
            e.stopImmediatePropagation();
            e.stopPropagation();
        } catch (err) {}

        clearProgressIndicators();
        handleProtectedActivation(el).catch(err => sbvcWarn('handleProtectedActivation error', err));
    }, true);

    sbvcLog('setupProtectedNavGuard: inicializado');
}

// Livewire events: recargar auth y ejecutar swal inyectado
document.addEventListener('livewire:navigate', (ev) => sbvcLog('EVENT livewire:navigate', ev?.detail));
document.addEventListener('livewire:navigating', () => sbvcLog('EVENT livewire:navigating'));
document.addEventListener('livewire:navigated', () => {
    sbvcLog('EVENT livewire:navigated');
    loadSbvcAuth();
    runSwalFromDom();
});

// Inicialización
try {
    loadSbvcAuth();
    setupProtectedNavGuard();
} catch (e) {
    sbvcWarn('Error inicializando SBVC guard', e);
}

// DOMContentLoaded fallback
document.addEventListener('DOMContentLoaded', () => {
    sbvcLog('EVENT DOMContentLoaded');
    loadSbvcAuth();
    runSwalFromDom();
    // Inicializar validación establecimiento
    try {
        initEstablecimientoForm();
    } catch (err) {
        sbvcWarn('initEstablecimientoForm falló durante DOMContentLoaded', err);
    }
});

// ===============================
// Toast global y utilidades (sin cambios funcionales)
// ===============================
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

window.Toast = Toast;

window.showSuccess = function (message, title = '¡Éxito!') {
    Toast.fire({ icon: 'success', title, text: message });
};

window.showError = function (message, title = 'Error') {
    Toast.fire({ icon: 'error', title, text: message });
};

window.showWarning = function (message, title = 'Advertencia') {
    Toast.fire({ icon: 'warning', title, text: message });
};

window.showInfo = function (message, title = 'Información') {
    Toast.fire({ icon: 'info', title, text: message });
};

window.confirmDelete = function (message = '¿Estás seguro de que deseas eliminar este elemento?') {
    return Swal.fire({
        title: '¿Estás seguro?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });
};