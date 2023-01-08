<div class="page-header">
    <h3>Kostumer Baru</h3>
</div>
<!--Proses penyimpanan tambah data kostumer akan di proses pada function kostumer_add_act()-->
<form action="<?php echo base_url() . 'admin/kostumer_add_act' ?>" method="post">
    <div class="form-group">
        <label>Nama Kostumer</label>
        <input type="text" name="nama" class="form-control">
        <?php echo form_error('nama'); ?>
    </div>
    <div class="form-group">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control"></textarea>
    </div>
    <div class="form-group">
        <label>Jenis Kelamin</label>
        <select name="jk" class="form-control">
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select>
    </div>
    <div class="form-group">
        <label>HP</label>
        <input type="number" name="hp" class="form-control">
    </div>
    <div class="form-group">
        <label>No. KTP</label>
        <input type="text" name="ktp" class="form-control">
    </div>
    <div class="form-group">
        <input type="submit" value="Simpan" class="btn btn-primary">
    </div>
</form>