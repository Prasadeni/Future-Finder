<?php
$currentPage = 'careers.php';
require_once __DIR__ . '/Includes/db_connection.php';

// Get career ID from URL
$careerID = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($careerID === 0) {
    header('Location: /future_finder/careers.php');
    exit;
}

// ── Fetch career details ──
$careerStmt = mysqli_prepare($conn, "SELECT * FROM Careers WHERE CareerID = ?");
mysqli_stmt_bind_param($careerStmt, 'i', $careerID);
mysqli_stmt_execute($careerStmt);
$careerResult = mysqli_stmt_get_result($careerStmt);
$career = mysqli_fetch_assoc($careerResult);
mysqli_stmt_close($careerStmt);

if (!$career) {
    header('Location: /future_finder/careers.php');
    exit;
}

// ── Fetch skills for this career ──
$skillsStmt = mysqli_prepare($conn, "
    SELECT s.Name, s.Category 
    FROM Skill s 
    JOIN Career_Skills cs ON s.SkillID = cs.SkillID 
    WHERE cs.CareerID = ?
");
mysqli_stmt_bind_param($skillsStmt, 'i', $careerID);
mysqli_stmt_execute($skillsStmt);
$skillsResult = mysqli_stmt_get_result($skillsStmt);
$skills = mysqli_fetch_all($skillsResult, MYSQLI_ASSOC);
mysqli_stmt_close($skillsStmt);

// ── Fetch courses for this career ──
$coursesStmt = mysqli_prepare($conn, "SELECT Title, Provider, URL, IsFree FROM Courses WHERE CareerID = ?");
mysqli_stmt_bind_param($coursesStmt, 'i', $careerID);
mysqli_stmt_execute($coursesStmt);
$coursesResult = mysqli_stmt_get_result($coursesStmt);
$courses = mysqli_fetch_all($coursesResult, MYSQLI_ASSOC);
mysqli_stmt_close($coursesStmt);

// ── Fetch roadmap for this career (table is `Roadmap`, not `Roadmaps`) ──
$roadmapStmt = mysqli_prepare($conn, "SELECT * FROM Roadmap WHERE CareerID = ? ORDER BY StageNumber");
mysqli_stmt_bind_param($roadmapStmt, 'i', $careerID);
mysqli_stmt_execute($roadmapStmt);
$roadmapResult = mysqli_stmt_get_result($roadmapStmt);
$roadmap = mysqli_fetch_all($roadmapResult, MYSQLI_ASSOC);
mysqli_stmt_close($roadmapStmt);

// ── Fetch related careers (same industry) ──
$relatedStmt = mysqli_prepare($conn, "
    SELECT CareerID, Title, Industry 
    FROM Careers 
    WHERE Industry = ? AND CareerID != ? 
    LIMIT 4
");
mysqli_stmt_bind_param($relatedStmt, 'si', $career['Industry'], $careerID);
mysqli_stmt_execute($relatedStmt);
$relatedResult = mysqli_stmt_get_result($relatedStmt);
$relatedCareers = mysqli_fetch_all($relatedResult, MYSQLI_ASSOC);
mysqli_stmt_close($relatedStmt);

mysqli_close($conn);

// ── Helper functions (unchanged) ──
function getDemandTag($demand) {
    $map = [
        'Very High' => 'tag-very-high',
        'High' => 'tag-high',
        'Medium' => 'tag-medium',
        'Low' => 'tag-low'
    ];
    return $map[$demand] ?? 'tag-medium';
}

function getCareerIcon($title) {
    $titleLower = strtolower($title);
    if (strpos($titleLower, 'software') !== false || strpos($titleLower, 'developer') !== false) return 'fa-code';
    if (strpos($titleLower, 'data') !== false) return 'fa-database';
    if (strpos($titleLower, 'cyber') !== false || strpos($titleLower, 'security') !== false) return 'fa-shield-halved';
    if (strpos($titleLower, 'network') !== false) return 'fa-network-wired';
    if (strpos($titleLower, 'design') !== false || strpos($titleLower, 'ux') !== false) return 'fa-paint-brush';
    if (strpos($titleLower, 'project') !== false || strpos($titleLower, 'manager') !== false) return 'fa-people-group';
    if (strpos($titleLower, 'marketing') !== false) return 'fa-bullhorn';
    if (strpos($titleLower, 'analyst') !== false) return 'fa-chart-line';
    if (strpos($titleLower, 'engineer') !== false) return 'fa-microchip';
    if (strpos($titleLower, 'devops') !== false) return 'fa-server';
    return 'fa-briefcase';
}

function homeCareerIcon($title) {
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
    <title><?= htmlspecialchars($career['Title']) ?> | Future Finder</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="CSS/home-page.css">
    
    <style>
        /* Same styles as before – no changes needed */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: #0d0e3a;
            color: #ffffff;
            min-height: 100vh;
        }
        .detail-wrapper {
            max-width: 1100px;
            margin: 0 auto;
            padding: 30px 24px 80px;
        }
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.4);
            margin-bottom: 24px;
        }
        .breadcrumb a {
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            transition: color 0.2s;
        }
        .breadcrumb a:hover {
            color: #36ada3;
        }
        .breadcrumb .separator {
            color: rgba(255,255,255,0.15);
        }
        .breadcrumb .current {
            color: #36ada3;
            font-weight: 600;
        }
        .career-header {
            background: rgba(26, 31, 122, 0.5);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 20px;
            padding: 36px 32px;
            margin-bottom: 30px;
        }
        .career-header .header-top {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 10px;
        }
        .career-header .header-icon {
            font-size: 44px;
        }
        .career-header h1 {
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            font-weight: 900;
            margin-bottom: 2px;
        }
        .career-header .industry-tag {
            display: inline-block;
            background: rgba(54, 173, 163, 0.12);
            border: 1px solid rgba(54, 173, 163, 0.15);
            padding: 4px 16px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #36ada3;
        }
        .career-header .header-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 16px 28px;
            margin-top: 12px;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.6);
        }
        .career-header .header-meta .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .career-header .header-meta .meta-item i {
            color: rgba(54, 173, 163, 0.5);
            width: 18px;
        }
        .career-header .header-meta .tag {
            display: inline-block;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .career-header .header-meta .tag-very-high {
            background: rgba(54, 173, 163, 0.2);
            color: #36ada3;
        }
        .career-header .header-meta .tag-high {
            background: rgba(54, 173, 163, 0.12);
            color: #36ada3;
        }
        .career-header .header-meta .tag-medium {
            background: rgba(255, 193, 7, 0.12);
            color: #ffc107;
        }
        .career-header .header-meta .tag-low {
            background: rgba(239, 68, 68, 0.12);
            color: #ef4444;
        }
        .career-description {
            background: rgba(26, 31, 122, 0.3);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 16px;
            padding: 24px 28px;
            margin-bottom: 30px;
        }
        .career-description h2 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 6px;
            color: #36ada3;
        }
        .career-description p {
            font-size: 1rem;
            color: rgba(255,255,255,0.7);
            line-height: 1.8;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 30px;
        }
        .detail-card {
            background: rgba(26, 31, 122, 0.3);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 16px;
            padding: 24px 26px;
        }
        .detail-card .card-title {
            font-size: 1rem;
            font-weight: 700;
            color: #36ada3;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .skills-list .skill-tag {
            background: rgba(54, 173, 163, 0.08);
            border: 1px solid rgba(54, 173, 163, 0.1);
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 500;
            color: rgba(255,255,255,0.8);
            transition: all 0.2s;
        }
        .skills-list .skill-tag:hover {
            background: rgba(54, 173, 163, 0.15);
            border-color: rgba(54, 173, 163, 0.25);
            transform: translateY(-2px);
        }
        .skills-list .skill-tag .skill-category {
            font-size: 0.6rem;
            font-weight: 600;
            text-transform: uppercase;
            color: rgba(54, 173, 163, 0.5);
            margin-left: 4px;
        }
        .courses-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .courses-list .course-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            background: rgba(13, 14, 58, 0.3);
            border-radius: 10px;
            transition: background 0.2s;
            text-decoration: none;
            color: rgba(255,255,255,0.7);
        }
        .courses-list .course-item:hover {
            background: rgba(54, 173, 163, 0.08);
        }
        .courses-list .course-item i {
            color: #36ada3;
            font-size: 14px;
            flex-shrink: 0;
        }
        .courses-list .course-item .course-info {
            flex: 1;
        }
        .courses-list .course-item .course-info .course-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #fff;
        }
        .courses-list .course-item .course-info .course-provider {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.4);
        }
        .courses-list .course-item .course-badge {
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 10px;
            border-radius: 20px;
            text-transform: uppercase;
        }
        .courses-list .course-item .badge-free {
            background: rgba(54, 173, 163, 0.12);
            color: #36ada3;
        }
        .courses-list .course-item .badge-paid {
            background: rgba(239, 68, 68, 0.12);
            color: #ef4444;
        }
        .courses-list .course-item .course-link {
            color: rgba(255,255,255,0.2);
            transition: color 0.2s;
            flex-shrink: 0;
        }
        .courses-list .course-item .course-link:hover {
            color: #36ada3;
        }
        .courses-list .empty-text {
            color: rgba(255,255,255,0.3);
            font-size: 0.9rem;
            font-style: italic;
        }
        .roadmap-section {
            background: rgba(26, 31, 122, 0.3);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 16px;
            padding: 28px 30px;
            margin-bottom: 30px;
        }
        .roadmap-section .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #36ada3;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .roadmap-steps {
            display: flex;
            flex-direction: column;
            gap: 0;
            position: relative;
            padding-left: 30px;
        }
        .roadmap-steps::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 12px;
            bottom: 12px;
            width: 2px;
            background: rgba(54, 173, 163, 0.15);
        }
        .roadmap-step {
            position: relative;
            padding: 12px 0 12px 24px;
        }
        .roadmap-step::before {
            content: '';
            position: absolute;
            left: -26px;
            top: 16px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #36ada3;
            border: 2px solid #0d0e3a;
            box-shadow: 0 0 0 3px rgba(54, 173, 163, 0.2);
        }
        .roadmap-step .step-number {
            font-size: 0.7rem;
            font-weight: 700;
            color: rgba(54, 173, 163, 0.5);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .roadmap-step .step-title {
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
        }
        .roadmap-step .step-time {
            font-size: 0.75rem;
            color: rgba(54, 173, 163, 0.5);
            font-weight: 600;
        }
        .roadmap-steps .empty-text {
            color: rgba(255,255,255,0.3);
            font-size: 0.9rem;
            font-style: italic;
            padding: 16px 0;
        }
        .related-section {
            margin-top: 40px;
        }
        .related-section .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #36ada3;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
        }
        .related-card {
            background: rgba(26, 31, 122, 0.3);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 18px 20px;
            text-align: center;
            transition: transform 0.3s, border-color 0.3s;
            text-decoration: none;
            color: #fff;
        }
        .related-card:hover {
            transform: translateY(-4px);
            border-color: rgba(54, 173, 163, 0.2);
        }
        .related-card .related-icon {
            font-size: 28px;
            display: block;
            margin-bottom: 6px;
        }
        .related-card .related-title {
            font-weight: 600;
            font-size: 0.9rem;
        }
        .related-card .related-industry {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.3);
        }
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 24px;
        }
        .action-buttons .btn {
            padding: 12px 28px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .action-buttons .btn-primary {
            background: #36ada3;
            color: #fff;
            border: none;
        }
        .action-buttons .btn-primary:hover {
            background: #2d9992;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(54, 173, 163, 0.25);
        }
        .action-buttons .btn-secondary {
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.7);
            border: 1px solid rgba(255,255,255,0.08);
        }
        .action-buttons .btn-secondary:hover {
            background: rgba(255,255,255,0.12);
            transform: translateY(-2px);
        }
        .action-buttons .btn-outline {
            background: transparent;
            color: #36ada3;
            border: 1px solid rgba(54, 173, 163, 0.3);
        }
        .action-buttons .btn-outline:hover {
            background: rgba(54, 173, 163, 0.08);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }
            .career-header {
                padding: 24px 20px;
            }
            .career-header .header-top {
                flex-wrap: wrap;
            }
            .career-description {
                padding: 18px 20px;
            }
            .roadmap-section {
                padding: 20px;
            }
            .roadmap-steps {
                padding-left: 20px;
            }
            .roadmap-step {
                padding-left: 16px;
            }
            .roadmap-step::before {
                left: -22px;
                width: 10px;
                height: 10px;
            }
            .action-buttons {
                flex-direction: column;
            }
            .action-buttons .btn {
                justify-content: center;
            }
            .related-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 480px) {
            .detail-wrapper {
                padding: 16px 12px 40px;
            }
            .career-header {
                padding: 18px 16px;
            }
            .career-header .header-meta {
                flex-direction: column;
                gap: 8px;
            }
            .related-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- ── Navbar ── -->
    <?php require_once __DIR__ . '/shared/navbar.php'; ?>

    <div class="detail-wrapper">

        <!-- Breadcrumb -->
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="/future_finder/index.php"><i class="fas fa-home"></i></a>
            <span class="separator"><i class="fas fa-chevron-right" style="font-size:10px;"></i></span>
            <a href="/future_finder/careers.php">Careers</a>
            <span class="separator"><i class="fas fa-chevron-right" style="font-size:10px;"></i></span>
            <span class="current"><?= htmlspecialchars($career['Title']) ?></span>
        </nav>

        <!-- Career Header -->
        <div class="career-header">
            <div class="header-top">
                <span class="header-icon"><?= homeCareerIcon($career['Title']) ?></span>
                <div>
                    <h1><?= htmlspecialchars($career['Title']) ?></h1>
                    <span class="industry-tag"><i class="fas fa-building" style="margin-right:6px;"></i> <?= htmlspecialchars($career['Industry']) ?></span>
                </div>
            </div>
            <div class="header-meta">
                <span class="meta-item"><i class="fas fa-money-bill-wave"></i> <?= htmlspecialchars($career['SalaryRange']) ?></span>
                <span class="meta-item">
                    <i class="fas fa-chart-simple"></i> Demand: 
                    <span class="tag <?= getDemandTag($career['Demand']) ?>"><?= htmlspecialchars($career['Demand']) ?></span>
                </span>
                <span class="meta-item">
                    <i class="fas fa-arrow-up"></i> Growth: 
                    <span class="tag <?= getDemandTag($career['Growth']) ?>"><?= htmlspecialchars($career['Growth']) ?></span>
                </span>
                <span class="meta-item"><i class="fas fa-graduation-cap"></i> <?= htmlspecialchars($career['RequiredEducation']) ?></span>
            </div>
        </div>

        <!-- Description -->
        <div class="career-description">
            <h2><i class="fas fa-align-left" style="margin-right:10px;"></i> About This Career</h2>
            <p><?= htmlspecialchars($career['Description']) ?></p>
        </div>

        <!-- Skills + Courses Grid -->
        <div class="detail-grid">
            <!-- Skills -->
            <div class="detail-card">
                <div class="card-title"><i class="fas fa-tools"></i> Required Skills</div>
                <?php if (empty($skills)): ?>
                    <p class="empty-text">No specific skills listed for this career.</p>
                <?php else: ?>
                    <div class="skills-list">
                        <?php foreach ($skills as $skill): ?>
                            <span class="skill-tag">
                                <?= htmlspecialchars($skill['Name']) ?>
                                <span class="skill-category">· <?= htmlspecialchars($skill['Category']) ?></span>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Courses -->
            <div class="detail-card">
                <div class="card-title"><i class="fas fa-book-open"></i> Recommended Courses</div>
                <?php if (empty($courses)): ?>
                    <p class="empty-text">No courses recommended for this career yet.</p>
                <?php else: ?>
                    <div class="courses-list">
                        <?php foreach ($courses as $course): ?>
                            <a href="<?= htmlspecialchars($course['URL']) ?>" target="_blank" class="course-item">
                                <i class="fas fa-external-link-alt"></i>
                                <div class="course-info">
                                    <div class="course-title"><?= htmlspecialchars($course['Title']) ?></div>
                                    <div class="course-provider"><?= htmlspecialchars($course['Provider']) ?></div>
                                </div>
                                <span class="course-badge <?= $course['IsFree'] ? 'badge-free' : 'badge-paid' ?>">
                                    <?= $course['IsFree'] ? 'Free' : 'Paid' ?>
                                </span>
                                <span class="course-link"><i class="fas fa-arrow-right"></i></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Roadmap -->
        <div class="roadmap-section">
            <div class="section-title"><i class="fas fa-road"></i> Career Roadmap</div>
            <?php if (empty($roadmap)): ?>
                <p class="empty-text" style="color:rgba(255,255,255,0.3);font-style:italic;">No roadmap stages defined for this career yet.</p>
            <?php else: ?>
                <div class="roadmap-steps">
                    <?php foreach ($roadmap as $step): ?>
                        <div class="roadmap-step">
                            <div class="step-number">Stage <?= htmlspecialchars($step['StageNumber']) ?></div>
                            <div class="step-title"><?= htmlspecialchars($step['Title']) ?></div>
                            <div class="step-time"><i class="far fa-clock"></i> <?= htmlspecialchars($step['EstimatedTime']) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="User/dashboard.php" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <a href="User/before_assessment.php" class="btn btn-outline">
                <i class="fas fa-clipboard-check"></i> Take Assessment
            </a>
            <a href="User/compare.php" class="btn btn-secondary">
                <i class="fas fa-arrows-left-right"></i> Compare Careers
            </a>
        </div>

        <!-- Related Careers -->
        <?php if (!empty($relatedCareers)): ?>
            <div class="related-section">
                <div class="section-title"><i class="fas fa-link"></i> Related Careers</div>
                <div class="related-grid">
                    <?php foreach ($relatedCareers as $related): ?>
                        <a href="/future_finder/career-details.php?id=<?= $related['CareerID'] ?>" class="related-card">
                            <span class="related-icon"><?= homeCareerIcon($related['Title']) ?></span>
                            <div class="related-title"><?= htmlspecialchars($related['Title']) ?></div>
                            <div class="related-industry"><?= htmlspecialchars($related['Industry']) ?></div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <!-- ── Footer ── -->
    <?php require_once __DIR__ . '/shared/footer.php'; ?>

</body>
</html>