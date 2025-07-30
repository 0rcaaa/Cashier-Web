<?php
session_start();
require '../../../service/utility.php';

if (isset($_SESSION['loggedIn']) == False) {
    header('location: ../auth/index.php');
    exit();
}

if (isset($_SESSION['success'])) {
    echo "<script>alert('" . $_SESSION['success'] . "');</script>";
    unset($_SESSION['success']);
} else if (isset($_SESSION['error'])) {
    echo "<script>alert('" . $_SESSION['error'] . "');</script>";
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Transaction Detail</title>
    <link href="../../assets/images/logo/logo_white.png" rel="icon">
    <link href="../../css/output.css" rel="stylesheet">
</head>

<body
    x-data="{ page: 'history', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
        <?php include '../../components/sidebar.html'; ?>
        <!-- ===== Sidebar End ===== -->

        <!-- ===== Content Area Start ===== -->
        <div
            class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            <!-- ===== Header Start ===== -->
            <?php include '../../components/header.html'; ?>
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
                                <li class="font-medium text-primary">Transaction</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-4">
                            <div class="rounded-sm h-fit border relative border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
                                <h4 class="text-xl font-bold text-black dark:text-white ">Cart</h4>
                                <div class="max-w-full overflow-x-auto">
                                    <table class="w-full table-auto my-4">
                                        <thead>
                                            <tr class="bg-gray-2 text-center dark:bg-meta-4">
                                                <th class=" px-4 py-4 font-medium text-black dark:text-white">
                                                    Product
                                                </th>
                                                <th class="px-4 py-4 font-medium text-black dark:text-white">
                                                    Price
                                                </th>
                                                <th class=" px-4 py-4 font-medium text-black dark:text-white">
                                                    Quantity
                                                </th>
                                                <th class=" px-4 py-4 font-medium text-black dark:text-white">
                                                    Total
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="cartData">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="mt-4">
                                <h1 class="text-xl font-medium dark:text-white text-black pb-2">Order Summary</h1>
                                <div class="-my-3 divide-y divide-gray-200 dark:divide-gray-800">
                                    <dl class="flex items-center justify-between gap-4 py-3">
                                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Items total</dt>
                                        <dd class="text-base font-medium text-gray-900 dark:text-white"><span id="sumItems">---</span></dd>
                                    </dl>


                                    <dl class="flex items-center justify-between gap-4 py-3">
                                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Subtotal</dt>
                                        <dd class="text-base font-medium text-gray-900 dark:text-white">IDR. <span id="sumTotal">---</span></dd>
                                    </dl>

                                    <dl class="flex items-center justify-between gap-4 py-3">
                                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Member</dt>
                                        <dd class="text-base font-medium text-gray-900 dark:text-white"><span id="member">---</span></dd>
                                    </dl>

                                    <dl class="flex items-center justify-between gap-4 py-3">
                                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Saved</dt>
                                        <dd class="text-base font-medium text-green-500">IDR. <span id="saved">---</span></dd>
                                    </dl>

                                    <dl class="flex items-center justify-between gap-4 py-3">
                                        <dt class="text-base font-bold text-gray-900 dark:text-white">Total</dt>
                                        <dd class="text-base font-bold text-gray-900 dark:text-white">IDR. <span id="subTotal">---</span></dd>
                                    </dl>

                                    <div class="border-y border-lg  border-white border-dashed"></div>

                                    <dl class="flex items-center justify-between gap-4 py-3">
                                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Cash</dt>
                                        <dd class="text-base font-medium text-gray-900 dark:text-white">IDR. <span id="cash">---</span></dd>
                                    </dl>

                                    <dl class="flex items-center justify-between gap-4 py-3">
                                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Change Due</dt>
                                        <dd class="text-base font-medium text-gray-900 dark:text-white">IDR. <span id="exchange">---</span></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="w-full col-span-2 mx-auto ">
                            <div class="flex flex-col gap-4">
                                <div class="bg-white dark:border-strokedark dark:bg-boxdark p-5 rounded-lg shadow-md">
                                    <p class="font-semibold text-2xl my-3 text-black dark:text-white">Invoice Preview</p>
                                    <div id="wrapper" class="flex justify-center text-black">
                                        <div id="struk" class="bg-white rounded shadow-lg p-6 w-full max-w-sm text-sm">
                                            <div class="text-center mb-4">
                                                <img src="<?= base_url() ?>/src/assets/images/logo/logo.png" class="w-1/4 mx-auto" alt="">
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
                                                <div class="flex justify-between"><span>Items</span><span id="qty">-</span></div>
                                                <div class="flex justify-between"><span>Subtotal</span><span id="subtotal">-</span></div>
                                                <div class="flex justify-between"><span>Cash</span><span id="cashInv">-</span></div>
                                                <div class="flex justify-between"><span>Discount</span><span id="discountInv">-</span></div>
                                                <div class="border-y border-lg  border-black border-dashed"></div>
                                                <div class="flex justify-between font-semibold text-lg"><span>Total</span><span id="Total">-</span></div>
                                                <div class="flex justify-between"><span>Change</span><span id="exchangeInv">-</span></div>
                                            </div>

                                            <p class="text-center text-xs text-gray-500">Terima kasih telah berbelanja di 0rca Store!</p>
                                        </div>
                                    </div>
                                    <button onclick="export_to_pdf()" class="flex mt-3 w-full items-center justify-center rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white hover:bg-meta-4 focus:outline-none focus:ring-4  focus:ring-primary-300 cursor-pointer">Create Invoice</button>
                                    <button onclick="send_wa()" class="flex mt-3 w-full items-center justify-center rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white hover:bg-meta-4 focus:outline-none focus:ring-4  focus:ring-primary-300 cursor-pointer">Send To Member</button>
                                </div>
                            </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        document.addEventListener('DOMContentLoaded', get_records);

        const rupiah = num => parseInt(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

        let invoiceData = null; // Simpan data invoice untuk WhatsApp

        function get_records() {
            const url = `<?= base_url() ?>/service/api.php?action=get_inv&order=<?= $_GET['order'] ?>`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (!Array.isArray(data) || data.length === 0) {
                        alert("Transaksi belum dilakukan");
                        window.location.href = "<?= base_url() ?>/src/pages/dashboard/confirm_transaction.php?order_id=<?= $_GET['order'] ?>";
                        return;
                    }

                    invoiceData = data; // Simpan untuk digunakan di send_wa()
                    const d = data[0];
                    const row = document.getElementById('cartItems');
                    const cart = document.getElementById('cartData');

                    document.getElementById('noInv').textContent = d.noInv;
                    document.getElementById('date').textContent = new Date(d.date).toLocaleString('id-ID', {
                        dateStyle: 'short',
                        timeStyle: 'short'
                    });

                    const totalQty = data.reduce((sum, i) => sum + parseInt(i.qty), 0);
                    const totalHarga = parseInt(d.total_price);
                    const discountValue = d.discount_value ? parseInt(d.discount_value) : 0;
                    const saved = (discountValue / 100) * totalHarga;
                    const afterDiscount = totalHarga - saved;
                    const kembalian = parseInt(d.cash) - afterDiscount;

                    [
                        ['sumTotal', totalHarga],
                        ['sumItems', totalQty],
                        ['saved', saved],
                        ['discountInv', saved],
                        ['subTotal', afterDiscount],
                        ['cash', parseInt(d.cash)],
                        ['cashInv', parseInt(d.cash)],
                        ['exchange', kembalian],
                        ['exchangeInv', kembalian],
                        ['subtotal', totalHarga],
                        ['qty', totalQty],
                        ['Total', afterDiscount],
                        ['exchange', kembalian]
                    ].forEach(([id, val]) => {
                        const el = document.getElementById(id);
                        if (el) el.textContent = rupiah(val);
                    });

                    const member = document.getElementById('memberName');
                    if (d.nama_pelanggan && d.nama_pelanggan !== 'default') {
                        member.innerHTML = `Member: <span class="font-semibold">${d.nama_pelanggan}</span>`;
                        document.getElementById('member').textContent = d.nama_pelanggan;
                    } else {
                        member.innerHTML = '';
                        document.getElementById('member').textContent = '-';
                    }

                    const itemRows = data.map(i => `
                    <tr class="text-center">
                        <td class="p-2">${i.product_name}</td>
                        <td>${rupiah(i.price)}</td>
                        <td>${i.qty}</td>
                        <td>${rupiah(i.subPrice)}</td>
                    </tr>
                `).join('');
                    const invoiceRows = data.map(i => `
                    <tr>
                        <td>${i.product_name}</td>
                        <td class="text-center">${i.qty}</td>
                        <td class="text-right">${rupiah(i.subPrice)}</td>
                    </tr>
                `).join('');
                    cart.innerHTML = itemRows;
                    row.innerHTML = invoiceRows;
                })
                .catch(err => console.error('Gagal mengambil data:', err));
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

        function send_wa() {
            if (!invoiceData) {
                alert("Data invoice belum siap. Silakan tunggu...");
                return;
            }

            const d = invoiceData[0];
            const items = invoiceData.map(i => `- ${i.product_name} (${i.qty} x Rp${rupiah(i.price)})`).join('\n');
            const nomor = d.customer_phone ?? '628123456789'; // Pastikan format 62xxx
            const totalHarga = parseInt(d.total_price);
            const discountValue = d.discount_value ? parseInt(d.discount_value) : 0;
            const saved = (discountValue / 100) * totalHarga;
            const afterDiscount = totalHarga - saved;
            const kembalian = parseInt(d.cash) - afterDiscount;

            const pesan = `🧾 *0rca Store*\n` +
                `No Struk: ${d.noInv}\n` +
                `Tanggal: ${new Date(d.date).toLocaleString('id-ID')}\n` +
                `Kasir: <?= $_SESSION['name'] ?>\n` +
                `Pelanggan: ${d.nama_pelanggan ?? '-'}\n\n` +
                `📦 *Items:*\n${items}\n\n` +
                `💰 Total: Rp${rupiah(totalHarga)}\n` +
                `🎁 Diskon: Rp${rupiah(saved)}\n` +
                `🪙 Cash: Rp${rupiah(d.cash)}\n` +
                `💵 Kembalian: Rp${rupiah(kembalian)}\n\n` +
                `Terima kasih telah berbelanja! 😊`;

                console. log(JSON.stringify({
                        target: nomor,
                        message: pesan
                    }));

            // Kirim ke API PHP menggunakan fetch
            fetch('<?= base_url() ?>/service/send_wa.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        target: nomor,
                        message: pesan
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        alert('Invoice berhasil dikirim ke WhatsApp!');
                    } else {
                        alert('Gagal mengirim invoice: ' + res.message);
                    }
                })
                .catch(err => {
                    console.error('Error kirim WA:', err);
                    alert('Terjadi kesalahan saat mengirim pesan WA.');
                });
        }
    </script>



</body>

</html>