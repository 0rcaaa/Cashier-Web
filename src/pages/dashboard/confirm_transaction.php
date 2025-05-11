<?php
session_start();
require '../../../service/utility.php';
require '../../../service/connection.php';

if (!isset($_SESSION['loggedIn'])) {
  header('location: ../auth/index.php');
  exit();
}

function get_cart($order_id, $conn)
{
  $stmt = $conn->prepare("
    SELECT o.total_items, o.total_price, m.name AS nama_pelanggan, 
           p.name AS product_name, p.uniqcode AS qrcode, p.price, od.qty
    FROM orders o
    JOIN order_details od ON o.id = od.order_fid
    JOIN products p ON od.product_fid = p.id
    LEFT JOIN members m ON o.member_id = m.id
    WHERE o.id = ?
  ");
  $stmt->bind_param("s", $order_id);
  $stmt->execute();
  $result = $stmt->get_result();

  $cart = [];
  while ($row = $result->fetch_assoc()) {
    $cart[] = [
      'name' => $row['product_name'],
      'qrcode' => $row['qrcode'],
      'price' => (int)$row['price'],
      'qty' => (int)$row['qty']
    ];
  }

  return $cart;
}

if (isset($_GET['order_id'])) {
  $order_id = $_GET['order_id'];
  $cart = get_cart($order_id, $conn);
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
                  <hr class="my-5">

                  <input class="w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-gray-500 dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                    name="cash" id="cash" type="text" placeholder="Masukkan jumlah cash" inputmode="numeric" pattern="[0-9]*" />

                  <p class="pt-3 dark:text-white">Exchange : IDR. <span id="exchange">0,00</span></p>
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
    const subtotal = <?= $subtotal ?>;

    // Format angka jadi Rupiah (JS)
    function formatRupiah(angka) {
      let number_string = angka.toString().replace(/[^,\d]/g, '');
      const split = number_string.split(',');
      let sisa = split[0].length % 3;
      let rupiah = split[0].substr(0, sisa);
      const ribuan = split[0].substr(sisa).match(/\d{3}/g);

      if (ribuan) {
        rupiah += (sisa ? '.' : '') + ribuan.join('.');
      }

      return split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    }

    // Unformat: ambil angka murni dari string Rupiah
    function parseRupiah(str) {
      return parseInt(str.replace(/\./g, '').replace(',', '')) || 0;
    }

    // Event saat ketik Cash input
    cashInput.addEventListener('input', function(e) {
      let angka = parseRupiah(this.value);
      this.value = formatRupiah(angka);

      const exchangeValue = angka - subtotal;
      exchangeDisplay.textContent = formatRupiah(exchangeValue > 0 ? exchangeValue : 0);
    });

    // Submit transaksi
    function confirmPayment() {
      const cashValue = parseRupiah(cashInput.value);
      const exchangeValue = cashValue - subtotal

      if (cashValue < subtotal) {
        alert('Jumlah cash kurang dari total belanja.');
        return;
      }

      fetch('<?= base_url() ?>/service/auth.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            action: 'transaction',
            order_id: <?= json_encode($order_id) ?>,
            cash: cashValue,
            method: 'cash'
          })
        })
        .then(response => response.json())
        .then(data => {
          alert('Payment successful!');
          window.location.href = 'invoice.php?transaction=' + data.transaction_id;
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Payment failed. Please try again.');
        });
    }
  </script>
</body>

</html>