<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BebanGajiController;
use App\Http\Controllers\BebanOperasionalController;
use App\Http\Controllers\BelanjaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HutangController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\JasaController;
use App\Http\Controllers\JenisUsahaController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LabaRugiController;
use App\Http\Controllers\LaporanTransaksi;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemasukanBebasController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\TransaksiProdukController;
use App\Http\Controllers\TransaksiJasaController;

// use Monolog\Handler\RotatingFileHandler;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'index'])->name('login');

Route::post('/proses_login', [AuthController::class, 'proses_login'])->name('proses_login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
Route::put('/profil/{id}/update-identitas', [ProfilController::class, 'update_identitas'])->name('profil.update-identitas');
Route::put('/profil/{id}/update-password', [ProfilController::class, 'update_password'])->name('profil.update-password');

Route::group(['middleware' => ['auth']], function () {

    Route::group(['middleware' => ['cek_login:admin', 'cek_login:kasir', 'cek_wujud_usaha:Produk']], function () {
    });

    Route::group(['middleware' => ['cek_login:admin']], function () {
        Route::get('/laporan', [BebanOperasionalController::class, 'laporan'])->name('laporan');

        Route::get('/beban-operasional', [BebanOperasionalController::class, 'index'])->name('beban-operasional');
        Route::get('/beban-operasional/create', [BebanOperasionalController::class, 'create'])->name('beban-operasional.create');
        Route::post('/beban-operasional/store', [BebanOperasionalController::class, 'store'])->name('beban-operasional.store');
        Route::get('/beban-operasional/{id}/edit', [BebanOperasionalController::class, 'edit'])->name('beban-operasional.edit');
        Route::put('/beban-operasional/{id}/update', [BebanOperasionalController::class, 'update'])->name('beban-operasional.update');
        Route::delete('/beban-operasional/{id}/delete', [BebanOperasionalController::class, 'destroy'])->name('beban-operasional.delete');

        Route::get('/beban-gaji', [BebanGajiController::class, 'index'])->name('beban-gaji');
        Route::get('/beban-gaji/create', [BebanGajiController::class, 'create'])->name('beban-gaji.create');
        Route::post('/beban-gaji/store', [BebanGajiController::class, 'store'])->name('beban-gaji.store');
        Route::get('/beban-gaji/{id}/edit', [BebanGajiController::class, 'edit'])->name('beban-gaji.edit');
        Route::put('/beban-gaji/{id}/update', [BebanGajiController::class, 'update'])->name('beban-gaji.update');
        Route::delete('/beban-gaji/{id}/delete', [BebanGajiController::class, 'destroy'])->name('beban-gaji.delete');

        Route::get('/pemasukan-bebas', [PemasukanBebasController::class, 'index'])->name('pemasukan-bebas');
        Route::get('/pemasukan-bebas/create', [PemasukanBebasController::class, 'create'])->name('pemasukan-bebas.create');
        Route::post('/pemasukan-bebas/store', [PemasukanBebasController::class, 'store'])->name('pemasukan-bebas.store');
        Route::get('/pemasukan-bebas/{id}/edit', [PemasukanBebasController::class, 'edit'])->name('pemasukan-bebas.edit');
        Route::put('/pemasukan-bebas/{id}/update', [PemasukanBebasController::class, 'update'])->name('pemasukan-bebas.update');
        Route::delete('/pemasukan-bebas/{id}/delete', [PemasukanBebasController::class, 'destroy'])->name('pemasukan-bebas.delete');

        Route::get('/inventaris', [InventarisController::class, 'index'])->name('inventaris');
        Route::get('/inventaris/create', [InventarisController::class, 'create'])->name('inventaris.create');
        Route::post('/inventaris/store', [InventarisController::class, 'store'])->name('inventaris.store');
        Route::get('/inventaris/{id}/edit', [InventarisController::class, 'edit'])->name('inventaris.edit');
        Route::put('/inventaris/{id}/update', [InventarisController::class, 'update'])->name('inventaris.update');
        Route::delete('/inventaris/{id}/delete', [InventarisController::class, 'destroy'])->name('inventaris.delete');

        Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan');
        Route::get('/pelanggan/create', [PelangganController::class, 'create'])->name('pelanggan.create');
        Route::post('/pelanggan/store', [PelangganController::class, 'store'])->name('pelanggan.store');
        Route::get('/pelanggan/{id}/edit', [PelangganController::class, 'edit'])->name('pelanggan.edit');
        Route::put('/pelanggan/{id}/update', [PelangganController::class, 'update'])->name('pelanggan.update');
        Route::delete('/pelanggan/{id}/delete', [PelangganController::class, 'destroy'])->name('pelanggan.delete');
        Route::get('/pelanggan/{id}/bon-hutang', [PelangganController::class, 'bon_hutang'])->name('pelanggan.bon-hutang');
        Route::get('/pelanggan/{id}/cetak-bon-hutang', [PelangganController::class, 'cetak_bon_hutang'])->name('pelanggan.bon-hutang.cetak');

        Route::get('/kas', [KasController::class, 'kas'])->name('kas');
        Route::get('/kas/print', [KasController::class, 'kas_print'])->name('kas.print');
        Route::post('/kas/filter-print', [KasController::class, 'filter_kas_print'])->name('kas.filter-print');

        Route::group(['middleware' => ['cek_wujud_usaha:Produk']], function () {
            Route::post('/produk/{id}/store', [ProdukController::class, 'store'])->name('produk.store');
            Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
            Route::get('/produk/{id}/edit-harga',[ProdukController::class,'edit_harga'])->name('produk.edit-harga');
            Route::put('/produk/{id}/update-harga',[ProdukController::class,'update_harga'])->name('produk.update-harga');

            Route::get('/belanja-produk', [BelanjaController::class, 'index'])->name('belanja-produk');
            Route::get('/belanja-produk/create', [BelanjaController::class, 'create'])->name('belanja-produk.create');
            Route::post('/belanja-produk/store', [BelanjaController::class, 'store'])->name('belanja-produk.store');
            Route::get('/belanja-produk/{id}/edit', [BelanjaController::class, 'edit'])->name('belanja-produk.edit');
            Route::put('/belanja-produk/{id}/update', [BelanjaController::class, 'update'])->name('belanja-produk.update');
            Route::delete('/belanja-produk/{id}/delete', [BelanjaController::class, 'destroy'])->name('belanja-produk.delete');
            Route::get('/belanja-produk/{id}/to-produk', [BelanjaController::class, 'to_produk_page'])->name('belanja_prduk.to-produk');

            Route::get('/laporan-transaksi-produk', [LaporanTransaksi::class, 'laporan_transaksi_produk'])->name('laporan-transaksi-produk');
            Route::get('/laporan-transaksi-produk/print', [LaporanTransaksi::class, 'laporan_transaksi_produk_print'])->name('laporan-transaksi-produk.print');
            Route::post('/laporan-transaksi-produk/filter-print', [LaporanTransaksi::class, 'filter_laporan_transaksi_produk_print'])->name('laporan-transaksi-produk.filter-print');

            Route::get('/laporan-labarugi-produk', [LabaRugiController::class, 'laporan_labarugi_produk'])->name('laporan-labarugi-produk');
            Route::get('/laporan-labarugi-produk/print', [LabaRugiController::class, 'laporan_labarugi_produk_print'])->name('laporan-labarugi-produk.print');
            Route::post('/laporan-labarugi-produk/filter-print', [LabaRugiController::class, 'filter_laporan_labarugi_produk_print'])->name('laporan-labarugi-produk.filter-print');
            
        });
        Route::group(['middleware' => ['cek_wujud_usaha:Jasa']], function () {
            Route::get('/jasa', [JasaController::class, 'index'])->name('jasa');
            Route::get('/jasa/create', [JasaController::class, 'create'])->name('jasa.create');
            Route::post('/jasa/store', [JasaController::class, 'store'])->name('jasa.store');
            Route::put('/jasa/{id}/validasi', [JasaController::class, 'validasi'])->name('jasa.validasi');
            Route::get('/jasa/{id}/edit', [JasaController::class, 'edit'])->name('jasa.edit');
            Route::put('/jasa/{id}/update', [JasaController::class, 'update'])->name('jasa.update');
            Route::delete('/jasa/{id}/delete', [JasaController::class, 'destroy'])->name('jasa.delete');
            Route::get('/jasa/{id}/edit-harga',[JasaController::class,'edit_harga'])->name('jasa.edit-harga');
            Route::put('/jasa/{id}/update-harga',[JasaController::class,'update_harga'])->name('jasa.update-harga');

            Route::get('/laporan-transaksi-jasa', [LaporanTransaksi::class, 'laporan_transaksi_jasa'])->name('laporan-transaksi-jasa');
            Route::get('/laporan-transaksi-jasa/print', [LaporanTransaksi::class, 'laporan_transaksi_jasa_print'])->name('laporan-transaksi-jasa.print');
            Route::post('/laporan-transaksi-jasa/filter-print', [LaporanTransaksi::class, 'filter_laporan_transaksi_jasa_print'])->name('laporan-transaksi-jasa.filter-print');

            Route::get('/laporan-labarugi-jasa', [LabaRugiController::class, 'laporan_labarugi_jasa'])->name('laporan-labarugi-jasa');
            Route::get('/laporan-labarugi-jasa/print', [LabaRugiController::class, 'laporan_labarugi_jasa_print'])->name('laporan-labarugi-jasa.print');
            Route::post('/laporan-labarugi-jasa/filter-print', [LabaRugiController::class, 'filter_laporan_labarugi_jasa_print'])->name('laporan-labarugi-jasa.filter-print');
        });
    });
    Route::group(['middleware' => ['cek_login:kasir']], function () {

        
        Route::get('/hutang', [HutangController::class, 'index'])->name('hutang');
        Route::get('/hutang/create', [HutangController::class, 'create'])->name('hutang.create');
        Route::post('/hutang/store', [HutangController::class, 'store'])->name('hutang.store');
        Route::get('/hutang/{id}/edit', [HutangController::class, 'edit'])->name('hutang.edit');
        Route::put('/hutang/{id}/update', [HutangController::class, 'update'])->name('hutang.update');
        Route::put('/hutang/{id}/delete', [HutangController::class, 'destroy'])->name('hutang.delete');
        Route::post('/hutang/{id}/lunas', [HutangController::class, 'lunas'])->name('hutang.lunas');
        Route::delete('/hutang/{id}/delete', [HutangController::class, 'destroy'])->name('hutang.delete');

        Route::group(['middleware' => ['cek_wujud_parent:Produk']], function () {
            Route::get('transaksi-produk', [TransaksiProdukController::class, 'index'])->name('transaksi-produk');
            Route::get('transaksi-produk/create', [TransaksiProdukController::class, 'create'])->name('transaksi-produk.create');
            Route::get('transaksi-produk/create/reset-keranjang', [TransaksiProdukController::class, 'reset_keranjang'])->name('transaksi-produk.create.reset-keranjang');
            Route::post('transaksi-produk/cart', [TransaksiProdukController::class, 'cart'])->name('transaksi-produk.cart');
            Route::get('transaksi-produk/bayar-pelanggan', [TransaksiProdukController::class, 'bayar_pelanggan'])->name('transaksi-produk.bayar-pelanggan');
            Route::get('transaksi-produk/bayar-umum', [TransaksiProdukController::class, 'bayar_umum'])->name('transaksi-produk.bayar-umum');
            Route::post('transaksi-produk/store-pelanggan', [TransaksiProdukController::class, 'store_pelanggan'])->name('transaksi-produk.store-pelanggan');
            Route::post('transaksi-produk/store-umum', [TransaksiProdukController::class, 'store_umum'])->name('transaksi-produk.store-umum');
            Route::get('transaksi-produk/{id}/detail-transaksi', [TransaksiProdukController::class, 'detail_transaksi'])->name('transaksi-produk.detail-transaksi');
            Route::get('transaksi-produk/{id}/cetak-transaksi', [TransaksiProdukController::class, 'cetak_transaksi'])->name('transaksi-produk.cetak-transaksi');
            Route::get('/produk-kasir', [ProdukController::class, 'index'])->name('produk-kasir');
        });

        Route::group(['middleware' => ['cek_wujud_parent:Jasa']], function () {
            Route::get('transaksi-jasa', [TransaksiJasaController::class, 'index'])->name('transaksi-jasa');
            Route::get('transaksi-jasa/create', [TransaksiJasaController::class, 'create'])->name('transaksi-jasa.create');
            Route::get('transaksi-jasa/create/reset-keranjang', [TransaksiJasaController::class, 'reset_keranjang'])->name('transaksi-jasa.create.reset-keranjang');
            Route::post('transaksi-jasa/cart', [TransaksiJasaController::class, 'cart'])->name('transaksi-jasa.cart');
            Route::get('transaksi-jasa/bayar-pelanggan', [TransaksiJasaController::class, 'bayar_pelanggan'])->name('transaksi-jasa.bayar-pelanggan');
            Route::get('transaksi-jasa/bayar-umum', [TransaksiJasaController::class, 'bayar_umum'])->name('transaksi-jasa.bayar-umum');
            Route::post('transaksi-jasa/store-pelanggan', [TransaksiJasaController::class, 'store_pelanggan'])->name('transaksi-jasa.store-pelanggan');
            Route::post('transaksi-jasa/store-umum', [TransaksiJasaController::class, 'store_umum'])->name('transaksi-jasa.store-umum');
            Route::get('transaksi-jasa/{id}/detail-transaksi', [TransaksiJasaController::class, 'detail_transaksi'])->name('transaksi-jasa.detail-transaksi');
            Route::get('transaksi-jasa/{id}/cetak-transaksi', [TransaksiJasaController::class, 'cetak_transaksi'])->name('transaksi-jasa.cetak-transaksi');
            Route::get('/jasa-kasir', [TransaksiJasaController::class, 'jasa_kasir'])->name('jasa-kasir');
        });
    });
    Route::group(['middleware' => ['cek_login:super-admin']], function () {

        Route::get('/jenis-usaha', [JenisUsahaController::class, 'index'])->name('jenis-usaha');
        Route::get('/jenis-usaha/create', [JenisUsahaController::class, 'create'])->name('jenis-usaha.create');
        Route::post('/jenis-usaha/store', [JenisUsahaController::class, 'store'])->name('jenis-usaha.store');
        Route::get('/jenis-usaha/{id}/edit', [JenisUsahaController::class, 'edit'])->name('jenis-usaha.edit');
        Route::put('/jenis-usaha/{id}update', [JenisUsahaController::class, 'update'])->name('jenis-usaha.update');
        Route::delete('/jenis-usaha/{id}/delete', [JenisUsahaController::class, 'destroy'])->name('jenis-usaha.delete');
        Route::get('/jenis-usaha/{id}/reset-password', [JenisUsahaController::class, 'reset_password'])->name('jenis-usaha.reset-password');
        Route::put('/jenis-usaha/{id}/update-password', [JenisUsahaController::class, 'update_password'])->name('jenis-usaha.update-password');

        Route::get('/kasir', [KasirController::class, 'index'])->name('kasir');
        Route::get('/kasir/{id_jenis_usaha}/create', [KasirController::class, 'create'])->name('kasir.create');
        Route::post('/kasir/{id_jenis_usaha}/store', [KasirController::class, 'store'])->name('kasir.store');
        Route::get('/kasir/{id}/edit', [KasirController::class, 'edit'])->name('kasir.edit');
        Route::put('/kasir/{id}update', [KasirController::class, 'update'])->name('kasir.update');
        Route::delete('/kasir/{id}/delete', [KasirController::class, 'destroy'])->name('kasir.delete');
        Route::get('/kasir/{id}/reset-password', [KasirController::class, 'reset_password'])->name('kasir.reset-password');
        Route::put('/kasir/{id}/update-password', [KasirController::class, 'update_password'])->name('kasir.update-password');

        Route::get('/inventaris-all', [InventarisController::class, 'all'])->name('inventaris-all');
        Route::get('/inventaris-all/print', [InventarisController::class, 'print_all'])->name('inventaris-all.print');

        Route::get('/jenis-usaha/{id}/saldo-omses-profit', [JenisUsahaController::class, 'saldo_omset_profit'])->name('jenis-usaha.saldo-omset-profit');
        Route::get('/jenis-usaha/{id}/data-kas', [JenisUsahaController::class, 'data_kas'])->name('jenis-usaha.data-kas');
        Route::post('/jenis-usaha/{id}/filter-data-kas', [JenisUsahaController::class, 'filter_data_kas'])->name('jenis-usaha.filter-data-kas');
        Route::get('/jenis-usaha/{id}/data-transaksi', [JenisUsahaController::class, 'data_transaksi'])->name('jenis-usaha.data-transaksi');
        Route::post('/jenis-usaha/{id}/filter-data-transaksi', [JenisUsahaController::class, 'filter_data_transaksi'])->name('jenis-usaha.filter-data-transaksi');
    });
});
