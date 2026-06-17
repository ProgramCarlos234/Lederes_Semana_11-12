            </div><!-- end content-area -->
        </main>
    </div><!-- end container -->

    <!-- Chatbot Widget -->
    <style>
        #chatbot-widget {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }

        #chatbot-toggle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #2E8B57;
            color: white;
            border: none;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(46, 139, 87, 0.3);
            transition: all 0.3s ease;
        }

        #chatbot-toggle:hover {
            background: #256f47;
            transform: scale(1.1);
        }

        #chatbot-popup {
            width: 380px;
            height: 520px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            display: none;
            flex-direction: column;
            margin-bottom: 10px;
        }

        #chatbot-popup.active {
            display: flex;
        }

        #chatbot-header {
            background: #2E8B57;
            color: white;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #chatbot-header .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        #chatbot-close {
            background: transparent;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }

        #chatbot-iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>

    <div id="chatbot-widget">
        <div id="chatbot-popup">
            <div id="chatbot-header">
                <div class="logo">
                    <i class="fas fa-heartbeat"></i>
                    Asistente RuralMed
                </div>
                <button id="chatbot-close" onclick="toggleChatbot()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <iframe id="chatbot-iframe" src="chatbot/index.html"></iframe>
        </div>
        <button id="chatbot-toggle" onclick="toggleChatbot()" title="Asistente Médico">
            <i class="fas fa-comments"></i>
        </button>
    </div>

    <script>
        function toggleChatbot() {
            const popup = document.getElementById('chatbot-popup');
            popup.classList.toggle('active');
        }
    </script>

    <!-- Scripts -->
    <script src="assets/js/main.js"></script>
</body>
</html>
