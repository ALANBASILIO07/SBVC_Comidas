/**
 * Nombre del archivo        : establecimiento.js
 * Descripción               : Validación y manejo UI para formulario de establecimiento.
 *                            Realiza sanitización de campos, validaciones cliente,
 *                            muestra errores acumulados con SweetAlert2 e inserta
 *                            mensajes inline y estilos en los campos inválidos.
 * Fecha de creación         : 14/01/2026
 * Elaboró                   : Alan Osvaldo Basilio Delgado
 * Fecha de liberación       : 14/01/2026
 * Autorizó                  : Maileth Patiño Ensastegui
 * Versión                   : 1.1
 * Fecha de mantenimiento    : 14/01/2026
 * Folio de mantenimiento    :
 * Tipo de mantenimiento     : UX / Validación y compatibilidad Livewire
 * Descripción del mantenimiento: Se adapta a ES Modules y se corrige regex para compatibilidad con Vite/Rollup.
 * Responsable               : Alan Osvaldo Basilio Delgado
 * Revisor                   : Maileth Patiño Ensastegui
 */

/* PROLOGUE: Validaciones cliente para formulario establecimiento */
/* Archivo: resources/js/establecimiento.js */

// Exportamos una función por defecto para que app.js pueda importarla.
export default function initEstablecimientoForm () {
  'use strict';

  // Regex seguro: evita secuencias problemáticas en build.
  // Permite letras latinas extendidas, dígitos, espacios y estos símbolos: . - & , ( ) '
  const nameRegex = /^[A-Za-zÀ-ÿ0-9\s.\-&,()']+$/;

  const sanitizeText = (value) => {
    if (!value) return '';
    return String(value).replace(/[\x00-\x1F\x7F]/g, '').trim();
  };

  const markInvalidField = (el, message) => {
    if (!el) return;
    el.classList.add('is-invalid', 'border-red-600', 'ring-1', 'ring-red-200');
    let next = el.nextElementSibling;
    if (next && next.classList && next.classList.contains('invalid-feedback')) {
      next.remove();
    }
    const span = document.createElement('div');
    span.className = 'invalid-feedback text-sm mt-1 text-red-600';
    span.textContent = message;
    el.insertAdjacentElement('afterend', span);
  };

  const clearInvalidField = (el) => {
    if (!el) return;
    el.classList.remove('is-invalid', 'border-red-600', 'ring-1', 'ring-red-200');
    let next = el.nextElementSibling;
    if (next && next.classList && next.classList.contains('invalid-feedback')) {
      next.remove();
    }
  };

  const validateForm = (form) => {
    const errors = [];
    if (!form) return errors;

    const nombreEl = form.querySelector('[name="nombre"]');
    const correoEl = form.querySelector('[name="email"]');
    const telefonoEl = form.querySelector('[name="telefono"]');

    [nombreEl, correoEl, telefonoEl].forEach(el => { if(el) clearInvalidField(el); });

    const nombre = sanitizeText(nombreEl?.value || '');
    const correo = sanitizeText(correoEl?.value || '');
    const telefono = sanitizeText(telefonoEl?.value || '');

    // Nombre: requerido + regex
    if (!nombre) {
      errors.push({ field: nombreEl, msg: 'El nombre es requerido.' });
    } else if (!nameRegex.test(nombre)) {
      errors.push({ field: nombreEl, msg: 'Nombre inválido: solo letras, números y . - & , ( ) \' permitidos.' });
    }

    // Correo: requerido + simple formato
    if (!correo) {
      errors.push({ field: correoEl, msg: 'El correo es requerido.' });
    } else {
      const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRe.test(correo)) {
        errors.push({ field: correoEl, msg: 'Correo inválido.' });
      }
    }

    // Teléfono: opcional pero si existe debe cumplir patrón
    if (telefono) {
      const telRe = /^[\d+\s()-]{6,20}$/;
      if (!telRe.test(telefono)) {
        errors.push({ field: telefonoEl, msg: 'Teléfono inválido.' });
      }
    }

    return errors;
  };

  const showErrorsWithSweetAlert = (errors) => {
    if (!errors || errors.length === 0) return;

    const list = errors.map(e => `• ${e.msg}`).join('<br>');

    if (window.Swal && typeof window.Swal.fire === 'function') {
      window.Swal.fire({
        title: 'Por favor revisa los siguientes errores',
        html: list,
        icon: 'error',
        confirmButtonText: 'Aceptar',
        customClass: {
          confirmButton: 'sbvc-btn sbvc-btn-primary'
        },
        buttonsStyling: false
      });
    } else {
      alert(errors.map(e => e.msg).join('\n'));
    }

    errors.forEach(e => {
      if (e.field) markInvalidField(e.field, e.msg);
    });
  };

  // Attach to form submit (form id: establecimientoForm)
  const form = document.querySelector('#establecimientoForm');
  if (form) {
    form.addEventListener('submit', (ev) => {
      const errors = validateForm(form);
      if (errors.length > 0) {
        ev.preventDefault();
        showErrorsWithSweetAlert(errors);
        return false;
      }
      // If you prefer AJAX, handle fetch here. Otherwise allow normal submit.
      return true;
    });
  }

  // Public API (if needed)
  return {
    validateForm,
    sanitizeText
  };
}