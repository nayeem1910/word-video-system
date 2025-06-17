<?php
function getImageFromPexels($query) {
    $apiKey = "fOuF5nlf1UEsPIEuAeOjNeXpGXZAMCoxJWwNvyMkCyLWB8Oku5LZ4dpQ";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.pexels.com/v1/search?query=" . urlencode($query) . "&per_page=1");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: $apiKey"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    return $data['photos'][0]['src']['landscape'] ?? null;
}
?>