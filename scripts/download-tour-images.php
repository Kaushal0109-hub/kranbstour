<?php
/** One-time script: download tour images. Run: php scripts/download-tour-images.php */

$base = __DIR__ . '/../public/images';
$dirs = ['cities', 'monuments'];
foreach ($dirs as $d) {
    if (! is_dir("{$base}/{$d}")) {
        mkdir("{$base}/{$d}", 0755, true);
    }
}

$images = [
    'cities/hero-main.jpg' => 'https://images.unsplash.com/photo-1564507592333-c60657eea523?auto=format&fit=crop&w=1920&h=1080&q=85',
    'cities/agra-card.jpg' => 'https://images.unsplash.com/photo-1564507592333-c60657eea523?auto=format&fit=crop&w=1200&h=1500&q=85',
    'cities/agra-banner.jpg' => 'https://images.unsplash.com/photo-1523980077198-60824a7b2148?auto=format&fit=crop&w=1200&h=675&q=85',
    'cities/delhi-card.jpg' => 'https://images.unsplash.com/photo-1764113156911-7d2412fabbf8?auto=format&fit=crop&w=1200&h=1500&q=85',
    'cities/delhi-banner.jpg' => 'https://images.unsplash.com/photo-1764113156911-7d2412fabbf8?auto=format&fit=crop&w=1200&h=675&q=85',
    'cities/jaipur-card.jpg' => 'https://images.unsplash.com/photo-1603262110263-fb0112e7cc33?auto=format&fit=crop&w=1200&h=1500&q=85',
    'cities/jaipur-banner.jpg' => 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?auto=format&fit=crop&w=1200&h=675&q=85',
    'cities/varanasi-card.jpg' => 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=1200&h=1500&q=85',
    'cities/varanasi-banner.jpg' => 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=1200&h=675&q=85',
    'cities/fallback.jpg' => 'https://images.unsplash.com/photo-1564507592333-c60657eea523?auto=format&fit=crop&w=800&h=600&q=85',
    'cities/avatar-1.jpg' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&h=120&q=80',
    'cities/avatar-2.jpg' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&h=120&q=80',
    'cities/avatar-3.jpg' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&h=120&q=80',
    'monuments/agra-taj-mahal.jpg' => 'https://images.unsplash.com/photo-1564507592333-c60657eea523?auto=format&fit=crop&w=800&h=600&q=85',
    'monuments/agra-fort.jpg' => 'https://images.unsplash.com/photo-1506462945848-ac8ea6f609cc?auto=format&fit=crop&w=800&h=600&q=85',
    'monuments/agra-fatehpur-sikri.jpg' => 'https://images.unsplash.com/photo-1523980077198-60824a7b2148?auto=format&fit=crop&w=800&h=600&q=85',
    'monuments/agra-baby-taj.jpg' => 'https://images.unsplash.com/photo-1732639839547-8114ca041317?auto=format&fit=crop&w=800&h=600&q=85',
    'monuments/delhi-humayun-tomb.jpg' => 'https://images.unsplash.com/photo-1764113156911-7d2412fabbf8?auto=format&fit=crop&w=800&h=600&q=85',
    'monuments/delhi-red-fort.jpg' => 'https://images.unsplash.com/photo-1764113156911-7d2412fabbf8?auto=format&fit=crop&w=800&h=600&q=85',
    'monuments/delhi-qutub-minar.jpg' => 'https://images.unsplash.com/photo-1603262110263-fb0112e7cc33?auto=format&fit=crop&w=800&h=600&q=85',
    'monuments/delhi-india-gate.jpg' => 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?auto=format&fit=crop&w=800&h=600&q=85',
    'monuments/jaipur-amber-fort.jpg' => 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?auto=format&fit=crop&w=800&h=600&q=85',
    'monuments/jaipur-hawa-mahal.jpg' => 'https://images.unsplash.com/photo-1603262110263-fb0112e7cc33?auto=format&fit=crop&w=800&h=600&q=85',
    'monuments/jaipur-city-palace.jpg' => 'https://images.unsplash.com/photo-1477587458883-471a5ed94245?auto=format&fit=crop&w=800&h=600&q=85',
    'monuments/varanasi-ghats.jpg' => 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=800&h=600&q=85',
    'monuments/varanasi-ganga-aarti.jpg' => 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=800&h=600&q=85',
    'monuments/varanasi-boat-ride.jpg' => 'https://images.unsplash.com/photo-1508009603885-50cf7c579365?auto=format&fit=crop&w=800&h=600&q=85',
    'monuments/golden-triangle.jpg' => 'https://images.unsplash.com/photo-1564507592333-c60657eea523?auto=format&fit=crop&w=1200&h=675&q=85',
];

foreach ($images as $path => $url) {
    $dest = "{$base}/{$path}";
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_USERAGENT => 'Mozilla/5.0',
    ]);
    $data = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code === 200 && $data && strlen($data) > 1000) {
        file_put_contents($dest, $data);
        echo "OK {$path} (" . strlen($data) . " bytes)\n";
    } else {
        echo "FAIL {$path} HTTP {$code}\n";
    }
}

echo "Done.\n";
