// Chatbot de RuralMed
class Chatbot {
    constructor() {
        this.isDoctorMode = false;
        this.messagesContainer = document.getElementById('chat-messages');
        this.userInput = document.getElementById('user-input');
        this.sendBtn = document.getElementById('send-btn');
        this.quickBtns = document.querySelectorAll('.quick-btn');
        this.themeToggle = document.getElementById('chatbot-theme-toggle');
        
        this.init();
    }

    init() {
        // Event listeners
        this.sendBtn.addEventListener('click', () => this.handleSend());
        this.userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.handleSend();
        });

        this.quickBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const action = btn.dataset.action;
                this.handleQuickAction(action);
            });
        });

        this.themeToggle.addEventListener('click', () => this.toggleTheme());

        // Cargar tema guardado
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        this.updateThemeIcon(savedTheme);
    }

    toggleTheme() {
        const html = document.documentElement;
        const currentTheme = html.getAttribute('data-theme') || 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        this.updateThemeIcon(newTheme);
    }

    updateThemeIcon(theme) {
        const icon = this.themeToggle.querySelector('i');
        icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }

    handleQuickAction(action) {
        let message = '';
        switch(action) {
            case 'horarios':
                message = '¿Cuáles son los horarios de los médicos?';
                break;
            case 'especialidades':
                message = '¿Qué especialidades hay disponibles?';
                break;
            case 'agendar':
                message = 'Quiero agendar una cita';
                break;
            case 'modo-doctor':
                message = 'Modo Doctor RURALMED123';
                break;
        }
        this.userInput.value = message;
        this.handleSend();
    }

    handleSend() {
        const message = this.userInput.value.trim();
        if (!message) return;

        // Mostrar mensaje del usuario
        this.addMessage(message, 'user');
        this.userInput.value = '';

        // Simular respuesta del bot
        setTimeout(() => {
            this.getBotResponse(message);
        }, 600);
    }

    addMessage(text, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}-message`;
        
        const avatar = document.createElement('div');
        avatar.className = 'message-avatar';
        avatar.innerHTML = type === 'user' ? 
            '<i class="fas fa-user"></i>' : 
            '<i class="fas fa-robot"></i>';
        
        const content = document.createElement('div');
        content.className = 'message-content';
        content.innerHTML = `<p>${text}</p>`;
        
        messageDiv.appendChild(avatar);
        messageDiv.appendChild(content);
        
        this.messagesContainer.appendChild(messageDiv);
        this.scrollToBottom();
    }

    scrollToBottom() {
        this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
    }

    getBotResponse(userMessage) {
        let response = '';
        const messageLower = userMessage.toLowerCase();

        // Verificar modo doctor
        if (messageLower.includes('modo doctor') || messageLower.includes('modo-doctor')) {
            if (userMessage.includes('RURALMED123') || userMessage.includes('123456')) {
                this.isDoctorMode = true;
                response = '✅ <strong>Modo Doctor activado exitosamente!</strong><br><br>Ahora puedes consultar:<br>• Stock de medicamentos<br>• Tu horario de atencion<br><br>¿Que deseas consultar?';
                this.addMessage(response, 'bot');
                return;
            } else {
                response = '⚠️ Para acceder al Modo Doctor necesitas la clave. Por favor escribe: "Modo Doctor [Código]"';
                this.addMessage(response, 'bot');
                return;
            }
        }

        // Respuestas para modo doctor
        if (this.isDoctorMode) {
            if (messageLower.includes('paracetamol') || messageLower.includes('medicamento') || 
                messageLower.includes('stock') || messageLower.includes('inventario')) {
                
                response = '📋 <strong>Consulta de Stock:</strong><br><br>• <strong>Paracetamol 500mg:</strong> 150 unidades disponibles<br>• <strong>Ibuprofeno 400mg:</strong> 120 unidades disponibles<br>• <strong>Amoxicilina 500mg:</strong> 80 unidades disponibles<br>• <strong>Omeprazol 20mg:</strong> 95 unidades disponibles';
                
                this.addMessage(response, 'bot');
                return;
            }

            if (messageLower.includes('horario') || messageLower.includes('turnos') || 
                messageLower.includes('citas')) {
                
                response = '📅 <strong>Tu Horario (Dr. Ricardo Andres):</strong><br><br>• <strong>Lunes:</strong> 08:00 - 14:00<br>• <strong>Martes:</strong> 08:00 - 14:00<br>• <strong>Miercoles:</strong> 14:00 - 20:00<br>• <strong>Jueves:</strong> 08:00 - 14:00<br>• <strong>Viernes:</strong> 08:00 - 14:00';
                
                this.addMessage(response, 'bot');
                return;
            }
        }

        // Respuestas para pacientes (modo publico)
        if (messageLower.includes('especialidad')) {
            response = '🏥 <strong>Especialidades disponibles:</strong><br><br>• <strong>Medicina General</strong><br>• <strong>Enfermería</strong><br>• <strong>Pediatría</strong><br>• <strong>Cardiología</strong><br>• <strong>Ginecología</strong><br>• <strong>Psicología</strong><br>• <strong>Fisioterapia</strong>';
            
            this.addMessage(response, 'bot');
            return;
        }

        if (messageLower.includes('horario') || messageLower.includes('doctor') || 
            messageLower.includes('médico')) {
            
            response = '⏰ <strong>Horarios de atención:</strong><br><br><strong>Dr. Ricardo Andres Villanueva Castro</strong><br>• Lunes - Viernes: 08:00 - 14:00<br><br><strong>Dra. Elena Margarita Salazar Mendez</strong><br>• Lunes, Miércoles, Viernes: 08:00 - 14:00<br><br><strong>Dr. Jorge Luis Ramirez Toledo</strong><br>• Martes y Jueves: 14:00 - 20:00';
            
            this.addMessage(response, 'bot');
            return;
        }

        if (messageLower.includes('cita') || messageLower.includes('agendar') || 
            messageLower.includes('reservar')) {
            
            response = '📝 <strong>Para agendar una cita:</strong><br><br>1. Inicia sesion en el sistema RuralMed<br>2. Ve a la seccion de "Generar Cita"<br>3. Selecciona el doctor y la fecha<br><br>O puedes comunicarte con recepcion al telefono: <strong>987-654-321</strong>';
            
            this.addMessage(response, 'bot');
            return;
        }

        if (messageLower.includes('medicamento') || messageLower.includes('farmacia') || 
            messageLower.includes('inventario') || messageLower.includes('stock')) {
            
            response = 'Lo siento, no tengo acceso al inventario de farmacia para pacientes. Por favor, comunicate directamente con el area de recepcion.';
            
            this.addMessage(response, 'bot');
            return;
        }

        // Si el paciente describe sintomas
        if (messageLower.includes('dolor') || messageLower.includes('fiebre') || 
            messageLower.includes('malestar') || messageLower.includes('sintoma')) {
            
            response = 'Entiendo su situacion, pero como asistente virtual no puedo dar diagnosticos. Le sugiero agendar una cita con el especialista en <strong>Medicina General</strong> para una evaluacion adecuada.';
            
            this.addMessage(response, 'bot');
            return;
        }

        // Respuesta por defecto
        response = '¿En qué puedo ayudarte? Puedes consultar sobre horarios, especialidades o agendar una cita. Si eres profesional medico, usa el "Modo Doctor".';
        
        this.addMessage(response, 'bot');
    }
}

// Inicializar el chatbot cuando la pagina se carga
document.addEventListener('DOMContentLoaded', () => {
    new Chatbot();
});
