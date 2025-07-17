/**
 * Sistema Global de Manejo de Uploads de Imágenes
 * Incluye preview con animaciones y notificaciones toast
 */

/**
 * Versión para Livewire - NO muestra toast (Livewire lo maneja)
 */
function previewImageWithAnimationLivewire(event, querySelector, imageType = 'imagen') {
    // Simplemente llamar a la función original pero sin toast
    const input = event.target;
    const imgPreview = document.querySelector(querySelector);

    if (!input.files.length) return;

    const file = input.files[0];

    // Validación básica del archivo
    if (!file.type.startsWith('image/')) {
        showImageErrorToast('El archivo seleccionado no es una imagen válida');
        input.value = '';
        return;
    }

    // Validación de tamaño (2MB)
    if (file.size > 2 * 1024 * 1024) {
        showImageErrorToast('La imagen no puede ser mayor a 2MB');
        input.value = '';
        return;
    }

    // Mostrar spinner
    const spinner = document.getElementById('cover-image-spinner');
    if (spinner) {
        spinner.style.display = 'flex';
    }

    const objectURL = URL.createObjectURL(file);

    // Animación simple
    imgPreview.style.transition = 'opacity 0.2s ease';
    imgPreview.style.opacity = '0.5';

    setTimeout(() => {
        imgPreview.src = objectURL;
        imgPreview.style.opacity = '1';

        // Ocultar spinner
        if (spinner) {
            spinner.style.display = 'none';
        }

        // NO mostrar toast - Livewire lo hará
    }, 500);
}

/**
 * Previsualiza imagen con animación suave y notificación
 * @param {Event} event - Evento del input file
 * @param {string} querySelector - Selector CSS del elemento img
 * @param {string} imageType - Tipo de imagen para personalizar mensaje
 */
function previewImageWithAnimation(event, querySelector, imageType = 'imagen') {
    const input = event.target;
    const imgPreview = document.querySelector(querySelector);

    if (!input.files.length) return;

    const file = input.files[0];

    // Validación básica del archivo
    if (!file.type.startsWith('image/')) {
        showImageErrorToast('El archivo seleccionado no es una imagen válida');
        input.value = ''; // Limpiar input
        return;
    }

    // Validación del nombre del archivo (máximo 100 caracteres)
    if (file.name.length > 100) {
        showImageErrorToast('El nombre del archivo es demasiado largo. Máximo 100 caracteres.');
        input.value = ''; // Limpiar input
        return;
    }

    // Validación de tamaño (2MB)
    if (file.size > 2 * 1024 * 1024) {
        showImageErrorToast('La imagen no puede ser mayor a 2MB');
        input.value = ''; // Limpiar input
        return;
    }

    // Mostrar spinner si existe (para covers y productos)
    const spinner = document.getElementById('cover-image-spinner') ||
        document.getElementById('product-image-spinner');
    if (spinner) {
        spinner.style.display = 'flex';
    }

    const objectURL = URL.createObjectURL(file);

    // Animación ligera y rápida
    imgPreview.style.transition = 'opacity 0.2s ease';
    imgPreview.style.opacity = '0.5';

    setTimeout(() => {
        imgPreview.src = objectURL;
        imgPreview.style.opacity = '1';

        // Ocultar spinner si existe
        if (spinner) {
            spinner.style.display = 'none';
        }

        // Mostrar notificación de éxito SOLO para covers (no Livewire)
        showImageUploadToast(imageType);
    }, 500); // Aumentar tiempo para mostrar el spinner
}

/**
 * Muestra notificación toast de éxito para imagen cargada
 * @param {string} imageType - Tipo de imagen
 */
function showImageUploadToast(imageType = 'imagen') {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: `¡${capitalizeFirst(imageType)} cargada!`,
            text: `La ${imageType.toLowerCase()} se ha cargado correctamente`,
            icon: 'success',
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            customClass: {
                popup: 'rounded-lg shadow-lg border-l-4 border-green-500 bg-white'
            }
        });
    }
}

/**
 * Muestra notificación toast de error
 * @param {string} message - Mensaje de error
 */
function showImageErrorToast(message = 'Error al cargar la imagen') {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¡Error!',
            text: message,
            icon: 'error',
            timer: 2500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            customClass: {
                popup: 'rounded-lg shadow-lg border-l-4 border-red-500 bg-white'
            }
        });
    }
}

/**
 * Capitaliza la primera letra de una cadena
 * @param {string} str - Cadena a capitalizar
 * @returns {string} - Cadena capitalizada
 */
function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

/**
 * Función específica para Livewire - maneja preview con eventos
 * @param {Event} event - Evento del input
 * @param {string} querySelector - Selector del elemento img
 * @param {string} wireModel - Nombre del modelo Livewire
 * @param {string} imageType - Tipo de imagen
 */
function previewImageLivewire(event, querySelector, wireModel, imageType = 'imagen') {
    const input = event.target;
    const imgPreview = document.querySelector(querySelector);

    if (!input.files.length) return;

    const file = input.files[0];

    // Validación básica
    if (!file.type.startsWith('image/')) {
        showImageErrorToast('El archivo seleccionado no es una imagen válida');
        input.value = '';
        return;
    }

    // Validación del nombre del archivo (máximo 100 caracteres)
    if (file.name.length > 100) {
        showImageErrorToast('El nombre del archivo es demasiado largo. Máximo 100 caracteres.');
        input.value = '';
        return;
    }

    // Validación de tamaño (1MB = 1024 * 1024 bytes)
    if (file.size > 1024 * 1024) {
        showImageErrorToast('La imagen no puede ser mayor a 1MB');
        input.value = '';
        return;
    }

    const objectURL = URL.createObjectURL(file);

    // Animación optimizada - solo opacity para mejor rendimiento
    imgPreview.style.transition = 'opacity 0.2s ease';
    imgPreview.style.opacity = '0.5';

    setTimeout(() => {
        imgPreview.src = objectURL;
        imgPreview.style.opacity = '1';

        showImageUploadToast(imageType);
    }, 100);
}

// Hacer las funciones accesibles globalmente
window.previewImageWithAnimation = previewImageWithAnimation;
window.previewImageWithAnimationLivewire = previewImageWithAnimationLivewire;
window.previewImageLivewire = previewImageLivewire;
window.showImageUploadToast = showImageUploadToast;
window.showImageErrorToast = showImageErrorToast;
