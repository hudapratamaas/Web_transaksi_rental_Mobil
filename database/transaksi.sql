CREATE TABLE `transaksi` (
 `transaksi_id` int(11) NOT NULL,
 `transaksi_karyawan` int(11) NOT NULL,
 `transaksi_kostumer` int(11) NOT NULL,
 `transaksi_mobil` int(11) NOT NULL,
 `transaksi_tgl_pinjam` date NOT NULL,
 `transaksi_tgl_kembali` date NOT NULL,
 `transaksi_harga` int(11) NOT NULL,
 `transaksi_denda` int(11) NOT NULL,
 `transaksi_tgl` date NOT NULL,
 `transaksi_totaldenda` int(11) NOT NULL,
 `transaksi_status` int(11) NOT NULL,
 `transaksi_tgldikembalikan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;