<?php
$currentPage = 'careers.php';
require_once __DIR__ . '/Includes/db_connection.php';

// Fetch all careers
$careersQuery = mysqli_query($conn, "SELECT CareerID, Title, Description, SalaryRange, Demand, Growth, RequiredEducation, Industry FROM Careers ORDER BY Industry, Title");
$allCareers = mysqli_fetch_all($careersQuery, MYSQLI_ASSOC);

// Get unique industries for filter
$industries = [];
foreach ($allCareers as $career) {
    if (!in_array($career['Industry'], $industries)) {
        $industries[] = $career['Industry'];
    }
}
sort($industries);

mysqli_close($conn);

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
    if (strpos($value, 'software') !== false || strpos($value, 'developer') !== false) return '💻';
    if (strpos($value, 'project') !== false || strpos($value, 'manager') !== false) return '📋';
    if (strpos($value, 'marketing') !== false) return '📢';
    return '💼';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careers | Future Finder</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/home-page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        /* ── Additional styles for careers page ── */
        .careers-wrapper {
            max-width: 1280px;
            margin: 0 auto;
            padding: 40px 24px 80px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .page-header .section-tag {
            display: inline-block;
            background: rgba(54, 173, 163, 0.12);
            border: 1px solid rgba(54, 173, 163, 0.2);
            border-radius: 50px;
            padding: 4px 18px;
            font-size: 11px;
            font-weight: 700;
            color: #36ada3;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .page-header h1 {
            font-size: clamp(2.2rem, 4.5vw, 3.4rem);
            font-weight: 900;
            margin-bottom: 6px;
        }
        .page-header h1 span {
            color: #36ada3;
        }
        .page-header p {
            color: rgba(255,255,255,0.6);
            font-size: 1.05rem;
            max-width: 580px;
            margin: 0 auto;
        }

        /* ── Search & Filter ── */
        .search-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
            justify-content: center;
            margin-bottom: 40px;
        }
        .search-filter .search-wrap {
            flex: 1;
            min-width: 260px;
            max-width: 460px;
            position: relative;
        }
        .search-filter .search-wrap i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.3);
            font-size: 16px;
        }
        .search-filter .search-wrap input {
            width: 100%;
            padding: 12px 16px 12px 48px;
            background: rgba(26, 31, 122, 0.4);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 50px;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            transition: border-color 0.3s;
        }
        .search-filter .search-wrap input:focus {
            outline: none;
            border-color: #36ada3;
        }
        .search-filter .search-wrap input::placeholder {
            color: rgba(255,255,255,0.3);
        }

        .search-filter .filter-wrap {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }
        .search-filter .filter-wrap .filter-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: rgba(255,255,255,0.4);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-right: 4px;
        }
        .search-filter .filter-wrap .filter-btn {
            padding: 8px 18px;
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 30px;
            background: transparent;
            color: rgba(255,255,255,0.6);
            font-family: 'Poppins', sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .search-filter .filter-wrap .filter-btn:hover {
            background: rgba(54, 173, 163, 0.1);
            border-color: rgba(54, 173, 163, 0.3);
            color: #fff;
        }
        .search-filter .filter-wrap .filter-btn.active {
            background: rgba(54, 173, 163, 0.15);
            border-color: #36ada3;
            color: #36ada3;
        }
        .search-filter .filter-wrap .filter-btn.clear-btn {
            border-color: transparent;
            color: rgba(255,255,255,0.3);
            font-size: 0.75rem;
        }
        .search-filter .filter-wrap .filter-btn.clear-btn:hover {
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.2);
            background: rgba(239, 68, 68, 0.05);
        }

        .results-count {
            text-align: center;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.4);
            margin-bottom: 24px;
        }
        .results-count span {
            color: #36ada3;
            font-weight: 700;
        }

        /* ── Career Grid (matching home page) ── */
        .career-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
        }
        .career-grid .no-results {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            color: rgba(255,255,255,0.4);
        }
        .career-grid .no-results i {
            font-size: 48px;
            color: rgba(54, 173, 163, 0.15);
            display: block;
            margin-bottom: 12px;
        }

        /* ── Career Card (matching home page style) ── */
        .career-showcase-card {
            background: rgba(26, 31, 122, 0.5);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            padding: 24px 22px 20px;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), 
                        box-shadow 0.4s ease, 
                        border-color 0.3s;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .career-showcase-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 48px rgba(54, 173, 163, 0.08);
            border-color: rgba(54, 173, 163, 0.15);
        }
        .career-showcase-card .career-showcase-icon {
            font-size: 28px;
            margin-bottom: 8px;
        }
        .career-showcase-card .career-field {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #36ada3;
            margin-bottom: 4px;
        }
        .career-showcase-card h3 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 6px;
            color: #fff;
        }
        .career-showcase-card p {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.6);
            line-height: 1.6;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .career-showcase-card .career-skills {
            display: flex;
            flex-wrap: wrap;
            gap: 6px 10px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid rgba(255,255,255,0.06);
            font-size: 0.75rem;
            color: rgba(255,255,255,0.5);
        }
        .career-showcase-card .career-skills span {
            background: rgba(255,255,255,0.04);
            padding: 3px 10px;
            border-radius: 20px;
        }
        .career-showcase-card .career-skills .tag {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .career-showcase-card .career-skills .tag-high {
            background: rgba(54, 173, 163, 0.15);
            color: #36ada3;
        }
        .career-showcase-card .career-skills .tag-very-high {
            background: rgba(54, 173, 163, 0.25);
            color: #36ada3;
        }
        .career-showcase-card .career-skills .tag-medium {
            background: rgba(255, 193, 7, 0.12);
            color: #ffc107;
        }
        .career-showcase-card .career-skills .tag-low {
            background: rgba(239, 68, 68, 0.12);
            color: #ef4444;
        }
        .career-showcase-card .career-detail {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.35);
            margin-top: 6px;
        }
        .career-showcase-card .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .career-showcase-card .card-footer .btn-detail {
            padding: 6px 18px;
            border: 1px solid rgba(54, 173, 163, 0.3);
            border-radius: 30px;
            background: transparent;
            color: #36ada3;
            font-family: 'Poppins', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }
        .career-showcase-card .card-footer .btn-detail:hover {
            background: #36ada3;
            color: #fff;
            transform: translateY(-2px);
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .careers-wrapper {
                padding: 24px 16px 60px;
            }
            .search-filter {
                flex-direction: column;
                align-items: stretch;
            }
            .search-filter .search-wrap {
                max-width: 100%;
            }
            .search-filter .filter-wrap {
                justify-content: center;
            }
            .career-grid {
                grid-template-columns: 1fr;
            }
            .page-header h1 {
                font-size: 2rem;
            }
        }
        @media (max-width: 480px) {
            .career-grid {
                grid-template-columns: 1fr;
            }
            .search-filter .filter-wrap {
                gap: 6px;
            }
            .search-filter .filter-wrap .filter-btn {
                padding: 6px 14px;
                font-size: 0.7rem;
            }
        }
    </style>
