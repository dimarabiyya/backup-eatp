-- =====================================================================
-- Indeks MySQL (database import_excel) untuk halaman detail visit:
-- setiap klik Detail mencari data GAC berdasarkan kolom `link`.
--
-- Cara jalan:
--   mysql -h 10.0.5.32 -u root -p import_excel < optimasi_index_mysql.sql
--
-- Cek dulu apakah indeks sudah ada (jika sudah, lewati):
--   SHOW INDEX FROM tbl_gac_data WHERE Column_name = 'link';
-- =====================================================================

CREATE INDEX idx_gac_link ON tbl_gac_data (link);
