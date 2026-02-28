<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Toko Buku</title>
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

        /* Remove button animation */
        .remove-btn:hover {
            transform: scale(1.1);
        }

        .remove-btn {
            transition: transform 0.2s ease;
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Navbar -->
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

                <div class="flex items-center space-x-6">
                    <a href="/" class="text-gray-700 hover:text-purple-600 transition">Beranda</a>
                    <a href="/cart" class="relative text-purple-600 font-semibold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    <!-- Shopping Cart Content -->
    <div class="container mx-auto px-6 py-12">
        <!-- Breadcrumb -->
        <div class="mb-8">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="/" class="hover:text-purple-600">Beranda</a>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-purple-600 font-medium">Keranjang Belanja</span>
            </div>
        </div>

        <!-- Title -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold font-serif text-gray-800 mb-2">Keranjang Belanja</h1>
            <p class="text-gray-600">3 item dalam keranjang Anda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4">

                <!-- Cart Item 1 -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-start space-x-4">
                        <!-- Book Image -->
                        <div
                            class="w-24 h-32 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <div class="text-white text-3xl">📚</div>
                        </div>

                        <!-- Book Details -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-1">Laravel untuk Pemula</h3>
                                    <p class="text-sm text-gray-500">Andi Prasetyo</p>
                                </div>
                                <button class="remove-btn text-red-500 hover:text-red-700 p-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Price -->
                            <div class="mb-4">
                                <span class="text-sm text-gray-400 line-through mr-2">Rp 150.000</span>
                                <span class="text-xl font-bold text-purple-600">Rp 120.000</span>
                                <span
                                    class="ml-2 bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full font-semibold">-20%</span>
                            </div>

                            <!-- Quantity & Subtotal -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center border-2 border-gray-300 rounded-lg overflow-hidden">
                                    <button
                                        class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold">-</button>
                                    <input type="number" value="1" min="1"
                                        class="w-16 text-center py-1 border-0 focus:outline-none font-semibold">
                                    <button
                                        class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold">+</button>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Subtotal</p>
                                    <p class="text-xl font-bold text-gray-800">Rp 120.000</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart Item 2 -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-start space-x-4">
                        <div
                            class="w-24 h-32 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <div class="text-white text-3xl">📖</div>
                        </div>

                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-1">Atomic Habits (Bahasa
                                        Indonesia)</h3>
                                    <p class="text-sm text-gray-500">James Clear</p>
                                </div>
                                <button class="remove-btn text-red-500 hover:text-red-700 p-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <div class="mb-4">
                                <span class="text-xl font-bold text-purple-600">Rp 98.000</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center border-2 border-gray-300 rounded-lg overflow-hidden">
                                    <button
                                        class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold">-</button>
                                    <input type="number" value="2" min="1"
                                        class="w-16 text-center py-1 border-0 focus:outline-none font-semibold">
                                    <button
                                        class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold">+</button>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Subtotal</p>
                                    <p class="text-xl font-bold text-gray-800">Rp 196.000</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart Item 3 -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-start space-x-4">
                        <div
                            class="w-24 h-32 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <div class="text-white text-3xl">💰</div>
                        </div>

                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-1">Rich Dad Poor Dad (Bahasa
                                        Indonesia)</h3>
                                    <p class="text-sm text-gray-500">Robert T. Kiyosaki</p>
                                </div>
                                <button class="remove-btn text-red-500 hover:text-red-700 p-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <div class="mb-4">
                                <span class="text-xl font-bold text-purple-600">Rp 95.000</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center border-2 border-gray-300 rounded-lg overflow-hidden">
                                    <button
                                        class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold">-</button>
                                    <input type="number" value="1" min="1"
                                        class="w-16 text-center py-1 border-0 focus:outline-none font-semibold">
                                    <button
                                        class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold">+</button>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Subtotal</p>
                                    <p class="text-xl font-bold text-gray-800">Rp 95.000</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Continue Shopping -->
                <div class="pt-4">
                    <a href="/"
                        class="text-purple-600 font-semibold hover:text-purple-700 transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                        Lanjut Belanja
                    </a>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <h2 class="text-2xl font-bold font-serif mb-6">Ringkasan Pesanan</h2>

                    <!-- Order Details -->
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal (4 item)</span>
                            <span class="font-semibold">Rp 411.000</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Diskon</span>
                            <span class="font-semibold text-red-600">-Rp 30.000</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Ongkir</span>
                            <span class="font-semibold text-green-600">GRATIS</span>
                        </div>

                        <div class="border-t pt-4">
                            <div class="flex justify-between text-lg">
                                <span class="font-bold">Total Bayar</span>
                                <span class="font-bold text-purple-600 text-2xl">Rp 381.000</span>
                            </div>
                        </div>
                    </div>

                    <!-- Voucher -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Voucher</label>
                        <div class="flex space-x-2">
                            <input type="text" placeholder="Masukkan kode voucher"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <button
                                class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition font-semibold">
                                Pakai
                            </button>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <button
                        class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white py-4 rounded-lg hover:shadow-xl transition font-bold text-lg mb-4">
                        Lanjut ke Checkout
                    </button>

                    <!-- Info -->
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Pembayaran Aman</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Gratis Ongkir Seluruh Indonesia</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Garansi 100% Original</span>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="mt-6 pt-6 border-t">
                        <p class="text-sm text-gray-600 mb-3">Metode Pembayaran:</p>
                        <div class="flex items-center space-x-2 flex-wrap">
                            <div class="bg-gray-100 px-3 py-2 rounded text-xs font-semibold">BCA</div>
                            <div class="bg-gray-100 px-3 py-2 rounded text-xs font-semibold">Mandiri</div>
                            <div class="bg-gray-100 px-3 py-2 rounded text-xs font-semibold">GoPay</div>
                            <div class="bg-gray-100 px-3 py-2 rounded text-xs font-semibold">OVO</div>
                            <div class="bg-gray-100 px-3 py-2 rounded text-xs font-semibold">DANA</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- You May Also Like -->
        <div class="mt-20">
            <h2 class="text-2xl font-bold font-serif mb-8">Anda Mungkin Suka</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- Simplified book card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:-translate-y-2 transition-transform">
                    <div class="h-48 bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center">
                        <div class="text-white text-center">
                            <div class="text-4xl mb-2">🌟</div>
                            <p class="font-serif font-bold text-sm px-2">Laskar Pelangi</p>
                        </div>
                    </div>
                    <div class="p-4">
                        <h4 class="font-semibold text-sm mb-2">Laskar Pelangi</h4>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs text-gray-400 line-through">Rp 85.000</span>
                                <p class="text-purple-600 font-bold">Rp 75.000</p>
                            </div>
                            <button class="bg-purple-600 text-white p-2 rounded-lg hover:bg-purple-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Repeat for other books -->
            </div>
        </div>
    </div>

    <!-- Footer -->
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
