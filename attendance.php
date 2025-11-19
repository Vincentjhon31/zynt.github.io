<?php
require __DIR__ . '/db.php';
$pageTitle = 'Attendance';
include __DIR__ . '/_header.php';
$pdo = db();

$eventId = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;

if (isset($_GET['export']) && $eventId) {
	$event = $pdo->prepare('SELECT * FROM events WHERE id=?');
	$event->execute([$eventId]);
	$e = $event->fetch(PDO::FETCH_ASSOC);
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="attendance_' . $e['id'] . '.csv"');
	$out = fopen('php://output', 'w');
	fputcsv($out, ['Student No.', 'Name', 'Program', 'Year', 'Check-in At']);
	$stmt = $pdo->prepare('SELECT s.student_id, s.name, s.program, s.year_level, a.check_in_at FROM attendance a JOIN students s ON s.id=a.student_id WHERE a.event_id=? ORDER BY a.check_in_at DESC');
	$stmt->execute([$eventId]);
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		fputcsv($out, [$row['student_id'], $row['name'], $row['program'], $row['year_level'], $row['check_in_at']]);
	}
	fclose($out);
	exit;
}

$events = $pdo->query('SELECT id, title, event_date FROM events ORDER BY event_date DESC')->fetchAll(PDO::FETCH_ASSOC);

$rows = [];
if ($eventId) {
	$stmt = $pdo->prepare('SELECT a.id, s.student_id, s.name, s.program, s.year_level, a.check_in_at FROM attendance a JOIN students s ON s.id=a.student_id WHERE a.event_id=? ORDER BY a.check_in_at DESC');
	$stmt->execute([$eventId]);
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="row">
	<div class="col-lg-4">
		<div class="card mb-3">
			<div class="card-header">Filter by Event</div>
			<div class="card-body">
				<form method="get">
					<select name="event_id" class="form-select" onchange="this.form.submit()">
						<option value="0">-- choose --</option>
						<?php foreach ($events as $e): ?>
							<option value="<?php echo h($e['id']); ?>" <?php if($eventId==$e['id']) echo 'selected'; ?>><?php echo h($e['title']); ?> (<?php echo h($e['event_date']); ?>)</option>
						<?php endforeach; ?>
					</select>
				</form>
				<?php if ($eventId): ?>
					<a class="btn btn-sm btn-outline-secondary mt-2" href="?event_id=<?php echo $eventId; ?>&export=1">Export CSV</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="col-lg-8">
		<div class="card">
			<div class="card-header">Attendance</div>
			<div class="table-responsive">
				<table class="table table-striped mb-0">
					<thead><tr><th>Student No.</th><th>Name</th><th>Program</th><th>Year</th><th>Check-in</th></tr></thead>
					<tbody>
					<?php foreach ($rows as $r): ?>
						<tr>
							<td><?php echo h($r['student_id']); ?></td>
							<td><?php echo h($r['name']); ?></td>
							<td><?php echo h($r['program']); ?></td>
							<td><?php echo h($r['year_level']); ?></td>
							<td><?php echo h($r['check_in_at']); ?></td>
						</tr>
					<?php endforeach; ?>
					<?php if (!$rows): ?>
						<tr><td colspan="5" class="text-center text-muted">No records</td></tr>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php include __DIR__ . '/_footer.php'; ?>


