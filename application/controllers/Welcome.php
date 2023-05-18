<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class welcome extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */

	public function index()
	{
		$this->load->view('welcome');
	}

	public function login()
	{
		$nomor_nrk = $this->input->post('nomor_nrk');
		$check = $this->db->get_where('tb_akun', ['nomor_nrk' => $nomor_nrk])->row_array();
		if ($check) {
			if ($check['nomor_nrk'] == $nomor_nrk) {
				$data = [
					'nomor_nrk' => $check['nomor_nrk'],
					'nama' => $check['nama'],
				];
				$this->session->set_flashdata('messageAlert', $this->message('Selamat datang ' . $check['nama']));
				$this->session->set_userdata($data);
				redirect('dashboard', $data);
			} else {
				$this->session->set_flashdata('messageAlert', $this->message('Nomor NRK Tidak Sesuai'));
				redirect('welcome');
			}
		} else {
			$this->session->set_flashdata('messageAlert', $this->message('Ada Yang Salah Dengan Database Anda, Hubungi Teknisi Anda'));
			redirect('welcome');
		}
	}

	public function logout()
	{
		$account_data = $this->session->all_userdata();
		foreach ($account_data as $key) {
			if ($key != 'nomor_nrk') {
				$this->session->unset_userdata($key);
			}
		}
		$this->session->sess_destroy();
		$this->session->set_flashdata('message', $this->message('Anda Berhasil Logout'));
		redirect('welcome');
	}

	public function do_enter()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$status_upload = $this->upload_file();
			if ($status_upload != false) {
				$inputFileName = './upload/data/import/' . $status_upload;
				// $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
				$spreadsheet = $reader->load($inputFileName);
				$sheet = $spreadsheet->getSheet(0);
				$count_rows = 0;
				foreach ($sheet->getRowIterator() as $s) {
					$nomor_nrk = $spreadsheet->getActiveSheet()->getCell('A' . $s->getRowIndex());
					$nama = $spreadsheet->getActiveSheet()->getCell('B' . $s->getRowIndex());
					$tanggal_lahir = $spreadsheet->getActiveSheet()->getCell('C' . $s->getRowIndex());

					$data = [
						'id_nrk' => rand(111111, 999999),
						'nomor_nrk' => $nomor_nrk,
						'nama' => $nama,
						'tanggal_lahir' => $tanggal_lahir
					];
					$this->db->insert('tb_akun', $data);
					$count_rows++;
					rmdir('./upload/');
				}
				$this->session->set_flashdata('message', $this->message('Berhasil Tambahkan Data NRK'));
				redirect('welcome');
			} else {
				$this->session->set_flashdata('message', $this->message('File Gagal Di Upload'));
				redirect('welcome');
			}
		} else {
			redirect('welcome');
		}
	}

	public function upload_file()
	{
		$uploadPath = './upload/data/import/';
		if (!is_dir($uploadPath)) {
			mkdir($uploadPath, 0777, TRUE);
		}

		$config['upload_path'] = $uploadPath;
		$config['allowed_types'] = 'xls|xlsx|csv';
		$config['max_size'] = 10000000;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if ($this->upload->do_upload('filenrk')) {
			$fileData = $this->upload->data();
			return $fileData['file_name'];
		} else {
			return false;
		}
	}

	public function message($message)
	{
		$message_alert = "alert('$message')";
		return $message_alert;
	}
}
