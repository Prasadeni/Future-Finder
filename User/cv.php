<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /future_finder/login.php');
    exit;
}

if (($_SESSION['role'] ?? 'user') === 'admin') {
    header('Location: /future_finder/admin.php');
    exit;
}

require_once __DIR__ . '/../Includes/db_connection.php';

$userID = intval($_SESSION['user_id']);
$firstName = htmlspecialchars($_SESSION['first_name'] ?? '');
$lastName = htmlspecialchars($_SESSION['last_name'] ?? '');
$fullName = trim($firstName . ' ' . $lastName);

// ── Handle Save (AJAX) ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_cv') {
    header('Content-Type: application/json');
    
    $personal = [
        'first_name' => $_POST['first_name'] ?? '',
        'last_name' => $_POST['last_name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'location' => $_POST['location'] ?? '',
        'linkedin' => $_POST['linkedin'] ?? '',
        'website' => $_POST['website'] ?? '',
        'title' => $_POST['title'] ?? '',
        'summary' => $_POST['summary'] ?? '',
    ];
    
    $education = [];
    $eduInstitution = $_POST['edu_institution'] ?? [];
    $eduDegree = $_POST['edu_degree'] ?? [];
    $eduField = $_POST['edu_field'] ?? [];
    $eduStart = $_POST['edu_start'] ?? [];
    $eduEnd = $_POST['edu_end'] ?? [];
    $eduGpa = $_POST['edu_gpa'] ?? [];
    
    for ($i = 0; $i < count($eduInstitution); $i++) {
        if (!empty($eduInstitution[$i])) {
            $education[] = [
                'institution' => $eduInstitution[$i],
                'degree' => $eduDegree[$i] ?? '',
                'field' => $eduField[$i] ?? '',
                'start' => $eduStart[$i] ?? '',
                'end' => $eduEnd[$i] ?? '',
                'gpa' => $eduGpa[$i] ?? '',
            ];
        }
    }
    
    $work = [];
    $workCompany = $_POST['work_company'] ?? [];
    $workPosition = $_POST['work_position'] ?? [];
    $workLocation = $_POST['work_location'] ?? [];
    $workStart = $_POST['work_start'] ?? [];
    $workEnd = $_POST['work_end'] ?? [];
    $workDescription = $_POST['work_description'] ?? [];
    $workCurrent = $_POST['work_current'] ?? [];
    
    for ($i = 0; $i < count($workCompany); $i++) {
        if (!empty($workCompany[$i])) {
            $work[] = [
                'company' => $workCompany[$i],
                'position' => $workPosition[$i] ?? '',
                'location' => $workLocation[$i] ?? '',
                'start' => $workStart[$i] ?? '',
                'end' => $workEnd[$i] ?? '',
                'description' => $workDescription[$i] ?? '',
                'current' => isset($workCurrent[$i]) ? 1 : 0,
            ];
        }
    }
    
    $skills = $_POST['skills'] ?? '';
    $additional = [
        'languages' => $_POST['languages'] ?? '',
        'certifications' => $_POST['certifications'] ?? '',
        'interests' => $_POST['interests'] ?? '',
    ];
    
    $cvData = json_encode([
        'personal' => $personal,
        'education' => $education,
        'work' => $work,
        'skills' => $skills,
        'additional' => $additional,
    ]);
    
    $check = mysqli_prepare($conn, "SELECT CVID FROM CV WHERE UserID = ?");
    mysqli_stmt_bind_param($check, 'i', $userID);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);
    $exists = mysqli_stmt_num_rows($check) > 0;
    mysqli_stmt_close($check);
    
    if ($exists) {
        $update = mysqli_prepare($conn, "UPDATE CV SET PersonalDetails=? WHERE UserID=?");
        mysqli_stmt_bind_param($update, 'si', $cvData, $userID);
        $success = mysqli_stmt_execute($update);
        mysqli_stmt_close($update);
    } else {
        $insert = mysqli_prepare($conn, "INSERT INTO CV (UserID, PersonalDetails) VALUES (?, ?)");
        mysqli_stmt_bind_param($insert, 'is', $userID, $cvData);
        $success = mysqli_stmt_execute($insert);
        mysqli_stmt_close($insert);
    }
    
    echo json_encode(['success' => $success]);
    exit;
}

