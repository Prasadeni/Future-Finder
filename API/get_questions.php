<?php

header('Content-Type: application/json');

require_once '../Includes/db_connection.php';

$sql = "SELECT QuestionID, Text, Category, Weight, Options 
        FROM Questions 
        ORDER BY 
            FIELD(Category, 'technical', 'analytical', 'creative', 'management'),
            QuestionID ASC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(['error' => true, 'message' => 'Could not fetch questions: ' . mysqli_error($conn)]);
    exit;
}

$questions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['Options'] = json_decode($row['Options']);
    $questions[] = $row;
}

echo json_encode(['questions' => $questions, 'total' => count($questions)]);

mysqli_close($conn);
?>