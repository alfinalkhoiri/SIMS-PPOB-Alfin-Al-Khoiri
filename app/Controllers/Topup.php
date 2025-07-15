<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Topup extends BaseController
{
    public function index()
    {
        if (!session()->get('token')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $token  = session()->get('token');
        $client = \Config\Services::curlrequest();

        try {
            // Get profile
            $profileResponse = $client->get('https://take-home-test-api.nutech-integrasi.com/profile', [
                'headers' => ['Authorization' => 'Bearer ' . $token]
            ]);
            $profileData = json_decode($profileResponse->getBody(), true);

            // Get balance
            $balanceResponse = $client->get('https://take-home-test-api.nutech-integrasi.com/balance', [
                'headers' => ['Authorization' => 'Bearer ' . $token]
            ]);
            $balanceData = json_decode($balanceResponse->getBody(), true);

            return view('topup', [
                'title'         => 'Top Up | HIS PPOB - Alfin Al Khoiri',
                'first_name'    => $profileData['data']['first_name'] ?? 'User',
                'last_name'     => $profileData['data']['last_name'] ?? '',
                'profile_image' => $profileData['data']['profile_image'] ?? base_url('Assets/avatar.png'),
                'balance'       => $balanceData['data']['balance'] ?? 0,
            ]);
        } catch (\Exception $e) {
            return redirect()->to('/login')->with('error', 'Token tidak tidak valid atau kadaluwarsa ');
        }
    }

    public function submit()
    {
        $amount = (int) $this->request->getPost('amount');

        // Validasi form (prevent sebelum kirim ke API)
        if ($amount < 10000 || $amount > 1000000) {
            return redirect()->back()->withInput()->with('error', 'Nominal top up harus antara Rp 10.000 – Rp 1.000.000.');
        }

        $token  = session()->get('token');
        $client = \Config\Services::curlrequest();

        try {
            $response = $client->post('https://take-home-test-api.nutech-integrasi.com/topup', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json'
                ],
                'json' => ['top_up_amount' => $amount]
            ]);

            $result = json_decode($response->getBody(), true);

            // Swagger: status 0 = success
            if (isset($result['status']) && $result['status'] === 0) {
                // update saldo di session (optional)
                session()->set('balance', $result['data']['balance']);
                return redirect()->to('/topup')->with('success', $result['message'] ?? 'Top Up Balance berhasil');
            }

            // Swagger: status 102 = Bad Request, 108 = Token Invalid
            return redirect()->back()->withInput()->with('error', $result['message'] ?? 'Paramter amount hanya boleh angka dan tidak boleh lebih kecil dari 0 ');
        } catch (\CodeIgniter\HTTP\Exceptions\HTTPException $e) {
            return redirect()->back()->withInput()->with('error', 'HTTP Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Koneksi gagal: ' . $e->getMessage());
        }
    }
}
