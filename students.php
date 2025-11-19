<?php
require __DIR__ . '/db.php';
$pageTitle = 'Students';
include __DIR__ . '/_header.php';

$pdo = db();

// Handle create/update/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'] ?? '';
	if ($action === 'create') {
		$stmt = $pdo->prepare('INSERT INTO students(student_id, name, program, year_level) VALUES(?,?,?,?)');
		$stmt->execute([
			trim($_POST['student_id'] ?? ''),
			trim($_POST['name'] ?? ''),
			trim($_POST['program'] ?? ''),
			trim($_POST['year_level'] ?? ''),
		]);
		echo '<div class="alert alert-success">Student added.</div>';
	}
	if ($action === 'update') {
		$stmt = $pdo->prepare('UPDATE students SET student_id=?, name=?, program=?, year_level=? WHERE id=?');
		$stmt->execute([
			trim($_POST['student_id'] ?? ''),
			trim($_POST['name'] ?? ''),
			trim($_POST['program'] ?? ''),
			trim($_POST['year_level'] ?? ''),
			(int)($_POST['id'] ?? 0),
		]);
		echo '<div class="alert alert-success">Student updated.</div>';
	}
}

if (isset($_GET['delete'])) {
	$id = (int)$_GET['delete'];
	$pdo->prepare('DELETE FROM students WHERE id=?')->execute([$id]);
	echo '<div class="alert alert-warning">Student deleted.</div>';
}

$students = $pdo->query('SELECT * FROM students ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
	<div class="col-lg-5">
		<div class="card mb-3">
			<div class="card-header">Add Student</div>
			<div class="card-body">
				<form method="post">
					<input type="hidden" name="action" value="create">
					<div class="mb-2"><label class="form-label">Student No.</label><input required name="student_id" class="form-control"></div>
					<div class="mb-2"><label class="form-label">Full Name</label><input required name="name" class="form-control"></div>
					<div class="mb-2"><label class="form-label">Program</label><input name="program" class="form-control"></div>
					<div class="mb-2"><label class="form-label">Year Level</label><input name="year_level" class="form-control"></div>
					<button class="btn btn-primary">Add</button>
				</form>
			</div>
		</div>
	</div>
	<div class="col-lg-7">
		<div class="card">
			<div class="card-header d-flex justify-content-between align-items-center">
				<span>Students</span>
				<a href="qr.php" class="btn btn-sm btn-outline-primary">Generate QR</a>
			</div>
			<div class="table-responsive">
				<table class="table table-striped mb-0">
					<thead><tr><th>ID</th><th>Student No.</th><th>Name</th><th>Program</th><th>Year</th><th></th></tr></thead>
					<tbody>
					<?php foreach ($students as $s): ?>
						<tr>
							<td><?php echo h($s['id']); ?></td>
							<td><?php echo h($s['student_id']); ?></td>
							<td><?php echo h($s['name']); ?></td>
							<td><?php echo h($s['program']); ?></td>
							<td><?php echo h($s['year_level']); ?></td>
							<td class="text-end">
								<button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#edit<?php echo $s['id']; ?>">Edit</button>
								<a class="btn btn-sm btn-outline-danger" href="?delete=<?php echo $s['id']; ?>" onclick="return confirm('Delete this student?')">Delete</a>
							</td>
						</tr>
						<div class="modal fade" id="edit<?php echo $s['id']; ?>" tabindex="-1">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header"><h5 class="modal-title">Edit Student</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
									<form method="post">
										<input type="hidden" name="action" value="update">
										<input type="hidden" name="id" value="<?php echo h($s['id']); ?>">
										<div class="modal-body">
											<div class="mb-2"><label class="form-label">Student No.</label><input required name="student_id" class="form-control" value="<?php echo h($s['student_id']); ?>"></div>
											<div class="mb-2"><label class="form-label">Full Name</label><input required name="name" class="form-control" value="<?php echo h($s['name']); ?>"></div>
											<div class="mb-2"><label class="form-label">Program</label><input name="program" class="form-control" value="<?php echo h($s['program']); ?>"></div>
											<div class="mb-2"><label class="form-label">Year Level</label><input name="year_level" class="form-control" value="<?php echo h($s['year_level']); ?>"></div>
										</div>
										<div class="modal-footer"><button class="btn btn-primary">Save</button></div>
									</form>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php include __DIR__ . '/_footer.php'; ?>


