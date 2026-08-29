<?php
$currentPage = 'about.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Future Finder</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
      

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #0d0e3a;
            color: #ffffff;
            overflow-x: hidden;
        }

        .about-wrapper {
            position: relative;
            z-index: 1;
            max-width: 1280px;
            margin: 0 auto;
            padding: 40px 24px 80px;
        }

        .about-wrapper::before {
            content: '';
            position: fixed;
            top: -20%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(54, 173, 163, 0.06) 0%, transparent 70%);
            border-radius: 50%;
            animation: floatOrb 25s ease-in-out infinite alternate;
            pointer-events: none;
            z-index: 0;
        }

        .about-wrapper::after {
            content: '';
            position: fixed;
            bottom: -20%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(54, 173, 163, 0.04) 0%, transparent 70%);
            border-radius: 50%;
            animation: floatOrb 30s ease-in-out infinite alternate-reverse;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes floatOrb {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(80px, 50px) scale(1.15); }
        }

        .about-wrapper > * {
            position: relative;
            z-index: 1;
        }

        /* ── Hero Section ── */
        .about-hero {
            text-align: center;
            padding: 60px 20px 40px;
            animation: fadeInUp 1s ease forwards;
        }
        .about-hero .badge {
            display: inline-block;
            background: rgba(54, 173, 163, 0.12);
            border: 1px solid rgba(54, 173, 163, 0.2);
            border-radius: 50px;
            padding: 6px 20px;
            font-size: 12px;
            font-weight: 700;
            color: #36ada3;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        .about-hero .badge:hover {
            background: rgba(54, 173, 163, 0.2);
            transform: translateY(-2px);
        }
        .about-hero h1 {
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 16px;
            background: linear-gradient(135deg, #ffffff 0%, #36ada3 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .about-hero p {
            font-size: 1.15rem;
            color: rgba(255,255,255,0.7);
            max-width: 640px;
            margin: 0 auto 8px;
            line-height: 1.8;
        }
        .about-hero .hero-line {
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, #36ada3, transparent);
            margin: 20px auto 0;
            border-radius: 4px;
        }

        /* ── Stats Row ── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 24px;
            margin: 50px 0 60px;
        }
        .stat-card {
            background: rgba(26, 31, 122, 0.5);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            padding: 28px 20px;
            text-align: center;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s ease;
            opacity: 0;
            transform: translateY(30px);
        }
        .stat-card.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 60px rgba(54, 173, 163, 0.12);
            border-color: rgba(54, 173, 163, 0.2);
        }
        .stat-card .stat-number {
            font-size: 2.6rem;
            font-weight: 900;
            color: #36ada3;
            line-height: 1;
        }
        .stat-card .stat-label {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.6);
            font-weight: 500;
            margin-top: 6px;
        }
        .stat-card .stat-icon {
            font-size: 28px;
            color: rgba(54, 173, 163, 0.4);
            margin-bottom: 8px;
            display: block;
        }

        /* ── Section titles ── */
        .section-title {
            text-align: center;
            margin-bottom: 48px;
        }
        .section-title h2 {
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            font-weight: 800;
        }
        .section-title h2 span {
            color: #36ada3;
        }
        .section-title p {
            color: rgba(255,255,255,0.6);
            max-width: 520px;
            margin: 8px auto 0;
            font-size: 1rem;
        }

        /* ── Mission & Vision ── */
        .mission-vision {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 70px;
        }
        .mv-card {
            background: rgba(26, 31, 122, 0.4);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 20px;
            padding: 40px 32px;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s ease;
            opacity: 0;
            transform: translateY(30px);
        }
        .mv-card.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .mv-card:nth-child(1) { transition-delay: 0.1s; }
        .mv-card:nth-child(2) { transition-delay: 0.2s; }
        .mv-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 48px rgba(54, 173, 163, 0.08);
            border-color: rgba(54, 173, 163, 0.15);
        }
        .mv-card .mv-icon {
            font-size: 40px;
            color: #36ada3;
            margin-bottom: 16px;
            display: block;
        }
        .mv-card h3 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .mv-card p {
            color: rgba(255,255,255,0.65);
            line-height: 1.8;
            font-size: 0.95rem;
        }

        /* ── Values Grid ── */
        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
            margin-bottom: 70px;
        }
        .value-card {
            background: rgba(26, 31, 122, 0.35);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 16px;
            padding: 32px 24px;
            text-align: center;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), border-color 0.3s, box-shadow 0.3s;
            opacity: 0;
            transform: translateY(30px);
        }
        .value-card.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .value-card:nth-child(1) { transition-delay: 0.05s; }
        .value-card:nth-child(2) { transition-delay: 0.12s; }
        .value-card:nth-child(3) { transition-delay: 0.19s; }
        .value-card:nth-child(4) { transition-delay: 0.26s; }
        .value-card:hover {
            transform: translateY(-8px);
            border-color: rgba(54, 173, 163, 0.3);
            box-shadow: 0 16px 48px rgba(54, 173, 163, 0.06);
        }
        .value-card .val-icon {
            font-size: 32px;
            color: #36ada3;
            margin-bottom: 12px;
            display: block;
        }
        .value-card h4 {
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .value-card p {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.55);
            line-height: 1.6;
        }

        /* ── Features Grid ── */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 70px;
        }
        .feature-card {
            background: rgba(26, 31, 122, 0.3);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 16px;
            padding: 28px 24px;
            text-align: center;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s, border-color 0.3s;
            opacity: 0;
            transform: translateY(30px);
        }
        .feature-card.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .feature-card:nth-child(1) { transition-delay: 0.05s; }
        .feature-card:nth-child(2) { transition-delay: 0.11s; }
        .feature-card:nth-child(3) { transition-delay: 0.17s; }
        .feature-card:nth-child(4) { transition-delay: 0.23s; }
        .feature-card:nth-child(5) { transition-delay: 0.29s; }
        .feature-card:nth-child(6) { transition-delay: 0.35s; }
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 48px rgba(54, 173, 163, 0.06);
            border-color: rgba(54, 173, 163, 0.15);
        }
        .feature-card .feature-icon {
            font-size: 36px;
            color: #36ada3;
            margin-bottom: 10px;
            display: block;
        }
        .feature-card h4 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .feature-card p {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.55);
            line-height: 1.6;
        }

        /* ── Group Details ── */
        .group-section {
            background: rgba(26, 31, 122, 0.3);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 20px;
            padding: 32px 28px;
            margin-bottom: 50px;
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        .group-section.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .group-section .group-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #36ada3;
            margin-bottom: 16px;
            text-align: center;
        }
        .group-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        .group-table th,
        .group-table td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .group-table th {
            color: rgba(255,255,255,0.4);
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .group-table td {
            color: rgba(255,255,255,0.8);
        }
        .group-table .leader-badge {
            background: rgba(54, 173, 163, 0.15);
            color: #36ada3;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .group-table .team-role {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.4);
        }

        /* ── CTA Section ── */
        .cta-section {
            background: linear-gradient(135deg, rgba(54, 173, 163, 0.06), rgba(26, 31, 122, 0.2));
            border: 1px solid rgba(54, 173, 163, 0.1);
            border-radius: 24px;
            padding: 48px 40px;
            text-align: center;
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        .cta-section.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .cta-section h2 {
            font-size: clamp(1.5rem, 2.5vw, 2.2rem);
            font-weight: 800;
            margin-bottom: 8px;
        }
        .cta-section p {
            color: rgba(255,255,255,0.6);
            margin-bottom: 24px;
            font-size: 1rem;
        }
        .cta-section .btn-cta {
            display: inline-block;
            padding: 14px 44px;
            background: #36ada3;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 50px;
            text-decoration: none;
            transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
            box-shadow: 0 4px 20px rgba(54, 173, 163, 0.25);
        }
        .cta-section .btn-cta:hover {
            background: #2d9992;
            transform: translateY(-3px);
            box-shadow: 0 8px 32px rgba(54, 173, 163, 0.3);
        }
        .cta-section .btn-cta:active {
            transform: scale(0.97);
        }
        .cta-section .btn-cta i {
            margin-right: 10px;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .mission-vision {
                grid-template-columns: 1fr;
            }
            .about-hero {
                padding: 30px 16px 20px;
            }
            .about-hero h1 {
                font-size: 2.2rem;
            }
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
            .stat-card .stat-number {
                font-size: 2rem;
            }
            .cta-section {
                padding: 32px 20px;
            }
            .values-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .group-table {
                font-size: 0.8rem;
            }
            .group-table th,
            .group-table td {
                padding: 8px 10px;
            }
        }

        @media (max-width: 480px) {
            .stats-row {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }
            .values-grid {
                grid-template-columns: 1fr 1fr;
                gap: 14px;
            }
            .features-grid {
                grid-template-columns: 1fr;
            }
            .about-wrapper {
                padding: 20px 12px 60px;
            }
            .group-section {
                padding: 20px 16px;
            }
            .group-table {
                font-size: 0.7rem;
            }
            .group-table th,
            .group-table td {
                padding: 6px 8px;
            }
        }
    </style>
</head>
<body>

    <!-- ── Navbar ── -->
    <?php require_once __DIR__ . '/shared/navbar.php'; ?>

    <!-- ── Main Content ── -->
    <div class="about-wrapper">

        <!-- Hero -->
        <section class="about-hero">
            <div class="badge"><i class="fas fa-rocket" style="margin-right:8px;"></i> About Future Finder</div>
            <h1>Empowering Your <span style="background: linear-gradient(135deg, #36ada3 0%, #2d9992 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Career Journey</span></h1>
            <p>We combine intelligent assessments, personalised roadmaps, and curated learning resources to help university students discover and pursue their ideal career paths.</p>
            <div class="hero-line"></div>
        </section>

        <!-- Stats -->
        <div class="stats-row" id="statsRow">
            <div class="stat-card">
                <span class="stat-icon"><i class="fas fa-users"></i></span>
                <div class="stat-number" data-count="50" data-suffix="+">0</div>
                <div class="stat-label">Students Guided</div>
            </div>
            <div class="stat-card">
                <span class="stat-icon"><i class="fas fa-briefcase"></i></span>
                <div class="stat-number" data-count="15" data-suffix="+">0</div>
                <div class="stat-label">Career Paths</div>
            </div>
            <div class="stat-card">
                <span class="stat-icon"><i class="fas fa-star"></i></span>
                <div class="stat-number" data-count="90" data-suffix="%">0</div>
                <div class="stat-label">Satisfaction Rate</div>
            </div>
            <div class="stat-card">
                <span class="stat-icon"><i class="fas fa-graduation-cap"></i></span>
                <div class="stat-number" data-count="5" data-suffix="+">0</div>
                <div class="stat-label">Partner Universities</div>
            </div>
        </div>

        <!-- Mission & Vision -->
        <div class="mission-vision" id="missionVision">
            <div class="mv-card">
                <span class="mv-icon"><i class="fas fa-bullseye"></i></span>
                <h3>Our Mission</h3>
                <p>To bridge the gap between university education and professional careers by providing students with data‑driven career guidance, personalised learning pathways, and actionable insights that lead to fulfilling and successful careers.</p>
            </div>
            <div class="mv-card">
                <span class="mv-icon"><i class="fas fa-eye"></i></span>
                <h3>Our Vision</h3>
                <p>To become the leading career guidance platform for university students worldwide, transforming how students discover, prepare for, and launch their careers through intelligent technology and human‑centred design.</p>
            </div>
        </div>

        <!-- Values -->
        <div class="section-title">
            <h2>Our Core <span>Values</span></h2>
            <p>The principles that guide everything we do at Future Finder.</p>
        </div>

        <div class="values-grid" id="valuesGrid">
            <div class="value-card">
                <span class="val-icon"><i class="fas fa-lightbulb"></i></span>
                <h4>Innovation</h4>
                <p>We continuously evolve our platform with cutting‑edge technology and fresh ideas to better serve students.</p>
            </div>
            <div class="value-card">
                <span class="val-icon"><i class="fas fa-heart"></i></span>
                <h4>Empathy</h4>
                <p>We put students first, understanding their unique challenges and aspirations at every step of their journey.</p>
            </div>
            <div class="value-card">
                <span class="val-icon"><i class="fas fa-shield-alt"></i></span>
                <h4>Integrity</h4>
                <p>We provide honest, unbiased, and transparent career guidance that students can trust and rely on.</p>
            </div>
            <div class="value-card">
                <span class="val-icon"><i class="fas fa-rocket"></i></span>
                <h4>Excellence</h4>
                <p>We strive for the highest quality in every assessment, recommendation, and resource we deliver.</p>
            </div>
        </div>

                <!-- Features -->
        <div class="section-title">
            <h2>Key <span>Features</span></h2>
            <p>Everything you need to make smarter career decisions.</p>
        </div>

        <div class="features-grid" id="featuresGrid">
            <div class="feature-card">
                <span class="feature-icon"><i class="fas fa-user-plus"></i></span>
                <h4>User Registration &amp; Login</h4>
                <p>Secure account creation and authentication with role-based access for students and administrators.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon"><i class="fas fa-clipboard-check"></i></span>
                <h4>Skill Assessment</h4>
                <p>Answer 12 carefully designed questions to identify your strengths, interests, and personality traits.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon"><i class="fas fa-star"></i></span>
                <h4>Career Recommendations</h4>
                <p>Get personalized career matches based on your assessment results, with detailed descriptions and match scores.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon"><i class="fas fa-road"></i></span>
                <h4>Career Roadmap</h4>
                <p>Visualize your career progression with step‑by‑step stages, including required skills and estimated time.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon"><i class="fas fa-file-pdf"></i></span>
                <h4>CV Generator</h4>
                <p>Create, preview, and download professional CVs in PDF format, tailored to your career goals.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon"><i class="fas fa-arrows-left-right"></i></span>
                <h4>Career Comparison</h4>
                <p>Compare up to two careers side‑by‑side to make informed decisions based on salary, demand, and growth.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon"><i class="fas fa-book-open"></i></span>
                <h4>Learning Resources</h4>
                <p>Discover recommended courses and learning materials to build the skills needed for your chosen career.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon"><i class="fas fa-chart-simple"></i></span>
                <h4>Dashboard Analytics</h4>
                <p>Track your assessment progress, view career matches with visual charts, and monitor your career journey.</p>
            </div>
        </div>
        
        <!-- Group Details (no collaborations) -->
        <div class="group-section" id="groupSection">
            <div class="group-title"><i class="fas fa-users" style="margin-right:10px;"></i> Project Team</div>
            <table class="group-table">
                <thead>
                    <tr>
                        <th>Index Number</th>
                        <th>Name</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>UWU/IIT/23/023</td>
                        <td>I.C.P.P. De Silva <span class="leader-badge">Leader</span></td>
                        <td class="team-role">Full Stack Developer</td>
                    </tr>
                    <tr>
                        <td>UWU/IIT/23/046</td>
                        <td>S.A.T.S. Samaraweeraarachchi</td>
                        <td class="team-role">Backend Developer</td>
                    </tr>
                    <tr>
                        <td>UWU/IIT/23/092</td>
                        <td>R. Venujan</td>
                        <td class="team-role">Full Stack Developer</td>
                    </tr>
                    <tr>
                        <td>UWU/IIT/23/100</td>
                        <td>S. Thivishan</td>
                        <td class="team-role">Full Stack Developer</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- CTA -->
        <section class="cta-section" id="ctaSection">
            <h2>Ready to Discover Your Career Path?</h2>
            <p>Start your journey today with our intelligent career assessment and personalised roadmap.</p>
            <a href="restricted.php" class="btn-cta">
                <i class="fas fa-arrow-right"></i> Start Your Assessment
            </a>
        </section>

    </div>

    <!-- ── Footer ── -->
    <?php require_once __DIR__ . '/shared/footer.php'; ?>

    <!-- ── Professional Animations Script ── -->
    <script>
        (() => {
            const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.12,
                rootMargin: '0px 0px -20px 0px'
            });

            document.querySelectorAll(
                '.stat-card, .mv-card, .value-card, .feature-card, .group-section, .cta-section'
            ).forEach(el => observer.observe(el));

            const countObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) return;
                    const el = entry.target;
                    const raw = el.dataset.count;
                    const suffix = el.dataset.suffix || '';

                    if (reduced) {
                        el.textContent = raw + suffix;
                    } else {
                        const target = Number(raw);
                        const start = performance.now();
                        const duration = 1400;
                        const update = (now) => {
                            const progress = Math.min((now - start) / duration, 1);
                            const ease = 1 - Math.pow(1 - progress, 3);
                            el.textContent = Math.round(target * ease) + suffix;
                            if (progress < 1) requestAnimationFrame(update);
                        };
                        requestAnimationFrame(update);
                    }
                    countObserver.unobserve(el);
                });
            }, { threshold: 0.5 });

            document.querySelectorAll('[data-count]').forEach(el => countObserver.observe(el));
        })();
    </script>

</body>
</html>