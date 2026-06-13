<?php 
    $filter = explode('/', $filter);
    $dari_tgl = $filter[0];
    $sampai_tgl = $filter[1];
    $status_pengaduan_filter = $filter[2]; // Mengubah nama agar tidak bentrok di dalam loop
    
    $dari_tgl = date("Y-m-d\T00:00:01", strtotime($dari_tgl));
    $sampai_tgl = date("Y-m-d\T23:59:59", strtotime($sampai_tgl));
    $this->db->join('masyarakat', 'pengaduan.id_masyarakat=masyarakat.id_masyarakat');
    $this->db->join('kelurahan', 'pengaduan.id_kelurahan=kelurahan.id_kelurahan');
    $this->db->order_by('id_pengaduan', 'desc');
    if ($status_pengaduan_filter == 'semua')
    {
        $pengaduan = $this->db->get_where('pengaduan', ['tgl_pengaduan >=' => $dari_tgl, 'tgl_pengaduan <=' => $sampai_tgl])->result_array();
    }
    else
    {
        $pengaduan = $this->db->get_where('pengaduan', ['tgl_pengaduan >=' => $dari_tgl, 'tgl_pengaduan <=' => $sampai_tgl, 'status_pengaduan' => $status_pengaduan_filter])->result_array();
    }

    // --- LOGIKA HITUNG RANGKUMAN STATUS ---
    $jumlah_selesai = 0;
    $jumlah_proses = 0;
    $jumlah_total = count($pengaduan);

    foreach ($pengaduan as $dp) {
        if ($dp['status_pengaduan'] == 'selesai') {
            $jumlah_selesai++;
        } elseif ($dp['status_pengaduan'] == 'proses' || $dp['status_pengaduan'] == 'pengerjaan' || $dp['status_pengaduan'] == 'belum_ditanggapi') { 
            // Otomatis menghitung status 'proses' ataupun 'pengerjaan'
            $jumlah_proses++;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        .btn {
          display: inline-block;
          font-weight: 400;
          color: #212529;
          text-align: center;
          vertical-align: middle;
          user-select: none;
          background-color: transparent;
          border: 2px solid transparent;
          padding: 0.375rem 0.75rem;
          font-size: 1rem;
          line-height: 1.5;
          border-radius: 0.25rem;
          transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
          text-decoration: none;
        }

        .btn-success {
          color: #fff;
          background-color: #28a745;
          border-color: #28a745;
        }

        /* Style untuk kotak rangkuman */
        .rangkuman-container {
            display: flex;
            gap: 15px;
            margin: 20px 0;
        }
        .rangkuman-box {
            border: 1px solid #dee2e6;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #f8f9fa;
            min-width: 150px;
        }
        .rangkuman-box h5 {
            margin: 0 0 5px 0;
            font-size: 14px;
            color: #6c757d;
        }
        .rangkuman-box p {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
        }

        @media print {
            .not-printed {
                display: none;
            }
            .rangkuman-box {
                background-color: #fff !important; /* Supaya hemat tinta saat diprint */
            }
        }
    </style>
</head>
<body>
    <a class="btn btn-success not-printed" href="<?= base_url('pelaporLaporan/printLaporan/') . $filter[0] . '/' . $filter[1] . '/' . $filter[2]; ?>">Cetak</a>
    
    <?php 
        $status_judul = explode('_', $status_pengaduan_filter);
        $status_judul = implode(' ', $status_judul);
        $status_judul = ucwords($status_judul);
    ?>
    
    <h4>Laporan Pengaduan</h4>
    <p>Periode: <strong><?= date('d-M-Y', strtotime($dari_tgl)); ?></strong> s/d <strong><?= date('d-M-Y', strtotime($sampai_tgl)); ?></strong> | Filter Status: <strong><?= $status_judul; ?></strong></p>
    
    <div class="rangkuman-container">
        <div class="rangkuman-box">
            <h5>Total Laporan</h5>
            <p style="color: #0056b3;"><?= $jumlah_total; ?></p>
        </div>
        <div class="rangkuman-box">
            <h5>Sudah Selesai</h5>
            <p style="color: #28a745;"><?= $jumlah_selesai; ?></p>
        </div>
        <div class="rangkuman-box">
            <h5>Pending</h5>
            <p style="color: #ffc107;"><?= $jumlah_proses; ?></p>
        </div>
    </div>
    <table cellpadding="10" cellspacing="0" border="1" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="background-color: #f1f1f1;">
                <th>No.</th>
                <th>Pelapor</th>
                <th>Tanggal Pengaduan</th>
                <th>Isi Laporan</th>
                <th>Lokasi</th>
                <th>Foto</th>
                <th>Status</th>
                <th>Tanggapan</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($pengaduan as $dp): ?>
                <?php 
                    $getTanggapan = $this->db->order_by('tanggapan.id_tanggapan', 'desc')->get_where('tanggapan', ['id_pengaduan' => $dp['id_pengaduan']])->row_array();
                    $status_row = explode('_', $dp['status_pengaduan']);
                    $status_row = implode(' ', $status_row);
                    $status_row = ucwords($status_row);
                ?>
                <tr>
                    <td align="center"><?= $i++; ?></td>
                    <td><?= $dp['username']; ?></td>
                    <td><?= date('d-m-Y, H:i', strtotime($dp['tgl_pengaduan'])); ?></td>
                    <td><?= $dp['isi_laporan']; ?></td>
                    <td><?= $dp['kelurahan']; ?></td>
                    <td align="center">
                        <img height="75" src="<?= base_url('assets/img/img_pengaduan/') . $dp['foto']; ?>" alt="<?= $dp['foto']; ?>">
                    </td>
                    <td><?= $status_row; ?></td>
                    <td>
                        <?php if ($getTanggapan): ?>
                            <p><?= $getTanggapan['isi_tanggapan']; ?></p>
                        <?php else: ?>
                            <p align="center" style="color: #999;">- Belum ada tanggapan -</p>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

<script>
    window.print();
</script>
</body>
</html>