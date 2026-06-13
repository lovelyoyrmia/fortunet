<?php if (validation_errors()): ?>
  <div class="toast bg-danger" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false" style="z-index: 999999; position: fixed; right: 1.5rem; bottom: 3.5rem">
    <div class="toast-header">
      <strong class="mr-auto">Gagal!</strong>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body">
      <?= validation_errors(); ?>
    </div>
  </div>
<?php endif ?>

<div class="container">
	<div class="row">
		<div class="col-lg-6 p-3">
			<div class="card">
			  <div class="card-header">
			  	<h3 class="my-auto"><i class="fas fa-fw fa-plus"></i> Add Masyarakat</h3>
			  </div>
			  <div class="card-body">
			  	<form action="<?= base_url('masyarakat/addMasyarakat'); ?>" method="post">
					<div class="form-group">
						<label for="nama">Nama</label>
						<input type="text" id="nama" class="form-control <?= (form_error('nama')) ? 'is-invalid' : ''; ?>" name="nama" required value="<?= set_value('nama'); ?>">
						<div class="invalid-feedback">
			              <?= form_error('nama'); ?>
			            </div>
					</div>
					<div class="form-group">
						<label for="username">Username</label>
						<input type="text" id="username" class="form-control <?= (form_error('username')) ? 'is-invalid' : ''; ?>" name="username" required value="<?= set_value('username'); ?>">
						<div class="invalid-feedback">
			              <?= form_error('username'); ?>
			            </div>
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" id="password" class="form-control <?= (form_error('password')) ? 'is-invalid' : ''; ?>" name="password" required value="<?= set_value('password'); ?>">
						<div class="invalid-feedback">
			              <?= form_error('password'); ?>
			            </div>
					</div>
					<div class="form-group">
						<label for="password_verify">Verifikasi Password</label>
						<input type="password" id="password_verify" class="form-control <?= (form_error('password_verify')) ? 'is-invalid' : ''; ?>" name="password_verify" required value="<?= set_value('password_verify'); ?>">
						<div class="invalid-feedback">
			              <?= form_error('password_verify'); ?>
			            </div>
					</div>
					<div class="form-group">
						<label for="no_telepon">No. Telepon</label>
						<input type="number" id="no_telepon" class="form-control <?= (form_error('no_telepon')) ? 'is-invalid' : ''; ?>" name="no_telepon" required value="<?= set_value('no_telepon'); ?>">
						<div class="invalid-feedback">
			              <?= form_error('no_telepon'); ?>
			            </div>
					</div>
					<div class="form-group">
						<label for="alamat">Alamat</label>
						<textarea id="alamat" class="form-control <?= (form_error('alamat')) ? 'is-invalid' : ''; ?>" name="alamat" required><?= set_value('alamat'); ?></textarea>
						<div class="invalid-feedback">
			              <?= form_error('alamat'); ?>
			            </div>
					</div>
					<div class="form-group">
							<label for="form_kecamatan">Kecamatan</label>
							<select class="custom-select <?= (form_error('id_kecamatan')) ? 'is-invalid' : ''; ?>" name="id_kecamatan" id="form_kecamatan">
								<option value="0">Pilih Kecamatan</option>
								<?php foreach ($kecamatan as $dataKecamatan): ?>
									<option value="<?= $dataKecamatan['id_kecamatan']; ?>"><?= $dataKecamatan['kecamatan']; ?></option>
								<?php endforeach ?>
							</select>
							<div class="invalid-feedback">
								<?= form_error('id_kecamatan'); ?>
							</div>
					</div>
					<div class="form-group">
							<label for="form_kelurahan">Kelurahan</label>
							<select id="form_kelurahan" class="custom-select <?= (form_error('id_kelurahan')) ? 'is-invalid' : ''; ?>" name="id_kelurahan">
								<option value="0">Pilih Kelurahan</option>
							</select>
							<div class="invalid-feedback">
								<?= form_error('id_kelurahan'); ?>
							</div>
					</div>
					<div class="form-group text-right">
						<button type="submit" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Simpan</button>
					</div>
				</form>
			  </div>
			</div>
		</div>
	</div>
</div>
<script>
const kecamatanSelect = document.getElementById('form_kecamatan');
const kelurahanSelect = document.getElementById('form_kelurahan');

kecamatanSelect.addEventListener('change', async function () {
    const idKecamatan = this.value;
    kelurahanSelect.innerHTML =
        '<option value="">Loading...</option>';
    try {
        const response = await fetch(
            '<?= base_url("Landing/getKelurahan"); ?>',
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    id_kecamatan: idKecamatan
                })
            }
        );

        const data = await response.json();
        kelurahanSelect.innerHTML =
            '<option value="">Pilih Kelurahan</option>';
        data.forEach(item => {
            kelurahanSelect.innerHTML += `
                <option value="${item.id_kelurahan}">
                    ${item.kelurahan}
                </option>
            `;
        });
    } catch (error) {
        console.error(error);
        kelurahanSelect.innerHTML =
            '<option value="">Gagal memuat data</option>';
    }
});
</script>
