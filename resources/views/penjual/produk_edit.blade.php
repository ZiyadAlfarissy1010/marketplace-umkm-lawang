<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - UMKM Lawang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="/penjual/dashboard">Dashboard UMKM</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Edit Produk</h2>
        
        <div class="card mt-3 shadow-sm border-0">
            <div class="card-body">
                <form action="/penjual/produk/{{ $produk->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label>Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control" value="{{ $produk->nama_produk }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Kategori</label>
                        <select name="kategori_id" class="form-control" required>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ ($produk->kategori_id == $kategori->id) ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control">{{ $produk->deskripsi }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control" value="{{ $produk->harga }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Stok</label>
                            <input type="number" name="stok" class="form-control" value="{{ $produk->stok }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Gambar Saat Ini</label><br>
                        @if($produk->gambar)
                            <img src="{{ asset('storage/'.$produk->gambar) }}" width="100" class="img-thumbnail mb-2">
                        @endif
                    </div>
                    <div class="mb-3">
                        <label>Ubah Gambar (Kosongkan jika tidak ingin ganti)</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-warning">Update Produk</button>
                    <a href="/penjual/dashboard" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

</body>
</html>