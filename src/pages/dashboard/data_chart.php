<?php
include '../../../service/connection.php';

$query = "SELECT 
            DATE_FORMAT(t.transaction_date, '%Y-%m') AS bulan,
            SUM(td.subtotal) AS total_penjualan,
            SUM(dp.price * td.quantity) AS total_modal,
            SUM(td.subtotal) - SUM(dp.price * td.quantity) AS keuntungan
          FROM transactions t
          JOIN transaction_details td ON t.id = td.transaction_id
          JOIN products p ON td.product_id = p.id
          JOIN details_product dp ON p.details_id = dp.id
          GROUP BY DATE_FORMAT(t.transaction_date, '%Y-%m')
          ORDER BY bulan";

$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);