</head>
<body>

    <!-- ── Navbar ── -->
    <?php require_once __DIR__ . '/shared/navbar.php'; ?>

    <div class="careers-wrapper">

        <!-- Page Header -->
        <div class="page-header">
            <div class="section-tag"><i class="fas fa-briefcase" style="margin-right:6px;"></i> Explore Careers</div>
            <h1>Discover Your <span>Future</span></h1>
            <p>Browse through a wide range of career paths, explore job details, and find the one that matches your skills and interests.</p>
        </div>

        <!-- Search & Filter -->
        <div class="search-filter">
            <div class="search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search careers by title, industry, or keyword...">
            </div>
            <div class="filter-wrap">
                <span class="filter-label"><i class="fas fa-filter"></i> Industry:</span>
                <button class="filter-btn active" data-industry="all" onclick="filterIndustry('all')">All</button>
                <?php foreach ($industries as $industry): ?>
                    <button class="filter-btn" data-industry="<?= htmlspecialchars($industry) ?>" onclick="filterIndustry('<?= htmlspecialchars($industry) ?>')">
                        <?= htmlspecialchars($industry) ?>
                    </button>
                <?php endforeach; ?>
                <button class="filter-btn clear-btn" onclick="clearFilters()"><i class="fas fa-times"></i> Reset</button>
            </div>
        </div>

        <!-- Results Count -->
        <div class="results-count">
            Showing <span id="visibleCount">0</span> of <span id="totalCount">0</span> careers
        </div>

        <!-- Career Grid -->
        <div class="career-grid" id="careerGrid">
            <?php if (empty($allCareers)): ?>
                <div class="no-results">
                    <i class="fas fa-database"></i>
                    <p>No careers available at the moment. Please check back later.</p>
                </div>
            <?php else: ?>
                <?php foreach ($allCareers as $career): ?>
                    <article class="career-showcase-card" 
                             data-title="<?= htmlspecialchars(strtolower($career['Title'])) ?>"
                             data-industry="<?= htmlspecialchars($career['Industry']) ?>"
                             data-description="<?= htmlspecialchars(strtolower($career['Description'])) ?>"
                             data-demand="<?= htmlspecialchars($career['Demand']) ?>"
                             data-growth="<?= htmlspecialchars($career['Growth']) ?>">
                        
                        <div class="career-showcase-icon" aria-hidden="true"><?= homeCareerIcon($career['Title']) ?></div>
                        <span class="career-field"><?= htmlspecialchars($career['Industry']) ?></span>
                        <h3><?= htmlspecialchars($career['Title']) ?></h3>
                        <p><?= htmlspecialchars($career['Description']) ?></p>
                        
                        <div class="career-skills">
                            <span><i class="fas fa-money-bill-wave"></i> <?= htmlspecialchars($career['SalaryRange']) ?></span>
                            <span>
                                <i class="fas fa-chart-simple"></i> 
                                <span class="tag tag-<?= strtolower(str_replace(' ', '-', $career['Demand'])) ?>"><?= htmlspecialchars($career['Demand']) ?></span>
                            </span>
                            <span>
                                <i class="fas fa-arrow-up"></i> 
                                <span class="tag tag-<?= strtolower(str_replace(' ', '-', $career['Growth'])) ?>"><?= htmlspecialchars($career['Growth']) ?></span>
                            </span>
                        </div>
                        <p class="career-detail"><strong>Education:</strong> <?= htmlspecialchars($career['RequiredEducation']) ?></p>
                        
                        <div class="card-footer">
                            <span style="font-size:0.7rem;color:rgba(255,255,255,0.3);">
                                <i class="fas fa-graduation-cap"></i> <?= htmlspecialchars($career['RequiredEducation']) ?>
                            </span>
                            <a href="/future_finder/career-details.php?id=<?= $career['CareerID'] ?>" class="btn-detail">
                                View Details <i class="fas fa-arrow-right" style="margin-left:4px;"></i>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>

    <!-- ── Footer ── -->
    <?php require_once __DIR__ . '/shared/footer.php'; ?>

    <script>
        // ============================================================
        // CAREERS PAGE — SEARCH & FILTER
        // ============================================================

        const searchInput = document.getElementById('searchInput');
        const careerGrid = document.getElementById('careerGrid');
        const totalCount = document.getElementById('totalCount');
        const visibleCount = document.getElementById('visibleCount');
        let allCards = Array.from(document.querySelectorAll('.career-showcase-card'));
        let currentIndustry = 'all';

        totalCount.textContent = allCards.length;

        function filterCareers() {
            const query = searchInput.value.toLowerCase().trim();
            let visible = 0;

            allCards.forEach(card => {
                const title = card.dataset.title || '';
                const industry = card.dataset.industry || '';
                const description = card.dataset.description || '';
                const demand = card.dataset.demand || '';
                const growth = card.dataset.growth || '';

                const matchesSearch = !query || 
                    title.includes(query) || 
                    industry.toLowerCase().includes(query) || 
                    description.includes(query) ||
                    demand.toLowerCase().includes(query) ||
                    growth.toLowerCase().includes(query);

                const matchesIndustry = currentIndustry === 'all' || industry === currentIndustry;

                if (matchesSearch && matchesIndustry) {
                    card.style.display = 'flex';
                    visible++;
                } else {
                    card.style.display = 'none';
                }
            });

            visibleCount.textContent = visible;

            let noResults = document.querySelector('.no-results');
            if (visible === 0) {
                if (!noResults) {
                    noResults = document.createElement('div');
                    noResults.className = 'no-results';
                    noResults.innerHTML = `
                        <i class="fas fa-search"></i>
                        <p>No careers match your search. Try adjusting your filters.</p>
                    `;
                    careerGrid.appendChild(noResults);
                }
                noResults.style.display = 'block';
            } else {
                if (noResults) {
                    noResults.style.display = 'none';
                }
            }
        }

        function filterIndustry(industry) {
            currentIndustry = industry;
            document.querySelectorAll('.filter-btn[data-industry]').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.industry === industry);
            });
            filterCareers();
        }

        function clearFilters() {
            searchInput.value = '';
            currentIndustry = 'all';
            document.querySelectorAll('.filter-btn[data-industry]').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.industry === 'all');
            });
            filterCareers();
        }

        searchInput.addEventListener('input', filterCareers);
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                clearFilters();
                searchInput.blur();
            }
        });

        filterCareers();

        document.addEventListener('DOMContentLoaded', () => {
            allCards.forEach((card, i) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100 + i * 60);
            });
        });
    </script>

</body>
</html>