<?php
require __DIR__ . '/db.php';
$pageTitle = 'Events';
include __DIR__ . '/_header.php';

$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'] ?? '';
	if ($action === 'create') {
		$stmt = $pdo->prepare('INSERT INTO events(title, event_date) VALUES(?,?)');
		$stmt->execute([
			trim($_POST['title'] ?? ''),
			trim($_POST['event_date'] ?? ''),
		]);
		echo '<div class="alert alert-success">Event added.</div>';
	}
	if ($action === 'update') {
		$stmt = $pdo->prepare('UPDATE events SET title=?, event_date=? WHERE id=?');
		$stmt->execute([
			trim($_POST['title'] ?? ''),
			trim($_POST['event_date'] ?? ''),
			(int)($_POST['id'] ?? 0),
		]);
		echo '<div class="alert alert-success">Event updated.</div>';
	}
}

if (isset($_GET['delete'])) {
	$id = (int)$_GET['delete'];
	$pdo->prepare('DELETE FROM events WHERE id=?')->execute([$id]);
	echo '<div class="alert alert-warning">Event deleted.</div>';
}

$events = $pdo->query('SELECT * FROM events ORDER BY event_date DESC')->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
	<div class="col-lg-5">
		<div class="card mb-3">
			<div class="card-header">Add Event</div>
			<div class="card-body">
				<form method="post">
					<input type="hidden" name="action" value="create">
					<div class="mb-2"><label class="form-label">Title</label><input required name="title" class="form-control"></div>
					<div class="mb-2"><label class="form-label">Date</label><input required type="date" name="event_date" class="form-control"></div>
					<button class="btn btn-primary">Add</button>
				</form>
			</div>
		</div>
	</div>
	<div class="col-lg-7">
		<div class="card">
			<div class="card-header">Events</div>
			<div class="table-responsive">
				<table class="table table-striped mb-0">
					<thead><tr><th>ID</th><th>Title</th><th>Date</th><th></th></tr></thead>
					<tbody>
					<?php foreach ($events as $e): ?>
						<tr>
							<td><?php echo h($e['id']); ?></td>
							<td><?php echo h($e['title']); ?></td>
							<td><?php echo h($e['event_date']); ?></td>
							<td class="text-end">
								<button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#edit<?php echo $e['id']; ?>">Edit</button>
								<a class="btn btn-sm btn-outline-danger" href="?delete=<?php echo $e['id']; ?>" onclick="return confirm('Delete this event?')">Delete</a>
							</td>
						</tr>
						<div class="modal fade" id="edit<?php echo $e['id']; ?>" tabindex="-1">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header"><h5 class="modal-title">Edit Event</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
									<form method="post">
										<input type="hidden" name="action" value="update">
										<input type="hidden" name="id" value="<?php echo h($e['id']); ?>">
										<div class="modal-body">
											<div class="mb-2"><label class="form-label">Title</label><input required name="title" class="form-control" value="<?php echo h($e['title']); ?>"></div>
											<div class="mb-2"><label class="form-label">Date</label><input required type="date" name="event_date" class="form-control" value="<?php echo h($e['event_date']); ?>"></div>
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


