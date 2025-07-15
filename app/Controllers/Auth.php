<?php

namespace App\Controllers;

use Config\Services;
use App\Controllers\BaseController;

class Auth extends BaseController
{

    protected $session;
    protected $client;

    public function __construct()
    {
        $this->session = session();
        $this->client = Services::curlrequest();
    }


    // Auth Registration
    public function registrationform()
    {
        return view('auth/registration', [
            'title' => 'Login | HIS PPOB-Alfin Al Khoiri'
        ]);
    }

    public function registration()
    {
        // Validasi input form
        $rules = [
            'first_name'        => 'required',
            'last_name'         => 'required',
            'email'             => 'required|valid_email',
            'password'          => 'required|min_length[6]',
            'confirm_password'  => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Semua field wajib diisi dengan benar.');
        }

        // Siapkan data untuk dikirim ke API
        $data = [
            'email'      => $this->request->getPost('email'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'password'   => $this->request->getPost('password')
        ];

        // Kirim ke API eksternal
        $client = \Config\Services::curlrequest();
        try {
            $response = $client->post('https://take-home-test-api.nutech-integrasi.com/registration', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
                'http_errors' => false // Tambahkan ini
            ]);

            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody(), true);

            // Tangani 400 dan tampilkan pesan dari API
            if ($statusCode === 400 && isset($body['message'])) {
                return redirect()->back()->withInput()->with('error', 'Gagal: ' . $body['message'] ?? 'Paramter email tidak sesuai format');
            }

            if ($statusCode === 200 && isset($body['status'])) {
                if ($body['status'] == 0) {
                    // Registrasi berhasil
                    return redirect()->to('/login')->with('success', $body['message'] ?? 'Registrasi berhasil Silakan login.');
                } else {
                    // Registrasi gagal (misal status 102, email format salah, dll)
                    return redirect()->back()->withInput()->with('error', $body['message'] ?? 'Registrasi gagal.');
                }
            } else {
                // Tidak sesuai format respons yang diharapkan
                return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan tidak diketahui dari server.');
            }
        } catch (\CodeIgniter\HTTP\Exceptions\HTTPException $e) {
            return redirect()->back()->withInput()->with('error', 'Error HTTP: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Auth Login
    public function loginForm()
    {
        return view('auth/login', [
            'title' => 'Login | HIS PPOB-Alfin Al Khoiri'
        ]);
    }

    public function login()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Email atau password tidak valid');
        }

        $client = \Config\Services::curlrequest();
        $data = [
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password')
        ];

        try {
            $response = $client->post('https://take-home-test-api.nutech-integrasi.com/login', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);

            // Mendapatkan body response dan decode JSON
            $body = json_decode($response->getBody(), true);

            // Jika statusnya 0, berarti login sukses
            if ($body['status'] == 0 && isset($body['data']['token'])) {
                session()->set('token', $body['data']['token']);
                return redirect()->to('/')->with('success', 'Login Sukses');
            }

            // Menangani status 103 (Unauthorized)
            elseif ($body['status'] == 103) {
                return redirect()->back()->withInput()->with('error', 'Username atau password salah');
            }
            // Menangani status lainnya seperti 102 (Bad Request)
            elseif ($body['status'] == 102) {
                return redirect()->back()->withInput()->with('error', $body['message'] ?? 'Paramter tidak sesuai');
            }
            // Jika status tidak diketahui
            else {
                return redirect()->back()->withInput()->with('error', $body['message'] ?? 'Login gagal.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Paramter email tidak sesuai format ' . $e->getMessage());
        }
    }
}
