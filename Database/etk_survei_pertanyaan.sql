
-- Tabel Pertanyaan Survei. Biar bisa diedit admin
CREATE TABLE `etk_survei_pertanyaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pertanyaan` text NOT NULL,
  `tipe` enum('skala','pilihan','text') NOT NULL DEFAULT 'skala',
  `opsi` text DEFAULT NULL COMMENT 'Dipisah | untuk tipe pilihan. Contoh: Sangat Puas|Puas|Cukup|Kurang',
  `urutan` int(11) NOT NULL DEFAULT '0',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Isi pertanyaan default SKALA 1-5
INSERT INTO `etk_survei_pertanyaan` (`pertanyaan`, `tipe`, `urutan`) VALUES
('Bagaimana kemudahan proses pengajuan clearance etik?', 'skala', 1),
('Bagaimana kecepatan respon dari Komite Etik?', 'skala', 2),
('Seberapa jelas informasi persyaratan yang diberikan?', 'skala', 3),
('Bagaimana profesionalisme tim Komite Etik?', 'skala', 4),
('Apakah Anda puas dengan hasil keputusan?', 'skala', 5),
('Saran dan masukan untuk perbaikan layanan', 'text', 6);

-- Tabel Jawaban Survei
CREATE TABLE `etk_survei_jawaban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pengajuan_pemohon` int(11) NOT NULL COMMENT 'FK ke tabel pengajuan',
  `id_peneliti` int(11) NOT NULL COMMENT 'FK ke tabel user peneliti',
  `tanggal_isi` datetime NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_pengajuan` (`id_pengajuan_pemohon`) -- 1 pengajuan hanya 1x survei
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Detail Jawaban per Pertanyaan
CREATE TABLE `etk_survei_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_survei_jawaban` int(11) NOT NULL,
  `id_pertanyaan` int(11) NOT NULL,
  `jawaban` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_survei_jawaban` (`id_survei_jawaban`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
