<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login')->with('status', 'Please login to continue.');
});

Auth::routes([
    'register' => false,
]);

Route::group(['middleware' => ['auth']], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::group(['middleware' => ['role:admin']], function() {
        // ROUTE PENGATURAN
        Route::view('pengaturan', 'pengaturan.index')->name('pengaturan');
        Route::prefix('pengaturan')->group(function () {
            Route::get('/wa', [App\Http\Controllers\WaController::class, 'index'])->name('pengaturan.wa');
            Route::get('/wa/get-wa-group', [App\Http\Controllers\WaController::class, 'get_group_wa'])->name('pengaturan.wa.get-group-wa');
            Route::patch('/wa/{group_wa}/update', [App\Http\Controllers\WaController::class, 'update'])->name('pengaturan.wa.update');

            Route::get('/akun', [App\Http\Controllers\PengaturanController::class, 'index'])->name('pengaturan.akun');
            Route::post('/akun/store', [App\Http\Controllers\PengaturanController::class, 'store'])->name('pengaturan.akun.store');
            Route::patch('/akun/{akun}/update', [App\Http\Controllers\PengaturanController::class, 'update'])->name('pengaturan.akun.update');
            Route::delete('/akun/{akun}/delete', [App\Http\Controllers\PengaturanController::class, 'destroy'])->name('pengaturan.akun.delete');
        });

        Route::get('/histori-pesan', [App\Http\Controllers\HistoriController::class, 'index'])->name('histori-pesan');
        Route::post('/histori-pesan/resend/{pesanWa}', [App\Http\Controllers\HistoriController::class, 'resend'])->name('histori.resend');
        Route::delete('/histori-pesan/delete-sended', [App\Http\Controllers\HistoriController::class, 'delete_sended'])->name('histori.delete-sended');
        // END ROUTE PENGATURAN

        Route::prefix('legalitas')->group(function(){

            Route::prefix('kategori')->group(function(){
                Route::post('/store', [App\Http\Controllers\LegalitasController::class, 'kategori_store'])->name('legalitas.kategori-store');
                Route::patch('/update/{id}', [App\Http\Controllers\LegalitasController::class, 'kategori_update'])->name('legalitas.kategori-update');
                Route::delete('/destroy/{id}', [App\Http\Controllers\LegalitasController::class, 'kategori_destroy'])->name('legalitas.kategori-destroy');
            });

            Route::get('/', [App\Http\Controllers\LegalitasController::class, 'index'])->name('legalitas');
            Route::post('/store', [App\Http\Controllers\LegalitasController::class, 'store'])->name('legalitas.store');
            Route::patch('/update/{legalitas}', [App\Http\Controllers\LegalitasController::class, 'update'])->name('legalitas.update');
            Route::delete('/destroy/{legalitas}', [App\Http\Controllers\LegalitasController::class, 'destroy'])->name('legalitas.destroy');

            Route::post('/kirim-wa/{legalitas}', [App\Http\Controllers\LegalitasController::class, 'kirim_wa'])->name('legalitas.kirim-wa');

        });

        Route::prefix('dokumen')->group(function(){
            Route::get('/', [App\Http\Controllers\DokumenController::class, 'index'])->name('dokumen');

            Route::prefix('mutasi-rekening')->group(function(){
                Route::get('/', [App\Http\Controllers\DokumenController::class, 'mutasi_rekening'])->name('dokumen.mutasi-rekening');
                Route::post('/store', [App\Http\Controllers\DokumenController::class, 'mutasi_rekening_store'])->name('dokumen.mutasi-rekening.store');
                Route::delete('/destroy/{mutasi}', [App\Http\Controllers\DokumenController::class, 'mutasi_rekening_destroy'])->name('dokumen.mutasi-rekening.destroy');
                Route::post('/kirim-wa/{mutasi}', [App\Http\Controllers\DokumenController::class, 'kirim_wa'])->name('dokumen.mutasi-rekening.kirim-wa');
            });

            Route::prefix('kontrak-tambang')->group(function(){
                Route::get('/', [App\Http\Controllers\DokumenController::class, 'kontrak_tambang'])->name('dokumen.kontrak-tambang');
                Route::post('/store', [App\Http\Controllers\DokumenController::class, 'kontrak_tambang_store'])->name('dokumen.kontrak-tambang.store');
                Route::delete('/destroy/{kontrak_tambang}', [App\Http\Controllers\DokumenController::class, 'kontrak_tambang_destroy'])->name('dokumen.kontrak-tambang.destroy');
                Route::post('/kirim-wa/{kontrak_tambang}', [App\Http\Controllers\DokumenController::class, 'kirim_wa_tambang'])->name('dokumen.kontrak-tambang.kirim-wa');
            });

            Route::prefix('kontrak-vendor')->group(function(){
                Route::get('/', [App\Http\Controllers\DokumenController::class, 'kontrak_vendor'])->name('dokumen.kontrak-vendor');
                Route::post('/store', [App\Http\Controllers\DokumenController::class, 'kontrak_vendor_store'])->name('dokumen.kontrak-vendor.store');
                Route::delete('/destroy/{kontrak_vendor}', [App\Http\Controllers\DokumenController::class, 'kontrak_vendor_destroy'])->name('dokumen.kontrak-vendor.destroy');
                Route::post('/kirim-wa/{kontrak_vendor}', [App\Http\Controllers\DokumenController::class, 'kirim_wa_vendor'])->name('dokumen.kontrak-vendor.kirim-wa');
            });

            Route::prefix('sph')->group(function(){
                Route::get('/', [App\Http\Controllers\DokumenController::class, 'sph'])->name('dokumen.sph');
                Route::post('/store', [App\Http\Controllers\DokumenController::class, 'sph_store'])->name('dokumen.sph.store');
                Route::delete('/destroy/{sph}', [App\Http\Controllers\DokumenController::class, 'sph_destroy'])->name('dokumen.sph.destroy');
                Route::post('/kirim-wa/{sph}', [App\Http\Controllers\DokumenController::class, 'kirim_wa_sph'])->name('dokumen.sph.kirim-wa');
            });
        });

        Route::prefix('company-profile')->group(function(){
            Route::get('/', [App\Http\Controllers\DokumenController::class, 'company_profile'])->name('company-profile');
            Route::post('/store', [App\Http\Controllers\DokumenController::class, 'company_profile_store'])->name('company-profile.store');
            Route::delete('/destroy/{company_profile}', [App\Http\Controllers\DokumenController::class, 'company_profile_destroy'])->name('company-profile.destroy');
            Route::post('/kirim-wa/{company_profile}', [App\Http\Controllers\DokumenController::class, 'kirim_wa_cp'])->name('company-profile.kirim-wa');
        });
    });


        // ROUTE DB
    Route::view('db', 'db.index')->name('db')->middleware('role:admin,user');
    Route::prefix('db')->group(function () {
        Route::group(['middleware' => ['role:admin,user']], function() {
            Route::get('/customer', [App\Http\Controllers\CustomerController::class, 'index'])->name('db.customer');
            Route::patch('/customer/{customer}/update-harga', [App\Http\Controllers\CustomerController::class, 'update_harga'])->name('db.customer.update-harga');
        });
        Route::group(['middleware' => ['role:admin']], function() {
            Route::post('/customer/store', [App\Http\Controllers\CustomerController::class, 'store'])->name('db.customer.store');
            Route::patch('/customer/{customer}/update', [App\Http\Controllers\CustomerController::class, 'update'])->name('db.customer.update');
            Route::delete('/customer/{customer}/delete', [App\Http\Controllers\CustomerController::class, 'destroy'])->name('db.customer.delete');

            Route::get('/investor', [App\Http\Controllers\InvestorController::class, 'index'])->name('db.investor');
            Route::post('/investor/store', [App\Http\Controllers\InvestorController::class, 'store'])->name('db.investor.store');
            Route::patch('/investor/{investor}/update', [App\Http\Controllers\InvestorController::class, 'update'])->name('db.investor.update');
            Route::delete('/investor/{investor}/delete', [App\Http\Controllers\InvestorController::class, 'destroy'])->name('db.investor.delete');

            Route::get('/supplier', [App\Http\Controllers\SupplierController::class, 'index'])->name('db.supplier');
            Route::post('/supplier/store', [App\Http\Controllers\SupplierController::class, 'store'])->name('db.supplier.store');
            Route::patch('/supplier/{supplier}/update', [App\Http\Controllers\SupplierController::class, 'update'])->name('db.supplier.update');
            Route::delete('/supplier/{supplier}/delete', [App\Http\Controllers\SupplierController::class, 'destroy'])->name('db.supplier.delete');

            Route::get('/rekening', [App\Http\Controllers\RekeningController::class, 'index'])->name('db.rekening');
            Route::patch('/rekening/{rekening}/update', [App\Http\Controllers\RekeningController::class, 'update'])->name('db.rekening.update');
        });
    });



    Route::group(['middleware' => ['role:admin,user,investor']], function() {
        Route::get('rekap', [App\Http\Controllers\RekapController::class, 'index'])->name('rekap');
        Route::prefix('rekap')->group(function() {
            Route::get('/kas-besar', [App\Http\Controllers\RekapController::class, 'kas_besar'])->name('rekap.kas-besar');
            Route::get('/kas-besar/print/{bulan}/{tahun}', [App\Http\Controllers\RekapController::class, 'kas_besar_print'])->name('rekap.kas-besar.print');
            Route::get('/kas-besar/detail-tagihan/{invoice}', [App\Http\Controllers\RekapController::class, 'detail_tagihan'])->name('rekap.kas-besar.detail-tagihan');
            Route::get('/kas-besar/detail-bayar/{invoice}', [App\Http\Controllers\RekapController::class, 'detail_bayar'])->name('rekap.kas-besar.detail-bayar');

            Route::get('/kas-supplier', [App\Http\Controllers\RekapController::class, 'kas_supplier'])->name('rekap.kas-supplier');
            Route::get('/kas-supplier/print/{bulan}/{tahun}/{supplier}', [App\Http\Controllers\RekapController::class, 'kas_supplier_print'])->name('rekap.kas-supplier.print');
            Route::get('/kas-supplier/detail-bayar/{invoice}', [App\Http\Controllers\RekapController::class, 'detail_bayar_supplier'])->name('rekap.kas-supplier.detail-bayar');
            Route::get('/kas-supplier/detail-bayar/print/{invoice}', [App\Http\Controllers\RekapController::class, 'detail_bayar_supplier_pdf'])->name('rekap.kas-supplier.detail-bayar.print');

            Route::get('/invoice/{customer}', [App\Http\Controllers\RekapController::class, 'rekap_invoice'])->name('rekap.invoice');

            Route::get('/statistik/{customer}', [App\Http\Controllers\StatistikController::class, 'index'])->name('statistik.index');
            Route::get('/statistik/{customer}/print', [App\Http\Controllers\StatistikController::class, 'print'])->name('statistik.print');
        });
    });

    // END ROUTE REKAP
    Route::group(['middleware' => ['role:admin,user']], function() {
        Route::get('/billing', [App\Http\Controllers\BillingController::class, 'index'])->name('billing');
        Route::prefix('billing')->group(function() {

            Route::get('/form-ppn', [App\Http\Controllers\FormPpnController::class, 'index'])->name('form-ppn');
            Route::post('/form-ppn/bayar/{invoice}', [App\Http\Controllers\FormPpnController::class, 'bayar'])->name('form-ppn.bayar');

            Route::get('/form-deposit/masuk', [App\Http\Controllers\FormDepositController::class, 'masuk'])->name('form-deposit.masuk');
            Route::post('/form-deposit/masuk/store', [App\Http\Controllers\FormDepositController::class, 'masuk_store'])->name('form-deposit.masuk.store');
            Route::get('/form-deposit/keluar', [App\Http\Controllers\FormDepositController::class, 'keluar'])->name('form-deposit.keluar');
            Route::post('/form-deposit/keluar/store', [App\Http\Controllers\FormDepositController::class, 'keluar_store'])->name('form-deposit.keluar.store');

            Route::get('billing/deviden', [App\Http\Controllers\FormDevidenController::class, 'index'])->name('billing.deviden.index');
            Route::post('billing/deviden/store', [App\Http\Controllers\FormDevidenController::class, 'store'])->name('billing.deviden.store');

            Route::get('/form-lain/masuk', [App\Http\Controllers\FormLainController::class, 'masuk'])->name('form-lain.masuk');
            Route::post('/form-lain/masuk/store', [App\Http\Controllers\FormLainController::class, 'masuk_store'])->name('form-lain.masuk.store');
            Route::get('/form-lain/keluar', [App\Http\Controllers\FormLainController::class, 'keluar'])->name('form-lain.keluar');
            Route::post('/form-lain/keluar/store', [App\Http\Controllers\FormLainController::class, 'keluar_store'])->name('form-lain.keluar.store');

            Route::get('/form-supplier/titipan', [App\Http\Controllers\FormSupplierController::class, 'titipan'])->name('form-supplier.titipan');
            Route::post('/form-supplier/titipan/store', [App\Http\Controllers\FormSupplierController::class, 'titipan_store'])->name('form-supplier.titipan-store');
            Route::get('/form-supplier/get-rek-supplier/{id}', [App\Http\Controllers\FormSupplierController::class, 'getRekSupplier'])->name('form-supplier.get-rek-supplier');
            Route::get('/form-supplier/pengembalian', [App\Http\Controllers\FormSupplierController::class, 'pengembalian'])->name('form-supplier.pengembalian');
            Route::post('/fomr-supplier/pengembalian/store', [App\Http\Controllers\FormSupplierController::class, 'pengembalian_store'])->name('form-supplier.pengembalian-store');

            Route::get('/form-transaksi/tambah/{customer}', [App\Http\Controllers\FormTransaksiController::class, 'tambah'])->name('form-transaksi.tambah');
            Route::post('/form-transaksi/tambah-store', [App\Http\Controllers\FormTransaksiController::class, 'tambah_store'])->name('form-transaksi.tambah-store');
            Route::patch('/form-transaksi/edit/{transaksi}', [App\Http\Controllers\FormTransaksiController::class, 'edit_store'])->name('form-transaksi.edit_storebab');
            Route::delete('/form-transaksi/delete/{transaksi}', [App\Http\Controllers\FormTransaksiController::class, 'delete'])->name('form-transaksi.delete');
            Route::post('/form-transaksi/lanjutkan/{customer}', [App\Http\Controllers\FormTransaksiController::class, 'lanjutkan'])->name('form-transaksi.lanjutkan');

            Route::prefix('nota-tagihan')->group(function(){
                Route::get('/{customer}', [App\Http\Controllers\NotaTagihanController::class, 'index'])->name('nota-tagihan.index');
                Route::patch('/edit/{transaksi}', [App\Http\Controllers\NotaTagihanController::class, 'edit_store'])->name('nota-tagihan.edit_store');
                Route::post('/{customer}/cut-off', [App\Http\Controllers\NotaTagihanController::class, 'cutoff'])->name('nota-tagihan.cutoff');

                Route::prefix('keranjang')->group(function(){
                    Route::get('/{customer}', [App\Http\Controllers\NotaTagihanController::class, 'keranjang'])->name('nota-tagihan.keranjang');
                    Route::post('/delete/{transaksi}', [App\Http\Controllers\NotaTagihanController::class, 'keranjang_delete'])->name('nota-tagihan.keranjang.delete');
                    Route::post('/lanjutkan/{customer}', [App\Http\Controllers\NotaTagihanController::class, 'keranjang_lanjut'])->name('nota-tagihan.keranjang.lanjutkan');
                });
            });



            Route::get('/nota-bayar', [App\Http\Controllers\NotaBayarController::class, 'index'])->name('nota-bayar.index');
            Route::post('/nota-bayar/{supplier}/cutoff', [App\Http\Controllers\NotaBayarController::class, 'cutoff'])->name('nota-bayar.cutoff');

            Route::prefix('invoice-ppn')->group(function(){
                Route::get('/{customer}', [App\Http\Controllers\InvoicePpnController::class, 'index'])->name('invoice-ppn.index');
                Route::post('/{customer}/cutoff', [App\Http\Controllers\InvoicePpnController::class, 'cutoff'])->name('invoice-ppn.cutoff');

                Route::prefix('keranjang')->group(function(){
                    Route::get('/{customer}', [App\Http\Controllers\InvoicePpnController::class, 'keranjang'])->name('invoice-ppn.keranjang');
                    Route::post('/delete/{transaksi}', [App\Http\Controllers\InvoicePpnController::class, 'keranjang_delete'])->name('invoice-ppn.keranjang.delete');
                    Route::post('/lanjutkan/{customer}', [App\Http\Controllers\InvoicePpnController::class, 'keranjang_lanjut'])->name('invoice-ppn.keranjang.lanjutkan');
                });
            });



        });

        Route::prefix('pajak')->group(function(){
            Route::get('/', [App\Http\Controllers\PajakController::class, 'index'])->name('pajak.index');
            Route::get('/detail-tagihan/{invoice}', [App\Http\Controllers\PajakController::class, 'detail_tagihan'])->name('pajak.detail-tagihan');
            Route::get('/detail-ppn/{invoice}', [App\Http\Controllers\PajakController::class, 'detail_ppn'])->name('pajak.detail-ppn');

            Route::prefix('rekap-ppn')->group(function(){
                Route::get('/', [App\Http\Controllers\PajakController::class, 'rekap_ppn'])->name('pajak.rekap-ppn');
                Route::get('/masukan/{rekapPpn}', [App\Http\Controllers\PajakController::class, 'rekap_ppn_masukan_detail'])->name('pajak.rekap-ppn.masukan');
                Route::get('/keluaran/{rekapPpn}', [App\Http\Controllers\PajakController::class, 'rekap_ppn_keluaran_detail'])->name('pajak.rekap-ppn.keluaran');
            });

            Route::prefix('ppn-masukan')->group(function(){
                Route::get('/', [App\Http\Controllers\PajakController::class, 'ppn_masukan'])->name('pajak.ppn-masukan');
                Route::patch('/store-faktur/{ppnMasukan}', [App\Http\Controllers\PajakController::class, 'ppn_masukan_store_faktur'])->name('pajak.ppn-masukan.store-faktur');
                Route::post('/keranjang-store', [App\Http\Controllers\PajakController::class, 'ppn_masukan_keranjang_store'])->name('pajak.ppn-masukan.keranjang-store');
                Route::post('/keranjang-destroy/{ppnMasukan}', [App\Http\Controllers\PajakController::class, 'ppn_masukan_keranjang_destroy'])->name('pajak.ppn-masukan.keranjang-destroy');
                Route::post('/keranjang-lanjut', [App\Http\Controllers\PajakController::class, 'ppn_masukan_keranjang_lanjut'])->name('pajak.ppn-masukan.keranjang-lanjut');
            });

            Route::prefix('ppn-keluaran')->group(function(){
                Route::get('/', [App\Http\Controllers\PajakController::class, 'ppn_keluaran'])->name('pajak.ppn-keluaran');
                Route::post('/expired/{ppnKeluaran}', [App\Http\Controllers\PajakController::class, 'ppn_keluaran_expired'])->name('pajak.ppn-keluaran.expired');
                Route::patch('/store-faktur/{ppnKeluaran}', [App\Http\Controllers\PajakController::class, 'ppn_keluaran_store_faktur'])->name('pajak.ppn-keluaran.store-faktur');
                Route::get('/keranjang', [App\Http\Controllers\PajakController::class, 'ppn_keluaran_keranjang'])->name('pajak.ppn-keluaran.keranjang');
                Route::post('/keranjang-store', [App\Http\Controllers\PajakController::class, 'ppn_keluaran_keranjang_store'])->name('pajak.ppn-keluaran.keranjang-store');
                Route::post('/keranjang-destroy/{ppnKeluaran}', [App\Http\Controllers\PajakController::class, 'ppn_keluaran_keranjang_destroy'])->name('pajak.ppn-keluaran.keranjang-destroy');
                Route::post('/keranjang-lanjut', [App\Http\Controllers\PajakController::class, 'ppn_keluaran_keranjang_lanjut'])->name('pajak.ppn-keluaran.keranjang-lanjut');
            });
        });

    });

    Route::group(['middleware' => ['role:supplier']], function() {
        Route::get('/kas-per-supplier', [App\Http\Controllers\KasPerSupplierController::class, 'index'])->name('kas-per-supplier');
        Route::get('/kas-per-supplier/print/{bulan}/{tahun}', [App\Http\Controllers\KasPerSupplierController::class, 'print'])->name('kas-per-supplier.print');
        Route::get('/kas-per-supplier/detail-bayar/{invoice}', [App\Http\Controllers\KasPerSupplierController::class, 'detail_bayar'])->name('kas-per-supplier.detail-bayar');
        Route::get('/kas-per-supplier/detail-bayar/print/{invoice}', [App\Http\Controllers\KasPerSupplierController::class, 'detail_bayar_print'])->name('kas-per-supplier.detail-bayar.print');
    });
});
