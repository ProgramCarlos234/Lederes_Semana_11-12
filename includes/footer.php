            </div><!-- end content-area -->
        </main>
    </div><!-- end container -->

    <!-- Botón Flotante RuralBot -->
    <style>
        #ruralbot-widget {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
        }

        #ruralbot-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            background: linear-gradient(135deg, #2E8B57 0%, #3CB371 100%);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(46, 139, 87, 0.35);
            transition: all 0.3s ease;
        }

        #ruralbot-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(46, 139, 87, 0.45);
        }

        #ruralbot-btn i {
            font-size: 20px;
        }

        #ruralbot-popup {
            width: 420px;
            height: 620px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            display: none;
            flex-direction: column;
            margin-bottom: 14px;
        }

        #ruralbot-popup.active {
            display: flex;
        }

        #ruralbot-header {
            background: linear-gradient(135deg, #2E8B57 0%, #3CB371 100%);
            color: white;
            padding: 18px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #ruralbot-header .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 18px;
        }

        #ruralbot-header .logo i {
            font-size: 24px;
        }

        #ruralbot-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        #ruralbot-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        #ruralbot-iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>

    <div id="ruralbot-widget">
        <div id="ruralbot-popup">
            <div id="ruralbot-header">
                <div class="logo">
                    <i class="fas fa-heartbeat"></i>
                    RuralBot
                </div>
                <button id="ruralbot-close" onclick="toggleRuralBot()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <iframe id="ruralbot-iframe" src="chatbot/index.html"></iframe>
        </div>
        <button id="ruralbot-btn" onclick="toggleRuralBot()">
            <i class="fas fa-comments"></i>
            RuralBot
        </button>
    </div>

    <script>
        function toggleRuralBot() {
            const popup = document.getElementById('ruralbot-popup');
            popup.classList.toggle('active');
        }
    </script>

    <!-- Scripts -->
    <script src="assets/js/main.js"></script>
</body>
</html>
