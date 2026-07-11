<?php

session_start();

// If user is not logged in, send them to the login page
// $_SESSION['user_id'] is only set after successful login via login.php
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit;
}

// Read logged-in user's name to show in the navbar
$navName = htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']);
$userRole = $_SESSION['role'] ?? 'user';

// Only 'user' role can do the assessment (not admin)
if ($userRole === 'admin') {
    header('Location: ../admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Assessment | Future Finder</title>
    <link rel="stylesheet" href="../CSS/assessment.css">
</head>
<body>

<?php require_once __DIR__ . '/../shared/navbar.php'; ?>
    


<div class="wrapper">

    <!-- Page header -->
    <div class="page-header">
        <h1>🎯 Career Assessment</h1>
        <p>Answer all 12 questions honestly. Each answer is scored and matched to a career path that suits you.</p>
    </div>

    <!-- Progress bar -->
    <div class="progress-wrap">
        <div class="progress-info">
            <span>Question <strong id="currentQ">1</strong> of <strong id="totalQ">12</strong></span>
            <span id="progressPercent">0% Complete</span>
        </div>
        <div class="progress-bar-bg">
            <div class="progress-bar-fill" id="progressFill" style="width:0%"></div>
        </div>
        <!-- Badge colour changes based on question category (technical/analytical/creative/management) -->
        <span class="category-badge cat-technical" id="categoryBadge">Technical</span>
    </div>

    <!-- Warning alert shown if user tries next without selecting an answer -->
    <div class="alert alert-warning" id="alertBox">
        ⚠️ Please select an answer before continuing.
    </div>

    <!-- Question area — renderQuestion() injects HTML here -->
    <div id="questionArea">
        <div class="state-box" id="loadingBox">
            <div class="spinner"></div>
            <h3>Loading Assessment...</h3>
            <p>Please wait while we prepare your questions.</p>
        </div>
    </div>

    <!-- Navigation buttons -->
    <div class="nav-buttons" id="navButtons" style="display:none;">
        <button class="btn btn-outline" id="btnPrev"   onclick="prevQuestion()">← Previous</button>
        <button class="btn btn-primary"  id="btnNext"   onclick="nextQuestion()">Next →</button>
        <!-- Submit only shown on final question -->
        <button class="btn btn-success"  id="btnSubmit" onclick="submitAssessment()" style="display:none;">
            ✅ Submit Assessment
        </button>
    </div>

    <!-- Dot navigator — one dot per question, click to jump -->
    <div class="dot-nav" id="dotNav"></div>

</div>

<!-- ── JavaScript ────────────────────────────────────────── -->
<script>

// ── State ────────────────────────────────────────────────
let questions    = [];   // All 12 question objects from get_questions.php
let answers      = {};   // { QuestionID: "selected label string" }
let currentIdx   = 0;    // 0-based index of current question
let AssessmentID = null; // Set by start_assessment.php response

// Maps category name → CSS class for the badge colour
const categoryColors = {
    technical:  'cat-technical',
    analytical: 'cat-analytical',
    creative:   'cat-creative',
    management: 'cat-management'
};

// ── On page load ─────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {

    // Step 1: Fetch all 12 questions from DB via PHP API
    fetch('../API/get_questions.php')
        .then(r => r.json())
        .then(data => {
            if (data.error) { showError(data.message); return; }

            questions = data.questions;
            document.getElementById('totalQ').textContent = questions.length;
            document.getElementById('loadingBox').style.display = 'none';
            document.getElementById('navButtons').style.display = 'flex';

            buildDotNav();    // build question dot navigator
            renderQuestion(0); // show first question
            startAssessment(); // create Assessment record in DB
        })
        .catch(err => showError('Could not load questions: ' + err.message));
});

