<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit;
}

$userRole = $_SESSION['role'] ?? 'user';
if ($userRole === 'admin') {
    header('Location: ../admin.php');
    exit;
}

$firstName = htmlspecialchars($_SESSION['first_name'] ?? 'User');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Career Assessment | Future Finder</title>
    <link rel="stylesheet" href="../CSS/assessment.css">
    <style>
        body {
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
        }

        .intro-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px 60px;
        }

        .intro-image {
            display: block;
            max-width: 100%;
            width: 480px;
            height: auto;
            border-radius: 16px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .intro-card {
            max-width: 820px;
            width: 100%;
            background: rgba(30, 31, 74, 0.78);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-radius: 24px;
            padding: 28px 44px 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            text-align: center;
            color: #f0f4f8;
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .intro-card h1 {
            font-size: 1.8rem;
            color: #36ada3;
            margin-bottom: 4px;
            font-weight: 700;
        }

        .intro-card .subtitle {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 20px;
            letter-spacing: 0.5px;
        }

        .intro-card .description {
            font-size: 0.95rem;
            line-height: 1.7;
            text-align: left;
            margin-bottom: 18px;
            color: rgba(255, 255, 255, 0.88);
        }

        .intro-card .description strong {
            color: #36ada3;
        }

        .intro-card .greeting {
            font-size: 1.05rem;
            font-weight: 600;
            color: #36ada3;
            margin-bottom: 22px;
        }

        .button-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            margin-top: 6px;
        }

        .btn-start,
        .btn-back {
            display: inline-block;
            width: 240px;
            padding: 14px 20px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s, background 0.2s;
            box-sizing: border-box;
        }

        .btn-start {
            background: linear-gradient(135deg, #36ada3, #2d9a90);
            color: #fff;
            border: none;
            box-shadow: 0 4px 14px rgba(54, 173, 163, 0.35);
        }
        .btn-start:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(54, 173, 163, 0.5);
            color: #fff;
        }

        .btn-back {
            background: transparent;
            color: rgba(255, 255, 255, 0.7);
            border: 2px solid rgba(255, 255, 255, 0.25);
        }
        .btn-back:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.5);
        }

        @media (max-width: 768px) {
            .intro-image {
                width: 100%;
                max-width: 400px;
            }
            .intro-card {
                padding: 24px 28px 24px;
            }
            .intro-card h1 {
                font-size: 1.5rem;
            }
            .intro-card .description {
                font-size: 0.9rem;
            }
            .btn-start,
            .btn-back {
                width: 200px;
                padding: 12px 16px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .intro-wrapper {
                padding: 20px 16px 40px;
            }
            .intro-card {
                padding: 20px 16px 20px;
                border-radius: 18px;
            }
            .intro-card h1 {
                font-size: 1.3rem;
            }
            .btn-start,
            .btn-back {
                width: 180px;
                padding: 10px 14px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/../shared/navbar.php'; ?>

<div class="intro-wrapper">

    <!-- Centered image -->
    <img src="../Images/assessment2.png" alt="Career Assessment" class="intro-image">

    <!-- Glass card -->
    <div class="intro-card">
        <h1>About Career Assessment</h1>
        <p class="subtitle">Understand how this works before you begin</p>

        <div class="description">
            <p>
                <strong>Welcome to your personalised career journey !</strong>
            </p>
            
            <p style="margin-top: 12px;">
                This career assessment is carefully designed to help you discover career paths that align with your 
                <strong>natural strengths, interests, and working style</strong>. It goes beyond surface-level questions 
                to provide meaningful insights about where you could thrive professionally.
            </p>

            <div style="margin-top: 16px; padding: 16px 20px; background: rgba(54, 173, 163, 0.12); border-radius: 12px; border-left: 4px solid #36ada3;">
                <p style="margin: 0; font-weight: 600; color: #36ada3;">📌 What to expect:</p>
                <ul style="margin: 8px 0 0 20px; color: rgba(255,255,255,0.85); line-height: 1.8; list-style-type: none; padding: 0;">
                    <li style="margin-bottom: 6px;">✅ <strong>12 targeted questions</strong> – takes approximately 5–10 minutes</li>
                    <li style="margin-bottom: 6px;">✅ <strong>No right or wrong answers</strong> – it's about understanding your natural preferences</li>
                    <li style="margin-bottom: 6px;">✅ <strong>Four career dimensions</strong> – Technical, Analytical, Creative, and Management</li>
                    <li style="margin-bottom: 6px;">✅ <strong>Personalised recommendations</strong> – get matched with careers that fit your profile</li>
                </ul>
            </div>

            <p style="margin-top: 16px;">
                <strong>💡 How to get the most accurate results:</strong> Answer each question honestly based on how you 
                <em>genuinely</em> think and feel, not what you think is the "correct" or "expected" response. Your first 
                instinct is often the most authentic – go with it!
            </p>

            <div style="margin-top: 16px; padding: 14px 18px; background: rgba(255, 255, 255, 0.06); border-radius: 12px;">
                <p style="margin: 0; font-size: 0.95rem; color: rgba(255,255,255,0.75);">
                    ⏱️ <strong style="color: #fff;">Quick tip:</strong> Find a quiet space where you won't be disturbed. 
                    Take your time with each question – there's no time limit.
                </p>
            </div>

            <p style="margin-top: 16px; font-size: 0.95rem; color: rgba(255,255,255,0.7);">
                🔒 Your answers are <strong>private and secure</strong>. Only you and your career advisor (if applicable) 
                will be able to view your results.
            </p>
        </div>

        <div class="greeting">
            Good Luck, <?php echo $firstName; ?>!
        </div>

        <div class="button-group">
            <a href="assessment.php" class="btn-start">START NOW</a>
            <a href="dashboard.php" class="btn-back">← Back to Dashboard</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../shared/footer.php'; ?>

</body>
</html>