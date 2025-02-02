

<!-- Tombol Tambah -->
<a href="<?= base_url('admin/penghapusan/add') ?>" class="btn btn-primary mb-3">
    <i class="fa fa-plus"></i> Tambah Penghapusan
</a>

<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Aset</th>
            <th>Nama Aset</th>
            <th>Jumlah Dihapuskan</th>
            <th>Penyebab</th>
            <th>Tanggal Hapus</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; foreach ($penghapusan as $hapus) { ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= esc($hapus['kodeaset']) ?></td>
            <td><?= esc($hapus['namaaset']) ?></td>
            <td><?= esc($hapus['jumlahdihapuskan']) ?></td>
            <td><?= esc($hapus['penyebab']) ?></td>
            <td><?= date('d-m-Y', strtotime($hapus['tanggalhapus'])) ?></td>
            <td>
                <a href="<?= base_url('admin/penghapusan/edit/' . $hapus['idpenghapusan']) ?>" class="btn btn-success btn-sm">
                    <i class="fa fa-edit"></i>
                </a>
                <a href="<?= base_url('admin/penghapusan/delete/' . $hapus['idpenghapusan']) ?>" 
                    class="btn btn-danger btn-sm" 
                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
            