// ── Create Assessment record in DB ───────────────────────
// Calls start_assessment.php → INSERT INTO Assessments (UserID from session, Date, Status='in_progress')
// Returns AssessmentID which is then sent with all answers on submit
function startAssessment() {
    fetch('../API/start_assessment.php', { method: 'POST' })
        .then(r => r.json())
        .then(data => {
            if (data.auth === false) {
                // Session expired — redirect back to login
                alert('Your session has expired. Please log in again.');
                window.location.href = '../login.html';
                return;
            }
            if (data.AssessmentID) {
                AssessmentID = data.AssessmentID;
            }
        })
        .catch(err => console.log('start_assessment error:', err));
}

// ── Render a single question ─────────────────────────────
function renderQuestion(idx) {
    const q = questions[idx];
    if (!q) return;

    // Update progress display
    const percent = Math.round((idx / questions.length) * 100);
    document.getElementById('currentQ').textContent         = idx + 1;
    document.getElementById('progressFill').style.width    = percent + '%';
    document.getElementById('progressPercent').textContent = percent + '% Complete';

    // Update category badge colour
    const badge = document.getElementById('categoryBadge');
    badge.className   = 'category-badge ' + (categoryColors[q.Category] || 'cat-technical');
    badge.textContent = q.Category.charAt(0).toUpperCase() + q.Category.slice(1);

    // Parse options — each option is an object: { label: "...", scores: {...} }
    // get_questions.php json_decode()s them so they arrive as objects
    let opts = Array.isArray(q.Options) ? q.Options : JSON.parse(q.Options);

    const letters = ['A', 'B', 'C', 'D'];

    // Build option buttons — we show only the label string to the user
    // The scores object inside each option is used only by submit_assessment.php on the server
    const optionsHTML = opts.map((opt, i) => {
        const label = typeof opt === 'object' ? opt.label : opt;
        const isSelected = answers[q.QuestionID] === label;
        return `
        <label class="option-label ${isSelected ? 'selected' : ''}"
               onclick="selectOption(this, '${escapeStr(label)}', ${q.QuestionID})">
            <input type="radio" name="q${q.QuestionID}" value="${escapeStr(label)}" ${isSelected ? 'checked' : ''}>
            <div class="option-indicator"></div>
            <span class="option-letter">${letters[i]}.</span>
            <span class="option-text">${label}</span>
        </label>`;
    }).join('');

    // Inject question card into page
    document.getElementById('questionArea').innerHTML = `
        <div class="question-card">
            <div class="q-meta">
                <span class="q-number">Question ${idx + 1} of ${questions.length}</span>
                <span class="q-weight">Weight: ${q.Weight}/4</span>
            </div>
            <div class="q-text">${q.Text}</div>
            <div class="q-hint">Select the option that best describes you.</div>
            <div class="options-list">${optionsHTML}</div>
        </div>`;

    // Show/hide Previous button
    document.getElementById('btnPrev').style.display = idx === 0 ? 'none' : 'inline-flex';

    // Show Next or Submit on last question
    const isLast = idx === questions.length - 1;
    document.getElementById('btnNext').style.display   = isLast ? 'none'        : 'inline-flex';
    document.getElementById('btnSubmit').style.display = isLast ? 'inline-flex' : 'none';

    updateDotNav(idx);
    hideAlert();
}

// ── Handle answer selection ──────────────────────────────
function selectOption(labelEl, value, QuestionID) {
    // Deselect all options for this question
    document.querySelectorAll('.option-label').forEach(l => l.classList.remove('selected'));

    // Mark chosen option
    labelEl.classList.add('selected');

    // Save answer: key = QuestionID, value = the label string
    // submit_assessment.php matches this label back to the options array
    // to find the scores object, then multiplies by question weight
    answers[QuestionID] = value;

    document.getElementById('btnNext').disabled   = false;
    document.getElementById('btnSubmit').disabled = false;

    updateDotNav(currentIdx);
    hideAlert();
}

