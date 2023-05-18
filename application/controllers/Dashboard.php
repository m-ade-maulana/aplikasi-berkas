<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
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

    public function __construct()
    {
        parent::__construct();
        $username = $this->session->userdata('nama');
        if (empty($username)) {
            $this->session->set_flashdata('message', $this->messageAlert('error', 'Maaf anda harus masukan nomor NRK dahulu'));
            redirect('welcome');
        }
    }

    public function index()
    {
        $nomor_nrk = $this->session->userdata('nomor_nrk');
        $data['data_upload'] = $this->db->get_where('tb_berkas', ['nomor_nrk' => $nomor_nrk])->result_array();
        $this->load->view('dashboard', $data);
    }

    public function do_upload($directory)
    {
        $config['upload_path'] = './upload/' . $directory;
        $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|jpg|png|';
        $config['overwrite'] = TRUE;
        $config['max_size'] = '10000000';
        $config['max_height'] = '768';
        $config['max_width'] = '1024';

        $this->load->library('upload', $config);

        $dir_exist = true;
        if (!is_dir('upload/' . $directory)) {
            mkdir('./upload/' . $directory, 0777, true);
            $dir_exist = false;
        }

        if (!$this->upload->do_upload('userfile')) {
            if (!$dir_exist) {
                rmdir('./upload/' . $directory);
            }
            $this->session->set_flashdata('message', $this->messageAlert($this->upload->display_errors()));
            // echo "Gagal Upload Foto";
            redirect('dashboard');
        } else {
            // echo "Berhasil Upload Foto";
            // $nrk = $this->session->userdata('nrk');
            $upload_data = $this->upload->data();
            $nomor_nrk = $this->session->userdata('nomor_nrk');
            $data = [
                'id_file' => rand(11111, 999999),
                'nomor_nrk' => $nomor_nrk,
                'nama_file' => $upload_data['file_name'],
                'file_size' => $upload_data['file_size'],
                'tanggal_upload' => date('Y-m-d')
            ];
            $insert = $this->db->insert('tb_berkas', $data);
            if ($insert) {
                $this->session->set_flashdata('message', $this->messageAlert('Upload Berhasil'));
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('message', $this->messageAlert('Upload Gagal'));
                redirect('dashboard');
            }
        }
    }

    public function messageAlert($message)
    {
        $message_alert = "alert('$message')";
        return $message_alert;
    }
}
