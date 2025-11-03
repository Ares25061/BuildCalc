<?php

class SiteConnectionTester
{
    private $userAgents;
    private $timeout;

    public function __construct()
    {
        $this->timeout = 30;
        $this->userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
        ];
    }

    public function testPopularSites()
    {
        $sites = [
            'lemanapro.ru' => 'https://lemanapro.ru',
            'stroimaterial-moskva.ru' => 'https://stroimaterial-moskva.ru',
            'petrovich.ru' => 'https://petrovich.ru',
            'maxidom.ru' => 'https://www.maxidom.ru',
            'stroylandiya.ru' => 'https://stroylandiya.ru',
            'obi.ru' => 'https://www.obi.ru',
            'vseinstrumenti.ru' => 'https://vseinstrumenti.ru',
            'stroyka.ru' => 'https://stroyka.ru',
            'msk.saturn.net' => 'https://msk.saturn.net',
            'stroymateriyali.ru' => 'https://stroymateriyali.ru',
        ];

        $results = [];

        foreach ($sites as $name => $url) {
            echo "Проверяем {$name}... ";
            $results[$name] = $this->testSite($url);
            echo $results[$name]['success'] ? "✓ Успешно" : "✗ Ошибка";
            echo " ({$results[$name]['time']} сек.)\n";

            if (!$results[$name]['success']) {
                echo "   Ошибка: {$results[$name]['error']}\n";
            }

            // Пауза между запросами
            sleep(2);
        }

        return $results;
    }

    public function testSite($url)
    {
        $startTime = microtime(true);

        try {
            // Вариант 1: cURL (предпочтительный)
            $result = $this->testWithCurl($url);

            // Если cURL не сработал, пробуем file_get_contents с контекстом
            if (!$result['success']) {
                $result = $this->testWithFileGetContents($url);
            }

        } catch (Exception $e) {
            $result = [
                'success' => false,
                'error' => 'Exception: ' . $e->getMessage(),
                'http_code' => 0,
                'time' => round(microtime(true) - $startTime, 2)
            ];
        }

        $result['time'] = round(microtime(true) - $startTime, 2);
        return $result;
    }

    private function testWithCurl($url)
    {
        $ch = curl_init();

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_USERAGENT => $this->getRandomUserAgent(),
            CURLOPT_REFERER => 'https://www.google.com/',
            CURLOPT_ENCODING => 'gzip, deflate',
            CURLOPT_HEADER => true,
            CURLOPT_NOBODY => false,
        ];

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        $totalTime = curl_getinfo($ch, CURLINFO_TOTAL_TIME);

        curl_close($ch);

        $success = ($httpCode === 200 && !empty($response));

        return [
            'success' => $success,
            'error' => $success ? '' : "HTTP: {$httpCode}, cURL: {$error}",
            'http_code' => $httpCode,
            'time' => round($totalTime, 2)
        ];
    }

    private function testWithFileGetContents($url)
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => implode("\r\n", [
                    'User-Agent: ' . $this->getRandomUserAgent(),
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
                    'Accept-Encoding: gzip, deflate',
                    'Connection: keep-alive',
                    'Upgrade-Insecure-Requests: 1',
                    'Referer: https://www.google.com/',
                ]),
                'timeout' => $this->timeout,
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ]);

        $start = microtime(true);
        $content = @file_get_contents($url, false, $context);
        $time = microtime(true) - $start;

        if ($content === false) {
            $error = error_get_last();
            return [
                'success' => false,
                'error' => $error['message'] ?? 'Unknown error',
                'http_code' => 0,
                'time' => round($time, 2)
            ];
        }

        // Получаем HTTP-код из ответа
        $httpCode = 200;
        if (isset($http_response_header[0])) {
            preg_match('/HTTP\/\d\.\d\s+(\d+)/', $http_response_header[0], $matches);
            $httpCode = $matches[1] ?? 200;
        }

        $success = ($httpCode === 200 && !empty($content));

        return [
            'success' => $success,
            'error' => $success ? '' : "HTTP: {$httpCode}",
            'http_code' => $httpCode,
            'time' => round($time, 2)
        ];
    }

    public function getRandomUserAgent()
    {
        return $this->userAgents[array_rand($this->userAgents)];
    }

    public function generateReport($results)
    {
        echo "\n" . str_repeat("=", 70) . "\n";
        echo "ОТЧЕТ О ПРОВЕРКЕ ПОДКЛЮЧЕНИЯ К САЙТАМ\n";
        echo str_repeat("=", 70) . "\n";

        $successful = 0;
        $failed = 0;

        foreach ($results as $name => $result) {
            $status = $result['success'] ? '✓ ДОСТУПЕН' : '✗ НЕДОСТУПЕН';
            $color = $result['success'] ? '32' : '31'; // Зеленый/Красный

            echo sprintf("\033[%sm%-20s %-15s %-6s сек. HTTP: %d\033[0m\n",
                $color,
                $name,
                $status,
                $result['time'],
                $result['http_code']
            );

            if (!$result['success']) {
                echo "   Причина: {$result['error']}\n";
            }

            if ($result['success']) {
                $successful++;
            } else {
                $failed++;
            }
        }

        echo str_repeat("-", 70) . "\n";
        echo "ИТОГО: Успешно: {$successful}, Не удалось: {$failed}\n";

        return [
            'successful' => $successful,
            'failed' => $failed,
            'results' => $results
        ];
    }
}

// Дополнительный класс для расширенной проверки с прокси
class AdvancedSiteTester extends SiteConnectionTester
{
    private $proxies;

    public function setProxies(array $proxies)
    {
        $this->proxies = $proxies;
    }

    public function testWithProxy($url)
    {
        if (empty($this->proxies)) {
            return $this->testSite($url);
        }

        foreach ($this->proxies as $proxy) {
            echo "Пробуем прокси: {$proxy}... ";

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_PROXY => $proxy,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_USERAGENT => $this->getRandomUserAgent(),
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                echo "Успешно!\n";
                return [
                    'success' => true,
                    'http_code' => $httpCode,
                    'proxy' => $proxy
                ];
            }

            echo "Не удалось\n";
        }

        return ['success' => false, 'error' => 'Все прокси не сработали'];
    }
}

// Использование
echo "Запуск проверки подключения к строительным сайтам...\n\n";

$tester = new SiteConnectionTester();
$results = $tester->testPopularSites();
$report = $tester->generateReport($results);

// Сохранение результатов в файл
file_put_contents('site_connection_report.json', json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "\nПодробный отчет сохранен в site_connection_report.json\n";

// Пример использования с прокси (раскомментируйте при необходимости)
/*
$advancedTester = new AdvancedSiteTester();
$advancedTester->setProxies([
    'proxy1.example.com:8080',
    'proxy2.example.com:3128',
]);

$proxyResult = $advancedTester->testWithProxy('https://leroymerlin.ru');
print_r($proxyResult);
*/
