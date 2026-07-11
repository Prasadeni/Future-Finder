
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Future Finder - Smart Career Guidance System</title>
    <meta name="description" content="Future Finder helps students discover their ideal careers through smart assessments, roadmaps, and guidance." />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="CSS/home-style.css" />
    <link rel="stylesheet" href="CSS/home-responsive.css" />
  </head>
  <body>
    <div class="loading-screen"><div class="loader"></div></div>

  
    <header class="header">
      <div class="container navbar">
        <a href="index.html" class="brand">
          <img src="Images/Logo.jpg" alt="Future Finder logo" class="brand-logo" />
        </a>
        <nav class="nav-links">
          <a href="index.html" class="active">Home</a>
          <a href="#features">Features</a>
          <a href="#how-it-works">How It Works</a>
          <a href="#categories">Careers</a>
        </nav>
        <div class="nav-actions">
          <a href="login.html" class="btn btn-primary">Login</a>
          <button class="icon-btn menu-toggle" aria-label="Toggle menu"><i class="fa-solid fa-bars"></i></button>
        </div>
      </div>
    </header>

    <main>
      <section class="hero">
        <div class="container hero-grid">
          <div class="scroll-reveal">
            <div class="eyebrow"><i class="fa-solid fa-brain"></i> SMART CAREER GUIDANCE SYSTEM</div>
            <h1>Find Your Perfect Career Path with Confidence</h1>
            <p>Discover careers that match your personality, interests, and skills using guided assessments, personalized recommendations, and curated learning paths.</p>
            <div class="hero-actions">
              <!-- Guests must log in / register before taking the assessment,
                   so both entry points send them to the login page first. -->
              <a href="login.html" class="btn btn-primary"><i class="fa-solid fa-arrow-right"></i> Start Free Assessment</a>
              <a href="login.html" class="btn btn-secondary">Login</a>
            </div>
          </div>
          <div class="scroll-reveal hero-card">
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=900&q=80" alt="Students planning careers" />
          </div>
        </div>
      </section>

      <section class="stats-section">
        <div class="container stats-grid">
          <div class="stat-card scroll-reveal">
            <div class="stat-number counter" data-target="12" data-suffix="+">0</div>
            <div class="stat-label">Assessment Questions</div>
          </div>
          <div class="stat-card scroll-reveal">
            <div class="stat-number counter" data-target="20" data-suffix="+">0</div>
            <div class="stat-label">Career Quizzes</div>
          </div>
          <div class="stat-card scroll-reveal">
            <div class="stat-number counter" data-target="50" data-suffix="+">0</div>
            <div class="stat-label">Career Paths</div>
          </div>
          <div class="stat-card scroll-reveal">
            <div class="stat-number counter" data-target="98" data-suffix="%">0</div>
            <div class="stat-label">Personalized Results</div>
          </div>
        </div>
      </section>

      <section class="section" id="features">
        <div class="container">
          <div class="text-center scroll-reveal">
            <div class="eyebrow"><i class="fa-solid fa-star"></i> WHY CHOOSE FUTURE FINDER</div>
            <h2 class="section-title">Everything you need to make smarter career decisions</h2>
            <p class="section-copy">We combine assessments, mentorship-ready insights, and growth plans to help learners move from uncertainty to confidence.</p>
          </div>
          <div class="feature-grid mt-2">
            <article class="feature-card scroll-reveal">
              <div class="feature-icon"><i class="fa-solid fa-clipboard-list"></i></div>
              <h3>Career Assessment</h3>
              <p>Understand your strengths, interests, and preferred work style through guided questions.</p>
            </article>
            <article class="feature-card scroll-reveal">
              <div class="feature-icon"><i class="fa-solid fa-bolt"></i></div>
              <h3>Career Recommendation</h3>
              <p>Receive recommendations tailored to your profile and long-term aspirations.</p>
            </article>
            <article class="feature-card scroll-reveal">
              <div class="feature-icon"><i class="fa-solid fa-road"></i></div>
              <h3>Career Roadmap</h3>
              <p>Follow structured milestones from beginner to advanced levels with practical guidance.</p>
            </article>
            <article class="feature-card scroll-reveal">
              <div class="feature-icon"><i class="fa-solid fa-graduation-cap"></i></div>
              <h3>Course Suggestions</h3>
              <p>Get curated courses aligned to your top career match.</p>
            </article>
            <article class="feature-card scroll-reveal">
              <div class="feature-icon"><i class="fa-solid fa-chart-line"></i></div>
              <h3>Progress Dashboard</h3>
              <p>Track your assessment status and career readiness in one place.</p>
            </article>
            <article class="feature-card scroll-reveal">
              <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
              <h3>Secure Account</h3>
              <p>Your answers and results are saved securely to your personal account.</p>
            </article>
          </div>
        </div>
      </section>

      <section class="section" id="how-it-works">
        <div class="container">
          <div class="text-center scroll-reveal">
            <div class="eyebrow"><i class="fa-solid fa-gears"></i> HOW IT WORKS</div>
            <h2 class="section-title">Your future becomes clearer in four simple steps</h2>
          </div>
          <div class="timeline mt-2">
            <div class="timeline-item scroll-reveal">
              <h3>1. Create Account</h3>
              <p>Register with your name, email, and a password to build your career profile.</p>
            </div>
            <div class="timeline-item scroll-reveal">
              <h3>2. Log In</h3>
              <p>Sign in with your email and password to unlock the assessment.</p>
            </div>
            <div class="timeline-item scroll-reveal">
              <h3>3. Take the Assessment</h3>
              <p>Answer 12 guided questions to reveal your strengths and preferences.</p>
            </div>
            <div class="timeline-item scroll-reveal">
              <h3>4. Get Your Results</h3>
              <p>View your personalized career matches and recommended next steps.</p>
            </div>
          </div>
        </div>
      </section>

      <section class="section" id="categories">
        <div class="container">
          <div class="text-center scroll-reveal">
            <div class="eyebrow"><i class="fa-solid fa-layer-group"></i> POPULAR CAREER CATEGORIES</div>
            <h2 class="section-title">Explore in-demand career fields</h2>
          </div>
          <div class="category-grid mt-2">
            <article class="category-card scroll-reveal"><div class="category-icon"><i class="fa-solid fa-laptop-code"></i></div><h3>Software Engineering</h3><p>Build digital products and modern applications.</p></article>
            <article class="category-card scroll-reveal"><div class="category-icon"><i class="fa-solid fa-chart-column"></i></div><h3>Data Analysis</h3><p>Turn raw data into insights that drive decisions.</p></article>
            <article class="category-card scroll-reveal"><div class="category-icon"><i class="fa-solid fa-palette"></i></div><h3>Graphic Design</h3><p>Craft powerful visual stories through creative expression.</p></article>
            <article class="category-card scroll-reveal"><div class="category-icon"><i class="fa-solid fa-shield-halved"></i></div><h3>Cyber Security</h3><p>Protect data, networks, and digital infrastructure.</p></article>
          </div>
        </div>
      </section>

      <section class="cta-section">
        <div class="container">
          <div class="cta-card scroll-reveal">
            <div>
              <div class="eyebrow" style="background: rgba(255,255,255,0.16); color: white;">READY TO DISCOVER YOUR FUTURE?</div>
              <h2 style="font-size: clamp(1.6rem, 3vw, 2.4rem);">Kickstart your career journey with a free assessment today.</h2>
            </div>
            <!-- Same rule as the hero: guests log in first, then land on the assessment. -->
            <a href="login.html" class="btn btn-secondary">Start Assessment</a>
          </div>
        </div>
      </section>
    </main>

    <footer class="footer">
      <div class="container footer-grid">
        <div>
          <h4>Future Finder</h4>
          <p>Smart career guidance for students, professionals, and lifelong learners.</p>
        </div>
        <div>
          <h4>Quick Links</h4>
          <a href="#features">Features</a>
          <a href="#how-it-works">How It Works</a>
          <a href="#categories">Careers</a>
          <a href="login.html">Login / Register</a>
        </div>
        <div>
          <h4>Social</h4>
          <a href="#"><i class="fa-brands fa-facebook"></i> Facebook</a>
          <a href="#"><i class="fa-brands fa-linkedin"></i> LinkedIn</a>
          <a href="#"><i class="fa-brands fa-instagram"></i> Instagram</a>
        </div>
        <div>
          <h4>Contact</h4>
          <a href="mailto:hello@futurefinder.com">hello@futurefinder.com</a>
          <a href="tel:+233200000000">+233 20 000 0000</a>
        </div>
      </div>
      <div class="container footer-bottom">© 2026 Future Finder. All rights reserved.</div>
    </footer>

    <button class="back-to-top" aria-label="Back to top"><i class="fa-solid fa-arrow-up"></i></button>
    <div class="cookie-banner">
      <p>We use cookies to improve your experience and personalize content.</p>
      <button class="btn btn-primary" id="accept-cookies">Accept</button>
    </div>

    <script src="JS/home-script.js"></script>
    <script src="JS/home-counter.js"></script>
  </body>
</html>
