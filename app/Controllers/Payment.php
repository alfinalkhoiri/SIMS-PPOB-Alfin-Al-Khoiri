<?php

namespace App\Controllers;

use Config\Services;

class Payment extends BaseController
{
    public function index($service_code)
    {

        $token = session('token');
        if (!$token) return redirect()->to('/login');

        $user = $this->getUserProfile();
        $balance = $this->getUserBalance();

        // Ambil semua services
        $client = Services::curlrequest();
        $response = $client->get('https://take-home-test-api.nutech-integrasi.com/services', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        $body = json_decode($response->getBody(), true);
        $services = $body['data'] ?? [];

        // Temukan service berdasarkan service_code nya
        $selectedService = null;
        foreach ($services as $svc) {
            if ($svc['service_code'] === $service_code) {
                $selectedService = $svc;
                break;
            }
        }

        return view('/payment', [
            'title' => 'Payment | Alfin Al Khoiri',
            'service' => $selectedService,
            'user' => $user,
            'balance' => $balance,
        ]);
    }

    public function transaction()
    {
        $token = session('token');
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Token tidak ditemukan. Silakan login terlebih dahulu.');
        }

        $serviceCode = $this->request->getPost('service_code');
        $balance = $this->getUserBalance();

        if (!is_numeric($balance)) {
            return redirect()->back()->with('error', 'Gagal mengambil saldo Anda.');
        }

        // Cek apakah saldo mencukupi
        $servicesResponse = Services::curlrequest()->get('https://take-home-test-api.nutech-integrasi.com/services', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ],
            'http_errors' => false
        ]);

        $services = json_decode($servicesResponse->getBody(), true)['data'] ?? [];

        $selectedService = null;
        foreach ($services as $service) {
            if ($service['service_code'] === $serviceCode) {
                $selectedService = $service;
                break;
            }
        }

        // Jika layanan tidak ditemukan
        if (!$selectedService) {
            return redirect()->back()->with('error', 'Service tidak ditemukan.');
        }

        $tariff = $selectedService['service_tariff'];

        // Cek saldo cukup untuk transaksi
        if ($balance < $tariff) {
            return redirect()->back()->with('error', 'Saldo tidak mencukupi untuk melakukan transaksi.');
        }

        // Kirim transaksi
        try {
            $client = Services::curlrequest();
            $response = $client->post('https://take-home-test-api.nutech-integrasi.com/transaction', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'service_code' => $serviceCode,
                ],
                'http_errors' => false
            ]);

            $result = json_decode($response->getBody(), true);

            // Cek response status
            if ($response->getStatusCode() === 200 && $result['status'] === 0) {
                // Jika transaksi berhasil
                session()->setFlashdata('success', $result['message'] ?? 'Transaksi berhasil!');
                return redirect()->back();
            } else {
                return redirect()->back()->with('error', $result['message'] ?? 'Transaksi gagal. Coba lagi.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan server: ' . $e->getMessage());
        }
    }

    private function getUserBalance()
    {
        $token = session('token');

        if (!$token) {
            return null;
        }

        try {
            $client = Services::curlrequest();

            $response = $client->get('https://take-home-test-api.nutech-integrasi.com/balance', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
                'http_errors' => false
            ]);

            $body = json_decode($response->getBody(), true);

            if ($response->getStatusCode() === 200 && $body['status'] === 0) {
                return $body['data']['balance'];
            }
        } catch (\Exception $e) {
            log_message('error', 'Gagal ambil saldo: ' . $e->getMessage());
        }

        return null;
    }

    private function getUserProfile()
    {
        $token = session('token'); // Ambil token dari session

        if (!$token) {
            return null;
        }

        try {
            $client = \Config\Services::curlrequest();

            $response = $client->get('https://take-home-test-api.nutech-integrasi.com/profile', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                ],
                'http_errors' => false
            ]);

            $body = json_decode($response->getBody(), true);

            if ($response->getStatusCode() === 200 && $body['status'] === 0) {
                return $body['data'];
            }
        } catch (\Exception $e) {
            log_message('error', 'Gagal ambil profil user: ' . $e->getMessage());
        }

        return null;
    }
}
