<?php
function ambil_data() {
    if (!file_exists('tugas_data.json')) return [];
    $json = file_get_contents('tugas_data.json');
    return json_decode($json, true);
}

function simpan_data($data) {
    file_put_contents('tugas_data.json', json_encode($data, JSON_PRETTY_PRINT));
}
