// Sistema de tabs
document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', () => {
        const tabName = tab.getAttribute('data-tab');
        
        // Actualizar tabs
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        
        // Actualizar formularios
        document.querySelectorAll('.login-form').forEach(form => form.classList.remove('active'));
        document.querySelector(`.login-form.${tabName}`).classList.add('active');
    });
});

// Selector de tipo de usuario
document.querySelectorAll('.user-type').forEach(type => {
    type.addEventListener('click', () => {
        const userType = type.getAttribute('data-type');
        
        // Actualizar selección
        document.querySelectorAll('.user-type').forEach(t => t.classList.remove('selected'));
        type.classList.add('selected');
        
        // Actualizar campo hidden
        const form = type.closest('.login-form');
        const hiddenInput = form.querySelector('input[type="hidden"]');
        hiddenInput.value = userType;
    });
});

// Notificaciones
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.textContent = message;
    
    document.querySelector('.content-area').prepend(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Búsqueda en tiempo real
document.querySelector('.search-box input')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    // Implementar búsqueda según la página actual
});

// Cargar gráficos cuando la página esté lista
document.addEventListener('DOMContentLoaded', function() {
    // Los gráficos se inicializan en sus respectivas páginas
});

// Sidebar toggle para pantallas móviles
document.addEventListener('click', function(e) {
    // Toggle al hacer click en el botón de menú
    if (e.target.closest('.menu-toggle')) {
        document.body.classList.toggle('sidebar-open');
        // Añadir overlay si no existe
        if (!document.querySelector('.sidebar-overlay')) {
            const overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            overlay.addEventListener('click', () => document.body.classList.remove('sidebar-open'));
            document.body.appendChild(overlay);
        }
        return;
    }

    // Cerrar sidebar si se hace click en un enlace y estamos en móvil
    if (e.target.closest('.sidebar a') && window.innerWidth <= 992) {
        document.body.classList.remove('sidebar-open');
    }
});

// Manejo de formularios con AJAX
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Mostrar loading
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
        submitBtn.disabled = true;
        
        fetch(this.action || window.location.href, {
            method: this.method,
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                }
            } else {
                showNotification(data.message, 'danger');
            }
        })
        .catch(error => {
            showNotification('Error en la solicitud', 'danger');
            console.error('Error:', error);
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
});