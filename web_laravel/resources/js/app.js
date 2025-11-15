// --- Lógica para la página de "Completar Perfil de Cliente" ---
// "Gating": Solo se ejecuta si encontramos el selector de formalidad.
const formalidadSelect = document.getElementById('formalidad');

if (formalidadSelect) {
    // Estamos en la página correcta, inicializamos la lógica.
    inicializarLogicaPerfilCliente();
}

function inicializarLogicaPerfilCliente() {
    // ===== ELEMENTOS DEL DOM =====
    // 'formalidadSelect' ya está definido arriba
    const seccionFiscal = document.getElementById('seccion-fiscal');
    const abreDiasFestivosCheckbox = document.getElementById('abre_dias_festivos');
    const seccionFestivos = document.getElementById('seccion-festivos');
    
    // Headers
    const headerFiscal = document.getElementById('header-fiscal');
    const headerDireccion = document.getElementById('header-direccion');
    const headerPagos = document.getElementById('header-pagos');
    const headerHorarios = document.getElementById('header-horarios');

    // ===== FUNCIÓN: MOSTRAR/OCULTAR SECCIÓN FISCAL =====
    function toggleFiscalSection() {
        const esInformal = formalidadSelect.value === 'informal';
        
        if (esInformal) {
            seccionFiscal.style.display = 'none';
            renumerarCards(false);
        } else {
            seccionFiscal.style.display = 'block';
            renumerarCards(true);
        }
    }

    // ===== FUNCIÓN: MOSTRAR/OCULTAR HORARIO DE DÍAS FESTIVOS =====
    function toggleFestivosSection() {
        const abreFestivos = abreDiasFestivosCheckbox.checked;
        
        if (abreFestivos) {
            seccionFestivos.style.display = 'flex';
        } else {
            seccionFestivos.style.display = 'none';
            const inputsFestivos = seccionFestivos.querySelectorAll('input[type="time"]');
            inputsFestivos.forEach(input => input.value = '');
        }
    }

    // ===== FUNCIÓN: RENUMERAR CARDS =====
    function renumerarCards(incluyeFiscal) {
        if (incluyeFiscal) {
            headerFiscal.textContent = '2. Información Fiscal';
            headerDireccion.textContent = '3. Dirección del Establecimiento';
            headerPagos.textContent = '4. Métodos de Pago Aceptados';
            headerHorarios.textContent = '5. Horarios de Atención';
        } else {
            headerDireccion.textContent = '2. Dirección del Establecimiento';
            headerPagos.textContent = '3. Métodos de Pago Aceptados';
            headerHorarios.textContent = '4. Horarios de Atención';
        }
    }

    // ===== INICIALIZAR AL CARGAR LA PÁGINA =====
    // IMPORTANTE: Asegúrate de que este código se ejecute DESPUÉS de DOMContentLoaded
    // Si tu app.js se carga al final del <body> o usa `defer`, esto es seguro.
    // Si se carga en el <head>, envuelve todo esto en un listener 'DOMContentLoaded'.
    toggleFiscalSection();
    toggleFestivosSection();

    // ===== EVENT LISTENERS =====
    formalidadSelect.addEventListener('change', toggleFiscalSection);
    abreDiasFestivosCheckbox.addEventListener('change', toggleFestivosSection);

    // ... (Aquí puedes agregar las validaciones de RFC, CP, Teléfono) ...
}