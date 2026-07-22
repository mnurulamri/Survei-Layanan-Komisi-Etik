<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survei extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if(!$this->session->userdata('id_user')) redirect('auth');
        $this->load->model('Survei_model');
    }

    // Halaman form survei untuk peneliti
    public function form($id_pengajuan) 
    {
        // 1. Cek apakah pengajuan ini milik user login dan status LOLOS
        $pengajuan = $this->Survei_model->cek_pengajuan_lolos($id_pengajuan, $this->session->userdata('id_user'));
        if(!$pengajuan) show_404();

        // 2. Cek apakah sudah isi survei
        if($this->Survei_model->cek_sudah_isi($id_pengajuan)) {
            $this->session->set_flashdata('info', 'Anda sudah mengisi survei untuk pengajuan ini');
            redirect('dashboard');
        }

        $data['title'] = 'Survei Kepuasan Layanan';
        $data['pengajuan'] = $pengajuan;
        $data['pertanyaan'] = $this->Survei_model->get_pertanyaan_aktif();
        $this->load->view('templates/header', $data);
        $this->load->view('survei/v_form_survei', $data);
        $this->load->view('templates/footer');
    }

    // Proses simpan ajax
    public function simpan() 
    {
        $post = $this->input->post();
        $id_pengajuan = $post['id_pengajuan'];

        // Validasi double submit
        if($this->Survei_model->cek_sudah_isi($id_pengajuan)) {
            echo json_encode(['status' => 'error', 'msg' => 'Survei sudah pernah diisi']);
            return;
        }

        $data_header = [
            'id_pengajuan_pemohon' => $id_pengajuan,
            'id_peneliti' => $this->session->userdata('id_user'),
            'ip_address' => $this->input->ip_address()
        ];

        $this->db->trans_start();
        $this->db->insert('etk_survei_jawaban', $data_header);
        $id_survei_jawaban = $this->db->insert_id();

        $insert_batch = [];
        foreach($post['jawaban'] as $id_pertanyaan => $jawaban) {
            $insert_batch[] = [
                'id_survei_jawaban' => $id_survei_jawaban,
                'id_pertanyaan' => $id_pertanyaan,
                'jawaban' => $jawaban
            ];
        }
        if(!empty($insert_batch)) $this->db->insert_batch('etk_survei_detail', $insert_batch);
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE) {
            echo json_encode(['status' => 'error']);
        } else {
            echo json_encode(['status' => 'success']);
        }
    }

    // Halaman rekap untuk Admin
    public function rekap() 
    {
        if($this->session->userdata('level') != 'admin') show_404(); // hanya admin
        
        $data['title'] = 'Rekap Survei';
        $data['rekap'] = $this->Survei_model->get_rekap();
        $this->load->view('templates/header', $data);
        $this->load->view('survei/v_rekap_survei', $data);
        $this->load->view('templates/footer');
    }
}
