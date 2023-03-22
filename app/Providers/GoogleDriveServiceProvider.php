<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;

class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // \Storage::extend('google', function ($app, $config) {
        //     $client = new \Google_Client();
        //     $client->setClientId($config['clientId']);
        //     $client->setClientSecret($config['clientSecret']);
        //     $client->refreshToken($config['refreshToken']);

        //     $service = new \Google\Service\Drive($client);

        //     $adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, $config['folderId']);

        //     return new FilesystemAdapter(
        //                                  new Filesystem($adapter, $config),
        //                                  $adapter,
        //                                  $config
        //                                   );

            

        // });

        try {
            \Storage::extend('google', function($app, $config) {
                $options = [];

                if (!empty($config['teamDriveId'] ?? null)) {
                    $options['teamDriveId'] = $config['teamDriveId'];
                }

                $client = new \Google\Client();
                $client->setClientId($config['clientId']);
                $client->setClientSecret($config['clientSecret']);
                $client->refreshToken($config['refreshToken']);

                $service = new \Google\Service\Drive($client);
                $adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, $config['folderId'] ?? '/', $options);
                $driver = new \League\Flysystem\Filesystem($adapter);

                return new \Illuminate\Filesystem\FilesystemAdapter($driver, $adapter);
            });
        } catch(\Exception $e) {
            // your exception handling logic
        }
    }
}

// $adapter2 = new \Masbug\Flysystem\GoogleDriveAdapter(
//     $service,
//     'My_App_Root',
//     [
//         'useDisplayPaths' => true, /* this is the default */

//         /* These are global parameters sent to server along with per API parameters. Please see https://cloud.google.com/apis/docs/system-parameters for more info. */
//         'parameters' => [
//             /* This example tells the remote server to perform quota checks per unique user id. Otherwise the quota would be per client IP. */
//             'quotaUser' => (string)$some_unique_per_user_id
//         ]
//     ]
// );
//return new \League\Flysystem\Filesystem($adapter);