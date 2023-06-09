<?php
// Retrieve form data
$firstName = $_POST['firstName'];
$surname = $_POST['surname'];
$lastName = $_POST['lastName'];
$candidateNumber = $_POST['candidateNumber'];
$papersPassed = $_POST['papersPassed'];
$school = $_POST['school'];
$centerNumber = $_POST['centerNumber'];
$region = $_POST['region'];
$subjects = $_POST['subject'];
$grades = $_POST['grade'];

// Database connection details
$host = 'localhost';
$port = 5432;
$dbname = 'student_record';
$user = 'postgres';
$password = 'nkemdiran@gmail1';

try {
  // Create a PDO instance
  $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");

  // Insert student details into the database
  $sql = "INSERT INTO students (first_name, surname, last_name, candidate_number, papers_passed, school, center_number, region) 
          VALUES (:firstName, :surname, :lastName, :candidateNumber, :papersPassed, :school, :centerNumber, :region)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    'firstName' => $firstName,
    'surname' => $surname,
    'lastName' => $lastName,
    'candidateNumber' => $candidateNumber,
    'papersPassed' => $papersPassed,
    'school' => $school,
    'centerNumber' => $centerNumber,
    'region' => $region
  ]);

  // Get the inserted student's ID
  $studentId = $pdo->lastInsertId();

  // Insert subject and grade details into the database
  foreach ($subjects as $index => $subject) {
    $grade = $grades[$index];

    $sql = "INSERT INTO student_subjects (student_id, subject, grade) 
            VALUES (:studentId, :subject, :grade)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      'studentId' => $studentId,
      'subject' => $subject,
      'grade' => $grade
    ]);
  }

  // Close the database connection
  $pdo = null;

  // Send a response back to the client
  $response = [
    'success' => true,
    'message' => 'Student record has been added successfully.'
  ];
  header('Content-Type: application/json');
  echo json_encode($response);
} catch (PDOException $e) {
  // Handle database connection errors
  $response = [
    'success' => false,
    'message' => 'Failed to connect to the database.'
  ];
  header('Content-Type: application/json');
  echo json_encode($response);
}
