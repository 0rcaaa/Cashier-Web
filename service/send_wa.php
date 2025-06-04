<?php
include 'connection.php';

$data = json_decode(file_get_contents('php://input'));
$token = "pvrEpSfNdsSyGbnSRZP2"; // Ganti dengan token Fonnte milikmu

function KirimFonnte($token, $data, $conn) {
    // Validasi input
    if (!isset($data->target) || !isset($data->message)) {
        echo json_encode(['status' => 'fail', 'message' => 'Data target atau message tidak lengkap']);
        return;
    }

    $phone = $data->target;

    // Validasi apakah nomor ada di database
    $stmt = $conn->prepare("SELECT * FROM members WHERE phone = ? LIMIT 1");
    $stmt->bind_param('s', $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows < 1) {
        echo json_encode(['status' => 'fail', 'message' => 'Nomor telepon tidak terdaftar']);
        return;
    }

    // Kirim ke API Fonnte
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => array(
            'target'  => $phone,
            'message' => $data->message,
        ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: ' . $token
        )
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        echo json_encode(['status' => 'fail', 'message' => 'Curl error: ' . $err]);
    } else {
        echo json_encode(['status' => 'success', 'message' => 'Invoice berhasil dikirim', 'response' => json_decode($response)]);
    }
}

KirimFonnte($token, $data, $conn);