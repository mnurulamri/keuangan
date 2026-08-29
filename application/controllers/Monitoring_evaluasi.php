<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller: Monitoring_evaluasi
 * Modul Monitoring Evaluasi Penelitian
 * Framework: CodeIgniter 3 + AdminLTE
 */
class Monitoring_evaluasi extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Monitoring_evaluasi_model', 'mev');
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form', 'date']);
        $this->load->helper('menu_helper');

        // Cek autentikasi (sesuaikan dengan sistem auth Anda)
        // if (!$this->session->userdata('logged_in')) {
        //     redirect('auth/login');
        // }
    }

    // Helper: ambil user login sementara (sesuaikan dengan sistem Anda)
    private function _get_user_id()
    {
        return $this->session->userdata('logged_in')['kajietik_id_user'] ?? 1;
    }

    private function _is_reviewer()
    {
        return $this->session->userdata('role') === 'reviewer' || TRUE; // ubah sesuai kebutuhan
    }

    // ================================================================
    // DASHBOARD
    // ================================================================

    public function index()
    {
        $peneliti_id = $this->_get_user_id();
        $statistik   = $this->mev->get_statistik_dashboard($peneliti_id);
        $sesi_list   = $this->mev->get_semua_sesi($peneliti_id);

        $data = [
            'title'      => 'Dashboard Monitoring Evaluasi',
            'breadcrumb' => [['text' => 'Monitoring Evaluasi']],
            'statistik'  => $statistik,
            'sesi_list'  => $sesi_list,
        ];
        $this->_view('monitoring_evaluasi/dashboard', $data);
    }

    // ================================================================
    // SESI MONITORING — LIST
    // ================================================================

    public function sesi()
    {
        $peneliti_id = $this->_get_user_id();
        $sesi_list   = $this->mev->get_semua_sesi($peneliti_id);

        $data = [
            'title'      => 'Daftar Sesi Monitoring',
            'breadcrumb' => [
                ['text' => 'Monitoring Evaluasi', 'url' => site_url('monitoring_evaluasi')],
                ['text' => 'Daftar Sesi'],
            ],
            'sesi_list'  => $sesi_list,
        ];
        $this->_view('monitoring_evaluasi/sesi_list', $data);
    }

    // ================================================================
    // BUAT SESI BARU
    // ================================================================

    public function buat_sesi()
    {
        $peneliti_id = $this->_get_user_id();
        $penelitian  = $this->mev->get_penelitian_aktif($peneliti_id);

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('penelitian_id', 'Penelitian', 'required|integer');
            $this->form_validation->set_rules('periode', 'Periode', 'required|trim|max_length[50]');
            $this->form_validation->set_rules('tanggal_monitoring', 'Tanggal Monitoring', 'required');

            if ($this->form_validation->run()) {
                $sesi_id = $this->mev->tambah_sesi([
                    'kd_pengajuan'     => $this->input->post('penelitian_id'),
                    'periode'           => $this->input->post('periode'),
                    'tanggal_monitoring'=> $this->input->post('tanggal_monitoring'),
                    'id_user'       => $peneliti_id,
                    'status'            => 'draft',
                    'catatan_peneliti'  => $this->input->post('catatan_peneliti'),
                    'created_at'        => date('Y-m-d H:i:s'),
                ]);

                if ($sesi_id) {
                    $this->session->set_flashdata('success', 'Sesi monitoring berhasil dibuat. Silakan isi form monitoring.');
                    redirect('monitoring_evaluasi/isi_form/' . $sesi_id);
                } else {
                    $this->session->set_flashdata('error', 'Gagal membuat sesi monitoring.');
                }
            }
        }

        $data = [
            'title'      => 'Buat Sesi Monitoring Baru',
            'breadcrumb' => [
                ['text' => 'Monitoring Evaluasi', 'url' => site_url('monitoring_evaluasi')],
                ['text' => 'Daftar Sesi', 'url' => site_url('monitoring_evaluasi/sesi')],
                ['text' => 'Buat Sesi Baru'],
            ],
            'penelitian' => $penelitian,
        ];
        $this->_view('monitoring_evaluasi/sesi_form', $data);
    }

    // ================================================================
    // ISI FORM MONITORING (PERTANYAAN YA/TIDAK)
    // ================================================================

    public function isi_form($sesi_id = NULL)
    {
        if (!$sesi_id) redirect('monitoring_evaluasi/sesi');

        $sesi = $this->mev->get_sesi_by_id($sesi_id);
        if (!$sesi || $sesi->status === 'diverifikasi') {
            $this->session->set_flashdata('error', 'Sesi tidak ditemukan atau sudah diverifikasi.');
            redirect('monitoring_evaluasi/sesi');
        }

        if ($this->input->method() === 'post') {
            $action  = $this->input->post('action');
            $jawaban = $this->input->post('jawaban') ?? [];

            // Simpan jawaban
            $this->mev->simpan_jawaban_batch($sesi_id, $jawaban);

            // Simpan catatan
            $this->mev->update_sesi($sesi_id, [
                'catatan_peneliti' => $this->input->post('catatan_peneliti'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ]);

            if ($action === 'submit') {
                // Validasi: semua pertanyaan harus dijawab
                $total_pertanyaan = $this->mev->get_total_pertanyaan();
                if (count($jawaban) < $total_pertanyaan) {
                    $this->session->set_flashdata('error', 'Harap jawab semua pertanyaan sebelum mengirimkan.');
                } else {
                    $this->mev->submit_sesi($sesi_id);
                    $this->session->set_flashdata('success', 'Form monitoring berhasil dikirimkan untuk ditinjau reviewer.');
                    redirect('monitoring_evaluasi/detail/' . $sesi_id);
                }
            } else {
                $this->session->set_flashdata('success', 'Jawaban berhasil disimpan sebagai draft.');
            }
        }

        $pertanyaan_grouped = $this->mev->get_pertanyaan_by_kategori();
        $jawaban_existing   = $this->mev->get_jawaban_by_sesi($sesi_id);

        // Index jawaban berdasarkan pertanyaan_id untuk kemudahan akses di view
        $jawaban_map = [];
        foreach ($jawaban_existing as $j) {
            $jawaban_map[$j->pertanyaan_id] = $j;
        }

        $data = [
            'title'              => 'Form Monitoring - ' . $sesi->periode,
            'breadcrumb'         => [
                ['text' => 'Monitoring Evaluasi', 'url' => site_url('monitoring_evaluasi')],
                ['text' => 'Daftar Sesi', 'url' => site_url('monitoring_evaluasi/sesi')],
                ['text' => 'Isi Form Monitoring'],
            ],
            'sesi'               => $sesi,
            'pertanyaan_grouped' => $pertanyaan_grouped,
            'jawaban_map'        => $jawaban_map,
        ];
        $this->_view('monitoring_evaluasi/isi_form', $data);
    }

    // ================================================================
    // DETAIL / HASIL MONITORING
    // ================================================================

    public function detail($sesi_id = NULL)
    {
        if (!$sesi_id) redirect('monitoring_evaluasi/sesi');

        $sesi               = $this->mev->get_sesi_by_id($sesi_id);
        $jawaban_grouped    = $this->mev->get_jawaban_grouped_by_sesi($sesi_id);
        $rekapitulasi       = $this->mev->get_rekapitulasi_sesi($sesi_id);
        $rekap_kategori     = $this->mev->get_rekapitulasi_per_kategori($sesi_id);

        $data = [
            'title'           => 'Detail Monitoring - ' . ($sesi ? $sesi->periode : ''),
            'breadcrumb'      => [
                ['text' => 'Monitoring Evaluasi', 'url' => site_url('monitoring_evaluasi')],
                ['text' => 'Daftar Sesi', 'url' => site_url('monitoring_evaluasi/sesi')],
                ['text' => 'Detail Monitoring'],
            ],
            'sesi'            => $sesi,
            'jawaban_grouped' => $jawaban_grouped,
            'rekapitulasi'    => $rekapitulasi,
            'rekap_kategori'  => $rekap_kategori,
        ];
        $this->_view('monitoring_evaluasi/detail', $data);
    }

    // ================================================================
    // VERIFIKASI OLEH REVIEWER
    // ================================================================

    public function verifikasi($sesi_id = NULL)
    {
        if (!$sesi_id || !$this->_is_reviewer()) redirect('monitoring_evaluasi');

        if ($this->input->method() === 'post') {
            $catatan_reviewer = $this->input->post('catatan_reviewer');
            $reviewer_id      = $this->_get_user_id();

            $this->mev->verifikasi_sesi($sesi_id, $reviewer_id, $catatan_reviewer);
            $this->session->set_flashdata('success', 'Sesi monitoring berhasil diverifikasi.');
            redirect('monitoring_evaluasi/detail/' . $sesi_id);
        }

        $sesi = $this->mev->get_sesi_by_id($sesi_id);
        $data = [
            'title'      => 'Verifikasi Monitoring',
            'breadcrumb' => [
                ['text' => 'Monitoring Evaluasi', 'url' => site_url('monitoring_evaluasi')],
                ['text' => 'Verifikasi'],
            ],
            'sesi'       => $sesi,
        ];
        $this->_view('monitoring_evaluasi/verifikasi', $data);
    }

    // ================================================================
    // HAPUS SESI (hanya draft)
    // ================================================================

    public function hapus_sesi($sesi_id = NULL)
    {
        if (!$sesi_id) redirect('monitoring_evaluasi/sesi');

        $sesi = $this->mev->get_sesi_by_id($sesi_id);
        if ($sesi && $sesi->status === 'draft') {
            $this->mev->hapus_sesi($sesi_id);
            $this->session->set_flashdata('success', 'Sesi monitoring berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Sesi tidak dapat dihapus.');
        }
        redirect('monitoring_evaluasi/sesi');
    }

    // ================================================================
    // MANAJEMEN PERTANYAAN (Admin/Reviewer)
    // ================================================================

    public function pertanyaan()
    {
        $pertanyaan_grouped = $this->mev->get_pertanyaan_by_kategori();
        $data = [
            'title'              => 'Kelola Pertanyaan Monitoring',
            'breadcrumb'         => [
                ['text' => 'Monitoring Evaluasi', 'url' => site_url('monitoring_evaluasi')],
                ['text' => 'Kelola Pertanyaan'],
            ],
            'pertanyaan_grouped' => $pertanyaan_grouped,
        ];
        $this->_view('monitoring_evaluasi/pertanyaan', $data);
    }

    public function tambah_pertanyaan()
    {
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('kode', 'Kode', 'required|trim|max_length[10]');
            $this->form_validation->set_rules('pertanyaan', 'Pertanyaan', 'required|trim');
            $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim|max_length[100]');
            $this->form_validation->set_rules('urutan', 'Urutan', 'required|integer');

            if ($this->form_validation->run()) {
                $this->mev->tambah_pertanyaan([
                    'kode'       => strtoupper($this->input->post('kode')),
                    'pertanyaan' => $this->input->post('pertanyaan'),
                    'kategori'   => $this->input->post('kategori'),
                    'urutan'     => $this->input->post('urutan'),
                    'is_aktif'   => 1,
                ]);
                $this->session->set_flashdata('success', 'Pertanyaan berhasil ditambahkan.');
                redirect('monitoring_evaluasi/pertanyaan');
            }
        }

        $data = [
            'title'      => 'Tambah Pertanyaan Monitoring',
            'breadcrumb' => [
                ['text' => 'Monitoring Evaluasi', 'url' => site_url('monitoring_evaluasi')],
                ['text' => 'Kelola Pertanyaan', 'url' => site_url('monitoring_evaluasi/pertanyaan')],
                ['text' => 'Tambah Pertanyaan'],
            ],
        ];
        $this->_view('monitoring_evaluasi/pertanyaan_form', $data);
    }

    public function edit_pertanyaan($id = NULL)
    {
        if (!$id) redirect('monitoring_evaluasi/pertanyaan');
        $pertanyaan = $this->mev->get_pertanyaan_by_id($id);

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('pertanyaan', 'Pertanyaan', 'required|trim');
            $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim|max_length[100]');
            $this->form_validation->set_rules('urutan', 'Urutan', 'required|integer');

            if ($this->form_validation->run()) {
                $this->mev->update_pertanyaan($id, [
                    'pertanyaan' => $this->input->post('pertanyaan'),
                    'kategori'   => $this->input->post('kategori'),
                    'urutan'     => $this->input->post('urutan'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $this->session->set_flashdata('success', 'Pertanyaan berhasil diperbarui.');
                redirect('monitoring_evaluasi/pertanyaan');
            }
        }

        $data = [
            'title'      => 'Edit Pertanyaan Monitoring',
            'breadcrumb' => [
                ['text' => 'Monitoring Evaluasi', 'url' => site_url('monitoring_evaluasi')],
                ['text' => 'Kelola Pertanyaan', 'url' => site_url('monitoring_evaluasi/pertanyaan')],
                ['text' => 'Edit Pertanyaan'],
            ],
            'pertanyaan' => $pertanyaan,
        ];
        $this->_view('monitoring_evaluasi/pertanyaan_form', $data);
    }

    public function toggle_pertanyaan($id = NULL)
    {
        if ($id) $this->mev->toggle_aktif_pertanyaan($id);
        redirect('monitoring_evaluasi/pertanyaan');
    }

    public function hapus_pertanyaan($id = NULL)
    {
        if ($id) {
            $this->mev->hapus_pertanyaan($id);
            $this->session->set_flashdata('success', 'Pertanyaan berhasil dihapus.');
        }
        redirect('monitoring_evaluasi/pertanyaan');
    }

    // ================================================================
    // LAPORAN / REKAP
    // ================================================================

    public function laporan($penelitian_id = NULL)
    {
        $penelitian_list = $this->mev->get_penelitian_aktif();
        $riwayat         = $penelitian_id ? $this->mev->get_riwayat_monitoring($penelitian_id) : [];
        $penelitian      = $penelitian_id ? $this->mev->get_penelitian_by_id($penelitian_id) : NULL;

        $data = [
            'title'           => 'Laporan Monitoring Evaluasi',
            'breadcrumb'      => [
                ['text' => 'Monitoring Evaluasi', 'url' => site_url('monitoring_evaluasi')],
                ['text' => 'Laporan'],
            ],
            'penelitian_list' => $penelitian_list,
            'penelitian'      => $penelitian,
            'riwayat'         => $riwayat,
            'penelitian_id'   => $penelitian_id,
        ];
        $this->_view('monitoring_evaluasi/laporan', $data);
    }

    // ================================================================
    // HELPER VIEW LOADER
    // ================================================================

    private function _view($view, $data = [])
    {
        $data['base_url']  = base_url();
        $data['site_url']  = site_url();
        $data['flash_success'] = $this->session->flashdata('success');
        $data['flash_error']   = $this->session->flashdata('error');

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view($view, $data);
        $this->load->view('layout/footer');
    }
}
