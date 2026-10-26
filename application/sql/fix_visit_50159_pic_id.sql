-- ============================================================
-- Data Fix: visit_id 50159 - Perbaikan pic_id dan pm_desktop_by
-- Date    : 2026-10-15
-- Author  : system-fix (Antigravity AI)
-- Reason  : Kolom pic_id dan pm_desktop_by pada visit_id 50159
--           menyimpan format email (bdihrt96@gmail.com) alih-alih
--           user_id (budi.hartono). Diperbaiki agar konsisten
--           dengan seluruh record lain di t_trx_schedule yang
--           menggunakan user_id dari t_mtr_user.
-- User    : Budi Hartono (user_id=budi.hartono, email=bdihrt96@gmail.com)
-- ============================================================

UPDATE t_trx_schedule
SET
    pic_id        = 'budi.hartono',
    pm_desktop_by = 'budi.hartono',
    updated_on    = NOW(),
    updated_by    = 'system-fix'
WHERE visit_id = 50159
  AND pic_id        = 'bdihrt96@gmail.com'
  AND pm_desktop_by = 'bdihrt96@gmail.com';

-- Verifikasi (jalankan setelah UPDATE di atas):
-- SELECT sch.visit_id, sch.pic_id, sch.pm_desktop_by,
--        usr1.user_name AS pic_name, usr2.user_name AS desktop_by_name
-- FROM t_trx_schedule sch
-- LEFT JOIN t_mtr_user usr1 ON usr1.user_id = sch.pic_id
-- LEFT JOIN t_mtr_user usr2 ON usr2.user_id = sch.pm_desktop_by
-- WHERE sch.visit_id = 50159;
