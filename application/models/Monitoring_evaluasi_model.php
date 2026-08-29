<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model: Monitoring_evaluasi_model
 * Modul Monitoring Evaluasi Penelitian
 * Framework: CodeIgniter 3
 */
class Monitoring_evaluasi_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // ================================================================
    // PERTANYAAN MONITORING
    // ================================================================

    public function get_semua_pertanyaan($hanya_aktif = TRUE)
    {
        if ($hanya_aktif) {
            $this->db->where('is_aktif', 1);
        }
        return $this->db->order_by('urutan', 'ASC')
                        ->get('monitoring_pertanyaan_new')
                        ->result();
    }

    public function get_pertanyaan_by_id($id)
    {
        return $this->db->where('id', $id)
                        ->get('monitoring_pertanyaan_new')
                        ->row();
    }

    public function get_pertanyaan_by_kategori()
    {
        $this->db->where('is_aktif', 1);
        $this->db->order_by('kategori', 'ASC');
        $this->db->order_by('urutan', 'ASC');
        $rows = $this->db->get('monitoring_pertanyaan_new')->result();

        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row->kategori][] = $row;
        }
        return $grouped;
    }

    public function tambah_pertanyaan($data)
    {
        $this->db->insert('monitoring_pertanyaan_new', $data);
        return $this->db->insert_id();
    }

    public function update_pertanyaan($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('monitoring_pertanyaan_new', $data);
    }

    public function toggle_aktif_pertanyaan($id)
    {
        $pertanyaan = $this->get_pertanyaan_by_id($id);
        if (!$pertanyaan) return FALSE;

        $this->db->where('id', $id);
        return $this->db->update('monitoring_pertanyaan_new', [
            'is_aktif' => $pertanyaan->is_aktif ? 0 : 1
        ]);
    }

    public function hapus_pertanyaan($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('monitoring_pertanyaan_new');
    }

    public function get_total_pertanyaan()
    {
        return $this->db->where('is_aktif', 1)->count_all_results('monitoring_pertanyaan_new');
    }

    // ================================================================
    // PENELITIAN
    // ================================================================

    public function get_penelitian_aktif($peneliti_id = NULL)
    {
        /*$this->db->where('status', 'aktif');
        if ($peneliti_id) {
            $this->db->where('id_user', $peneliti_id);
        }
        $this->db->order_by('tahun', 'DESC');
        return $this->db->get('penelitian')->result();*/
        
        if ($peneliti_id) {
            $this->db->where('id_user', $peneliti_id);
        }
        $this->db->order_by('tgl_pengajuan', 'DESC');
        return $this->db->get('pengajuan')->result();
    }

    public function get_penelitian_by_id($id)
    {
        return $this->db->where('id', $id)->get('penelitian')->row();
    }

    // ================================================================
    // SESI MONITORING
    // ================================================================

    public function get_semua_sesi($peneliti_id = NULL)
    {
        $this->db->select('ms.*, p.judul_bhs_ind as judul_penelitian, p.kd_pengajuan');
        $this->db->from('monitoring_sesi ms');
        $this->db->join('pengajuan p', 'p.id = ms.id_user', 'left');

        if ($peneliti_id) {
            $this->db->where('ms.id_user', $peneliti_id);
        }
        $this->db->order_by('ms.tanggal_monitoring', 'DESC');
        return $this->db->get()->result();
    }

    public function get_sesi_by_id($id)
    {
        $this->db->select('ms.*, p.judul_bhs_ind as judul_penelitian, p.kd_pengajuan, p.tgl_pengajuan');
        $this->db->from('monitoring_sesi ms');
        $this->db->join('pengajuan p', 'p.id = ms.kd_pengajuan', 'left');
        $this->db->where('ms.id', $id);
        return $this->db->get()->row();
    }

    public function get_sesi_by_penelitian($penelitian_id)
    {
        $this->db->where('kd_pengajuan', $penelitian_id);
        $this->db->order_by('tanggal_monitoring', 'DESC');
        return $this->db->get('monitoring_sesi')->result();
    }

    public function tambah_sesi($data)
    {
        $this->db->insert('monitoring_sesi', $data);
        return $this->db->insert_id();
    }

    public function update_sesi($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('monitoring_sesi', $data);
    }

    public function submit_sesi($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('monitoring_sesi', [
            'status'     => 'submitted',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function verifikasi_sesi($id, $reviewer_id, $catatan_reviewer)
    {
        $this->db->where('id', $id);
        return $this->db->update('monitoring_sesi', [
            'status'           => 'diverifikasi',
            'catatan_reviewer' => $catatan_reviewer,
            'verified_by'      => $reviewer_id,
            'verified_at'      => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s')
        ]);
    }

    public function hapus_sesi($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('monitoring_sesi');
    }

    // ================================================================
    // JAWABAN MONITORING
    // ================================================================

    public function get_jawaban_by_sesi($sesi_id)
    {
        $this->db->select('mj.*, mp.pertanyaan, mp.kode, mp.kategori, mp.urutan');
        $this->db->from('monitoring_jawaban_new mj');
        $this->db->join('monitoring_pertanyaan_new mp', 'mp.id = mj.pertanyaan_id', 'left');
        $this->db->where('mj.sesi_id', $sesi_id);
        $this->db->order_by('mp.urutan', 'ASC');
        return $this->db->get()->result();
    }

    public function get_jawaban_grouped_by_sesi($sesi_id)
    {
        $jawaban = $this->get_jawaban_by_sesi($sesi_id);
        $grouped = [];
        foreach ($jawaban as $j) {
            $grouped[$j->kategori][] = $j;
        }
        return $grouped;
    }

    public function simpan_jawaban_batch($sesi_id, $jawaban_array)
    {
        // Hapus jawaban lama dulu
        $this->db->where('sesi_id', $sesi_id);
        $this->db->delete('monitoring_jawaban_new');

        if (empty($jawaban_array)) return TRUE;

        $insert_data = [];
        $now = date('Y-m-d H:i:s');

        foreach ($jawaban_array as $pertanyaan_id => $item) {
            $insert_data[] = [
                'sesi_id'        => $sesi_id,
                'pertanyaan_id'  => (int)$pertanyaan_id,
                'jawaban'        => isset($item['jawaban']) ? $item['jawaban'] : 'tidak',
                'keterangan'     => isset($item['keterangan']) ? trim($item['keterangan']) : NULL,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }

        return $this->db->insert_batch('monitoring_jawaban', $insert_data);
    }

    // ================================================================
    // STATISTIK & REKAPITULASI
    // ================================================================

    public function get_rekapitulasi_sesi($sesi_id)
    {
        $this->db->select('
            COUNT(*) as total,
            SUM(CASE WHEN jawaban = "ya" THEN 1 ELSE 0 END) as total_ya,
            SUM(CASE WHEN jawaban = "tidak" THEN 1 ELSE 0 END) as total_tidak,
            SUM(CASE WHEN jawaban = "tidak_berlaku" THEN 1 ELSE 0 END) as total_tidak_berlaku
        ');
        $this->db->from('monitoring_jawaban_new');
        $this->db->where('sesi_id', $sesi_id);
        return $this->db->get()->row();
    }

    public function get_rekapitulasi_per_kategori($sesi_id)
    {
        $this->db->select('
            mp.kategori,
            COUNT(*) as total,
            SUM(CASE WHEN mj.jawaban = "ya" THEN 1 ELSE 0 END) as total_ya,
            SUM(CASE WHEN mj.jawaban = "tidak" THEN 1 ELSE 0 END) as total_tidak,
            SUM(CASE WHEN mj.jawaban = "tidak_berlaku" THEN 1 ELSE 0 END) as total_tidak_berlaku
        ');
        $this->db->from('monitoring_jawaban_new mj');
        $this->db->join('monitoring_pertanyaan_new mp', 'mp.id = mj.pertanyaan_id', 'left');
        $this->db->where('mj.sesi_id', $sesi_id);
        $this->db->group_by('mp.kategori');
        return $this->db->get()->result();
    }

    public function get_statistik_dashboard($peneliti_id = NULL)
    {
        // Total sesi
        if ($peneliti_id) {
            $this->db->where('id_user', $peneliti_id);
        }
        $total_sesi = $this->db->count_all_results('monitoring_sesi');

        // Sesi submitted (belum diverifikasi)
        $q = $this->db->where('status', 'submitted');
        if ($peneliti_id) $q->where('id_user', $peneliti_id);
        $total_submitted = $q->count_all_results('monitoring_sesi');

        // Sesi diverifikasi
        $q2 = $this->db->where('status', 'diverifikasi');
        if ($peneliti_id) $q2->where('id_user', $peneliti_id);
        $total_verified = $q2->count_all_results('monitoring_sesi');

        // Sesi draft
        $q3 = $this->db->where('status', 'draft');
        if ($peneliti_id) $q3->where('id_user', $peneliti_id);
        $total_draft = $q3->count_all_results('monitoring_sesi');

        return [
            'total_sesi'      => $total_sesi,
            'total_submitted' => $total_submitted,
            'total_verified'  => $total_verified,
            'total_draft'     => $total_draft,
        ];
    }

    public function get_riwayat_monitoring($penelitian_id)
    {
        $this->db->select('
            ms.id, ms.periode, ms.tanggal_monitoring, ms.status,
            COUNT(mj.id) as total_jawaban,
            SUM(CASE WHEN mj.jawaban = "ya" THEN 1 ELSE 0 END) as total_ya
        ');
        $this->db->from('monitoring_sesi ms');
        $this->db->join('monitoring_jawaban_new mj', 'mj.sesi_id = ms.id', 'left');
        $this->db->where('ms.kd_pengajuan', $penelitian_id);
        $this->db->group_by('ms.id');
        $this->db->order_by('ms.tanggal_monitoring', 'ASC');
        return $this->db->get()->result();
    }
}
