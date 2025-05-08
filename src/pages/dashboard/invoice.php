<?php
require '../../../service/utility.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Struk Belanja</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-sm">
    <div class="text-center mb-4">
        <img src="<?=base_url()?>/src/assets/images/logo/logo.png" class="w-[25%] h-auto mx-auto" alt="">
        <h1 class="text-xl font-bold">0rca Store</h1>
        <p class="text-sm text-gray-600">Jl. Merdeka No. 123, Jakarta</p>
        <p class="text-sm text-gray-600">Telp: (021) 12345678</p>
    </div>

    <div class="border-t border-b py-2 mb-4">
      <p class="text-sm">No Struk: <span class="font-semibold" id="invCode">-</span></p>
      <p class="text-sm">Tanggal: <span class="font-semibold" id="dateTime">-</span></p>
      <p class="text-sm">Kasir: <span class="font-semibold" class="cashierName">-</span></p>
    </div>

    <table class="w-full text-sm mb-4">
      <thead>
        <tr class="border-b text-gray-700">
          <th class="text-left py-1">Item</th>
          <th class="text-center py-1">Qty</th>
          <th class="text-right py-1">Harga</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Paracetamol 500mg</td>
          <td class="text-center">2</td>
          <td class="text-right">Rp 10.000</td>
        </tr>
      </tbody>
    </table>

    <div class="border-t pt-2 text-sm space-y-1 mb-4">
      <div class="flex justify-between">
        <span>Subtotal</span>
        <span id="subTotal">-</span>
      </div>
      <!-- <div class="flex justify-between">
        <span>Diskon</span>
        <span id="discountValue">Rp 5.000</span>
      </div> -->
      <div class="flex justify-between font-semibold text-lg">
        <span>Total</span>
        <span id="summary">Rp 70.000</span>
      </div>
    </div>

    <p class="text-center text-xs text-gray-500">
      Terima kasih telah berbelanja di 0rca Store!
    </p>
  </div>
</body>
</html>