// ── Navigate forward ─────────────────────────────────────
function nextQuestion() {
    const q = questions[currentIdx];
    if (!answers[q.QuestionID]) { showAlert(); return; }
    currentIdx++;
    renderQuestion(currentIdx);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ── Navigate backward ────────────────────────────────────
function prevQuestion() {
    if (currentIdx > 0) {
        currentIdx--;
        renderQuestion(currentIdx);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

// ── Submit all answers ───────────────────────────────────
// Sends AssessmentID + all answers to submit_assessment.php
// PHP matches each answer label → options array → scores object
// Multiplies scores by weight, sums per career, saves top 3 recommendations
function submitAssessment() {
    const q = questions[currentIdx];
    if (!answers[q.QuestionID]) { showAlert(); return; }

    // Check all 12 questions answered
    const unanswered = questions.filter(q => !answers[q.QuestionID]);
    if (unanswered.length > 0) {
        alert(`You still have ${unanswered.length} unanswered question(s). Please go back and answer them.`);
        return;
    }

    const btnSubmit       = document.getElementById('btnSubmit');
    btnSubmit.disabled    = true;
    btnSubmit.textContent = '⏳ Submitting...';

    // Build payload: AssessmentID + array of { QuestionID, SelectedOption (label string) }
    const payload = {
        AssessmentID: AssessmentID,
        answers: Object.entries(answers).map(([qID, selectedLabel]) => ({
            QuestionID:     parseInt(qID),
            SelectedOption: selectedLabel
        }))
    };

    // POST to PHP — which handles all scoring and saving
    fetch('../API/submit_assessment.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Go to results page — AssessmentID passed as query param
            window.location.href = 'results.php?id=' + AssessmentID;
        } else {
            alert('Submission error: ' + (data.message || 'Please try again.'));
            btnSubmit.disabled    = false;
            btnSubmit.textContent = '✅ Submit Assessment';
        }
    })
    .catch(() => {
        alert('Network error. Please check your XAMPP server is running.');
        btnSubmit.disabled    = false;
        btnSubmit.textContent = '✅ Submit Assessment';
    });
}

// ── Dot navigator ─────────────────────────────────────────
function buildDotNav() {
    const dotNav = document.getElementById('dotNav');
    dotNav.innerHTML = '';
    questions.forEach((q, i) => {
        const dot     = document.createElement('div');
        dot.className = 'dot dot-unanswered';
        dot.id        = 'dot-' + i;
        dot.title     = 'Question ' + (i + 1);
        dot.onclick   = () => { currentIdx = i; renderQuestion(i); window.scrollTo({top:0,behavior:'smooth'}); };
        dotNav.appendChild(dot);
    });
}

function updateDotNav(activeIdx) {
    questions.forEach((q, i) => {
        const dot = document.getElementById('dot-' + i);
        if (!dot) return;
        if (i === activeIdx)              dot.className = 'dot dot-current';
        else if (answers[q.QuestionID])   dot.className = 'dot dot-answered';
        else                              dot.className = 'dot dot-unanswered';
    });
}

// ── Alert helpers ─────────────────────────────────────────
function showAlert() {
    const a = document.getElementById('alertBox');
    a.classList.add('show');
    a.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}
function hideAlert() { document.getElementById('alertBox').classList.remove('show'); }

// ── Error display ─────────────────────────────────────────
function showError(msg) {
    document.getElementById('questionArea').innerHTML = `
        <div class="state-box">
            <h3>⚠️ Error Loading Questions</h3>
            <p>${msg}</p>
            <p style="margin-top:10px;font-size:13px;color:#64748b">
                Make sure XAMPP is running and the futurefinder database is set up correctly.
            </p>
        </div>`;
}

// ── HTML escape for attribute strings ────────────────────
function escapeStr(str) {
    return String(str).replace(/\\/g,'\\\\').replace(/'/g,"\\'").replace(/"/g,'&quot;');
}

</script>

<?php require_once __DIR__ . '/../shared/footer.php'; ?>
    
</body>
</html>
