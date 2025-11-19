<?php
require __DIR__ . '/db.php';
header('Content-Type: application/json');
$pdo = db();

$input = json_decode(file_get_contents('php://input'), true);
$eventId = (int)($input['event_id'] ?? 0);
$payload = $input['payload'] ?? '';

if (!$eventId || !$payload) {
	echo json_encode(['ok' => false, 'error' => 'Invalid input']);
	exit;
}

// Expect payload like {type:'student', id: <students.id>, code: '<student_id>'}
try {
	$data = json_decode($payload, true);
	if (!is_array($data) || ($data['type'] ?? '') !== 'student') {
		throw new Exception('Invalid QR content');
	}
	$studentDbId = (int)($data['id'] ?? 0);
	if (!$studentDbId) throw new Exception('Missing student id');

	$student = $pdo->prepare('SELECT * FROM students WHERE id=?');
	$student->execute([$studentDbId]);
	$stu = $student->fetch(PDO::FETCH_ASSOC);
	if (!$stu) throw new Exception('Student not found');

	$now = date('Y-m-d H:i:s');
	$stmt = $pdo->prepare('INSERT OR IGNORE INTO attendance(event_id, student_id, check_in_at) VALUES(?,?,?)');
	$stmt->execute([$eventId, $studentDbId, $now]);
	if ($stmt->rowCount() === 0) {
		// already exists, update timestamp
		$pdo->prepare('UPDATE attendance SET check_in_at=? WHERE event_id=? AND student_id=?')->execute([$now, $eventId, $studentDbId]);
	}

	echo json_encode(['ok' => true, 'student' => ['id'=>$stu['id'],'name'=>$stu['name'],'student_id'=>$stu['student_id']], 'check_in_at' => $now]);
} catch (Throwable $e) {
	echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}


