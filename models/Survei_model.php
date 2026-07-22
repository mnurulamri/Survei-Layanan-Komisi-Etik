<?php
class Survei_model extends CI_Model {

    public function cek_pengajuan_lolos($id_pengajuan, $id_user) {
        return $this->db->get_where('pengajuan', [
            'id' => $id_pengajuan, 
            'id_peneliti' => $id_user,
            'status' => 'LOLOS' // Sesuaikan dengan status kamu
        ])->row();
    }

    public function cek_sudah_isi($id_pengajuan) {
        return $this->db->get_where('etk_survei_jawaban', ['id_pengajuan_pemohon' => $id_pengajuan])->num_rows() > 0;
    }

    public function get_pertanyaan_aktif() {
        return $this->db->where('aktif', 1)->order_by('urutan', 'ASC')->get('etk_survei_pertanyaan')->result();
    }

    public function get_rekap() {
        // Ambil rata2 skor per pertanyaan
        $this->db->select('p.pertanyaan, AVG(d.jawaban) as rata2, COUNT(d.id) as total');
        $this->db->from('etk_survei_detail d');
        $this->db->join('etk_survei_pertanyaan p', 'p.id = d.id_pertanyaan');
        $this->db->where('p.tipe', 'skala');
        $this->db->group_by('d.id_pertanyaan');
        return $this->db->get()->result();
    }

    public function get_rekap_skala() {
    $this->db->select('p.pertanyaan, ROUND(AVG(d.jawaban),2) as rata2, COUNT(d.id) as total');
    $this->db->from('etk_survei_detail d');
    $this->db->join('etk_survei_pertanyaan p', 'p.id = d.id_pertanyaan');
    $this->db->where('p.tipe', 'skala');
    $this->db->group_by('d.id_pertanyaan');
    $this->db->order_by('p.urutan', 'ASC');
    return $this->db->get()->result();
}

public function get_rekap_text() {
    $this->db->select('d.jawaban, j.tanggal_isi');
    $this->db->from('etk_survei_detail d');
    $this->db->join('etk_survei_jawaban j', 'j.id = d.id_survei_jawaban');
    $this->db->join('etk_survei_pertanyaan p', 'p.id = d.id_pertanyaan');
    $this->db->where('p.tipe', 'text');
    $this->db->where('d.jawaban !=', '');
    $this->db->order_by('j.tanggal_isi', 'DESC');
    return $this->db->get()->result();
}

public function get_total_responden() {
    return $this->db->count_all('etk_survei_jawaban');
}

public function hitung_ikm() {
    // Rumus IKM: Rata2 skor / 5 * 100
    $rata2 = $this->db->select('AVG(d.jawaban) as rata2')
                ->from('etk_survei_detail d')
                ->join('etk_survei_pertanyaan p', 'p.id = d.id_pertanyaan')
                ->where('p.tipe', 'skala')->get()->row()->rata2;
    return round(($rata2 / 5) * 100, 2);
}
}
