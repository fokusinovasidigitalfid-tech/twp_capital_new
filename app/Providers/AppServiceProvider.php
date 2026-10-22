<?php

namespace App\Providers;

use Illuminate\Database\Events\ConnectionEstablished;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // /admin dan /tenant menambah prefix sendiri (App\Http\Middleware\Section)
        URL::forceRootUrl(\App\Support\Portal::base());
		// https dipaksa kecuali diakses dari localhost
		if (!in_array(request()->getHost(), ['localhost', '127.0.0.1'])) {
			URL::forceScheme('https');
		}

        // Kode lama (dari MySQL) mengirim tanggal sebagai 'Y-m-d H:i:s'. Setting bahasa SQL Server
        // membacanya sebagai tahun-hari-bulan, jadi format tanggal dipaksa ymd di setiap koneksi.
        Event::listen(ConnectionEstablished::class, function (ConnectionEstablished $event) {
            if ($event->connection->getDriverName() === 'sqlsrv') {
                $event->connection->unprepared('SET DATEFORMAT ymd');
            }
        });
    }
}
