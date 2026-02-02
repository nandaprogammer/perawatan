<?php
$id = $_GET['perawatan'] ?? 0;

$perawatanList = [
    ["Perawatan 1", "Isi Deskripsi Perawatan 1", 125000, "perawatan1.jpg"],
    ["Perawatan 2", "Isi Deskripsi Perawatan 2", 135000, "perawatan2.jpg"],
    ["Perawatan 3", "Isi Deskripsi Perawatan 3", 145000, "perawatan3.jpg"],
    ["Perawatan 4", "Isi Deskripsi Perawatan 4", 155000, "perawatan4.jpg"]
];

$pilper = $_POST['pilper'] ?? $id;
if (!isset($perawatanList[$pilper])) $pilper = 0;

$pilhar = $perawatanList[$pilper][2];

// input
$notransaksi = $_POST['notransaksi'] ?? '';
$tanggal     = $_POST['tanggal'] ?? '';
$nama        = $_POST['nama_pemesan'] ?? '';
$jumlah      = $_POST['jumlah'] ?? '';
$total       = $_POST['total'] ?? '';
$bayar       = $_POST['bayar'] ?? '';
$kembalian   = $_POST['kembalian'] ?? '';
$vip         = isset($_POST['vip']) ? 50000 : 0;

// cancel
if (isset($_POST['cancel'])) {
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// proses
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // validasi wajib isi
    if (
        empty($notransaksi) || empty($tanggal) || empty($nama) ||
        empty($jumlah)
    ) {
        echo "<script>alert('Semua data wajib diisi');</script>";
    }

    // hitung total
    if (isset($_POST['hitung'])) {
        if ((int)$jumlah > 0) {
            $total = ($pilhar * (int)$jumlah) + $vip;
        }
    }

    // hitung kembalian
    if (isset($_POST['hitung_kembalian'])) {
        $total = ($pilhar * (int)$jumlah) + $vip;

        if ((int)$bayar < $total) {
            echo "<script>alert('Uang pembayaran kurang');</script>";
            $kembalian = '';
        } else {
            $kembalian = (int)$bayar - $total;
        }
    }

    // simpan
    if (isset($_POST['simpan'])) {

        if ((int)$bayar < $total) {
            echo "<script>alert('Transaksi gagal, uang kurang');</script>";
        } else {

            $vipText = $vip ? 'Ya (+50000)' : 'Tidak';

            echo "<script>
                alert(
                    'DATA TRANSAKSI\\n\\n' +
                    'No Transaksi : $notransaksi\\n' +
                    'Tanggal : $tanggal\\n' +
                    'Nama : $nama\\n' +
                    'Perawatan : {$perawatanList[$pilper][0]}\\n' +
                    'Harga : $pilhar\\n' +
                    'Jumlah : $jumlah\\n' +
                    'VIP : $vipText\\n' +
                    'Total : $total\\n' +
                    'Bayar : $bayar\\n' +
                    'Kembalian : $kembalian'
                );
                window.location.href='index.php';
            </script>";
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Form Transaksi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Perawatan</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="transaksi.php">Transaksi</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
<div class="card">
<div class="card-header text-center">
<h5>Form Transaksi</h5>
</div>

<div class="card-body">
<form method="post">

<input class="form-control mb-3" name="notransaksi" value="<?= $notransaksi ?>" placeholder="No Transaksi">
<input class="form-control mb-3" type="date" name="tanggal" value="<?= $tanggal ?>">
<input class="form-control mb-3" name="nama_pemesan" value="<?= $nama ?>" placeholder="Nama Pemesan">

<select class="form-select mb-2" name="pilper" onchange="this.form.submit()">
<?php foreach ($perawatanList as $index => $perawatan): ?>
<option value="<?= $index ?>" <?= $index == $pilper ? 'selected' : '' ?>>
<?= $perawatan[0] ?>
</option>
<?php endforeach; ?>
</select>

<input class="form-control mb-3" value="<?= $pilhar ?>" readonly>

<input class="form-control mb-3" type="number" name="jumlah" value="<?= $jumlah ?>" placeholder="Jumlah">

<div class="form-check mb-3">
<input class="form-check-input" type="checkbox" name="vip" <?= $vip ? 'checked' : '' ?>>
<label class="form-check-label">Tambahan Ruang VIP (+50.000)</label>
</div>

<button name="hitung" class="btn btn-primary mb-3">Hitung Total</button>

<input class="form-control mb-3" name="total" value="<?= $total ?>" placeholder="Total Harga" readonly>

<input class="form-control mb-3" type="number" name="bayar" value="<?= $bayar ?>" placeholder="Uang Bayar">

<button name="hitung_kembalian" class="btn btn-primary mb-2">Hitung Kembalian</button>

<input class="form-control mb-3" name="kembalian" value="<?= $kembalian ?>" placeholder="Kembalian" readonly>

<button name="simpan" class="btn btn-success">Simpan</button>
<button name="cancel" class="btn btn-danger">Cancel</button>

</form>
</div>
</div>
</div>
</body>
</html>
