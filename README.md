# Starter Laravel

Gunakan source code repo ini untuk memulai proyek baru menggunakan Laravel

## Related Rules & Documentation

Sebelum memulai mengerjakan proyek, Baca terlebih dahulu kisi-kisi SOP Backend di bawah ini :

1. [IDE](https://docs.google.com/document/d/1BvLu9TWiRTOtIMfv32S7pCC7HKZhKHrzIYSjQtiq5s0/edit#heading=h.pzonoej5v564)
2. [General Standard](https://docs.google.com/document/d/1nTVhMkoPdA8oe3D8Np5VRFNw6UGe29ke5kxDjSRorg0/edit#heading=h.pzonoej5v564)
3. [Rest API](https://docs.google.com/document/d/18xUwbPlbztj58MQxQPfQ7UDobU2XIVHyTL9U7RFZzi4/edit)
4. [Database](https://docs.google.com/document/d/1iobAeFVNVtvzciSmNy47XwJzZmF6iayYF7iJAZnlVeg/edit#heading=h.mmnjwrhsbzn6)
5. [Laravel Basic](https://docs.google.com/document/d/1mHmumYMtJrlw9YLrocozwQkvVtB8xBpiheEijXiOozU/edit#heading=h.pzonoej5v564)

## Mengaktifkan Logger Grafana

Aktifkan logger Grafana Loki pada **Server Production** & **Server Testing** untuk membantu proses debugging. 

Jangan aktifkan logger Grafana Loki ketika development pada device lokal agar tidak terlalu memenuhi logs di Grafana.

1. Install custom library monolog-loki dengan perintah
```
composer require wahyuagung/monolog-loki
```
2. Buka file **.env** dan tambahkan konfigurasi berikut
```
LOKI_ENTRYPOINT="http://103.157.96.153:3100"
LOKI_AUTH_BASIC_USER=
LOKI_AUTH_BASIC_PASSWORD=
LOKI_SYSTEM_NAME=null
LOKI_CONTEXT_PREFIX="context_"
LOKI_EXTRA_PREFIX=
```
3. Ganti konfigurasi **LOG_CHANNEL** menjadi **loki** dan **LOG_LEVEL** menjadi **debug**
```
LOG_CHANNEL=loki
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug
```
4. Sesuaikan konfigurasi **APP_NAME** dengan nama proyek yang dikerjakan dan tambahkan Flags server Testing / Production sesuai lokasinya, Hal ini berguna untuk mempermudah ketika melakukan filter logs di Grafana

5. Buka file **config/logging.php**, pada bagian channel tambahkan konfigurasi untuk grafana loki
```
'loki' => [
    'driver'    => 'custom',
    'level'     => env('LOG_LEVEL', 'debug'),
    'handler'   => Wahyuagung\MonologLoki\LokiHandler::class,
    'formatter' => Wahyuagung\MonologLoki\LokiFormatter::class,
    'via'       => Wahyuagung\MonologLoki\LokiNoFailureHandler::class,
    'formatter_with' => [
        'labels' => ['project' => env('APP_NAME', '')],
        'context' => [],
        'systemName' => env('LOKI_SYSTEM_NAME', ''),
        'extraPrefix' => env('LOKI_EXTRA_PREFIX', ''),
        'contextPrefix' => env('LOKI_CONTEXT_PREFIX', '')
    ],
    'handler_with'   => [
        'apiConfig'  => [
            'entrypoint'  => env('LOKI_ENTRYPOINT'),
            'context'     => [],
            'labels'      => [],
            'client_name' => '',
            'auth' => [
                'basic' => [
                    env('LOKI_AUTH_BASIC_USER', ''),
                    env('LOKI_AUTH_BASIC_PASSWORD', '')
                ],
            ]
        ],
    ],
],
```
Apabila mengalami kesulitan, silahkan cek pada file [config/logging.php](https://gitlab.com/dev-landa-system/starter-laravel/-/blob/main/config/logging.php#L129)