// ── Load existing CV data ──
$cvData = null;
$stmt = mysqli_prepare($conn, "SELECT PersonalDetails FROM CV WHERE UserID = ?");
mysqli_stmt_bind_param($stmt, 'i', $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if ($row = mysqli_fetch_assoc($result)) {
    $cvData = json_decode($row['PersonalDetails'], true);
}
mysqli_stmt_close($stmt);
mysqli_close($conn);

function getSaved($data, $key, $default = '') {
    return isset($data[$key]) ? htmlspecialchars($data[$key]) : $default;
}

function getSavedNested($data, $key1, $key2, $index, $default = '') {
    if (isset($data[$key1]) && isset($data[$key1][$index]) && isset($data[$key1][$index][$key2])) {
        return htmlspecialchars($data[$key1][$index][$key2]);
    }
    return $default;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Generator | Future Finder</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        /* ============================================================
           CV GENERATOR — ENHANCED VERSION
           ============================================================ */

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

        .cv-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 24px 60px;
        }

        /* ── Page header ── */
        .page-header {
            margin-bottom: 30px;
            animation: fadeInUp 0.8s ease forwards;
        }
        .page-header h1 {
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 900;
            margin-bottom: 2px;
        }
        .page-header h1 span {
            color: #36ada3;
        }
        .page-header p {
            color: rgba(255,255,255,0.6);
            font-size: 1rem;
        }

        /* ── Two-column layout ── */
        .cv-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            animation: fadeInUp 0.8s ease 0.1s forwards;
            opacity: 0;
        }

        /* ── Left: Form ── */
        .cv-form {
            background: rgba(26, 31, 122, 0.35);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 20px;
            padding: 32px 28px;
            max-height: 85vh;
            overflow-y: auto;
        }
        .cv-form::-webkit-scrollbar {
            width: 4px;
        }
        .cv-form::-webkit-scrollbar-track {
            background: transparent;
        }
        .cv-form::-webkit-scrollbar-thumb {
            background: #36ada3;
            border-radius: 4px;
        }

        .cv-form .form-section {
            margin-bottom: 28px;
        }
        .cv-form .form-section-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #36ada3;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border-bottom: 2px solid rgba(54, 173, 163, 0.2);
            padding-bottom: 6px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .cv-form .form-section-title i {
            font-size: 16px;
        }

        .cv-form .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 12px;
        }
        .cv-form .form-row.three-col {
            grid-template-columns: 1fr 1fr 1fr;
        }
        .cv-form .form-group {
            margin-bottom: 14px;
        }
        .cv-form .form-group.full-width {
            grid-column: 1 / -1;
        }
        .cv-form label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            color: rgba(255,255,255,0.5);
            margin-bottom: 3px;
        }
        .cv-form label .required {
            color: #ef4444;
            margin-left: 2px;
        }
        .cv-form input,
        .cv-form textarea,
        .cv-form select {
            width: 100%;
            padding: 10px 14px;
            background: rgba(13, 14, 58, 0.5);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 10px;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            transition: border-color 0.3s;
        }
        .cv-form input:focus,
        .cv-form textarea:focus,
        .cv-form select:focus {
            outline: none;
            border-color: #36ada3;
        }
        .cv-form input::placeholder,
        .cv-form textarea::placeholder {
            color: rgba(255,255,255,0.25);
        }
        .cv-form textarea {
            resize: vertical;
            min-height: 50px;
        }
        .cv-form select option {
            background: #0d0e3a;
            color: #fff;
        }

        /* ── Entry container (education/work) ── */
        .entry-container {
            background: rgba(13, 14, 58, 0.3);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 14px;
            border: 1px solid rgba(255,255,255,0.05);
            position: relative;
        }
        .entry-container .entry-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .entry-container .entry-header .entry-number {
            font-size: 0.8rem;
            font-weight: 600;
            color: rgba(255,255,255,0.3);
        }
        .entry-container .entry-header .btn-remove-entry {
            background: none;
            border: none;
            color: rgba(239, 68, 68, 0.5);
            cursor: pointer;
            font-size: 14px;
            transition: color 0.2s;
            padding: 4px 8px;
        }
        .entry-container .entry-header .btn-remove-entry:hover {
            color: #ef4444;
        }

        /* ── Checkbox / Radio styles ── */
        .cv-form .radio-group {
            display: flex;
            gap: 20px;
            padding-top: 4px;
        }
        .cv-form .radio-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            color: rgba(255,255,255,0.6);
            cursor: pointer;
        }
        .cv-form .radio-group input[type="radio"],
        .cv-form .radio-group input[type="checkbox"] {
            width: auto;
            accent-color: #36ada3;
            cursor: pointer;
        }

        /* ── Buttons ── */
        .cv-form .btn {
            padding: 10px 22px;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .cv-form .btn-primary {
            background: #36ada3;
            color: #fff;
        }
        .cv-form .btn-primary:hover {
            background: #2d9992;
            transform: translateY(-2px);
        }
        .cv-form .btn-secondary {
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.7);
        }
        .cv-form .btn-secondary:hover {
            background: rgba(255,255,255,0.15);
            transform: translateY(-2px);
        }
        .cv-form .btn-success {
            background: rgba(54, 173, 163, 0.15);
            color: #36ada3;
            border: 1px solid rgba(54, 173, 163, 0.2);
        }
        .cv-form .btn-success:hover {
            background: rgba(54, 173, 163, 0.25);
            transform: translateY(-2px);
        }
        .cv-form .btn-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        .cv-form .btn-danger:hover {
            background: rgba(239, 68, 68, 0.2);
            transform: translateY(-2px);
        }
        .cv-form .btn:active {
            transform: scale(0.97);
        }
        .cv-form .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .cv-form .form-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 8px;
        }
        .cv-form .status-msg {
            margin-top: 12px;
            font-size: 0.9rem;
            color: #36ada3;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .cv-form .status-msg.show {
            opacity: 1;
        }
        .cv-form .status-msg.error {
            color: #ef4444;
        }

        /* ── Right: Preview ── */
        .cv-preview {
            background: rgba(26, 31, 122, 0.35);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 20px;
            padding: 32px 28px;
            max-height: 85vh;
            overflow-y: auto;
            position: relative;
        }
        .cv-preview::-webkit-scrollbar {
            width: 4px;
        }
        .cv-preview::-webkit-scrollbar-track {
            background: transparent;
        }
        .cv-preview::-webkit-scrollbar-thumb {
            background: #36ada3;
            border-radius: 4px;
        }
        .cv-preview .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .cv-preview .preview-header h2 {
            font-size: 1.2rem;
            font-weight: 700;
        }
        .cv-preview .preview-header .preview-actions {
            display: flex;
            gap: 10px;
        }
        .cv-preview .preview-header .preview-actions button {
            padding: 8px 18px;
            border: none;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }
        .cv-preview .preview-header .preview-actions .btn-download {
            background: #36ada3;
            color: #fff;
        }
        .cv-preview .preview-header .preview-actions .btn-download:hover {
            background: #2d9992;
            transform: translateY(-2px);
        }
        .cv-preview .preview-header .preview-actions .btn-print {
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.7);
        }
        .cv-preview .preview-header .preview-actions .btn-print:hover {
            background: rgba(255,255,255,0.15);
            transform: translateY(-2px);
        }

        /* ── CV Preview Content ── */
        .cv-preview-content {
            background: #ffffff;
            color: #1a1a2e;
            border-radius: 12px;
            padding: 32px 28px;
            min-height: 400px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            line-height: 1.6;
        }
        .cv-preview-content .cv-name {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 2px;
            color: #1a1a2e;
        }
        .cv-preview-content .cv-title {
            font-size: 16px;
            font-weight: 600;
            color: #36ada3;
            margin-bottom: 4px;
        }
        .cv-preview-content .cv-contact {
            font-size: 13px;
            color: #555;
            margin-bottom: 6px;
        }
        .cv-preview-content .cv-summary {
            font-size: 13px;
            color: #333;
            margin-bottom: 16px;
            padding: 12px 16px;
            background: #f5f7fa;
            border-radius: 8px;
        }
        .cv-preview-content .cv-section {
            margin-bottom: 16px;
        }
        .cv-preview-content .cv-section h3 {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a2e;
            border-bottom: 2px solid #36ada3;
            padding-bottom: 4px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .cv-preview-content .cv-section .cv-item {
            margin-bottom: 10px;
        }
        .cv-preview-content .cv-section .cv-item-header {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 4px;
            font-weight: 600;
        }
        .cv-preview-content .cv-section .cv-item-sub {
            font-size: 12px;
            color: #666;
        }
        .cv-preview-content .cv-section .cv-item-desc {
            font-size: 13px;
            color: #333;
            margin-top: 2px;
            padding-left: 4px;
        }
        .cv-preview-content .cv-section .cv-item-desc ul {
            padding-left: 20px;
            margin: 2px 0;
        }
        .cv-preview-content .cv-section .cv-item-desc ul li {
            margin-bottom: 2px;
        }
        .cv-preview-content .cv-empty {
            color: #aaa;
            font-style: italic;
            text-align: center;
            padding: 60px 0;
        }
        .cv-preview-content .cv-empty i {
            font-size: 48px;
            color: #ddd;
            display: block;
            margin-bottom: 12px;
        }

        /* ── PRINT STYLES (FIXED) ── */
        @media print {
            /* Hide everything except the preview content's container chain */
            body > *:not(.cv-wrapper) {
                display: none !important;
            }
            .cv-wrapper {
                display: block !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
                background: #fff !important;
            }
            .cv-wrapper .cv-grid {
                display: block !important;
                grid-template-columns: 1fr !important;
                gap: 0 !important;
                opacity: 1 !important;
                animation: none !important;
            }
            .cv-wrapper .cv-grid .cv-preview {
                display: block !important;
                max-height: none !important;
                background: #fff !important;
                padding: 0 !important;
                border: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                backdrop-filter: none !important;
            }
            .cv-wrapper .cv-grid .cv-preview .preview-header,
            .cv-wrapper .cv-grid .cv-preview .preview-actions {
                display: none !important;
            }
            .cv-wrapper .cv-grid .cv-preview .cv-preview-content {
                display: block !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                padding: 1.5cm 2cm !important;
                margin: 0 !important;
                border-radius: 0 !important;
                background: #ffffff !important;
                color: #1a1a2e !important;
                font-size: 11pt !important;
                line-height: 1.5 !important;
                overflow: auto !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                box-shadow: none !important;
            }
            /* Preserve background colours and borders inside the preview */
            .cv-preview-content * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .cv-preview-content .cv-summary {
                background: #f5f7fa !important;
                border: none !important;
            }
            .cv-preview-content .cv-section h3 {
                border-bottom: 2px solid #36ada3 !important;
            }
            .cv-preview-content .cv-title {
                color: #36ada3 !important;
            }
            .cv-preview-content a {
                color: #36ada3 !important;
            }
            .cv-preview-content .cv-empty {
                display: none !important;
            }
            /* Ensure all text colours are correct */
            .cv-preview-content .cv-name,
            .cv-preview-content .cv-section h3,
            .cv-preview-content .cv-item-header,
            .cv-preview-content .cv-item-desc {
                color: #1a1a2e !important;
            }
            .cv-preview-content .cv-contact,
            .cv-preview-content .cv-item-sub {
                color: #555 !important;
            }
            /* Page margins */
            @page {
                margin: 0;
            }
        }

        /* ── Animations ── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Responsive ── */
        @media (max-width: 1024px) {
            .cv-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .cv-form,
            .cv-preview {
                max-height: none;
            }
            .cv-form .form-row {
                grid-template-columns: 1fr;
            }
            .cv-form .form-row.three-col {
                grid-template-columns: 1fr 1fr;
            }
        }
        @media (max-width: 600px) {
            .cv-wrapper {
                padding: 16px 12px 40px;
            }
            .cv-form {
                padding: 20px 16px;
            }
            .cv-preview {
                padding: 16px;
            }
            .cv-preview-content {
                padding: 20px 16px;
            }
            .cv-form .form-row.three-col {
                grid-template-columns: 1fr;
            }
            .cv-form .radio-group {
                flex-wrap: wrap;
                gap: 10px;
            }
            .cv-form .form-actions {
                flex-direction: column;
            }
            .cv-form .form-actions .btn {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <!-- ── Navbar ── -->
    <?php
        $currentPage = 'cv.php';
        require_once __DIR__ . '/../shared/navbaroptional.php';
    ?>

    <div class="cv-wrapper">

        <!-- Page Header -->
        <div class="page-header">
            <h1>CV <span>Generator</span></h1>
            <p>Create a professional CV. Fill in your details and get a live preview. Save or download as PDF.</p>
        </div>

        <!-- Grid -->
        <div class="cv-grid">

            <!-- ── LEFT: FORM ── -->
            <div class="cv-form" id="cvForm">
                <form id="cvFormData">

                    <!-- ===== SECTION 1: Personal Details ===== -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-user"></i> Personal Details
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>First Name <span class="required">*</span></label>
                                <input type="text" id="first_name" placeholder="John" value="<?= getSaved($cvData['personal'] ?? [], 'first_name') ?>">
                            </div>
                            <div class="form-group">
                                <label>Last Name <span class="required">*</span></label>
                                <input type="text" id="last_name" placeholder="Doe" value="<?= getSaved($cvData['personal'] ?? [], 'last_name') ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Email <span class="required">*</span></label>
                                <input type="email" id="email" placeholder="john.doe@email.com" value="<?= getSaved($cvData['personal'] ?? [], 'email') ?>">
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="tel" id="phone" placeholder="+94 77 123 4567" value="<?= getSaved($cvData['personal'] ?? [], 'phone') ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Location</label>
                                <input type="text" id="location" placeholder="Colombo, Sri Lanka" value="<?= getSaved($cvData['personal'] ?? [], 'location') ?>">
                            </div>
                            <div class="form-group">
                                <label>Professional Title</label>
                                <input type="text" id="title" placeholder="Software Engineer | Data Analyst" value="<?= getSaved($cvData['personal'] ?? [], 'title') ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>LinkedIn</label>
                                <input type="url" id="linkedin" placeholder="https://linkedin.com/in/username" value="<?= getSaved($cvData['personal'] ?? [], 'linkedin') ?>">
                            </div>
                            <div class="form-group">
                                <label>Website / Portfolio</label>
                                <input type="url" id="website" placeholder="https://portfolio.com" value="<?= getSaved($cvData['personal'] ?? [], 'website') ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Professional Summary</label>
                            <textarea id="summary" rows="3" placeholder="A brief summary of your professional background and career goals..."><?= getSaved($cvData['personal'] ?? [], 'summary') ?></textarea>
                        </div>
                    </div>

                    <!-- ===== SECTION 2: Education ===== -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-graduation-cap"></i> Education
                            <button type="button" class="btn btn-success" style="margin-left:auto;padding:4px 14px;font-size:12px;" onclick="addEducation()">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                        <div id="educationContainer">
                            <?php 
                            $eduEntries = $cvData['education'] ?? [];
                            if (empty($eduEntries)) {
                                $eduEntries = [['institution' => '', 'degree' => '', 'field' => '', 'start' => '', 'end' => '', 'gpa' => '']];
                            }
                            $eduIndex = 0;
                            foreach ($eduEntries as $edu): 
                            ?>
                            <div class="entry-container" data-entry-type="education">
                                <div class="entry-header">
                                    <span class="entry-number">Education #<?= $eduIndex + 1 ?></span>
                                    <button type="button" class="btn-remove-entry" onclick="removeEntry(this)" <?= count($eduEntries) <= 1 ? 'style="display:none;"' : '' ?>>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Institution</label>
                                        <input type="text" class="edu_institution" placeholder="University of Colombo" value="<?= htmlspecialchars($edu['institution'] ?? '') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Degree</label>
                                        <input type="text" class="edu_degree" placeholder="BSc in Computer Science" value="<?= htmlspecialchars($edu['degree'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Field of Study</label>
                                        <input type="text" class="edu_field" placeholder="Software Engineering" value="<?= htmlspecialchars($edu['field'] ?? '') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>GPA / Grade</label>
                                        <input type="text" class="edu_gpa" placeholder="3.8 / 4.0" value="<?= htmlspecialchars($edu['gpa'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input type="date" class="edu_start" value="<?= htmlspecialchars($edu['start'] ?? '') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input type="date" class="edu_end" value="<?= htmlspecialchars($edu['end'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>
                            <?php 
                            $eduIndex++;
                            endforeach; 
                            ?>
                        </div>
                    </div>

                    <!-- ===== SECTION 3: Work Experience ===== -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-briefcase"></i> Work Experience
                            <button type="button" class="btn btn-success" style="margin-left:auto;padding:4px 14px;font-size:12px;" onclick="addWork()">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                        <div id="workContainer">
                            <?php 
                            $workEntries = $cvData['work'] ?? [];
                            if (empty($workEntries)) {
                                $workEntries = [['company' => '', 'position' => '', 'location' => '', 'start' => '', 'end' => '', 'description' => '', 'current' => 0]];
                            }
                            $workIndex = 0;
                            foreach ($workEntries as $work): 
                            ?>
                            <div class="entry-container" data-entry-type="work">
                                <div class="entry-header">
                                    <span class="entry-number">Work #<?= $workIndex + 1 ?></span>
                                    <button type="button" class="btn-remove-entry" onclick="removeEntry(this)" <?= count($workEntries) <= 1 ? 'style="display:none;"' : '' ?>>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Company</label>
                                        <input type="text" class="work_company" placeholder="Google" value="<?= htmlspecialchars($work['company'] ?? '') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Position</label>
                                        <input type="text" class="work_position" placeholder="Junior Developer" value="<?= htmlspecialchars($work['position'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Location</label>
                                        <input type="text" class="work_location" placeholder="Colombo, Sri Lanka" value="<?= htmlspecialchars($work['location'] ?? '') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div class="radio-group">
                                            <label>
                                                <input type="checkbox" class="work_current" <?= ($work['current'] ?? 0) ? 'checked' : '' ?>>
                                                I currently work here
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input type="date" class="work_start" value="<?= htmlspecialchars($work['start'] ?? '') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input type="date" class="work_end" value="<?= htmlspecialchars($work['end'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="work_description" rows="2" placeholder="Key responsibilities and achievements..."><?= htmlspecialchars($work['description'] ?? '') ?></textarea>
                                </div>
                            </div>
                            <?php 
                            $workIndex++;
                            endforeach; 
                            ?>
                        </div>
                    </div>

                    <!-- ===== SECTION 4: Skills ===== -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-tools"></i> Skills
                        </div>
                        <div class="form-group">
                            <label>Skills (comma separated)</label>
                            <textarea id="skills" rows="3" placeholder="JavaScript, Python, React, SQL, Project Management, Team Leadership"><?= htmlspecialchars($cvData['skills'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- ===== SECTION 5: Additional ===== -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-ellipsis-h"></i> Additional Information
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Languages</label>
                                <input type="text" id="languages" placeholder="English (Fluent), Sinhala (Native), Tamil (Conversational)" value="<?= getSaved($cvData['additional'] ?? [], 'languages') ?>">
                            </div>
                            <div class="form-group">
                                <label>Certifications</label>
                                <input type="text" id="certifications" placeholder="AWS Certified Developer, Google UX Design" value="<?= getSaved($cvData['additional'] ?? [], 'certifications') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Interests</label>
                            <input type="text" id="interests" placeholder="Open source, Chess, Photography, Volunteering" value="<?= getSaved($cvData['additional'] ?? [], 'interests') ?>">
                        </div>
                    </div>

                    <!-- ===== Form Actions ===== -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-primary" onclick="generatePreview()">
                            <i class="fas fa-eye"></i> Preview CV
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="saveCV()">
                            <i class="fas fa-save"></i> Save CV
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                    <div class="status-msg" id="statusMsg"></div>

                </form>
            </div>

            <!-- ── RIGHT: PREVIEW ── -->
            <div class="cv-preview">
                <div class="preview-header">
                    <h2><i class="fas fa-file-pdf" style="color:#36ada3;"></i> CV Preview</h2>
                    <div class="preview-actions">
                        <button class="btn-download" onclick="downloadPDF()">
                            <i class="fas fa-download"></i> Download PDF
                        </button>
                        <button class="btn-print" onclick="downloadPDF()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
                <div class="cv-preview-content" id="cvPreviewContent">
                    <div class="cv-empty">
                        <i class="fas fa-file-alt"></i>
                        Fill in the form and click <strong>Preview CV</strong> to see your CV here.
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ── Footer ── -->
    <?php require_once __DIR__ . '/../shared/footer.php'; ?>

    <script>
        // ============================================================
        // CV GENERATOR — ENHANCED JAVASCRIPT
        // ============================================================

        let educationCount = document.querySelectorAll('#educationContainer .entry-container').length;
        let workCount = document.querySelectorAll('#workContainer .entry-container').length;

        // ── Add Education Entry ──
        function addEducation() {
            const container = document.getElementById('educationContainer');
            const entry = createEducationEntry(educationCount + 1);
            container.appendChild(entry);
            educationCount++;
            updateEntryNumbers('education');
            updateRemoveButtons('education');
        }

        // ── Create Education Entry ──
        function createEducationEntry(num) {
            const div = document.createElement('div');
            div.className = 'entry-container';
            div.dataset.entryType = 'education';
            div.innerHTML = `
                <div class="entry-header">
                    <span class="entry-number">Education #${num}</span>
                    <button type="button" class="btn-remove-entry" onclick="removeEntry(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Institution</label>
                        <input type="text" class="edu_institution" placeholder="University of Colombo">
                    </div>
                    <div class="form-group">
                        <label>Degree</label>
                        <input type="text" class="edu_degree" placeholder="BSc in Computer Science">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Field of Study</label>
                        <input type="text" class="edu_field" placeholder="Software Engineering">
                    </div>
                    <div class="form-group">
                        <label>GPA / Grade</label>
                        <input type="text" class="edu_gpa" placeholder="3.8 / 4.0">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" class="edu_start">
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" class="edu_end">
                    </div>
                </div>
            `;
            return div;
        }

        // ── Add Work Entry ──
        function addWork() {
            const container = document.getElementById('workContainer');
            const entry = createWorkEntry(workCount + 1);
            container.appendChild(entry);
            workCount++;
            updateEntryNumbers('work');
            updateRemoveButtons('work');
        }

        // ── Create Work Entry ──
        function createWorkEntry(num) {
            const div = document.createElement('div');
            div.className = 'entry-container';
            div.dataset.entryType = 'work';
            div.innerHTML = `
                <div class="entry-header">
                    <span class="entry-number">Work #${num}</span>
                    <button type="button" class="btn-remove-entry" onclick="removeEntry(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Company</label>
                        <input type="text" class="work_company" placeholder="Google">
                    </div>
                    <div class="form-group">
                        <label>Position</label>
                        <input type="text" class="work_position" placeholder="Junior Developer">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" class="work_location" placeholder="Colombo, Sri Lanka">
                    </div>
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="radio-group">
                            <label>
                                <input type="checkbox" class="work_current">
                                I currently work here
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" class="work_start">
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" class="work_end">
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="work_description" rows="2" placeholder="Key responsibilities and achievements..."></textarea>
                </div>
            `;
            return div;
        }

        // ── Remove Entry ──
        function removeEntry(btn) {
            const container = btn.closest('.entry-container');
            const type = container.dataset.entryType;
            container.remove();
            if (type === 'education') {
                educationCount--;
                updateEntryNumbers('education');
                updateRemoveButtons('education');
            } else {
                workCount--;
                updateEntryNumbers('work');
                updateRemoveButtons('work');
            }
        }

        // ── Update entry numbers ──
        function updateEntryNumbers(type) {
            const container = document.getElementById(type === 'education' ? 'educationContainer' : 'workContainer');
            const entries = container.querySelectorAll('.entry-container');
            const label = type === 'education' ? 'Education' : 'Work';
            entries.forEach((entry, index) => {
                const numberSpan = entry.querySelector('.entry-number');
                if (numberSpan) {
                    numberSpan.textContent = `${label} #${index + 1}`;
                }
            });
        }

        // ── Update remove buttons ──
        function updateRemoveButtons(type) {
            const container = document.getElementById(type === 'education' ? 'educationContainer' : 'workContainer');
            const entries = container.querySelectorAll('.entry-container');
            const removeBtns = container.querySelectorAll('.btn-remove-entry');
            if (entries.length <= 1) {
                removeBtns.forEach(btn => btn.style.display = 'none');
            } else {
                removeBtns.forEach(btn => btn.style.display = 'block');
            }
        }

        // ── Collect form data ──
        function collectFormData() {
            // Personal
            const personal = {
                first_name: document.getElementById('first_name').value,
                last_name: document.getElementById('last_name').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                location: document.getElementById('location').value,
                linkedin: document.getElementById('linkedin').value,
                website: document.getElementById('website').value,
                title: document.getElementById('title').value,
                summary: document.getElementById('summary').value
            };

            // Education
            const eduEntries = document.querySelectorAll('#educationContainer .entry-container');
            const education = [];
            eduEntries.forEach(entry => {
                const inst = entry.querySelector('.edu_institution');
                const deg = entry.querySelector('.edu_degree');
                const field = entry.querySelector('.edu_field');
                const start = entry.querySelector('.edu_start');
                const end = entry.querySelector('.edu_end');
                const gpa = entry.querySelector('.edu_gpa');
                if (inst && inst.value.trim()) {
                    education.push({
                        institution: inst.value.trim(),
                        degree: deg ? deg.value.trim() : '',
                        field: field ? field.value.trim() : '',
                        start: start ? start.value : '',
                        end: end ? end.value : '',
                        gpa: gpa ? gpa.value.trim() : ''
                    });
                }
            });

            // Work Experience
            const workEntries = document.querySelectorAll('#workContainer .entry-container');
            const work = [];
            workEntries.forEach(entry => {
                const company = entry.querySelector('.work_company');
                const position = entry.querySelector('.work_position');
                const location = entry.querySelector('.work_location');
                const start = entry.querySelector('.work_start');
                const end = entry.querySelector('.work_end');
                const desc = entry.querySelector('.work_description');
                const current = entry.querySelector('.work_current');
                if (company && company.value.trim()) {
                    work.push({
                        company: company.value.trim(),
                        position: position ? position.value.trim() : '',
                        location: location ? location.value.trim() : '',
                        start: start ? start.value : '',
                        end: end ? end.value : '',
                        description: desc ? desc.value.trim() : '',
                        current: current ? current.checked : false
                    });
                }
            });

            // Skills
            const skills = document.getElementById('skills').value.trim();

            // Additional
            const additional = {
                languages: document.getElementById('languages').value.trim(),
                certifications: document.getElementById('certifications').value.trim(),
                interests: document.getElementById('interests').value.trim()
            };

            return { personal, education, work, skills, additional };
        }

        // ── Generate preview ──
        function generatePreview() {
            const data = collectFormData();
            const preview = document.getElementById('cvPreviewContent');

            // Check if any data exists
            const hasData = data.personal.first_name || data.personal.last_name || 
                            data.education.length > 0 || data.work.length > 0 ||
                            data.skills || data.additional.languages;

            if (!hasData) {
                preview.innerHTML = `
                    <div class="cv-empty">
                        <i class="fas fa-file-alt"></i>
                        No data entered. Fill in the form and click <strong>Preview CV</strong>.
                    </div>
                `;
                return;
            }

            let html = '';

            // Name
            const fullName = [data.personal.first_name, data.personal.last_name].filter(Boolean).join(' ') || 'Your Name';
            html += `<div class="cv-name">${escapeHTML(fullName)}</div>`;

            // Title
            if (data.personal.title) {
                html += `<div class="cv-title">${escapeHTML(data.personal.title)}</div>`;
            }

            // Contact
            const contactParts = [];
            if (data.personal.email) contactParts.push(data.personal.email);
            if (data.personal.phone) contactParts.push(data.personal.phone);
            if (data.personal.location) contactParts.push(data.personal.location);
            if (contactParts.length > 0) {
                html += `<div class="cv-contact">${contactParts.map(escapeHTML).join(' | ')}</div>`;
            }

            // LinkedIn / Website
            const socialParts = [];
            if (data.personal.linkedin) {
                socialParts.push(`<a href="${data.personal.linkedin}" target="_blank" style="color:#36ada3;text-decoration:none;">LinkedIn</a>`);
            }
            if (data.personal.website) {
                socialParts.push(`<a href="${data.personal.website}" target="_blank" style="color:#36ada3;text-decoration:none;">Portfolio</a>`);
            }
            if (socialParts.length > 0) {
                html += `<div class="cv-contact">${socialParts.join(' | ')}</div>`;
            }

            // Summary
            if (data.personal.summary) {
                html += `<div class="cv-summary">${escapeHTML(data.personal.summary)}</div>`;
            }

            // Education
            if (data.education.length > 0) {
                html += `<div class="cv-section"><h3>Education</h3>`;
                data.education.forEach(edu => {
                    html += `<div class="cv-item">`;
                    if (edu.institution) html += `<div class="cv-item-header">${escapeHTML(edu.institution)}</div>`;
                    const subParts = [];
                    if (edu.degree) subParts.push(escapeHTML(edu.degree));
                    if (edu.field) subParts.push(escapeHTML(edu.field));
                    if (subParts.length > 0) {
                        html += `<div class="cv-item-sub">${subParts.join(' | ')}</div>`;
                    }
                    const dateParts = [];
                    if (edu.start) dateParts.push(edu.start);
                    if (edu.end) dateParts.push(edu.end);
                    if (dateParts.length > 0) {
                        html += `<div class="cv-item-sub">${dateParts.join(' - ')}</div>`;
                    }
                    if (edu.gpa) html += `<div class="cv-item-sub">GPA: ${escapeHTML(edu.gpa)}</div>`;
                    html += `</div>`;
                });
                html += `</div>`;
            }

            // Work Experience
            if (data.work.length > 0) {
                html += `<div class="cv-section"><h3>Work Experience</h3>`;
                data.work.forEach(w => {
                    html += `<div class="cv-item">`;
                    if (w.company) html += `<div class="cv-item-header">${escapeHTML(w.company)}</div>`;
                    if (w.position) html += `<div class="cv-item-sub"><strong>${escapeHTML(w.position)}</strong></div>`;
                    if (w.location) html += `<div class="cv-item-sub">${escapeHTML(w.location)}</div>`;
                    const dateParts = [];
                    if (w.start) dateParts.push(w.start);
                    if (w.end && !w.current) dateParts.push(w.end);
                    if (w.current) dateParts.push('Present');
                    if (dateParts.length > 0) {
                        html += `<div class="cv-item-sub">${dateParts.join(' - ')}</div>`;
                    }
                    if (w.description) {
                        html += `<div class="cv-item-desc">${escapeHTML(w.description).replace(/\n/g, '<br>')}</div>`;
                    }
                    html += `</div>`;
                });
                html += `</div>`;
            }

            // Skills
            if (data.skills) {
                html += `<div class="cv-section"><h3>Skills</h3>`;
                const skillsList = data.skills.split(',').map(s => s.trim()).filter(Boolean);
                if (skillsList.length > 0) {
                    html += `<div class="cv-item"><div class="cv-item-desc">${skillsList.map(escapeHTML).join(' • ')}</div></div>`;
                }
                html += `</div>`;
            }

            // Additional
            const hasAdditional = data.additional.languages || data.additional.certifications || data.additional.interests;
            if (hasAdditional) {
                html += `<div class="cv-section"><h3>Additional</h3>`;
                if (data.additional.languages) {
                    html += `<div class="cv-item"><div class="cv-item-sub"><strong>Languages:</strong> ${escapeHTML(data.additional.languages)}</div></div>`;
                }
                if (data.additional.certifications) {
                    html += `<div class="cv-item"><div class="cv-item-sub"><strong>Certifications:</strong> ${escapeHTML(data.additional.certifications)}</div></div>`;
                }
                if (data.additional.interests) {
                    html += `<div class="cv-item"><div class="cv-item-sub"><strong>Interests:</strong> ${escapeHTML(data.additional.interests)}</div></div>`;
                }
                html += `</div>`;
            }

            preview.innerHTML = html;
        }

        // ── Escape HTML ──
        function escapeHTML(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        // ── Save CV ──
        function saveCV() {
            const btn = document.querySelector('.btn-secondary .fa-save')?.closest('button') || document.querySelector('.btn-secondary');
            const status = document.getElementById('statusMsg');

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

            const data = collectFormData();

            const formData = new FormData();
            formData.append('action', 'save_cv');
            
            // Personal
            Object.keys(data.personal).forEach(key => {
                formData.append(key, data.personal[key]);
            });

            // Education
            data.education.forEach(edu => {
                formData.append('edu_institution[]', edu.institution);
                formData.append('edu_degree[]', edu.degree);
                formData.append('edu_field[]', edu.field);
                formData.append('edu_start[]', edu.start);
                formData.append('edu_end[]', edu.end);
                formData.append('edu_gpa[]', edu.gpa);
            });

            // Work
            data.work.forEach(w => {
                formData.append('work_company[]', w.company);
                formData.append('work_position[]', w.position);
                formData.append('work_location[]', w.location);
                formData.append('work_start[]', w.start);
                formData.append('work_end[]', w.end);
                formData.append('work_description[]', w.description);
                if (w.current) {
                    formData.append('work_current[]', '1');
                }
            });

            // Skills
            formData.append('skills', data.skills);

            // Additional
            Object.keys(data.additional).forEach(key => {
                formData.append(key, data.additional[key]);
            });

            fetch('cv.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save"></i> Save CV';
                if (res.success) {
                    status.textContent = '✅ CV saved successfully!';
                    status.className = 'status-msg show';
                    setTimeout(() => status.className = 'status-msg', 3000);
                } else {
                    status.textContent = '❌ Failed to save CV.';
                    status.className = 'status-msg show error';
                    setTimeout(() => status.className = 'status-msg', 3000);
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save"></i> Save CV';
                status.textContent = '❌ Error: ' + err.message;
                status.className = 'status-msg show error';
                setTimeout(() => status.className = 'status-msg', 3000);
            });
        }

        // ── Reset form ──
        function resetForm() {
            if (!confirm('Are you sure you want to reset all fields?')) return;
            
            document.querySelectorAll('#cvFormData input, #cvFormData textarea').forEach(el => {
                if (el.type === 'checkbox' || el.type === 'radio') {
                    el.checked = false;
                } else {
                    el.value = '';
                }
            });

            // Reset education to one empty entry
            const eduContainer = document.getElementById('educationContainer');
            eduContainer.innerHTML = '';
            const eduEntry = createEducationEntry(1);
            eduContainer.appendChild(eduEntry);
            educationCount = 1;
            updateRemoveButtons('education');

            // Reset work to one empty entry
            const workContainer = document.getElementById('workContainer');
            workContainer.innerHTML = '';
            const workEntry = createWorkEntry(1);
            workContainer.appendChild(workEntry);
            workCount = 1;
            updateRemoveButtons('work');

            // Reset preview
            document.getElementById('cvPreviewContent').innerHTML = `
                <div class="cv-empty">
                    <i class="fas fa-file-alt"></i>
                    Fill in the form and click <strong>Preview CV</strong> to see your CV here.
                </div>
            `;

            const status = document.getElementById('statusMsg');
            status.textContent = '🔄 Form reset.';
            status.className = 'status-msg show';
            setTimeout(() => status.className = 'status-msg', 2000);
        }

        // ── Download / Print PDF ──
        function downloadPDF() {
            // Generate the latest preview
            generatePreview();

            // Wait a moment for the preview to fully render, then trigger print
            setTimeout(() => {
                window.print();
            }, 300);
        }

        // ── Auto-generate preview on Enter key ──
        document.addEventListener('DOMContentLoaded', () => {
            // Check if there's saved data, if yes generate preview
            const hasData = document.querySelector('#first_name').value || 
                            document.querySelector('#last_name').value ||
                            document.querySelector('#skills').value;
            if (hasData) {
                generatePreview();
            }

            // Auto-generate preview on any input change (with debounce)
            let previewTimeout;
            document.querySelectorAll('#cvFormData input, #cvFormData textarea').forEach(el => {
                el.addEventListener('input', () => {
                    clearTimeout(previewTimeout);
                    previewTimeout = setTimeout(generatePreview, 800);
                });
            });

            // Work current checkbox – toggle end date
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('work_current')) {
                    const container = e.target.closest('.entry-container');
                    const endDate = container.querySelector('.work_end');
                    if (endDate) {
                        endDate.disabled = e.target.checked;
                        if (e.target.checked) endDate.value = '';
                    }
                }
            });

            // Initialize remove buttons
            updateRemoveButtons('education');
            updateRemoveButtons('work');
        });
    </script>

</body>
</html>