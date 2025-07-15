<?php

namespace App\Controllers;

use Config\Services;

class Home extends BaseController
{
    public function index()
    {
        if (!session()->get('token')) {
            return redirect()->to('/login')->with('error', 'Silakan login dulu.');
        }
        $client = \Config\Services::curlrequest();
        $token = session()->get('token');

        $headers = ['Authorization' => 'Bearer ' . $token];

        try {
            // GET Profile
            $profileResponse = $client->get('https://take-home-test-api.nutech-integrasi.com/profile', ['headers' => $headers]);
            $profile = json_decode($profileResponse->getBody(), true)['data'];

            // GET Balance
            $balanceResponse = $client->get('https://take-home-test-api.nutech-integrasi.com/balance', ['headers' => $headers]);
            $balance = json_decode($balanceResponse->getBody(), true)['data']['balance'];

            // GET Services
            $servicesResponse = $client->get('https://take-home-test-api.nutech-integrasi.com/services', ['headers' => $headers]);
            $services = json_decode($servicesResponse->getBody(), true)['data'];

            // GET Banner
            $bannerResponse = $client->get('https://take-home-test-api.nutech-integrasi.com/banner', ['headers' => $headers]);
            $banners = json_decode($bannerResponse->getBody(), true)['data'];

            return view('home', [
                'first_name' => $profile['first_name'],
                'last_name' => $profile['last_name'],
                'balance' => $balance,
                'services' => $services,
                'banners' => $banners,
                'avatar' => $profile['profile_image'],
                'title' => 'Home | HIS PPOB-Alfin Al Khoiri'
            ]);
        } catch (\Exception $e) {
            return view('home', ['error' => 'Gagal mengambil data: ' . $e->getMessage()]);
        }
    }
}
