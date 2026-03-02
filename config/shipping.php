<?php

return [
    // Provider ongkir: flat (fallback) atau rajaongkir (live API)
    'provider' => env('SHIPPING_PROVIDER', 'rajaongkir'),

    // Biaya dasar ongkir otomatis untuk pesanan yang memiliki item fisik
    'default_base_cost' => env('SHIPPING_DEFAULT_BASE_COST', 18000),

    // Biaya tambahan per item fisik setelah item pertama
    'additional_per_item' => env('SHIPPING_ADDITIONAL_PER_ITEM', 5000),

    // Override biaya dasar berdasarkan kota tujuan (gunakan huruf kecil)
    'city_overrides' => [
        'badung' => 12000,
        'denpasar' => 12000,
        'gianyar' => 14000,
        'tabanan' => 14000,
        'bangli' => 15000,
        'karangasem' => 17000,
        'buleleng' => 17000,
        'jembrana' => 17000,
        'klungkung' => 15000,
    ],

    // Berat default per item fisik (gram) untuk estimasi API
    'default_weight_per_item_gram' => env('SHIPPING_DEFAULT_WEIGHT_PER_ITEM_GRAM', 500),
    'minimum_weight_gram' => env('SHIPPING_MINIMUM_WEIGHT_GRAM', 500),

    'rajaongkir' => [
        'api_key' => env('RAJAONGKIR_API_KEY', ''),
        'origin_city_id' => env('RAJAONGKIR_ORIGIN_CITY_ID', ''),
        'origin_city_name' => env('RAJAONGKIR_ORIGIN_CITY_NAME', 'Badung'),
        'courier' => env('RAJAONGKIR_COURIER', 'jne'),
        'preferred_service' => env('RAJAONGKIR_PREFERRED_SERVICE', 'REG'),
        'timeout_seconds' => env('RAJAONGKIR_TIMEOUT_SECONDS', 8),
        'cache_minutes' => env('RAJAONGKIR_CACHE_MINUTES', 60),
    ],
];
