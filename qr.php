<?php
require __DIR__ . '/db.php';
$pageTitle = 'Generate Student QR';
include __DIR__ . '/_header.php';
$pdo = db();
$students = $pdo->query('SELECT id, student_id, name FROM students ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
	<div class="col-lg-4">
		<div class="card mb-3">
			<div class="card-header">Select Student</div>
			<div class="card-body">
				<select id="student" class="form-select">
					<option value="">-- choose --</option>
					<?php foreach ($students as $s): ?>
						<option value="<?php echo h($s['id']); ?>" data-code="<?php echo h($s['student_id']); ?>"><?php echo h($s['name']); ?> (<?php echo h($s['student_id']); ?>)</option>
					<?php endforeach; ?>
				</select>
				<div class="mt-3">
					<button id="btnPrint" class="btn btn-primary" disabled>Print QR</button>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-8">
		<div class="card">
			<div class="card-header">QR Preview</div>
			<div class="card-body text-center">
				<div id="qrcode"></div>
				<div class="small text-muted mt-2" id="qrText"></div>
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
const studentSel = document.getElementById('student');
const qrContainer = document.getElementById('qrcode');
const qrText = document.getElementById('qrText');
const btnPrint = document.getElementById('btnPrint');
let currentPayload = '';

function renderQR(payload){
	qrContainer.innerHTML='';
	if(!payload){
		qrText.textContent='';
		btnPrint.disabled = true;
		return;
	}
	new QRCode(qrContainer, {text: payload, width: 256, height: 256});
	qrText.textContent = payload;
	btnPrint.disabled = false;
}

studentSel.addEventListener('change', () => {
	const opt = studentSel.options[studentSel.selectedIndex];
	const id = opt.value;
	const code = opt.getAttribute('data-code');
	currentPayload = id ? JSON.stringify({type:'student', id: parseInt(id), code: code}) : '';
	renderQR(currentPayload);
});

btnPrint.addEventListener('click', () => {
	const dataUrl = qrContainer.querySelector('img')?.src || qrContainer.querySelector('canvas')?.toDataURL();
	const w = window.open('','_blank');
	w.document.write('<html><head><title>Print QR</title></head><body style="text-align:center">');
	w.document.write('<img src="'+dataUrl+'" style="width:300px;height:300px" />');
	w.document.write('<div>'+qrText.textContent+'</div>');
	w.document.write('</body></html>');
	w.document.close();
	w.focus();
	w.print();
	w.close();
});
</script>

<?php include __DIR__ . '/_footer.php'; ?>


