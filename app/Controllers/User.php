<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class User extends Controller
{
    public function profile()
    {
        if (!session()->get('token')) {
            return redirect()->to('/login')->with('error', 'Silakan login dulu.');
        }

        $token = session()->get('token');
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $client = \Config\Services::curlrequest();

        try {
            $response = $client->get('https://take-home-test-api.nutech-integrasi.com/profile', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                ]
            ]);

            $body = json_decode($response->getBody(), true);

            if ($body['status'] == 0 && isset($body['data'])) {
                $profile = $body['data'];
                return view('user/profile', [
                    'first_name'    => $profile['first_name'],
                    'last_name'     => $profile['last_name'],
                    'email'         => $profile['email'],
                    'profile_image' => $profile['profile_image'],
                    'edit_mode'     => false,
                    'title' => 'Akun | HIS PPOB-Alfin Al Khoiri'
                ]);
            } else {
                return redirect()->to('/login')->with('error', $body['message'] ?? 'Gagal mengambil data profil.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/login')->with('error', 'Gagal koneksi ke server: ' . $e->getMessage());
        }
    }

    public function edit()
    {
        $token = session()->get('token');
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $client = \Config\Services::curlrequest();

        try {
            $response = $client->get('https://take-home-test-api.nutech-integrasi.com/profile', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                ]
            ]);

            $body = json_decode($response->getBody(), true);

            if ($body['status'] == 0 && isset($body['data'])) {
                $profile = $body['data'];
                return view('user/profile', [
                    'first_name'    => $profile['first_name'],
                    'last_name'     => $profile['last_name'],
                    'email'         => $profile['email'],
                    'profile_image' => $profile['profile_image'],
                    'edit_mode'     => true,
                    'title' => 'Akun | HIS PPOB-Alfin Al Khoiri'
                ]);
            } else {
                return redirect()->to('/profile')->with('error', $body['message'] ?? 'Gagal membuka mode edit.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/profile')->with('error', 'Gagal koneksi: ' . $e->getMessage());
        }
    }

    public function update()
    {
        $token = session()->get('token');
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Silakan login dahulu');
        }

        $firstName = $this->request->getPost('first_name');
        $lastName  = $this->request->getPost('last_name');

        $client = \Config\Services::curlrequest();
        try {
            $response = $client->request('PUT', 'https://take-home-test-api.nutech-integrasi.com/profile/update', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json'
                ],
                'body' => json_encode([
                    'first_name' => $firstName,
                    'last_name'  => $lastName
                ])
            ]);

            $body = json_decode($response->getBody(), true);

            if ($body['status'] === 0) {
                return redirect()->to('/profile')->with('success', 'Update Profile berhasil');
            } else {
                return redirect()->to('/profile/edit')->with('error', $body['message'] ?? 'Gagal update profil.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/profile/edit')->with('error', 'Gagal koneksi: ' . $e->getMessage());
        }
    }

    public function uploadImage()
    {
        $token = session('token');
        if (!$token) return redirect()->to('/login');

        $file = $this->request->getFile('image');

        // Validasi file: harus valid dan ukuran maksimum 100KB
        if (!$file || !$file->isValid() || $file->getSize() > 100 * 1024) {
            return redirect()->back()->with('error', 'Ukuran gambar maksimal 100KB dan format harus valid.');
        }

        $tmpFilePath = $file->getTempName();
        $fileName    = $file->getName();
        $mimeType    = $file->getMimeType();

        $curlFile = new \CURLFile($tmpFilePath, $mimeType, $fileName);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://take-home-test-api.nutech-integrasi.com/profile/image',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => ['file' => $curlFile],
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Accept: application/json',
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return redirect()->back()->with('error', 'Curl error: ' . $error);
        }

        $result = json_decode($response, true);

        // === Handling sesuai spesifikasi Swagger ===
        if ($httpCode === 200 && isset($result['status']) && $result['status'] === 0) {
            // Update session dengan data baru dari API
            if (isset($result['data'])) {
                session()->set([
                    'email'         => $result['data']['email'],
                    'first_name'    => $result['data']['first_name'],
                    'last_name'     => $result['data']['last_name'],
                    'profile_image' => $result['data']['profile_image'],
                ]);
            }

            return redirect()->to('/profile')->with('success', $result['message'] ?? 'Update Profile Image berhasil.');
        }

        // === Error 400: Format gambar tidak sesuai ===
        if ($httpCode === 400 && isset($result['status']) && $result['status'] === 102) {
            return redirect()->back()->with('error', $result['message'] ?? 'Format Image tidak sesuai.');
        }

        // === Error 401: Token tidak valid/kadaluwarsa ===
        if ($httpCode === 401 && isset($result['status']) && $result['status'] === 108) {
            session()->destroy(); // keluar otomatis
            return redirect()->to('/login')->with('error', 'Token tidak tidak valid atau kadaluwarsa');
        }

        // === Fallback: error lainnya
        return redirect()->back()->with('error', $result['message'] ?? 'Gagal upload gambar.');
    }
}
