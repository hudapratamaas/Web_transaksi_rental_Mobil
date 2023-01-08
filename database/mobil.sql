CREATE TABLE `mobil` (
 `mobil_id` int(11) NOT NULL,
 `mobil_merk` varchar(30) NOT NULL,
 `mobil_plat` varchar(20) NOT NULL,
 `mobil_warna` varchar(30) NOT NULL,
 `mobil_tahun` int(11) NOT NULL,
 `mobil_status` int(11) NOT NULL COMMENT '1 = tersedia, 2 = di 
pinjam'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
