<?php
require __DIR__ . '/db.php';
$pageTitle = 'Scan & Record Attendance';
include __DIR__ . '/_header.php';
$pdo = db();
$events = $pdo->query('SELECT id, title, event_date FROM events ORDER BY event_date DESC')->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
	<div class="col-lg-4">
		<div class="card mb-3">
			<div class="card-header">Choose Event</div>
			<div class="card-body">
				<select id="event" class="form-select">
					<option value="">-- select event --</option>
					<?php foreach ($events as $e): ?>
						<option value="<?php echo h($e['id']); ?>"><?php echo h($e['title']); ?> (<?php echo h($e['event_date']); ?>)</option>
					<?php endforeach; ?>
				</select>
				<div class="form-text">Scanning is enabled only when an event is selected.</div>
				<div id="status" class="mt-2 small text-muted"></div>
			</div>
		</div>
	</div>
	<div class="col-lg-8">
		<div class="card">
			<div class="card-header d-flex justify-content-between align-items-center">
				<span>Scanner</span>
				<a class="btn btn-sm btn-outline-secondary" href="attendance.php">View Attendance</a>
			</div>
			<div class="card-body">
				<div id="reader" style="width:100%"></div>
				<div class="mt-3">
					<pre id="last" class="bg-light p-2 border" style="max-height:120px; overflow:auto"></pre>
				</div>
				<hr>
				<div>
					<label class="form-label">Or upload a QR image</label>
					<input id="qrFile" type="file" accept="image/*" class="form-control" />
					<div class="form-text">Select a photo or screenshot of a QR code to record attendance.</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
const eventSel = document.getElementById('event');
const statusEl = document.getElementById('status');
const lastEl = document.getElementById('last');
let html5QrCode;
let isScanning = false;
let lastDecoded = '';
const fileInput = document.getElementById('qrFile');

function log(msg){
	lastEl.textContent = new Date().toLocaleString() + ' - ' + msg + '\n' + lastEl.textContent;
}

async function recordAttendance(eventId, payload){
	try{
		const res = await fetch('submit_attendance.php', {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ event_id: eventId, payload })});
		const data = await res.json();
		if(data.ok){
			statusEl.textContent = 'Recorded: ' + data.student.name + ' at ' + data.check_in_at;
			statusEl.className = 'mt-2 text-success';
		}else{
			statusEl.textContent = data.error || 'Failed to record';
			statusEl.className = 'mt-2 text-danger';
		}
	}catch(e){
		statusEl.textContent = 'Network error';
		statusEl.className = 'mt-2 text-danger';
	}
}

async function startScanner(){
	if(isScanning) return;
	const eventId = eventSel.value;
	if(!eventId){
		statusEl.textContent = 'Select an event to start scanning.';
		statusEl.className = 'mt-2 text-muted';
		return;
	}
	if(!html5QrCode){
		html5QrCode = new Html5Qrcode('reader');
	}
	const config = { fps: 10, qrbox: 250 };
	const cameras = await Html5Qrcode.getCameras();
	const cameraId = cameras?.[0]?.id;
	if(!cameraId){
		statusEl.textContent = 'No camera found.';
		statusEl.className = 'mt-2 text-danger';
		return;
	}
	await html5QrCode.start(cameraId, config, (decodedText) => {
		if(decodedText && decodedText !== lastDecoded){
			lastDecoded = decodedText;
			log('Scanned: ' + decodedText);
			recordAttendance(eventId, decodedText);
			setTimeout(()=>{ lastDecoded = ''; }, 1500);
		}
	}, (err) => {
		// ignore
	});
	isScanning = true;
	statusEl.textContent = 'Scanner running...';
	statusEl.className = 'mt-2 text-success';
}

eventSel.addEventListener('change', async ()=>{
	if(isScanning && html5QrCode){
		await html5QrCode.stop();
		html5QrCode.clear();
		isScanning = false;
	}
	startScanner();
});

// Try auto-start if event preselected
startScanner();

fileInput.addEventListener('change', async (e) => {
	const eventId = eventSel.value;
	if(!eventId){
		statusEl.textContent = 'Select an event before uploading.';
		statusEl.className = 'mt-2 text-danger';
		fileInput.value = '';
		return;
	}
	const file = e.target.files?.[0];
	if(!file) return;
	try{
		if(!html5QrCode){
			html5QrCode = new Html5Qrcode('reader');
		}
		// Stop live camera if running
		if(isScanning){
			await html5QrCode.stop();
			html5QrCode.clear();
			isScanning = false;
		}
		statusEl.textContent = 'Decoding image...';
		statusEl.className = 'mt-2 text-muted';
		const result = await html5QrCode.scanFile(file, true);
		log('Image scanned: ' + result);
		recordAttendance(eventId, result);
	} catch(err){
		statusEl.textContent = 'Could not decode QR from image.';
		statusEl.className = 'mt-2 text-danger';
	} finally {
		fileInput.value = '';
		// Optionally restart camera scanning after image decode
		startScanner();
	}
});
</script>

<?php include __DIR__ . '/_footer.php'; ?>


