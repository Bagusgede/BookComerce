<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel untuk Pemula - Detail Buku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400;600;700&family=DM+Sans:wght@400;500;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }

        .font-serif {
            font-family: 'Crimson Pro', serif;
        }

        /* Image zoom on hover */
        .book-cover:hover {
            transform: scale(1.05);
        }

        .book-cover {
            transition: transform 0.3s ease;
        }

        /* Quantity buttons */
        .quantity-btn {
            transition: all 0.2s ease;
        }

        .quantity-btn:active {
            transform: scale(0.95);
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Navbar (sama seperti homepage) -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                    </svg>
                    <a href="/" class="text-2xl font-bold font-serif text-gray-800">Toko Buku</a>
                </div>

                <div class="hidden md:flex flex-1 mx-8">
                    <div class="w-full max-w-lg">
                        <div class="relative">
                            <input type="text" placeholder="Cari buku, penulis, atau ISBN..."
                                class="w-full px-4 py-2 pl-10 pr-4 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-6">
                    <a href="/" class="text-gray-700 hover:text-purple-600 transition">Beranda</a>
                    <a href="/cart" class="relative">
                        <svg class="w-6 h-6 text-gray-700 hover:text-purple-600 transition" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                    </a>
                    <a href="/login" class="text-gray-700 hover:text-purple-600 transition">Masuk</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="/" class="hover:text-purple-600">Beranda</a>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
                <a href="/kategori/teknologi" class="hover:text-purple-600">Teknologi</a>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-purple-600 font-medium">Laravel untuk Pemula</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Book Image -->
            <div>
                <div
                    class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl shadow-2xl overflow-hidden h-[600px] flex items-center justify-center book-cover">
                    <div class="text-white text-center p-8">
                        <div class="text-9xl mb-6">📚</div>
                        <p class="font-serif text-4xl font-bold">Laravel untuk Pemula</p>
                        <p class="text-xl mt-4 text-purple-100">Andi Prasetyo</p>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="mt-6 grid grid-cols-3 gap-4">
                    <div class="bg-white p-4 rounded-lg shadow text-center">
                        <div class="text-2xl mb-1">📖</div>
                        <p class="text-sm text-gray-600">350 Halaman</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow text-center">
                        <div class="text-2xl mb-1">📅</div>
                        <p class="text-sm text-gray-600">2024</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow text-center">
                        <div class="text-2xl mb-1">📦</div>
                        <p class="text-sm text-gray-600">Stok: 50</p>
                    </div>
                </div>
            </div>

            <!-- Book Details -->
            <div>
                <!-- Badge -->
                <div class="flex items-center space-x-2 mb-4">
                    <span
                        class="bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-sm font-semibold">TERLARIS</span>
                    <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-sm font-semibold">-20% DISKON</span>
                </div>

                <!-- Title -->
                <h1 class="text-4xl font-bold font-serif text-gray-800 mb-2">Laravel untuk Pemula</h1>
                <p class="text-xl text-gray-600 mb-4">oleh <span class="font-semibold">Andi Prasetyo</span></p>

                <!-- Rating -->
                <div class="flex items-center mb-6">
                    <div class="flex text-yellow-400 text-xl">
                        ⭐⭐⭐⭐⭐
                    </div>
                    <span class="ml-2 text-gray-600">4.8 (125 ulasan)</span>
                    <a href="#reviews" class="ml-4 text-purple-600 hover:underline text-sm">Lihat Ulasan</a>
                </div>

                <!-- Price -->
                <div class="bg-purple-50 p-6 rounded-xl mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Harga Normal</p>
                            <p class="text-2xl text-gray-400 line-through">Rp 150.000</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600 mb-1">Harga Diskon</p>
                            <p class="text-4xl font-bold text-purple-600">Rp 120.000</p>
                        </div>
                    </div>
                    <div class="mt-4 bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm font-semibold">
                        🎉 Hemat Rp 30.000!
                    </div>
                </div>

                <!-- Quantity & Add to Cart -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah</label>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center border-2 border-gray-300 rounded-lg overflow-hidden">
                            <button
                                class="quantity-btn px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold">
                                -
                            </button>
                            <input type="number" value="1" min="1" max="50"
                                class="w-20 text-center py-3 border-0 focus:outline-none font-semibold text-lg">
                            <button
                                class="quantity-btn px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold">
                                +
                            </button>
                        </div>
                        <button
                            class="flex-1 bg-purple-600 text-white py-3 px-8 rounded-lg hover:bg-purple-700 transition font-semibold text-lg shadow-lg">
                            🛒 Tambah ke Keranjang
                        </button>
                    </div>
                </div>

                <!-- Buy Now Button -->
                <button
                    class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white py-4 rounded-lg hover:shadow-xl transition font-bold text-lg">
                    ⚡ Beli Sekarang
                </button>

                <!-- Additional Info -->
                <div class="mt-8 space-y-3">
                    <div class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Garansi 100% Original</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Gratis Ongkir ke Seluruh Indonesia</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Proses Cepat & Aman</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Book Description & Details Tabs -->
        <div class="mt-16">
            <div class="border-b border-gray-200 mb-8">
                <div class="flex space-x-8">
                    <button class="pb-4 px-2 border-b-2 border-purple-600 text-purple-600 font-semibold">
                        Deskripsi
                    </button>
                    <button
                        class="pb-4 px-2 border-b-2 border-transparent text-gray-600 hover:text-purple-600 font-semibold">
                        Spesifikasi
                    </button>
                    <button
                        class="pb-4 px-2 border-b-2 border-transparent text-gray-600 hover:text-purple-600 font-semibold"
                        id="reviews">
                        Ulasan (125)
                    </button>
                </div>
            </div>

            <!-- Description Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <div class="lg:col-span-2">
                    <div class="prose max-w-none">
                        <h3 class="text-2xl font-bold font-serif mb-4">Tentang Buku Ini</h3>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            <strong>Laravel untuk Pemula</strong> adalah buku panduan lengkap yang dirancang khusus
                            untuk membantu Anda
                            menguasai Laravel, framework PHP paling populer di dunia. Buku ini cocok untuk pemula yang
                            ingin
                            memulai karir sebagai web developer profesional.
                        </p>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            Dalam buku ini, Anda akan belajar dari dasar hingga mahir, dengan pembahasan yang sistematis
                            dan
                            mudah dipahami. Setiap konsep dijelaskan dengan contoh kode yang praktis dan langsung bisa
                            diterapkan
                            dalam project nyata.
                        </p>

                        <h4 class="text-xl font-bold mt-8 mb-4">Yang Akan Anda Pelajari:</h4>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-purple-600 mr-2 mt-1" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Instalasi dan konfigurasi Laravel 10
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-purple-600 mr-2 mt-1" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Routing, Controllers, dan Views
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-purple-600 mr-2 mt-1" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Database Management dengan Eloquent ORM
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-purple-600 mr-2 mt-1" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Authentication & Authorization
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-purple-600 mr-2 mt-1" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                REST API Development
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-purple-600 mr-2 mt-1" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Testing dan Deployment
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Specifications -->
                <div>
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold font-serif mb-4">Spesifikasi</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">Penerbit</span>
                                <span class="font-semibold">Tech Publisher</span>
                            </div>
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">Tahun Terbit</span>
                                <span class="font-semibold">2024</span>
                            </div>
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">Jumlah Halaman</span>
                                <span class="font-semibold">350 halaman</span>
                            </div>
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">ISBN</span>
                                <span class="font-semibold">978-123-456-7890</span>
                            </div>
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">Bahasa</span>
                                <span class="font-semibold">Indonesia</span>
                            </div>
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">Berat</span>
                                <span class="font-semibold">500 gram</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600">Format</span>
                                <span class="font-semibold">Fisik & E-book</span>
                            </div>
                        </div>
                    </div>

                    <!-- Share -->
                    <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
                        <h3 class="text-xl font-bold font-serif mb-4">Bagikan</h3>
                        <div class="flex space-x-3">
                            <button class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                                <svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </button>
                            <button class="flex-1 bg-sky-500 text-white py-2 rounded-lg hover:bg-sky-600 transition">
                                <svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                </svg>
                            </button>
                            <button
                                class="flex-1 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
                                <svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="mt-16">
            <h3 class="text-2xl font-bold font-serif mb-8">Ulasan Pelanggan</h3>

            <!-- Review Summary -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="text-center md:text-left">
                        <div class="text-5xl font-bold text-purple-600 mb-2">4.8</div>
                        <div class="flex justify-center md:justify-start text-yellow-400 text-2xl mb-2">
                            ⭐⭐⭐⭐⭐
                        </div>
                        <p class="text-gray-600">Berdasarkan 125 ulasan</p>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600 w-12">5 ⭐</span>
                            <div class="flex-1 bg-gray-200 rounded-full h-2 mx-4">
                                <div class="bg-yellow-400 h-2 rounded-full" style="width: 85%"></div>
                            </div>
                            <span class="text-sm text-gray-600 w-12">85%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600 w-12">4 ⭐</span>
                            <div class="flex-1 bg-gray-200 rounded-full h-2 mx-4">
                                <div class="bg-yellow-400 h-2 rounded-full" style="width: 10%"></div>
                            </div>
                            <span class="text-sm text-gray-600 w-12">10%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600 w-12">3 ⭐</span>
                            <div class="flex-1 bg-gray-200 rounded-full h-2 mx-4">
                                <div class="bg-yellow-400 h-2 rounded-full" style="width: 3%"></div>
                            </div>
                            <span class="text-sm text-gray-600 w-12">3%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600 w-12">2 ⭐</span>
                            <div class="flex-1 bg-gray-200 rounded-full h-2 mx-4">
                                <div class="bg-yellow-400 h-2 rounded-full" style="width: 1%"></div>
                            </div>
                            <span class="text-sm text-gray-600 w-12">1%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600 w-12">1 ⭐</span>
                            <div class="flex-1 bg-gray-200 rounded-full h-2 mx-4">
                                <div class="bg-yellow-400 h-2 rounded-full" style="width: 1%"></div>
                            </div>
                            <span class="text-sm text-gray-600 w-12">1%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Individual Reviews -->
            <div class="space-y-6">
                <!-- Review 1 -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-start">
                        <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=667eea&color=fff"
                            class="w-12 h-12 rounded-full mr-4">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h4 class="font-semibold">Budi Santoso</h4>
                                    <p class="text-sm text-gray-500">2 hari yang lalu</p>
                                </div>
                                <div class="flex text-yellow-400">
                                    ⭐⭐⭐⭐⭐
                                </div>
                            </div>
                            <p class="text-gray-700 leading-relaxed">
                                Buku yang sangat bagus untuk pemula! Penjelasannya detail dan mudah dipahami.
                                Contoh kodenya juga sangat praktis. Sangat direkomendasikan!
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Review 2 -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-start">
                        <img src="https://ui-avatars.com/api/?name=Ani+Lestari&background=764ba2&color=fff"
                            class="w-12 h-12 rounded-full mr-4">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h4 class="font-semibold">Ani Lestari</h4>
                                    <p class="text-sm text-gray-500">1 minggu yang lalu</p>
                                </div>
                                <div class="flex text-yellow-400">
                                    ⭐⭐⭐⭐⭐
                                </div>
                            </div>
                            <p class="text-gray-700 leading-relaxed">
                                Setelah baca buku ini, saya bisa bikin aplikasi web sendiri! Materinya lengkap dari
                                dasar
                                sampai lanjutan. Sangat sepadan dengan harganya.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Load More Button -->
                <div class="text-center">
                    <button class="text-purple-600 font-semibold hover:text-purple-700 transition">
                        Lihat Semua Ulasan →
                    </button>
                </div>
            </div>
        </div>

        <!-- Recommended Books -->
        <div class="mt-20">
            <h3 class="text-2xl font-bold font-serif mb-8">Buku Terkait</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- Simplified book cards (sama seperti di homepage) -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:-translate-y-2 transition-transform">
                    <div class="h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                        <div class="text-white text-center">
                            <div class="text-4xl mb-2">🔥</div>
                            <p class="font-serif font-bold text-sm px-2">Clean Code</p>
                        </div>
                    </div>
                    <div class="p-4">
                        <h4 class="font-semibold text-sm mb-2">Clean Code</h4>
                        <p class="text-purple-600 font-bold">Rp 180.000</p>
                    </div>
                </div>
                <!-- Repeat for other recommended books -->
            </div>
        </div>
    </div>

    <!-- Footer (sama seperti homepage) -->
    <footer class="bg-gray-800 text-white py-12 mt-20">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold font-serif mb-4">Toko Buku</h3>
                    <p class="text-gray-400">Toko buku online terpercaya dengan ribuan koleksi buku berkualitas.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Kategori</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Fiksi</a></li>
                        <li><a href="#" class="hover:text-white transition">Non-Fiksi</a></li>
                        <li><a href="#" class="hover:text-white transition">Teknologi</a></li>
                        <li><a href="#" class="hover:text-white transition">Bisnis</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Bantuan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Cara Pemesanan</a></li>
                        <li><a href="#" class="hover:text-white transition">Pembayaran</a></li>
                        <li><a href="#" class="hover:text-white transition">Pengiriman</a></li>
                        <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Hubungi Kami</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Email: info@bookstore.com</li>
                        <li>Telepon: +62 812-3456-7890</li>
                        <li>Alamat: Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 Toko Buku. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

</body>

</html>
    