<?php
require_once 'Includes/db_connection.php';

$newCareers = [
    16 => 'technical',
    17 => 'technical',
    18 => 'technical',
    19 => 'technical',
    20 => 'management',
    21 => 'creative',
    22 => 'management'
];

$qRes = mysqli_query($conn, "SELECT QuestionID, Category, Options FROM Questions");
while ($q = mysqli_fetch_assoc($qRes)) {
    $qCat = $q['Category'];
    $options = json_decode($q['Options'], true);
    if (!$options) continue;

    $changed = false;
    foreach ($options as &$opt) {
        if (!isset($opt['scores'])) $opt['scores'] = [];
        foreach ($newCareers as $cid => $cat) {
            if (!isset($opt['scores'][$cid])) {
                if ($cat === $qCat) $score = 5;
                elseif (($cat === 'technical' && $qCat === 'analytical') || ($cat === 'analytical' && $qCat === 'technical')) $score = 3;
                elseif (($cat === 'creative' && $qCat === 'management') || ($cat === 'management' && $qCat === 'creative')) $score = 2;
                else $score = 1;
                $opt['scores'][$cid] = $score;
                $changed = true;
            }
        }
    }
    if ($changed) {
        $newJson = mysqli_real_escape_string($conn, json_encode($options));
        mysqli_query($conn, "UPDATE Questions SET Options = '$newJson' WHERE QuestionID = " . $q['QuestionID']);
        echo "Updated QuestionID " . $q['QuestionID'] . "\n";
    }
}
echo "Done!";
?>