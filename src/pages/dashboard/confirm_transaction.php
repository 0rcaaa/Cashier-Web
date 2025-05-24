<?php
session_start();
require '../../../service/utility.php';
require '../../../service/connection.php';


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


if (!isset($_SESSION['loggedIn'])) {
  header('location: ../auth/index.php');
  exit();
}

function get_cart($order_id, $conn)
{
  // Ambil data order & member dulu
  $stmt = $conn->prepare("SELECT 
      o.total_items, o.total_price,
      m.id AS member_id, m.name AS nama_pelanggan, m.points AS member_points
    FROM orders o
    LEFT JOIN members m ON o.member_id = m.id
    WHERE o.id = ?
  ");
  $stmt->bind_param("s", $order_id);
  $stmt->execute();
  $header_result = $stmt->get_result();
  $header = $header_result->fetch_assoc();

  if (!$header) {
    return ['error' => 'Order tidak ditemukan'];
  }

  // Ambil item di keranjang
  $stmt_items = $conn->prepare("SELECT 
      p.name AS product_name, p.uniqcode AS qrcode, p.price, od.qty
    FROM order_details od
    JOIN products p ON od.product_fid = p.id
    WHERE od.order_fid = ?
  ");
  $stmt_items->bind_param("s", $order_id);
  $stmt_items->execute();
  $items_result = $stmt_items->get_result();

  $cart = [];
  while ($row = $items_result->fetch_assoc()) {
    $cart[] = [
      'name' => $row['product_name'],
      'qrcode' => $row['qrcode'],
      'price' => (int)$row['price'],
      'qty' => (int)$row['qty']
    ];
  }

  // Ambil diskon valid jika member punya cukup poin
  $discounts = [];
  if (!empty($header['member_points'])) {
    $stmt_discount = $conn->prepare("SELECT id, title, points_required, percentage FROM discounts WHERE points_required <= ?");
    $stmt_discount->bind_param("i", $header['member_points']);
    $stmt_discount->execute();
    $discounts = $stmt_discount->get_result()->fetch_all(MYSQLI_ASSOC);
  }

  return [
    'order' => [
      'total_items' => (int)$header['total_items'],
      'total_price' => (int)$header['total_price'],
      'nama_pelanggan' => $header['nama_pelanggan'] ?? '-',
      'member_id' => (int)($header['member_id']),
      'member_points' => (int)($header['member_points'] ?? 0),
    ],
    'cart' => $cart,
    'discounts' => $discounts
  ];
}


if (isset($_GET['order_id'])) {
  $order_id = $_GET['order_id'];
  $response = get_cart($order_id, $conn);
  $cart = $response['cart'];
  $order = $response['order'];
  $discounts = $response['discounts'];
} else {
  header('location: transaction.php');
  exit();
}

function format_rupiah($angka)
{
  return number_format($angka, 0, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
    Transaction - Confirm Transaction | Kasir
  </title>
  <link href="../../assets/images/logo/logo_white.png" rel="icon">
  <link href="<?= base_url() ?>/src/css/output.css" rel="stylesheet">
</head>

<body
  x-data="{ page: 'transaction', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
  x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
  :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}">
  <!-- ===== Preloader Start ===== -->
  <?php include '../../components/preloader.html'; ?>
  <!-- ===== Preloader End ===== -->

  <!-- ===== Page Wrapper Start ===== -->
  <div class="flex h-screen overflow-hidden">
    <!-- ===== Sidebar Start ===== -->
    <?php include('../../components/sidebar.html'); ?>
    <!-- ===== Sidebar End ===== -->

    <!-- ===== Content Area Start ===== -->
    <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
      <!-- ===== Header Start ===== -->
      <?php include('../../components/header.html'); ?>
      <!-- ===== Header End ===== -->

      <!-- ===== Main Content Start ===== -->
      <main>
        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
          <div
            class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
              Transaction
            </h2>

            <nav>
              <ol class="flex items-center gap-2">
                <li>
                  <a class="font-medium hover:text-meta-5" href="index.php">Dashboard /</a>
                </li>
                <li>
                  <a class="font-medium hover:text-meta-5" href="transaction.php">Transaction /</a>
                </li>
                <li class="font-medium text-primary">Confirm</li>
              </ol>
            </nav>
          </div>

          <!-- table items -->
          <div class="container mx-auto p-6">
            <div class="bg-white dark:bg-boxdark rounded-lg shadow p-6">
              <h2 class="text-2xl font-semibold mb-4 dark:text-white">Cart Details</h2>

              <?php if (empty($cart)): ?>
                <p class="text-red-500">Cart is empty.</p>
              <?php else: ?>
                <table class="w-full table-auto mb-6">
                  <thead>
                    <tr class="bg-gray-200 dark:bg-meta-4">
                      <th class="px-4 py-2 text-left">Product</th>
                      <th class="px-4 py-2 text-center">UID</th>
                      <th class="px-4 py-2 text-center">Price</th>
                      <th class="px-4 py-2 text-center">Quantity</th>
                      <th class="px-4 py-2 text-center">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $subtotal = 0;
                    $total_items = 0;
                    foreach ($cart as $item):
                      $total = $item['price'] * $item['qty'];
                      $subtotal += $total;
                      $total_items += $item['qty'];
                    ?>
                      <tr class="border-b dark:border-strokedark">
                        <td class="px-4 py-2 dark:text-white"><?= htmlspecialchars($item['name']) ?></td>
                        <td class="px-4 py-2 text-center dark:text-white"><?= htmlspecialchars($item['qrcode']) ?></td>
                        <td class="px-4 py-2 text-center dark:text-white">IDR <?= format_rupiah($item['price']) ?></td>
                        <td class="px-4 py-2 text-center dark:text-white"><?= $item['qty'] ?></td>
                        <td class="px-4 py-2 text-center dark:text-white">IDR <?= format_rupiah($total) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>

                <div class="bg-gray-100 dark:bg-meta-4 p-4 rounded-lg mb-4">
                  <h3 class="text-lg font-semibold mb-2 dark:text-white">Order Summary</h3>
                  <p class="dark:text-white">Items Total: <strong><?= $total_items ?></strong></p>
                  <p class="dark:text-white">Subtotal: <strong>IDR <?= format_rupiah($subtotal) ?></strong></p>
                  <?php if ($order['member_id']!=0): ?>
                  <p class="dark:text-white">Member: <strong><?= htmlspecialchars($order['nama_pelanggan']) ?></strong></p>
                  <p class="dark:text-white">Available Points: <strong><?= $order['member_points'] ?></strong></p>

                  <?php if (!empty($discounts)): ?>
                    <label for="discount_option" class="block mt-3 mb-1 dark:text-white">Apply Discount:</label>
                    <select name="discount" id="discount_option" class="w-full rounded border border-stroke px-3 py-2 dark:bg-meta-4 dark:text-white">
                      <option value="0|0">Tidak pakai diskon</option>
                      <?php foreach ($discounts as $d): ?>
                        <option value="<?= $d['percentage'] ?>|<?= $d['id'] ?>">
                          <?= $d['title'] ?> - <?= $d['percentage'] ?>% (<?= $d['points_required'] ?> points)
                        </option>
                      <?php endforeach; ?>
                    </select>
                  <?php endif; ?>

                  <?php endif; ?>
                  <p class="pt-1 dark:text-white">Total after Discount: IDR <span id="finalTotal"><?= format_rupiah($subtotal) ?></span></p>

                  <hr class="my-4">

                  <input class="w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-gray-500 dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                    name="cash" id="cash" type="text" placeholder="Masukkan jumlah cash" inputmode="numeric" pattern="[0-9]*" oninput="calculateExchange()" />
                  <p class="pt-1 dark:text-white">Exchange : IDR <span id="exchange">0</span></p>
                </div>

                <div class="mt-4 flex justify-end">
                  <button onclick="confirmPayment()" class="bg-primary text-white px-6 py-2 rounded hover:bg-meta-4 mr-2">Confirm Payment</button>
                  <a href="transaction.php" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-700">Cancel</a>
                </div>
              <?php endif; ?>
            </div>

          </div>
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
    <!-- ===== Content Area End ===== -->
  </div>
  <!-- ===== Page Wrapper End ===== -->
  <script defer src="../../js/bundle.js"></script>
<script>
  const cashInput = document.getElementById('cash');
  const exchangeDisplay = document.getElementById('exchange');
  const discountSelect = document.getElementById('discount_option');
  const finalTotalDisplay = document.getElementById('finalTotal');
  const subtotal = <?= (int)$subtotal ?>;

  // Format angka ke Rupiah
  function formatRupiah(angka) {
    const number_string = Math.abs(angka).toString().replace(/[^,\d]/g, '');
    const split = number_string.split(',');
    const sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    const ribuan = split[0].substr(sisa).match(/\d{3}/g);

    if (ribuan) {
      rupiah += (sisa ? '.' : '') + ribuan.join('.');
    }

    return (angka < 0 ? '-' : '') + (split[1] !== undefined ? rupiah + ',' + split[1] : rupiah);
  }

  // Ambil angka dari format Rupiah
  function parseRupiah(str) {
    return parseInt(str.replace(/\./g, '').replace(',', '')) || 0;
  }

  // Hitung diskon, total akhir, dan kembalian
  function calculateExchange() {
    const cash = parseRupiah(cashInput.value);
    const [discountPercentage] = (discountSelect?.value || "0|0").split('|').map(Number);
    const discountValue = Math.floor((discountPercentage / 100)* subtotal) ;
    const finalTotal = subtotal - discountValue;
    const exchange = cash - finalTotal;

    finalTotalDisplay.textContent = formatRupiah(finalTotal);
    exchangeDisplay.textContent = formatRupiah(exchange > 0 ? exchange : exchange);
  }

  // Saat input cash diubah
  cashInput.addEventListener('input', function () {
    const angka = parseRupiah(this.value);
    this.value = formatRupiah(angka);
    calculateExchange();
  });

  // Saat opsi diskon berubah
  if (discountSelect) {
    discountSelect.addEventListener('change', calculateExchange);
  }

  // Submit transaksi
  function confirmPayment() {
    const cashValue = parseRupiah(cashInput.value);
    const [discountValue, discountId] = (discountSelect?.value || "0|0").split('|').map(Number);
    const finalTotal = subtotal - discountValue;

    if (cashValue < finalTotal) {
      alert('Jumlah cash kurang dari total belanja.');
      return;
    }

    fetch('<?= base_url() ?>/service/auth.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        action: 'transaction',
        order_id: <?= json_encode($order_id) ?>,
        cash: cashValue,
        discount: discountId,
        method: 'cash'
      })
    })
    .then(response => response.json())
    .then(data => {
      alert('Payment successful!');
      window.location.href = `transaction_details.php?order=<?= $order_id ?>`;
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Payment failed. Please try again.');
    });
  }
</script>
</body>

</html>