<?php
$uri = 'http://127.0.0.1:8000/api/trees';
$data = [
    'scientificName' => 'TestTree',
    'description' => 'desc',
    'lifespanMin' => '1',
    'lifespanMax' => '2',
    'heightMin' => '1',
    'heightMax' => '2',
    'familyName' => 'Fam',
    'genus' => 'Genus',
    'species' => 'Species',
    'growthRate' => 'Fast',
    'status' => true,
    'localNames' => [[ 'language' => 'en', 'localName' => 'Test' ]],
    'images' => [[ 'imageUrl' => 'http://example.com/img.jpg', 'imageType' => 'photo' ]],
];
$options = [
    'http' => [
        'header'  => "Content-Type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
        'ignore_errors' => true,
        'timeout' => 10,
    ],
];
$context = stream_context_create($options);
$response = @file_get_contents($uri, false, $context);
$statusLine = isset($http_response_header[0]) ? $http_response_header[0] : 'No response header';
echo "HTTP: " . $statusLine . PHP_EOL;
echo "Response body:\n";
echo ($response === false ? "<no body>\n" : $response . "\n");

$logFiles = [
    __DIR__.'/var/log/dev.log',
    __DIR__.'/var/log/prod.log',
    __DIR__.'/var/log/dev.log.1',
];
echo "\n--- Last log lines (if present) ---\n";
foreach ($logFiles as $file) {
    if (file_exists($file)) {
        echo "==> $file\n";
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $tail = array_slice($lines, -200);
        echo implode("\n", $tail) . "\n";
        break;
    }
}
