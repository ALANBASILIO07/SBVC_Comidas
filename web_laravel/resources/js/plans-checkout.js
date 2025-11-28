/**
 * Sistema de Checkout de Planes con PayPal
 *
 * Maneja la selección de planes y el flujo de pago con PayPal
 * Integra SweetAlert2 para notificaciones visuales
 *
 * @param {Object} config - Configuración del checkout
 * @param {string} config.createOrderUrl - URL para crear orden
 * @param {string} config.captureOrderUrl - URL para capturar orden
 * @param {string} config.csrfToken - Token CSRF de Laravel
 */
function initPlansCheckout(config)
{
    let selectedPlan = null;
    let paypalRendered = false;

    const cards = document.querySelectorAll('.plan-card');
    const radios = document.querySelectorAll('input[name="plan"]');

    // Clases para resaltar la card seleccionada
    const SELECTED_CLASSES = [
        'border-2',
        'border-orange-500',
        'ring-4',
        'ring-orange-500/40',
        'shadow-xl',
        'scale-[1.02]',
        'transform'
    ];

    /**
     * Limpia los estilos de selección de todas las cards
     */
    function clearSelectedStyles()
    {
        cards.forEach(card => card.classList.remove(...SELECTED_CLASSES));
    }

    /**
     * Aplica estilos a la card activa según el radio seleccionado
     */
    function setActiveCard()
    {
        clearSelectedStyles();

        const checked = document.querySelector('input[name="plan"]:checked');

        if (checked)
        {
            selectedPlan = checked.value;
            const card = checked.closest('.plan-card');

            if (card)
            {
                card.classList.add(...SELECTED_CLASSES);
            }
        }
    }

    /**
     * Resalta visualmente una card (feedback inmediato)
     */
    function fastHighlight(card)
    {
        clearSelectedStyles();
        card.classList.add(...SELECTED_CLASSES);
    }

    // Event listeners para las cards
    cards.forEach(card =>
    {
        // Highlight inmediato al presionar
        card.addEventListener(
            'pointerdown',
            () => fastHighlight(card),
            { passive: true }
        );

        // Seleccionar el plan al hacer click
        card.addEventListener('click', () =>
        {
            const radio = card.querySelector('input[name="plan"]');
            if (radio)
            {
                radio.checked = true;
                setActiveCard();
                ensurePayPalButtons();
            }
        });
    });

    // Event listeners para los radios
    radios.forEach(radio =>
    {
        radio.addEventListener('change', () =>
        {
            setActiveCard();
            ensurePayPalButtons();
        });
    });

    /**
     * Inicializa los botones de PayPal (solo una vez)
     */
    function ensurePayPalButtons()
    {
        if (paypalRendered) return;
        if (!window.paypal || typeof window.paypal.Buttons !== 'function')
        {
            console.warn('PayPal SDK no está disponible aún');
            return;
        }

        paypal.Buttons({
            style: {
                layout: 'vertical',
                shape: 'rect',
                color: 'gold',
                label: 'paypal'
            },

            /**
             * Validar antes de crear la orden
             */
            onClick: function (data, actions)
            {
                const checked = document.querySelector('input[name="plan"]:checked');

                if (!checked)
                {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Plan no seleccionado',
                        text: 'Por favor selecciona un plan antes de continuar',
                        confirmButtonColor: '#F7941D',
                        confirmButtonText: 'Entendido',
                        draggable: true
                    });

                    return actions.reject();
                }

                selectedPlan = checked.value;

                // No permitir pagar el plan básico (es gratis)
                if (selectedPlan === 'basico')
                {
                    Swal.fire({
                        icon: 'info',
                        title: 'Plan Básico Gratuito',
                        text: 'El plan básico es gratuito, no requiere pago',
                        confirmButtonColor: '#F7941D',
                        confirmButtonText: 'Entendido',
                        draggable: true
                    });

                    return actions.reject();
                }

                return actions.resolve();
            },

            /**
             * Crear orden en el servidor
             */
            createOrder: function (data, actions)
            {
                if (!selectedPlan)
                {
                    return actions.reject();
                }

                return fetch(config.createOrderUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': config.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ plan: selectedPlan })
                })
                .then(res =>
                {
                    if (!res.ok)
                    {
                        return res.json().then(err =>
                        {
                            throw new Error(err.message || 'Error al crear la orden');
                        });
                    }

                    return res.json();
                })
                .then(json => json.id)
                .catch(err =>
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: err.message || 'No se pudo crear la orden',
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'Entendido',
                        draggable: true
                    });

                    throw err;
                });
            },

            /**
             * Capturar el pago aprobado
             */
            onApprove: function (data, actions)
            {
                // Mostrar loading
                Swal.fire({
                    title: 'Procesando pago...',
                    text: 'Por favor espera mientras confirmamos tu pago',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () =>
                    {
                        Swal.showLoading();
                    }
                });

                return fetch(`${config.captureOrderUrl}/${data.orderID}/capture`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': config.csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(async res =>
                {
                    const json = await res.json().catch(() => ({}));

                    if (!res.ok)
                    {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al procesar el pago',
                            text: json.message || 'Ocurrió un error inesperado',
                            footer: json.debug_id ? `ID de seguimiento: ${json.debug_id}` : '',
                            confirmButtonColor: '#ef4444',
                            confirmButtonText: 'Entendido',
                            draggable: true
                        });

                        return;
                    }

                    // Pago exitoso
                    Swal.fire({
                        icon: 'success',
                        title: '¡Pago completado!',
                        html: `
                            <p class="mb-2">${json.message || '¡Tu plan ha sido activado exitosamente!'}</p>
                            <p class="text-sm text-gray-600">Plan: <strong class="text-orange-500">${selectedPlan.toUpperCase()}</strong></p>
                        `,
                        timer: 4000,
                        timerProgressBar: true,
                        confirmButtonColor: '#42A958',
                        confirmButtonText: 'Continuar',
                        draggable: true
                    })
                    .then(() =>
                    {
                        if (json.redirect)
                        {
                            window.location.href = json.redirect;
                        }
                        else
                        {
                            // Recargar página si no hay redirect
                            window.location.reload();
                        }
                    });
                })
                .catch(err =>
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo conectar con el servidor. Por favor intenta nuevamente.',
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'Entendido',
                        draggable: true
                    });
                });
            },

            /**
             * Usuario canceló el pago
             */
            onCancel: function ()
            {
                Swal.fire({
                    icon: 'info',
                    title: 'Pago cancelado',
                    text: 'Has cancelado el proceso de pago',
                    confirmButtonColor: '#6b7280',
                    confirmButtonText: 'Entendido',
                    draggable: true
                });
            },

            /**
             * Error con PayPal
             */
            onError: function (err)
            {
                console.error('PayPal error:', err);

                Swal.fire({
                    icon: 'error',
                    title: 'Error con PayPal',
                    text: 'Ocurrió un error con el servicio de PayPal. Por favor intenta nuevamente.',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Entendido',
                    draggable: true
                });
            }
        })
        .render('#paypal-button-container');

        paypalRendered = true;
    }

    /**
     * Espera a que el SDK de PayPal esté listo
     */
    function whenSdkReady()
    {
        if (window.paypal && typeof window.paypal.Buttons === 'function')
        {
            ensurePayPalButtons();
        }
        else
        {
            setTimeout(whenSdkReady, 150);
        }
    }

    // Iniciar cuando el DOM esté listo
    whenSdkReady();
}

// Exponer globalmente
window.initPlansCheckout = initPlansCheckout;

export default initPlansCheckout;
