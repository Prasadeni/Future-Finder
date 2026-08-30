<?php
session_start(); // needed for login detection
require_once __DIR__ . '/Includes/db_connection.php';

$isLoggedIn = isset($_SESSION['user_id']);
$firstName  = $_SESSION['first_name'] ?? 'User';

$homeCareers = [];
$homeCareerQuery = mysqli_query($conn, 'SELECT CareerID, Title, Description, SalaryRange, Demand, Growth, RequiredEducation, Industry FROM Careers ORDER BY CareerID ASC LIMIT 6');
if ($homeCareerQuery) {
  $homeCareers = mysqli_fetch_all($homeCareerQuery, MYSQLI_ASSOC);
}

function homeCareerIcon(string $title): string
{
  $value = strtolower($title);
  if (strpos($value, 'cyber') !== false) return '🛡️';
  if (strpos($value, 'data') !== false || strpos($value, 'analyst') !== false) return '📊';
  if (strpos($value, 'artificial') !== false || strpos($value, 'ai ') !== false) return '🧠';
  if (strpos($value, 'cloud') !== false || strpos($value, 'devops') !== false) return '☁️';
  if (strpos($value, 'robot') !== false) return '🤖';
  if (strpos($value, 'network') !== false) return '🌐';
  if (strpos($value, 'design') !== false) return '🎨';
  return '💻';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Future Finder helps you discover careers aligned with your interests, skills, strengths, and personality.">
  <title>Future Finder | Smart Career Guidance System</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="CSS/home-page.css">
  
  <style>
    /* ── Logged‑in hero styles (compact) ── */
    .hero-loggedin {
      text-align: center;
      padding: 32px 20px; /* reduced from 60px */
      background: rgba(26,31,122,0.4);
      border-radius: 20px;
      backdrop-filter: blur(8px);
      margin: 30px auto;
      max-width: 720px; /* slightly narrower */
      border: 1px solid rgba(255,255,255,0.08);
    }
    .hero-loggedin h1 {
      font-size: clamp(1.5rem, 3vw, 2.2rem); /* smaller */
      font-weight: 800;
      margin-bottom: 4px;
    }
    .hero-loggedin h1 span {
      color: #36ada3;
    }
    .hero-loggedin .sub-text {
      color: rgba(255,255,255,0.6);
      font-size: 0.95rem;
      margin-bottom: 18px;
    }
    .quick-links {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      justify-content: center;
      max-width: 380px;
      margin: 0 auto;
    }
    .quick-links .btn {
      flex: 0 0 calc(50% - 5px);
      padding: 10px 12px; /* smaller padding */
      border-radius: 30px;
      font-weight: 700;
      text-decoration: none;
      transition: 0.2s;
      text-align: center;
      box-sizing: border-box;
      font-size: 0.85rem;
    }
    .quick-links .btn-primary {
      background: #36ada3;
      color: #fff;
      border: 2px solid #36ada3;
    }
    .quick-links .btn-primary:hover {
      background: #2d9992;
      border-color: #2d9992;
    }
    .quick-links .btn-secondary {
      background: transparent;
      color: rgba(255,255,255,0.85);
      border: 2px solid rgba(255,255,255,0.25);
    }
    .quick-links .btn-secondary:hover {
      background: rgba(255,255,255,0.08);
      border-color: rgba(255,255,255,0.5);
    }
    .logout-row {
      margin-top: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      flex-wrap: wrap;
      font-size: 0.85rem;
    }
    .logout-row .not-you {
      color: rgba(255,255,255,0.4);
    }
    .btn-logout {
      background: transparent;
      color: #ef4444;
      border: 2px solid rgba(239,68,68,0.3);
      padding: 4px 18px;
      border-radius: 30px;
      font-weight: 600;
      text-decoration: none;
      transition: 0.2s;
      display: inline-block;
      font-size: 0.8rem;
    }
    .btn-logout:hover {
      background: rgba(239,68,68,0.1);
      border-color: #ef4444;
      color: #ff6b6b;
    }
    @media (max-width: 600px) {
      .quick-links .btn {
        flex: 0 0 100%;
        max-width: 220px;
      }
      .hero-loggedin {
        padding: 24px 16px;
        margin: 20px 10px;
      }
    }
  </style>
</head>

<body>
  <a class="skip-link" href="#main-content">Skip to main content</a>

  <!-- ===== Shared Navigation ===== -->
  <?php
  $currentPage = 'index.php';
  $basePath = '';
  require __DIR__ . '/shared/navbar.php';
  ?>

  <main id="main-content">
    <!-- ===== Hero ===== -->
    <section class="hero" id="home">
      <span class="orb one" aria-hidden="true"></span><span class="orb two" aria-hidden="true"></span>
      <div class="container hero-grid">
        <?php if ($isLoggedIn): ?>
          <!-- ── Logged‑in user hero (compact) ── -->
          <div class="hero-loggedin">
            <h1>Welcome back, <span><?= htmlspecialchars($firstName) ?></span>! 🎉</h1>
            <p class="sub-text">You're already on your career journey. Here's what you can do next:</p>
            <div class="quick-links">
              <a href="User/dashboard.php" class="btn btn-primary">Go to Dashboard</a>
              <a href="User/before_assessment.php" class="btn btn-secondary">Take Assessment</a>
              <a href="User/roadmap.php" class="btn btn-secondary">Career Roadmap</a>
              <a href="User/cv.php" class="btn btn-secondary">CV Generator</a>
            </div>
            <div class="logout-row">
              <span class="not-you">Not you?</span>
              <a href="logout.php" class="btn-logout">Logout</a>
            </div>
          </div>
        <?php else: ?>
          <!-- ── Guest hero ── -->
          <div class="hero-copy">
            <h1>Find Your Perfect <span class="gradient-text">Career Path</span> with Confidence</h1>
            <p>Future Finder turns your interests, skills, strengths, and preferences into clear career possibilities—so you can make your next move with confidence.</p>
            <div class="hero-actions">
              <a class="btn btn-primary" href="restricted.php">Start Assessment <span aria-hidden="true">→</span></a>
              <a class="btn btn-secondary" href="login.php">Login</a>
            </div>
          </div>
        <?php endif; ?>
      </div>
      <!-- ===== Image-only technology career marquee (shown to everyone) ===== -->
      <div class="career-strip" aria-label="Technology career gallery">
        <div class="marquee" id="career-marquee">
          <div class="marquee-group">
            <div class="career-image-card sprite-01" role="img" aria-label="Software developer"></div>
            <div class="career-image-card sprite-02" role="img" aria-label="Cybersecurity analyst"></div>
            <div class="career-image-card sprite-03" role="img" aria-label="Data scientist"></div>
            <div class="career-image-card sprite-04" role="img" aria-label="Artificial intelligence engineer"></div>
            <div class="career-image-card sprite-05" role="img" aria-label="Cloud engineer"></div>
            <div class="career-image-card sprite-06" role="img" aria-label="Game developer"></div>
            <div class="career-image-card sprite-07" role="img" aria-label="User experience designer"></div>
            <div class="career-image-card sprite-08" role="img" aria-label="Robotics engineer"></div>
            <div class="career-image-card sprite-09" role="img" aria-label="Network engineer"></div>
            <div class="career-image-card sprite-10" role="img" aria-label="Mobile application developer"></div>
            <div class="career-image-card sprite-11" role="img" aria-label="Database administrator"></div>
            <div class="career-image-card sprite-12" role="img" aria-label="DevOps engineer"></div>
            <div class="career-image-card sprite-13" role="img" aria-label="Virtual reality developer"></div>
            <div class="career-image-card sprite-14" role="img" aria-label="Electronics engineer"></div>
            <div class="career-image-card sprite-15" role="img" aria-label="Information technology support specialist"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== Statistics (shown to everyone) ===== -->
    <section class="section" aria-labelledby="stats-title">
      <div class="container">
        <div class="section-head reveal">
          <div class="section-tag">BUILT FOR BETTER DECISIONS</div>
          <h2 class="section-title" id="stats-title">More clarity at every step</h2>
        </div>
        <div class="stats-grid">
          <article class="stat-card reveal">
            <div class="stat-number" data-count="10" data-suffix="+">0</div>
            <div class="stat-label">Assessment Categories</div>
          </article>
          <article class="stat-card reveal">
            <div class="stat-number" data-count="50" data-suffix="+">0</div>
            <div class="stat-label">Career Paths</div>
          </article>
          <article class="stat-card reveal">
            <div class="stat-number" data-count="100" data-suffix="+">0</div>
            <div class="stat-label">Career Insights</div>
          </article>
          <article class="stat-card reveal">
            <div class="stat-number" data-count="100" data-suffix="%">0</div>
            <div class="stat-label">Personalized Results</div>
          </article>
        </div>
      </div>
    </section>

    <!-- ===== Our Careers (shown to everyone) ===== -->
    <section class="section careers-showcase" id="careers" aria-labelledby="careers-title">
      <div class="container">
        <div class="section-head reveal">
          <div class="section-tag">OUR CAREERS</div>
          <h2 class="section-title" id="careers-title">Explore Careers Shaping the Future of Technology</h2>
          <p class="section-copy">Discover high-impact technology careers, the skills they require, and the kind of problems you could solve.</p>
        </div>
        <?php if (empty($homeCareers)): ?>
          <p class="career-empty">Career information is currently unavailable.</p>
        <?php else: ?>
          <div class="career-showcase-grid">
            <?php foreach ($homeCareers as $career): ?>
              <article class="career-showcase-card reveal">
                <div class="career-showcase-icon" aria-hidden="true"><?= homeCareerIcon($career['Title']) ?></div>
                <span class="career-field"><?= htmlspecialchars($career['Industry']) ?></span>
                <h3><?= htmlspecialchars($career['Title']) ?></h3>
                <p><?= htmlspecialchars($career['Description']) ?></p>
                <div class="career-skills">
                  <span><?= htmlspecialchars($career['Demand']) ?> demand</span>
                  <span><?= htmlspecialchars($career['Growth']) ?> growth</span>
                  <span><?= htmlspecialchars($career['SalaryRange']) ?></span>
                </div>
                <p class="career-detail"><strong>Education:</strong> <?= htmlspecialchars($career['RequiredEducation']) ?></p>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <div class="careers-showcase-action reveal">
          <a class="btn btn-primary" href="careers.php">Explore All Careers <span aria-hidden="true">→</span></a>
        </div>
      </div>
    </section>

    <!-- ===== How it works (shown to everyone) ===== -->
    <section class="section" id="how-it-works">
      <div class="container">
        <div class="section-head reveal">
          <div class="section-tag">HOW IT WORKS</div>
          <h2 class="section-title">Everything You Need to Make Smarter Career Decisions</h2>
          <p class="section-copy">A simple four-step journey from uncertainty to a career direction you can act on.</p>
        </div>
        <div class="steps">
          <article class="step reveal"><span class="step-number">01</span>
            <div class="step-icon" aria-hidden="true">👤</div>
            <h3>Create Your Account</h3>
            <p>Set up your secure profile and begin your personal career journey.</p>
          </article>
          <article class="step reveal"><span class="step-number">02</span>
            <div class="step-icon" aria-hidden="true">📝</div>
            <h3>Complete the Assessment</h3>
            <p>Tell us about your interests, abilities, values, and preferences.</p>
          </article>
          <article class="step reveal"><span class="step-number">03</span>
            <div class="step-icon" aria-hidden="true">✨</div>
            <h3>View Career Matches</h3>
            <p>See personalized careers that fit your unique assessment profile.</p>
          </article>
          <article class="step reveal"><span class="step-number">04</span>
            <div class="step-icon" aria-hidden="true">🗺️</div>
            <h3>Explore Your Career Path</h3>
            <p>Learn what each path takes and plan meaningful next steps.</p>
          </article>
        </div>
      </div>
    </section>

    <!-- ===== Final CTA ===== -->
    <section class="cta">
      <div class="container">
        <div class="cta-box reveal">
          <div class="section-tag">TAKE THE FIRST STEP</div>
          <h2><?= $isLoggedIn ? 'Continue Your Career Journey' : 'Your Future Starts with the Right Career Choice' ?></h2>
          <p><?= $isLoggedIn ? 'You\'re already on the right track. Head to your dashboard to see your matches, compare careers, and plan your next move.' : 'Understand yourself better, explore possibilities with purpose, and turn career uncertainty into a plan you can believe in.' ?></p>
          <a class="btn btn-primary" href="<?= $isLoggedIn ? 'User/dashboard.php' : 'restricted.php' ?>">
            <?= $isLoggedIn ? 'Go to Dashboard' : 'Start Assessment' ?>
            <span aria-hidden="true">→</span>
          </a>
          <?php if (!$isLoggedIn): ?>
            <a class="btn btn-secondary" href="login.php" style="margin-left:10px;">Login</a>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </main>

  <!-- ===== Shared Footer ===== -->
  <?php require __DIR__ . '/shared/footer.php'; ?>

  <script>
    // ===== Homepage interactions (unchanged) =====
    (() => {
      const marquee = document.getElementById('career-marquee'),
        group = marquee.firstElementChild,
        copy = group.cloneNode(true);
      copy.setAttribute('aria-hidden', 'true');
      marquee.appendChild(copy);

      const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
      const revealObserver = new IntersectionObserver(entries => entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          revealObserver.unobserve(entry.target)
        }
      }), {
        threshold: .12
      });
      document.querySelectorAll('.reveal').forEach(item => revealObserver.observe(item));
      const countObserver = new IntersectionObserver(entries => entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const el = entry.target,
          target = Number(el.dataset.count),
          suffix = el.dataset.suffix || '';
        if (reduced) {
          el.textContent = target + suffix
        } else {
          const start = performance.now(),
            duration = 1300;
          const update = now => {
            const progress = Math.min((now - start) / duration, 1),
              ease = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(target * ease) + suffix;
            if (progress < 1) requestAnimationFrame(update)
          };
          requestAnimationFrame(update)
        }
        countObserver.unobserve(el)
      }), {
        threshold: .55
      });
      document.querySelectorAll('[data-count]').forEach(item => countObserver.observe(item));
    })();
  </script>
</body>

</html>