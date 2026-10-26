-- =====================================================================
-- Indeks untuk mempercepat halaman detail visit, approval, dashboard, grid
-- Database : PostgreSQL (db_eatp)
--
-- Cara jalan (di server DB, TIDAK mengubah data):
--   psql -h 10.0.5.32 -U eatp1 -d db_eatp -f optimasi_index_postgres.sql
--
-- - CONCURRENTLY: tabel tetap bisa dibaca/ditulis selama indeks dibuat
--   (aplikasi tidak perlu dimatikan). Jangan jalankan di dalam BEGIN/COMMIT.
-- - IF NOT EXISTS butuh PostgreSQL 9.5+. Cek versi: SELECT version();
--   Untuk versi lebih lama, hapus kata "IF NOT EXISTS".
-- - Bila satu baris gagal (mis. nama kolom beda), baris lain tetap jalan.
-- =====================================================================

-- 0. (Opsional) lihat indeks yang sudah ada, agar tidak dobel
-- SELECT tablename, indexname, indexdef FROM pg_indexes
--  WHERE tablename LIKE 't_trx_%' OR tablename LIKE 't_mtr_checklist%' ORDER BY 1, 2;

-- 1. Detail visit (visitsummary/detail, history_activity, report PDF, ATP desktop)
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_sched_checklist_visit      ON t_trx_schedule_checklist (visit_id, checklist_id);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_checklist_result_visit     ON t_trx_checklist_result (visit_id, checklist_id);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_checklist_photo_visit      ON t_trx_checklist_result_photo (visit_id, checklist_id);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_checklist_answer_chk       ON t_mtr_checklist_answer (checklist_id);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_sched_sign_visit           ON t_trx_schedule_sign (visit_id, sign_type, created_on DESC);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_sched_fop_photo_visit      ON t_trx_schedule_fop_photo (visit_id, created_on DESC);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_sched_fop_video_visit      ON t_trx_schedule_fop_video (visit_id, created_on DESC);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_sched_pending_visit        ON t_trx_schedule_pending_item (visit_id, is_deleted);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_tracking_jc                ON t_trx_tracking (jc_id, updated_on DESC);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_ecrf_approval_visit        ON t_trx_ecrf_approval (visit_id);

-- 2. Approval (dipanggil berkali-kali per buka detail)
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_sched_appr_visit_user      ON t_trx_schedule_result_approval (visit_id, user_id);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_sched_appr_visit_order     ON t_trx_schedule_result_approval (visit_id, order_no);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_sched_appr_sum_visit_user  ON t_trx_schedule_result_approval_sum (visit_id, user_id);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_chk_appr_visit_user        ON t_trx_checklist_result_approval (visit_id, user_id, status);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_chk_appr_visit_chk         ON t_trx_checklist_result_approval (visit_id, checklist_id);

-- 3. Dashboard & daftar schedule (join ke PO / link)
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_schedule_po                ON t_trx_schedule (po_id);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_schedule_link              ON t_trx_schedule (link_id);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_schedule_status            ON t_trx_schedule (status);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_po_link_po                 ON t_trx_po_link (po_id, link_id_int);

CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_schedule_created           ON t_trx_schedule (created_on DESC);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_schedule_pic               ON t_trx_schedule (pic_id);

-- 3b. Dijalankan di SETIAP request/halaman setelah login
--     (sesi, menu samping, breadcrumb, hak akses per-field)
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_ci_sessions_last_activity  ON ci_sessions (last_activity);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_menu_alias                 ON t_mtr_menu (alias, published);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_menu_parent                ON t_mtr_menu (parent_id, published);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_user_role_menu_group       ON t_mtr_user_role (menu_id, user_group_id);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_user_multi_role_user       ON t_mtr_user_multi_role (user_id);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_user_form_menu             ON t_mtr_user_form (menu_id, status);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_user_form_role_form        ON t_mtr_user_form_role (form_id, user_group_id);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_lookup_key                 ON t_mtr_lookup (lookup_key);

-- 3b-2. Login mencari user dengan lower(user_id) = '...'; tanpa indeks ini tabel user dibaca penuh
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_user_lower_id              ON t_mtr_user (lower(user_id));
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_secq_lower_id              ON t_mtr_security_question (lower(user_id));
-- Tabel user sering di-UPDATE (status login) sehingga membengkak; VACUUM tidak mengunci tabel
VACUUM ANALYZE t_mtr_user;

-- 3b-3. User Management: "Last Login" diambil dari log audit t_log per user.
--       Tanpa indeks ini seluruh t_log di-scan setiap halaman dibuka (models/muser.php
--       otomatis memakai cara lama sampai indeks ini selesai dibuat).
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_log_user_created           ON t_log (user_id, created_on);
ANALYZE t_log;

-- 3c. Sesi kedaluwarsa yang menumpuk (aman, CodeIgniter juga menghapusnya sendiri)
--     Jalankan sekali bila tabel ci_sessions sudah sangat besar:
-- DELETE FROM ci_sessions WHERE last_activity < EXTRACT(EPOCH FROM now())::int - 7200;

-- 4. Perbarui statistik agar planner memakai indeks baru
ANALYZE t_trx_schedule_checklist;
ANALYZE t_trx_checklist_result;
ANALYZE t_trx_checklist_result_photo;
ANALYZE t_trx_schedule_result_approval;
ANALYZE t_trx_schedule_result_approval_sum;
ANALYZE t_trx_checklist_result_approval;
ANALYZE t_trx_tracking;
ANALYZE t_trx_schedule;
ANALYZE ci_sessions;
ANALYZE t_mtr_menu;
ANALYZE t_mtr_user_role;
ANALYZE t_mtr_user_multi_role;

-- 5. (Opsional) cek ukuran tabel terbesar - tabel besar tanpa indeks = halaman lambat
-- SELECT relname, n_live_tup, pg_size_pretty(pg_total_relation_size(relid)) AS size,
--        seq_scan, idx_scan
--   FROM pg_stat_user_tables ORDER BY pg_total_relation_size(relid) DESC LIMIT 20;

-- 6. (Opsional) Search "PIC/Site/Visit ID" di Summary memakai ILIKE '%kata%'. Indeks biasa tidak
--    bisa dipakai untuk pola itu; indeks trigram bisa. Tanpa bagian ini search tetap jalan (~1 dtk).
CREATE EXTENSION IF NOT EXISTS pg_trgm;
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_sched_appr_user_trgm       ON t_trx_schedule_result_approval USING gin (user_id gin_trgm_ops);
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_site_link_id_trgm          ON t_mtr_site_link USING gin (link_id gin_trgm_ops);
ANALYZE t_mtr_site_link;
ANALYZE t_mtr_site_xl;
ANALYZE t_trx_po_xl;
ANALYZE t_trx_po_link;

-- Indeks MySQL (data GAC) ada di file terpisah: optimasi_index_mysql.sql
