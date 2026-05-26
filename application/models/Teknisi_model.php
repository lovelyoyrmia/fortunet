<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teknisi_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Admin_model', 'admo');
		$this->load->model('Log_model', 'lomo');
		$this->load->model('Pengaduan_model', 'pemo');
		$this->load->model('Tanggapan_model', 'tamo');
	}

    public function getDataTeknisi()
	{
		return $this->db->get_where('user', ['jabatan' => 'teknisi'])->result_array();
	}
    
    
	public function getPengaduan()
	{
		$this->db->join('masyarakat', 'pengaduan.id_masyarakat=masyarakat.id_masyarakat');
		// $this->db->join('kelurahan', 'pengaduan.id_kelurahan=kelurahan.id_kelurahan');
		$this->db->order_by('id_pengaduan', 'desc');
		return $this->db->get('pengaduan')->result_array();
	}
    
	public function getTanggapanByIdTeknisi($id_teknisi)
	{
		$this->db->join('user', 'tanggapan.id_user=user.id_user');
		$this->db->join('pengaduan', 'tanggapan.id_pengaduan=pengaduan.id_pengaduan');
		$this->db->where('tanggapan.id_user', $id_teknisi);
		$this->db->order_by('id_tanggapan', 'asc');
		return $this->db->get('tanggapan')->result_array();
	}
}