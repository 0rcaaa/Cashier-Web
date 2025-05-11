<?php
session_start();
require_once('../../../service/utility.php');
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Struk Belanja + Export PDF</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen space-y-4">

  <div id="wrapper" class="flex justify-center">
    <div id="struk" class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-sm text-sm">
      <div class="text-center mb-4">
        <img src="<?=base_url()?>/src/assets/images/logo/logo.png" class="w-1/4 mx-auto" alt="">
        <h1 class="text-xl font-bold">0rca Store</h1>
        <p class="text-gray-600">Jl. Merdeka No. 123, Jakarta</p>
        <p class="text-gray-600">Telp: (021) 12345678</p>
      </div>

      <div class="border-y border-dashed border-black py-2 mb-4 space-y-1">
        <p>No Struk: <span class="font-semibold" id="noInv">---</span></p>
        <p>Tanggal: <span class="font-semibold" id="date"></span></p>
        <p>Kasir: <span class="font-semibold"><?= $_SESSION['name'] ?></span></p>
        <p id="memberName"></p>
      </div>

      <table class="w-full mb-4">
        <thead>
          <tr class="border-b border-dashed border-black text-gray-700">
            <th class="text-left py-1">Item</th>
            <th class="text-center py-1">Qty</th>
            <th class="text-right py-1">Price</th>
          </tr>
        </thead>
        <tbody id="cartItems"></tbody>
      </table>

      <div class="border-t border-dashed border-black pt-2 space-y-1 mb-4">
        <div class="flex justify-between"><span>Subtotal</span><span id="subtotal">-</span></div>
        <div class="flex justify-between"><span>Items</span><span id="qty">-</span></div>
        <div class="flex justify-between"><span>Cash</span><span id="cash">-</span></div>
        <div class="border-y border-lg  border-black border-dashed"></div>
        <div class="flex justify-between font-semibold text-lg"><span>Total</span><span id="Total">-</span></div>
        <div class="flex justify-between"><span>Change</span><span id="exchange">-</span></div>
      </div>

      <p class="text-center text-xs text-gray-500">Terima kasih telah berbelanja di 0rca Store!</p>
    </div>
  </div>

  <button onclick="export_to_pdf()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">Export Struk ke PDF</button>

  <script>
    document.addEventListener('DOMContentLoaded', get_records);

    const rupiah = num => 'IDR. ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    function get_records() {
      const url = `<?= base_url() ?>/service/api.php?action=get_inv&transaction=<?= $_GET['transaction'] ?>`;

      fetch(url).then(res => res.json()).then(data => {
        const d = data[0],
          row = document.getElementById('cartItems');
        document.getElementById('noInv').textContent = d.noInv;
        document.getElementById('date').textContent = new Date().toLocaleString('id-ID', {
          dateStyle: 'short',
          timeStyle: 'short'
        });

        const totalQty = data.reduce((sum, i) => sum + i.qty, 0);
        const change = d.cash - d.total_price;

        [
          ['subtotal', d.total_price],
          ['qty', totalQty],
          ['cash', d.cash],
          ['Total', d.total_price],
          ['exchange', change]
        ]
        .forEach(([id, val]) => document.getElementById(id).textContent = rupiah(val));

        const member = document.getElementById('memberName');
        member.innerHTML = d.nama_pelanggan !== 'default' ? `Member: <span class="font-semibold">${d.nama_pelanggan}</span>` : '';

        row.innerHTML = data.map(i => `
      <tr><td>${i.product_name}</td><td class="text-center">${i.qty}</td><td class="text-right">Rp ${i.subPrice}</td></tr>
    `).join('');
      }).catch(err => console.error(err));
    }

    function export_to_pdf() {
      html2pdf().set({
        margin: 0.5,
        filename: 'struk-' + document.getElementById('noInv').textContent + '.pdf',
        image: {
          type: 'jpeg',
          quality: 0.98
        },
        html2canvas: {
          scale: 2
        },
        jsPDF: {
          unit: 'in',
          format: 'A4',
          orientation: 'portrait'
        }
      }).from(document.getElementById('wrapper')).save();
    }
  </script>

</body>

</html>