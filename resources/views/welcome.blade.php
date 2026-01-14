<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Flotta - Fleet Management System</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f7fafc;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        header {
            padding: 20px 0;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .nav-links a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero {
            padding: 120px 0 160px;
            color: white;
            position: relative;
            overflow: hidden;
            min-height: 600px;
            display: flex;
            align-items: center;
        }

        .hero-video-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.45) 0%, rgba(118, 75, 162, 0.45) 100%);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 100%;
        }

        .hero h1 {
            font-size: 64px;
            font-weight: 700;
            margin-bottom: 20px;
            animation: fadeInUp 0.8s ease;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero p {
            font-size: 24px;
            margin-bottom: 40px;
            animation: fadeInUp 0.8s ease 0.2s backwards;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            animation: fadeInUp 0.8s ease 0.4s backwards;
        }

        .btn {
            padding: 16px 40px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 18px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-primary {
            background: white;
            color: #667eea;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-3px);
        }

        /* Features Section */
        .features {
            background: white;
            padding: 80px 0;
            margin-top: -60px;
            margin-left: 30px;
            margin-right: 30px;
            border-radius: 30px;
            position: relative;
            z-index: 5;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-top: 60px;
        }

        .feature-card {
            padding: 40px;
            border-radius: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 30px;
        }

        .feature-card h3 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #2d3748;
        }

        .feature-card p {
            color: #4a5568;
            line-height: 1.6;
        }

        .section-title {
            text-align: center;
            font-size: 48px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 20px;
        }

        .section-subtitle {
            text-align: center;
            font-size: 20px;
            color: #718096;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Stats Section */
        .stats {
            padding: 80px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-top: 40px;
        }

        .stat-card {
            text-align: center;
            color: white;
        }

        .stat-number {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 18px;
            opacity: 0.9;
        }

        /* FAQ Section */
        .faq {
            background: white;
            padding: 80px 0;
        }

        .faq-container {
            max-width: 800px;
            margin: 60px auto 0;
        }

        .faq-item {
            margin-bottom: 20px;
            border-radius: 15px;
            overflow: hidden;
            background: #f7fafc;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .faq-item:hover {
            border-color: #667eea;
        }

        .faq-question {
            padding: 25px 30px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            font-size: 18px;
            color: #2d3748;
            background: white;
            transition: all 0.3s ease;
        }

        .faq-question:hover {
            background: #f7fafc;
        }

        .faq-icon {
            font-size: 24px;
            transition: transform 0.3s ease;
            color: #667eea;
        }

        .faq-item.active .faq-icon {
            transform: rotate(45deg);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: white;
        }

        .faq-answer-content {
            padding: 0 30px 25px 30px;
            color: #4a5568;
            line-height: 1.8;
        }

        .faq-item.active .faq-answer {
            max-height: 500px;
        }

        /* Footer */
        footer {
            background: #2d3748;
            color: white;
            padding: 40px 0;
            text-align: center;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero {
                padding: 80px 0 100px;
                min-height: 500px;
            }

            .hero h1 {
                font-size: 40px;
            }

            .hero p {
                font-size: 18px;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .section-title {
                font-size: 32px;
            }

            .nav-links {
                gap: 10px;
            }

            .nav-links a {
                padding: 8px 16px;
                font-size: 14px;
            }

            .faq-question {
                padding: 20px;
                font-size: 16px;
            }

            .faq-answer-content {
                padding: 0 20px 20px 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav>
                <div class="logo">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect width="40" height="40" rx="10" fill="white" fill-opacity="0.2" />
                        <path d="M12 20L20 12L28 20L20 28L12 20Z" fill="white" />
                    </svg>
                    Flotta
                </div>
                @if (Route::has('login'))
                    <div class="nav-links">
                        @auth
                            <a href="{{ url('/dashboard') }}">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <!-- Video Background -->
        <video class="hero-video-bg" autoplay muted loop playsinline>
            <source src="{{ asset('videos/flotta.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <!-- Overlay -->
        <div class="hero-overlay"></div>

        <!-- Content -->
        <div class="container">
            <div class="hero-content">
                <h1>Gestione Prenotazioni Flotta</h1>
                <p>Il sistema completo per gestire prenotazioni, manutenzioni e disponibilità dei tuoi veicoli aziendali
                </p>
                <div class="cta-buttons">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">Vai alla Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">Inizia Ora</a>
                        <a href="#features" class="btn btn-secondary">Scopri di Più</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <h2 class="section-title">Funzionalità Principali</h2>
            <p class="section-subtitle">Tutto ciò di cui hai bisogno per gestire la tua flotta in modo efficiente e
                professionale</p>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📅</div>
                    <h3>Timeline Interattivo</h3>
                    <p>Visualizza tutte le prenotazioni in un'interfaccia drag & drop intuitiva. Sposta e ridimensiona
                        le prenotazioni con un semplice gesto.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🚗</div>
                    <h3>Gestione Veicoli</h3>
                    <p>Gestisci l'intera flotta con informazioni dettagliate: targa, marca, modello e stato di
                        disponibilità in tempo reale.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🔧</div>
                    <h3>Manutenzione Programmata</h3>
                    <p>Pianifica e monitora le manutenzioni preventive. Il sistema blocca automaticamente i veicoli
                        durante gli interventi programmati.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">✓</div>
                    <h3>Assegnazione Automatica</h3>
                    <p>Il sistema trova automaticamente i veicoli disponibili per le date richieste, evitando conflitti
                        con manutenzioni e altre prenotazioni.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>Gestione Conducenti</h3>
                    <p>Gestisci l'anagrafica completa dei conducenti e associa facilmente le prenotazioni ai piloti
                        della tua organizzazione.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🔍</div>
                    <h3>Ricerca Avanzata</h3>
                    <p>Trova rapidamente veicoli, conducenti e prenotazioni con filtri intelligenti e ricerca in tempo
                        reale.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <h2 class="section-title text-white">I Nostri Numeri</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">10,000+</div>
                    <div class="stat-label">Veicoli Monitorati</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Aziende Clienti</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">99.9%</div>
                    <div class="stat-label">Uptime Garantito</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Supporto Clienti</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq" id="faq">
        <div class="container">
            <h2 class="section-title">Domande Frequenti</h2>
            <p class="section-subtitle">Risposte alle domande più comuni sul sistema di prenotazioni e gestione della
                flotta</p>

            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>Come funziona la timeline interattiva?</span>
                        <span class="faq-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            La timeline mostra tutti i veicoli della tua flotta su righe separate, con le date disposte
                            in colonne.
                            Le prenotazioni appaiono come barre colorate che puoi spostare con il drag & drop per
                            cambiare le date,
                            o ridimensionare trascinando i bordi per modificare la durata. Tutte le modifiche vengono
                            salvate automaticamente.
                        </div>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>Come viene assegnato un veicolo a una prenotazione?</span>
                        <span class="faq-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            Quando crei una nuova prenotazione, il sistema cerca automaticamente i veicoli disponibili
                            per il
                            periodo richiesto. Verifica che non ci siano conflitti con altre prenotazioni o manutenzioni
                            programmate.
                            Puoi anche assegnare manualmente un veicolo specifico se preferisci.
                        </div>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>Come gestisco le manutenzioni programmate?</span>
                        <span class="faq-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            Puoi programmare le manutenzioni specificando date, tipo di intervento, fornitore e costi.
                            Durante il
                            periodo di manutenzione, il veicolo viene automaticamente bloccato e non può essere
                            prenotato. Puoi
                            monitorare lo stato delle manutenzioni (programmate, in corso, completate) e visualizzarle
                            sulla timeline.
                        </div>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>Posso modificare o eliminare una prenotazione?</span>
                        <span class="faq-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            Assolutamente sì. Puoi modificare date e durata di una prenotazione direttamente dalla
                            timeline
                            usando il drag & drop. Per eliminare una prenotazione, passa il mouse sulla barra e clicca
                            sul pulsante
                            di eliminazione che appare. Il sistema chiederà conferma prima di procedere.
                        </div>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>Come faccio a sapere se un veicolo è disponibile?</span>
                        <span class="faq-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            La timeline visualizza chiaramente tutte le prenotazioni e manutenzioni. Gli spazi vuoti
                            indicano
                            disponibilità del veicolo. Puoi anche usare la funzione di ricerca per trovare rapidamente
                            veicoli
                            disponibili, oppure il sistema può suggerire automaticamente un veicolo libero quando crei
                            una nuova prenotazione.
                        </div>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>Quali informazioni posso gestire per i veicoli e conducenti?</span>
                        <span class="faq-icon">+</span>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            Per ogni veicolo puoi registrare targa, marca, modello e stato. Per i conducenti gestisci
                            nome, cognome
                            e altre informazioni anagrafiche. Il sistema mantiene uno storico completo di tutte le
                            prenotazioni e
                            manutenzioni per analisi e reportistica.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>© {{ date('Y') }} Flotta. Tutti i diritti riservati.</p>
            <p class="mt-2.5 opacity-70">Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP
                v{{ PHP_VERSION }})</p>
        </div>
    </footer>

    <script>
        function toggleFAQ(element) {
            const faqItem = element.parentElement;
            const wasActive = faqItem.classList.contains('active');

            // Close all FAQ items
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });

            // Open clicked item if it wasn't active
            if (!wasActive) {
                faqItem.classList.add('active');
            }
        }
    </script>
</body>

</html>
