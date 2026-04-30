<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem Manajemen Blog (CMS)</title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; color: #333; }

  /* HEADER */
  header {
    background: #2c3e50;
    color: #fff;
    padding: 14px 24px;
    display: flex;
    align-items: center;
    gap: 10px;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 6px rgba(0,0,0,.3);
  }
  header span.icon { font-size: 22px; }
  header h1 { font-size: 18px; font-weight: 600; }
  header small { display: block; font-size: 11px; color: #aab; font-weight: 400; }

  /* LAYOUT */
  .wrapper { display: flex; min-height: calc(100vh - 54px); }

  /* SIDEBAR */
  nav {
    width: 200px;
    background: #fff;
    border-right: 1px solid #dde1e7;
    padding: 20px 0;
    flex-shrink: 0;
  }
  nav p.label { font-size: 10px; color: #999; font-weight: 700; letter-spacing: 1px; padding: 0 18px 8px; text-transform: uppercase; }
  nav a {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 11px 18px;
    font-size: 14px;
    color: #555;
    text-decoration: none;
    cursor: pointer;
    transition: background .15s;
    border-left: 3px solid transparent;
  }
  nav a:hover { background: #f0f4ff; color: #3b5bdb; }
  nav a.aktif { background: #eef2ff; color: #3b5bdb; border-left-color: #3b5bdb; font-weight: 600; }

  /* MAIN CONTENT */
  main { flex: 1; padding: 24px; }

  /* CARD */
  .card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 1px 4px rgba(0,0,0,.08);
    padding: 20px 24px;
  }
  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
  }
  .card-header h2 { font-size: 16px; font-weight: 600; }

  /* BUTTONS */
  .btn {
    padding: 7px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    transition: opacity .15s;
  }
  .btn:hover { opacity: .85; }
  .btn-primary  { background: #3b5bdb; color: #fff; }
  .btn-success  { background: #2f9e44; color: #fff; }
  .btn-warning  { background: #e67700; color: #fff; }
  .btn-danger   { background: #e03131; color: #fff; }
  .btn-secondary{ background: #adb5bd; color: #fff; }

  /* TABLE */
  table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
  th { background: #f8f9fa; font-weight: 600; color: #555; padding: 10px 12px; text-align: left; border-bottom: 2px solid #dee2e6; }
  td { padding: 10px 12px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
  tr:hover td { background: #fafbff; }

  /* FOTO / GAMBAR THUMBNAIL */
  .thumb { width: 46px; height: 46px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd; background: #eee; }

  /* BADGE KATEGORI */
  .badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    background: #3b5bdb22;
    color: #3b5bdb;
  }

  /* AKSI */
  .aksi-btn { display: flex; gap: 6px; }
  .btn-sm { padding: 5px 12px; font-size: 12px; }

  /* MODAL OVERLAY */
  .modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    z-index: 200;
    align-items: center;
    justify-content: center;
  }
  .modal-overlay.aktif { display: flex; }

  /* MODAL BOX */
  .modal {
    background: #fff;
    border-radius: 12px;
    padding: 28px 32px;
    width: 480px;
    max-width: 95vw;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 8px 32px rgba(0,0,0,.18);
    position: relative;
  }
  .modal h3 { font-size: 17px; font-weight: 700; margin-bottom: 20px; }

  /* FORM */
  .form-row { display: flex; gap: 14px; }
  .form-row .form-group { flex: 1; }
  .form-group { margin-bottom: 14px; }
  .form-group label { display: block; font-size: 13px; font-weight: 600; color: #444; margin-bottom: 5px; }
  .form-group input,
  .form-group select,
  .form-group textarea {
    width: 100%;
    padding: 9px 12px;
    border: 1px solid #ced4da;
    border-radius: 7px;
    font-size: 13.5px;
    font-family: inherit;
    transition: border-color .15s;
  }
  .form-group input:focus,
  .form-group select:focus,
  .form-group textarea:focus { outline: none; border-color: #3b5bdb; }
  .form-group textarea { resize: vertical; min-height: 90px; }

  /* MODAL FOOTER */
  .modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 18px; }

  /* HAPUS MODAL */
  .hapus-modal { text-align: center; }
  .hapus-modal .ikon-hapus { font-size: 46px; color: #e03131; margin-bottom: 12px; }
  .hapus-modal h3 { font-size: 18px; margin-bottom: 6px; }
  .hapus-modal p  { font-size: 13px; color: #888; margin-bottom: 20px; }
  .hapus-modal .modal-footer { justify-content: center; }

  /* ALERT */
  .alert {
    padding: 10px 16px;
    border-radius: 7px;
    font-size: 13px;
    margin-bottom: 16px;
    display: none;
  }
  .alert.sukses { background: #d3f9d8; color: #2b8a3e; border: 1px solid #8ce99a; }
  .alert.error  { background: #ffe3e3; color: #c92a2a; border: 1px solid #ffa8a8; }

  /* PASSWORD MASK */
  .pass-mask { font-family: monospace; letter-spacing: 2px; color: #999; }

  /* EMPTY STATE */
  .empty { text-align: center; padding: 40px 0; color: #aaa; }
  .empty p { margin-top: 8px; font-size: 14px; }

  /* LOADING */
  .loading { text-align: center; padding: 30px; color: #aaa; font-size: 14px; }
</style>
</head>
<body>

<!-- HEADER -->
<header>
  <span class="icon">📰</span>
  <div>
    <h1>Sistem Manajemen Blog (CMS)</h1>
    <small>Blog Keren</small>
  </div>
</header>

<div class="wrapper">

  <!-- SIDEBAR -->
  <nav id="sidebar">
    <p class="label">Menu Utama</p>
    <a id="nav-penulis"   onclick="tampilMenu('penulis')">👤 Kelola Penulis</a>
    <a id="nav-artikel"   onclick="tampilMenu('artikel')">📄 Kelola Artikel</a>
    <a id="nav-kategori"  onclick="tampilMenu('kategori')">🗂️ Kelola Kategori</a>
  </nav>

  <!-- KONTEN -->
  <main id="konten">
    <div class="loading">⏳ Silakan pilih menu di sebelah kiri.</div>
  </main>

</div>

<!-- ======================================================
     MODAL TAMBAH PENULIS
====================================================== -->
<div class="modal-overlay" id="modal-tambah-penulis">
  <div class="modal">
    <h3>Tambah Penulis</h3>
    <div id="alert-tambah-penulis" class="alert"></div>
    <div class="form-row">
      <div class="form-group">
        <label>Nama Depan</label>
        <input type="text" id="tp-nama-depan" placeholder="Ahmad">
      </div>
      <div class="form-group">
        <label>Nama Belakang</label>
        <input type="text" id="tp-nama-belakang" placeholder="Fauzi">
      </div>
    </div>
    <div class="form-group">
      <label>Username</label>
      <input type="text" id="tp-username" placeholder="ahmad_f">
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" id="tp-password" placeholder="••••••••••••">
    </div>
    <div class="form-group">
      <label>Foto Profil</label>
      <input type="file" id="tp-foto" accept="image/*">
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-tambah-penulis')">Batal</button>
      <button class="btn btn-primary" onclick="simpanPenulis()">Simpan Data</button>
    </div>
  </div>
</div>

<!-- MODAL EDIT PENULIS -->
<div class="modal-overlay" id="modal-edit-penulis">
  <div class="modal">
    <h3>Edit Penulis</h3>
    <div id="alert-edit-penulis" class="alert"></div>
    <input type="hidden" id="ep-id">
    <div class="form-row">
      <div class="form-group">
        <label>Nama Depan</label>
        <input type="text" id="ep-nama-depan">
      </div>
      <div class="form-group">
        <label>Nama Belakang</label>
        <input type="text" id="ep-nama-belakang">
      </div>
    </div>
    <div class="form-group">
      <label>Username</label>
      <input type="text" id="ep-username">
    </div>
    <div class="form-group">
      <label>Password Baru (kosongkan jika tidak diganti)</label>
      <input type="password" id="ep-password" placeholder="••••••••••••">
    </div>
    <div class="form-group">
      <label>Foto Profil (kosongkan jika tidak diganti)</label>
      <input type="file" id="ep-foto" accept="image/*">
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-edit-penulis')">Batal</button>
      <button class="btn btn-primary" onclick="updatePenulis()">Simpan Perubahan</button>
    </div>
  </div>
</div>

<!-- MODAL HAPUS PENULIS -->
<div class="modal-overlay" id="modal-hapus-penulis">
  <div class="modal hapus-modal">
    <div class="ikon-hapus">🗑️</div>
    <h3>Hapus data ini?</h3>
    <p>Data yang dihapus tidak dapat dikembalikan.</p>
    <input type="hidden" id="hapus-penulis-id">
    <div id="alert-hapus-penulis" class="alert"></div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-hapus-penulis')">Batal</button>
      <button class="btn btn-danger" onclick="hapusPenulis()">Ya, Hapus</button>
    </div>
  </div>
</div>

<!-- ======================================================
     MODAL TAMBAH KATEGORI
====================================================== -->
<div class="modal-overlay" id="modal-tambah-kategori">
  <div class="modal">
    <h3>Tambah Kategori</h3>
    <div id="alert-tambah-kategori" class="alert"></div>
    <div class="form-group">
      <label>Nama Kategori</label>
      <input type="text" id="tk-nama" placeholder="Nama kategori...">
    </div>
    <div class="form-group">
      <label>Keterangan</label>
      <textarea id="tk-keterangan" placeholder="Deskripsi kategori..."></textarea>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-tambah-kategori')">Batal</button>
      <button class="btn btn-primary" onclick="simpanKategori()">Simpan Data</button>
    </div>
  </div>
</div>

<!-- MODAL EDIT KATEGORI -->
<div class="modal-overlay" id="modal-edit-kategori">
  <div class="modal">
    <h3>Edit Kategori</h3>
    <div id="alert-edit-kategori" class="alert"></div>
    <input type="hidden" id="ek-id">
    <div class="form-group">
      <label>Nama Kategori</label>
      <input type="text" id="ek-nama">
    </div>
    <div class="form-group">
      <label>Keterangan</label>
      <textarea id="ek-keterangan"></textarea>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-edit-kategori')">Batal</button>
      <button class="btn btn-primary" onclick="updateKategori()">Simpan Perubahan</button>
    </div>
  </div>
</div>

<!-- MODAL HAPUS KATEGORI -->
<div class="modal-overlay" id="modal-hapus-kategori">
  <div class="modal hapus-modal">
    <div class="ikon-hapus">🗑️</div>
    <h3>Hapus data ini?</h3>
    <p>Data yang dihapus tidak dapat dikembalikan.</p>
    <input type="hidden" id="hapus-kategori-id">
    <div id="alert-hapus-kategori" class="alert"></div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-hapus-kategori')">Batal</button>
      <button class="btn btn-danger" onclick="hapusKategori()">Ya, Hapus</button>
    </div>
  </div>
</div>

<!-- ======================================================
     MODAL TAMBAH ARTIKEL
====================================================== -->
<div class="modal-overlay" id="modal-tambah-artikel">
  <div class="modal">
    <h3>Tambah Artikel</h3>
    <div id="alert-tambah-artikel" class="alert"></div>
    <div class="form-group">
      <label>Judul</label>
      <input type="text" id="ta-judul" placeholder="Judul artikel...">
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Penulis</label>
        <select id="ta-penulis"></select>
      </div>
      <div class="form-group">
        <label>Kategori</label>
        <select id="ta-kategori"></select>
      </div>
    </div>
    <div class="form-group">
      <label>Isi Artikel</label>
      <textarea id="ta-isi" placeholder="Tulis isi artikel di sini..."></textarea>
    </div>
    <div class="form-group">
      <label>Gambar</label>
      <input type="file" id="ta-gambar" accept="image/*">
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-tambah-artikel')">Batal</button>
      <button class="btn btn-primary" onclick="simpanArtikel()">Simpan Data</button>
    </div>
  </div>
</div>

<!-- MODAL EDIT ARTIKEL -->
<div class="modal-overlay" id="modal-edit-artikel">
  <div class="modal">
    <h3>Edit Artikel</h3>
    <div id="alert-edit-artikel" class="alert"></div>
    <input type="hidden" id="ea-id">
    <div class="form-group">
      <label>Judul</label>
      <input type="text" id="ea-judul">
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Penulis</label>
        <select id="ea-penulis"></select>
      </div>
      <div class="form-group">
        <label>Kategori</label>
        <select id="ea-kategori"></select>
      </div>
    </div>
    <div class="form-group">
      <label>Isi Artikel</label>
      <textarea id="ea-isi"></textarea>
    </div>
    <div class="form-group">
      <label>Gambar (kosongkan jika tidak diganti)</label>
      <input type="file" id="ea-gambar" accept="image/*">
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-edit-artikel')">Batal</button>
      <button class="btn btn-primary" onclick="updateArtikel()">Simpan Perubahan</button>
    </div>
  </div>
</div>

<!-- MODAL HAPUS ARTIKEL -->
<div class="modal-overlay" id="modal-hapus-artikel">
  <div class="modal hapus-modal">
    <div class="ikon-hapus">🗑️</div>
    <h3>Hapus data ini?</h3>
    <p>Data yang dihapus tidak dapat dikembalikan.</p>
    <input type="hidden" id="hapus-artikel-id">
    <div id="alert-hapus-artikel" class="alert"></div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="tutupModal('modal-hapus-artikel')">Batal</button>
      <button class="btn btn-danger" onclick="hapusArtikel()">Ya, Hapus</button>
    </div>
  </div>
</div>

<!-- ======================================================
     JAVASCRIPT
====================================================== -->
<script>
// ── HELPERS ──────────────────────────────────────────────
function bukaModal(id) {
  document.getElementById(id).classList.add('aktif');
}
function tutupModal(id) {
  document.getElementById(id).classList.remove('aktif');
  // Reset alert
  const alert = document.querySelector('#' + id + ' .alert');
  if (alert) { alert.style.display = 'none'; alert.textContent = ''; }
}
function tampilAlert(elId, pesan, tipe) {
  const el = document.getElementById(elId);
  el.textContent = pesan;
  el.className = 'alert ' + tipe;
  el.style.display = 'block';
}
function esc(str) {
  const d = document.createElement('div');
  d.textContent = str;
  return d.innerHTML;
}

// ── NAVIGASI ─────────────────────────────────────────────
function tampilMenu(menu) {
  ['penulis','artikel','kategori'].forEach(m => {
    document.getElementById('nav-' + m).classList.remove('aktif');
  });
  document.getElementById('nav-' + menu).classList.add('aktif');

  const konten = document.getElementById('konten');
  konten.innerHTML = '<div class="loading">⏳ Memuat data...</div>';

  if (menu === 'penulis')   muatPenulis();
  if (menu === 'artikel')   muatArtikel();
  if (menu === 'kategori')  muatKategori();
}

// ════════════════════════════════════════════════════════
//  PENULIS
// ════════════════════════════════════════════════════════
function muatPenulis() {
  fetch('ambil_penulis.php')
    .then(r => r.json())
    .then(res => {
      const konten = document.getElementById('konten');
      let rows = '';
      if (res.status === 'sukses' && res.data.length > 0) {
        res.data.forEach(p => {
          const foto = p.foto !== 'default.png'
            ? 'uploads_penulis/' + esc(p.foto)
            : 'uploads_penulis/default.png';
          rows += `<tr>
            <td><img src="${foto}" class="thumb" onerror="this.src='uploads_penulis/default.png'"></td>
            <td>${esc(p.nama_depan + ' ' + p.nama_belakang)}</td>
            <td>${esc(p.user_name)}</td>
            <td><span class="pass-mask">••••••••••••</span></td>
            <td>
              <div class="aksi-btn">
                <button class="btn btn-warning btn-sm" onclick="bukaEditPenulis(${p.id})">Edit</button>
                <button class="btn btn-danger  btn-sm" onclick="konfirmasiHapusPenulis(${p.id})">Hapus</button>
              </div>
            </td>
          </tr>`;
        });
      } else {
        rows = '<tr><td colspan="5"><div class="empty">📭<p>Belum ada data penulis.</p></div></td></tr>';
      }

      konten.innerHTML = `
        <div class="card">
          <div class="card-header">
            <h2>Data Penulis</h2>
            <button class="btn btn-primary" onclick="bukaModalTambahPenulis()">+ Tambah Penulis</button>
          </div>
          <table>
            <thead><tr><th>FOTO</th><th>NAMA</th><th>USERNAME</th><th>PASSWORD</th><th>AKSI</th></tr></thead>
            <tbody>${rows}</tbody>
          </table>
        </div>`;
    });
}

function bukaModalTambahPenulis() {
  document.getElementById('tp-nama-depan').value  = '';
  document.getElementById('tp-nama-belakang').value = '';
  document.getElementById('tp-username').value    = '';
  document.getElementById('tp-password').value    = '';
  document.getElementById('tp-foto').value        = '';
  bukaModal('modal-tambah-penulis');
}

function simpanPenulis() {
  const fd = new FormData();
  fd.append('nama_depan',    document.getElementById('tp-nama-depan').value.trim());
  fd.append('nama_belakang', document.getElementById('tp-nama-belakang').value.trim());
  fd.append('user_name',     document.getElementById('tp-username').value.trim());
  fd.append('password',      document.getElementById('tp-password').value);
  const foto = document.getElementById('tp-foto').files[0];
  if (foto) fd.append('foto', foto);

  fetch('simpan_penulis.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      if (res.status === 'sukses') {
        tutupModal('modal-tambah-penulis');
        muatPenulis();
      } else {
        tampilAlert('alert-tambah-penulis', res.pesan, 'error');
      }
    });
}

function bukaEditPenulis(id) {
  fetch('ambil_satu_penulis.php?id=' + id)
    .then(r => r.json())
    .then(res => {
      if (res.status !== 'sukses') return;
      const p = res.data;
      document.getElementById('ep-id').value           = p.id;
      document.getElementById('ep-nama-depan').value   = p.nama_depan;
      document.getElementById('ep-nama-belakang').value = p.nama_belakang;
      document.getElementById('ep-username').value     = p.user_name;
      document.getElementById('ep-password').value     = '';
      document.getElementById('ep-foto').value         = '';
      bukaModal('modal-edit-penulis');
    });
}

function updatePenulis() {
  const fd = new FormData();
  fd.append('id',            document.getElementById('ep-id').value);
  fd.append('nama_depan',    document.getElementById('ep-nama-depan').value.trim());
  fd.append('nama_belakang', document.getElementById('ep-nama-belakang').value.trim());
  fd.append('user_name',     document.getElementById('ep-username').value.trim());
  fd.append('password',      document.getElementById('ep-password').value);
  const foto = document.getElementById('ep-foto').files[0];
  if (foto) fd.append('foto', foto);

  fetch('update_penulis.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      if (res.status === 'sukses') {
        tutupModal('modal-edit-penulis');
        muatPenulis();
      } else {
        tampilAlert('alert-edit-penulis', res.pesan, 'error');
      }
    });
}

function konfirmasiHapusPenulis(id) {
  document.getElementById('hapus-penulis-id').value = id;
  bukaModal('modal-hapus-penulis');
}

function hapusPenulis() {
  const fd = new FormData();
  fd.append('id', document.getElementById('hapus-penulis-id').value);

  fetch('hapus_penulis.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      if (res.status === 'sukses') {
        tutupModal('modal-hapus-penulis');
        muatPenulis();
      } else {
        tampilAlert('alert-hapus-penulis', res.pesan, 'error');
      }
    });
}

// ════════════════════════════════════════════════════════
//  KATEGORI
// ════════════════════════════════════════════════════════
function muatKategori() {
  fetch('ambil_kategori.php')
    .then(r => r.json())
    .then(res => {
      const konten = document.getElementById('konten');
      let rows = '';
      if (res.status === 'sukses' && res.data.length > 0) {
        res.data.forEach(k => {
          rows += `<tr>
            <td><span class="badge">${esc(k.nama_kategori)}</span></td>
            <td>${esc(k.keterangan || '-')}</td>
            <td>
              <div class="aksi-btn">
                <button class="btn btn-warning btn-sm" onclick="bukaEditKategori(${k.id})">Edit</button>
                <button class="btn btn-danger  btn-sm" onclick="konfirmasiHapusKategori(${k.id})">Hapus</button>
              </div>
            </td>
          </tr>`;
        });
      } else {
        rows = '<tr><td colspan="3"><div class="empty">📭<p>Belum ada data kategori.</p></div></td></tr>';
      }

      konten.innerHTML = `
        <div class="card">
          <div class="card-header">
            <h2>Data Kategori Artikel</h2>
            <button class="btn btn-primary" onclick="bukaModalTambahKategori()">+ Tambah Kategori</button>
          </div>
          <table>
            <thead><tr><th>NAMA KATEGORI</th><th>KETERANGAN</th><th>AKSI</th></tr></thead>
            <tbody>${rows}</tbody>
          </table>
        </div>`;
    });
}

function bukaModalTambahKategori() {
  document.getElementById('tk-nama').value       = '';
  document.getElementById('tk-keterangan').value = '';
  bukaModal('modal-tambah-kategori');
}

function simpanKategori() {
  const fd = new FormData();
  fd.append('nama_kategori', document.getElementById('tk-nama').value.trim());
  fd.append('keterangan',    document.getElementById('tk-keterangan').value.trim());

  fetch('simpan_kategori.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      if (res.status === 'sukses') {
        tutupModal('modal-tambah-kategori');
        muatKategori();
      } else {
        tampilAlert('alert-tambah-kategori', res.pesan, 'error');
      }
    });
}

function bukaEditKategori(id) {
  fetch('ambil_satu_kategori.php?id=' + id)
    .then(r => r.json())
    .then(res => {
      if (res.status !== 'sukses') return;
      const k = res.data;
      document.getElementById('ek-id').value          = k.id;
      document.getElementById('ek-nama').value        = k.nama_kategori;
      document.getElementById('ek-keterangan').value  = k.keterangan || '';
      bukaModal('modal-edit-kategori');
    });
}

function updateKategori() {
  const fd = new FormData();
  fd.append('id',            document.getElementById('ek-id').value);
  fd.append('nama_kategori', document.getElementById('ek-nama').value.trim());
  fd.append('keterangan',    document.getElementById('ek-keterangan').value.trim());

  fetch('update_kategori.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      if (res.status === 'sukses') {
        tutupModal('modal-edit-kategori');
        muatKategori();
      } else {
        tampilAlert('alert-edit-kategori', res.pesan, 'error');
      }
    });
}

function konfirmasiHapusKategori(id) {
  document.getElementById('hapus-kategori-id').value = id;
  bukaModal('modal-hapus-kategori');
}

function hapusKategori() {
  const fd = new FormData();
  fd.append('id', document.getElementById('hapus-kategori-id').value);

  fetch('hapus_kategori.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      if (res.status === 'sukses') {
        tutupModal('modal-hapus-kategori');
        muatKategori();
      } else {
        tampilAlert('alert-hapus-kategori', res.pesan, 'error');
      }
    });
}

// ════════════════════════════════════════════════════════
//  ARTIKEL
// ════════════════════════════════════════════════════════
function muatArtikel() {
  fetch('ambil_artikel.php')
    .then(r => r.json())
    .then(res => {
      const konten = document.getElementById('konten');
      let rows = '';
      if (res.status === 'sukses' && res.data.length > 0) {
        res.data.forEach(a => {
          rows += `<tr>
            <td><img src="uploads_artikel/${esc(a.gambar)}" class="thumb"></td>
            <td>${esc(a.judul)}</td>
            <td><span class="badge">${esc(a.nama_kategori)}</span></td>
            <td>${esc(a.nama_penulis)}</td>
            <td>${esc(a.hari_tanggal)}</td>
            <td>
              <div class="aksi-btn">
                <button class="btn btn-warning btn-sm" onclick="bukaEditArtikel(${a.id})">Edit</button>
                <button class="btn btn-danger  btn-sm" onclick="konfirmasiHapusArtikel(${a.id})">Hapus</button>
              </div>
            </td>
          </tr>`;
        });
      } else {
        rows = '<tr><td colspan="6"><div class="empty">📭<p>Belum ada data artikel.</p></div></td></tr>';
      }

      konten.innerHTML = `
        <div class="card">
          <div class="card-header">
            <h2>Data Artikel</h2>
            <button class="btn btn-primary" onclick="bukaModalTambahArtikel()">+ Tambah Artikel</button>
          </div>
          <table>
            <thead><tr><th>GAMBAR</th><th>JUDUL</th><th>KATEGORI</th><th>PENULIS</th><th>TANGGAL</th><th>AKSI</th></tr></thead>
            <tbody>${rows}</tbody>
          </table>
        </div>`;
    });
}

function isiDropdownPenulis(selectId, pilihId = null) {
  return fetch('ambil_penulis.php')
    .then(r => r.json())
    .then(res => {
      const sel = document.getElementById(selectId);
      sel.innerHTML = '';
      if (res.status === 'sukses') {
        res.data.forEach(p => {
          const opt = document.createElement('option');
          opt.value = p.id;
          opt.textContent = p.nama_depan + ' ' + p.nama_belakang;
          if (pilihId && p.id == pilihId) opt.selected = true;
          sel.appendChild(opt);
        });
      }
    });
}

function isiDropdownKategori(selectId, pilihId = null) {
  return fetch('ambil_kategori.php')
    .then(r => r.json())
    .then(res => {
      const sel = document.getElementById(selectId);
      sel.innerHTML = '';
      if (res.status === 'sukses') {
        res.data.forEach(k => {
          const opt = document.createElement('option');
          opt.value = k.id;
          opt.textContent = k.nama_kategori;
          if (pilihId && k.id == pilihId) opt.selected = true;
          sel.appendChild(opt);
        });
      }
    });
}

function bukaModalTambahArtikel() {
  document.getElementById('ta-judul').value  = '';
  document.getElementById('ta-isi').value    = '';
  document.getElementById('ta-gambar').value = '';
  Promise.all([
    isiDropdownPenulis('ta-penulis'),
    isiDropdownKategori('ta-kategori')
  ]).then(() => bukaModal('modal-tambah-artikel'));
}

function simpanArtikel() {
  const fd = new FormData();
  fd.append('judul',       document.getElementById('ta-judul').value.trim());
  fd.append('id_penulis',  document.getElementById('ta-penulis').value);
  fd.append('id_kategori', document.getElementById('ta-kategori').value);
  fd.append('isi',         document.getElementById('ta-isi').value.trim());
  const gambar = document.getElementById('ta-gambar').files[0];
  if (gambar) fd.append('gambar', gambar);

  fetch('simpan_artikel.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      if (res.status === 'sukses') {
        tutupModal('modal-tambah-artikel');
        muatArtikel();
      } else {
        tampilAlert('alert-tambah-artikel', res.pesan, 'error');
      }
    });
}

function bukaEditArtikel(id) {
  fetch('ambil_satu_artikel.php?id=' + id)
    .then(r => r.json())
    .then(res => {
      if (res.status !== 'sukses') return;
      const a = res.data;
      document.getElementById('ea-id').value    = a.id;
      document.getElementById('ea-judul').value = a.judul;
      document.getElementById('ea-isi').value   = a.isi;
      document.getElementById('ea-gambar').value = '';
      Promise.all([
        isiDropdownPenulis('ea-penulis', a.id_penulis),
        isiDropdownKategori('ea-kategori', a.id_kategori)
      ]).then(() => bukaModal('modal-edit-artikel'));
    });
}

function updateArtikel() {
  const fd = new FormData();
  fd.append('id',          document.getElementById('ea-id').value);
  fd.append('judul',       document.getElementById('ea-judul').value.trim());
  fd.append('id_penulis',  document.getElementById('ea-penulis').value);
  fd.append('id_kategori', document.getElementById('ea-kategori').value);
  fd.append('isi',         document.getElementById('ea-isi').value.trim());
  const gambar = document.getElementById('ea-gambar').files[0];
  if (gambar) fd.append('gambar', gambar);

  fetch('update_artikel.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      if (res.status === 'sukses') {
        tutupModal('modal-edit-artikel');
        muatArtikel();
      } else {
        tampilAlert('alert-edit-artikel', res.pesan, 'error');
      }
    });
}

function konfirmasiHapusArtikel(id) {
  document.getElementById('hapus-artikel-id').value = id;
  bukaModal('modal-hapus-artikel');
}

function hapusArtikel() {
  const fd = new FormData();
  fd.append('id', document.getElementById('hapus-artikel-id').value);

  fetch('hapus_artikel.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      if (res.status === 'sukses') {
        tutupModal('modal-hapus-artikel');
        muatArtikel();
      } else {
        tampilAlert('alert-hapus-artikel', res.pesan, 'error');
      }
    });
}

// ── CLOSE MODAL KLIK DI LUAR ─────────────────────────────
document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('aktif');
  });
});

// ── LOAD AWAL ────────────────────────────────────────────
tampilMenu('penulis');
</script>
</body>
</html>
