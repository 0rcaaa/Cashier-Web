<?php
include '../../../service/connection.php';

$query = "SELECT 
            DATE_FORMAT(t.transaction_date, '%Y-%m') AS bulan,
            SUM(td.subtotal) AS total_penjualan,
            SUM(p.price * td.quantity) AS total_modal,
            SUM(td.subtotal) - SUM(p.price * td.quantity) AS keuntungan
          FROM transactions t
          JOIN transaction_details td ON t.id = td.transaction_id
          JOIN products p ON td.product_id = p.id
          GROUP BY DATE_FORMAT(t.transaction_date, '%Y-%m')
          ORDER BY bulan";

$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = [
      "bulan" => $row["bulan"],
      "total_penjualan" => (float) $row["total_penjualan"],
      "total_modal" => (float) $row["total_modal"],
      "keuntungan" => (float) $row["keuntungan"]
  ];
}

echo json_encode($data);