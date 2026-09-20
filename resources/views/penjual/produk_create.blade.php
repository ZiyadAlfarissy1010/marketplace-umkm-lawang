<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - UMKM Lawang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="/penjual/dashboard">Dashboard UMKM</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Tambah Produk Baru</h2>
        
        <div class="card mt-3 shadow-sm border-0">
            <div class="card-body">
                <!-- PENTING: Tambahkan enctype="multipart/form-data" untuk upload file -->
                <form action="/penjual/produk" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label>Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Kategori</label>
                        <select name="kategori_id" class="form-control" required>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Stok</label>
                            <input type="number" name="stok" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Upload Gambar Produk</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*" required>
                        <small class="text-muted">Format: JPG, PNG, JPEG, GIF. Maks 2MB.</small>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan Produk</button>
                    <a href="/penjual/dashboard" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

</body>
</html>