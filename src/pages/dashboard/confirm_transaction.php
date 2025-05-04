<?php
session_start();
require '../../../service/utility.php';


if (!isset($_SESSION['loggedIn'])) {
  header('location: ../auth/index.php');
  exit();
}

// Ambil data cart dari POST
$cart = json_decode($_POST['cartData'], true);

function format_rupiah($angka)
{
  return number_format($angka, 0, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Confirm Transaction</title>
  <link href="../../assets/images/logo/logo_white.png" rel="icon">
  <link href="<?= base_url() ?>/src/css/output.css" rel="stylesheet">
</head>

<body class="dark text-bodydark bg-boxdark-2">

  <div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-white">Confirm Transaction</h1>

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
          <input class=" w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-gray-500 dark:bg-meta-4 dark:text-white dark:focus:border-primary"
            name="cash"
            id="cash"
            type="number" />
          <p class="pt-3">Exchange : IDR. <span id="exchange">0,00</span></p>
        </div>

        <input type="hidden" name="cart" value='<?= json_encode($cart) ?>'>
        <button type="submit" class="bg-primary text-white px-6 py-2 rounded hover:bg-meta-4 mr-2">Confirm Payment</button>
        <a href="transaction.php" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-700">Cancel</a>

      <?php endif; ?>

    </div>
  </div>

  <script>
    const cashInput = document.querySelector('input[name="cash"]');
    const exchangeDisplay = document.getElementById('exchange');

    cashInput.addEventListener('input', function() {
      const cashValue = parseFloat(cashInput.value.replace(/\./g, '').replace(',', '.'));
      const subtotal = <?= $subtotal ?>;

      if (!isNaN(cashValue)) {
        const exchangeValue = cashValue - subtotal;
        exchangeDisplay.textContent = formatRupiah(exchangeValue);
      } else {
        exchangeDisplay.textContent = '0,00';
      }
    });

    function formatRupiah(angka) {
      return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".").replace('.', ',');
    }

    function Confirm(){
      const cashValue = parseFloat(cashInput.value.replace(/\./g, '').replace(',', '.'));
      const subtotal = <?= $subtotal ?>;

      if (cashValue < subtotal) {
        alert('Cash amount is less than the total amount.');
        return false;
      } else{
        const 

        fetch('<?= base_url() ?>/service/transaction.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            cart: <?= json_encode($cart) ?>,
            cash: cashValue
          })
        })
      }

      return true;
    }
  </script>
</body>

</html>