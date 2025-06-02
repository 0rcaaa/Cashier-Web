<?php
session_start();
require '../../../service/utility.php';

if (isset($_SESSION['loggedIn']) == False) {
  header('location: ../auth/index.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
    Transaction
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
                <li class="font-medium text-primary">Transaction</li>
              </ol>
            </nav>
          </div>

          <div class="grid grid-cols-6 gap-6">
            <div class="rounded-sm h-fit border relative col-span-4 border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
              <div class="flex justify-between py-4">
                <h4 class="text-xl font-bold text-black dark:text-white ">Cart</h4>
                <div class="p-1 rounded-lg shadow-md ">
                  <form action="" id="searchBarcode">
                    <input autofocus placeholder="Add by Uniqcode" class="rounded border border-stroke bg-gray p-1 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary" type="text" name="barcodeInput" id="barcodeInput">
                    <button type="submit" z class="bg-primary py-1 px-4 text-sm font-medium text-white hover:bg-meta-4 w-auto cursor-pointer rounded">Add Product</button>
                  </form>
                </div>
              </div>
              <div class="max-w-full overflow-x-auto">
                <table class="w-full table-auto mb-4">
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
                      <th class=" px-4 py-4 font-medium text-black dark:text-white">
                        action
                      </th>
                    </tr>
                  </thead>
                  <tbody id="cartData">

                  </tbody>
                </table>
              </div>
            </div>
            <div class="w-full col-span-2 mx-auto ">
              <div class="flex flex-col gap-4">

                <div class="bg-white dark:border-strokedark dark:bg-boxdark p-5 rounded-lg shadow-md">
                  <h2 class="text-center text-2xl font-semibold mb-4">QR Code Scanner</h2>
                  <div id="reader" class="w-full"></div>
                  <input type="text" id="qr-result" disabled value="" class="hidden">
                </div>

                <div class="mt-6 w-full space-y-6 sm:mt-8 lg:mt-0 lg:max-w-xs xl:max-w-md">
                  <div class="flow-root">
                    <h1 class="text-xl font-medium dark:text-white text-black pb-2">Order Summary</h1>
                    <div class="-my-3 divide-y divide-gray-200 dark:divide-gray-800">
                      <dl class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Subtotal</dt>
                        <dd class="text-base font-medium text-gray-900 dark:text-white">IDR. <span id="sumTotal">-</span></dd>
                      </dl>

                      <dl class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Items total</dt>
                        <dd class="text-base font-medium text-gray-900 dark:text-white"><span id="sumItems">-</span></dd>
                      </dl>

                      <dl class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Member</dt>
                        <dd class="text-base font-medium text-gray-900 dark:text-white"><input class="rounded border border-stroke bg-gray px-4.5 py-1 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary" type="text" name="memberPhone" placeholder="Fill member by phone number"></dd>
                      </dl>

                      <!-- <dl class="flex items-center justify-between gap-4 py-3">
                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Savings</dt>
                        <dd class="text-base font-medium text-green-500"><select name="" id="dicount">
                          <option value="">-</option>
                        </select></dd>
                      </dl> -->
                    </div>
                  </div>
                  <form id="transactionForm" method="POST" action="">
                    <input type="hidden" name="cartData" id="cartDataInput">
                  </form>

                  <div id="modal" tabindex="-1" aria-hidden="true"
                    class="hidden fixed inset-0 z-99999 flex items-center justify-center backdrop-blur-md bg-black/30 justify-center">
                    <div class="relative p-4 w-[50%] max-w-2xl max-h-full">
                      <!-- Modal content -->
                      <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                          <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Verification Account</h3>
                          <button type="button"
                            onclick="document.getElementById('modal').classList.add('hidden')"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                          </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5 space-y-4">
                          <div>
                            <label for="Password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Masukkan Password Pengguna" required="">
                          </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                          <button type="button"
                            onclick="verifyMember()"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Verify</button>
                          <button type="button"
                            onclick="document.getElementById('modal').classList.add('hidden')"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            Cancel</button>
                        </div>
                      </div>
                    </div>
                  </div>


                  <button onclick="submitTransaction()" class="flex w-full items-center justify-center rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white hover:bg-meta-4 focus:outline-none focus:ring-4  focus:ring-primary-300 cursor-pointer">Proses Transaksi</button>
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
  <script src="<?= base_url() ?>/node_modules/html5-qrcode/html5-qrcode.min.js"></script>
  <script src="<?= base_url() ?>/node_modules/flowbite/dist/flowbite.min.js"></script>
  <script>
    //update value
    function onScanSuccess(decodedText, decodedResult) {
      console.log(decodedText);
      fetchProductData(decodedText);
      //kirim data output scan
    }

    //api handler
    function fetchProductData(qrcode) {
      fetch("<?= base_url() ?>/service/auth.php", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            action: 'scanProduct',
            qrcode: qrcode
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            console.log(data);
            alert(data.error);
            return;
          }
          addProductRow(data);
          document.getElementById("barcodeInput").value = '';
        })
        .catch(error => console.error('Error:', error));
    }

    document.getElementById('searchBarcode').addEventListener('submit', function(event) {
      event.preventDefault();
      barcodeInput();
    });

    function barcodeInput() {
      let barcode = document.getElementById("barcodeInput").value;

      if (!barcode) {
        alert("Masukkan barcode terlebih dahulu!");
        return;
      }

      fetchProductData(barcode);
    }

    function addProductRow(data) {
      const cartData = document.getElementById('cartData');

      //check existing record
      let existData = document.querySelector(`tr[data-product='${data.id}']`);
      if (existData) {
        qty.value = parseInt(qty.value) + 1;
        updateTotal(existData);
        return;
      }

      //create record if there is no record
      let tr = document.createElement('tr');
      tr.setAttribute("data-product", data.id);
      tr.innerHTML = `
                      <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                        <p id="productName" class="text-sm text-center font-medium text-black dark:text-white">${data.name}</p>
                      </td>
                      <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                        <p id="itemPrice" class="text-sm text-center font-medium text-black dark:text-white">${data.price}</p>
                      </td>
                      <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                        <div class="flex flex-row justify-center items-center gap-3">
                          <button id="decrement" class="bg-meta-1 align-middle cursor-pointer rounded-sm px-1 text-black">-</button>
                          <input id="qty" type="text" class="text-sm max-w-4 text-center font-medium text-black dark:text-white" value="1">
                          <button id="increment" class="bg-meta-3 align-middle cursor-pointer rounded-sm px-1 text-black">+</button>
                        </div>
                      </td>
                      <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                        <p id="total" class="text-sm text-center font-medium text-black dark:text-white">0</p>
                      </td>
                      <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                        <div class="flex justify-center items-center space-x-3.5">
                          <button id="removeBtn" class="hover:text-primary cursor-pointer">
                            <svg
                              class="fill-current"
                              width="18"
                              height="18"
                              viewBox="0 0 18 18"
                              fill="none"
                              xmlns="http://www.w3.org/2000/svg">
                              <path
                                d="M13.7535 2.47502H11.5879V1.9969C11.5879 1.15315 10.9129 0.478149 10.0691 0.478149H7.90352C7.05977 0.478149 6.38477 1.15315 6.38477 1.9969V2.47502H4.21914C3.40352 2.47502 2.72852 3.15002 2.72852 3.96565V4.8094C2.72852 5.42815 3.09414 5.9344 3.62852 6.1594L4.07852 15.4688C4.13477 16.6219 5.09102 17.5219 6.24414 17.5219H11.7004C12.8535 17.5219 13.8098 16.6219 13.866 15.4688L14.3441 6.13127C14.8785 5.90627 15.2441 5.3719 15.2441 4.78127V3.93752C15.2441 3.15002 14.5691 2.47502 13.7535 2.47502ZM7.67852 1.9969C7.67852 1.85627 7.79102 1.74377 7.93164 1.74377H10.0973C10.2379 1.74377 10.3504 1.85627 10.3504 1.9969V2.47502H7.70664V1.9969H7.67852ZM4.02227 3.96565C4.02227 3.85315 4.10664 3.74065 4.24727 3.74065H13.7535C13.866 3.74065 13.9785 3.82502 13.9785 3.96565V4.8094C13.9785 4.9219 13.8941 5.0344 13.7535 5.0344H4.24727C4.13477 5.0344 4.02227 4.95002 4.02227 4.8094V3.96565ZM11.7285 16.2563H6.27227C5.79414 16.2563 5.40039 15.8906 5.37227 15.3844L4.95039 6.2719H13.0785L12.6566 15.3844C12.6004 15.8625 12.2066 16.2563 11.7285 16.2563Z"
                                fill="" />
                              <path
                                d="M9.00039 9.11255C8.66289 9.11255 8.35352 9.3938 8.35352 9.75942V13.3313C8.35352 13.6688 8.63477 13.9782 9.00039 13.9782C9.33789 13.9782 9.64727 13.6969 9.64727 13.3313V9.75942C9.64727 9.3938 9.33789 9.11255 9.00039 9.11255Z"
                                fill="" />
                              <path
                                d="M11.2502 9.67504C10.8846 9.64692 10.6033 9.90004 10.5752 10.2657L10.4064 12.7407C10.3783 13.0782 10.6314 13.3875 10.9971 13.4157C11.0252 13.4157 11.0252 13.4157 11.0533 13.4157C11.3908 13.4157 11.6721 13.1625 11.6721 12.825L11.8408 10.35C11.8408 9.98442 11.5877 9.70317 11.2502 9.67504Z"
                                fill="" />
                              <path
                                d="M6.72245 9.67504C6.38495 9.70317 6.1037 10.0125 6.13182 10.35L6.3287 12.825C6.35683 13.1625 6.63808 13.4157 6.94745 13.4157C6.97558 13.4157 6.97558 13.4157 7.0037 13.4157C7.3412 13.3875 7.62245 13.0782 7.59433 12.7407L7.39745 10.2657C7.39745 9.90004 7.08808 9.64692 6.72245 9.67504Z"
                                fill="" />
                            </svg>
                          </button>
                        </div>
                      </td>
      `;

      cartData.appendChild(tr);
      updateTotal(tr);
      updateOrderSummary(tr);

      tr.querySelector('#increment').addEventListener("click", () => {
        tr.querySelector('#qty').value = parseInt(tr.querySelector('#qty').value) + 1;
        updateTotal(tr);
        updateOrderSummary(tr);
      });

      tr.querySelector('#decrement').addEventListener('click', () => {
        let qty = tr.querySelector('#qty');
        let currentValue = parseInt(qty.value);
        if (currentValue > 1) {
          qty.value = currentValue - 1;
        } else {
          tr.remove();
        }
        updateTotal(tr);
        updateOrderSummary(tr);
      });

      tr.querySelector('#removeBtn').addEventListener("click", () => {
        tr.remove();
        updateOrderSummary(tr);
      });
    }

    //ambil data dari table 
    function collectCartData() {
      const cartRows = document.getElementById('cartData').querySelectorAll('tr');
      const items = [];

      cartRows.forEach(row => {
        const id = row.getAttribute('data-product');
        const name = row.querySelector('#productName').textContent;
        const price = parseInt(row.querySelector("#itemPrice").textContent.replace(',', '')) || 0;
        const qty = parseInt(row.querySelector("#qty").value) || 0;
        const total = qty * price;

        items.push({
          id: id,
          name: name,
          price: price,
          qty: qty,
          total: total
        });
      });

      return items;
    }

    //kirim data yang di ambil dari table ke page selanjutnya sebagai array json ketika button proses transaksi di klik
    function submitTransaction() {
      const cartItems = collectCartData();
      const memberPhone = document.querySelector('input[name="memberPhone"]').value;

      if (cartItems.length === 0) {
        alert('Keranjang masih kosong!');
        return;
      }

      console.log(JSON.stringify({
        action: 'order',
        cartData: cartItems,
        memberPhone: memberPhone
      }));

      // Jika memberPhone diisi, lakukan verifikasi member dulu
      if (memberPhone) {
        // Tampilkan modal selama verifikasi
        document.getElementById('modal').classList.remove('hidden');
        return;
      } else {
        // Jika bukan member, langsung proses order tanpa verifikasi
        fetch("<?= base_url() ?>/service/auth.php", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              action: 'order',
              cartData: cartItems,
              memberPhone: memberPhone
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              window.location.href = 'confirm_transaction.php?order_id=' + data.order_id;
            } else {
              alert(data.error || 'Terjadi kesalahan saat proses transaksi');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim order.');
          });
      }
    }

    function verifyMember() {
      const password = document.getElementById('password').value;
      const memberPhone = document.querySelector('input[name="memberPhone"]').value;
      const cartItems = collectCartData();

      fetch("<?= base_url() ?>/service/auth.php", {
          method: 'POST', // perbaiki typo
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            action: 'verifyMember',
            memberPhone: memberPhone,
            password:password
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {
            // Jika verifikasi sukses, lanjutkan dengan order
            fetch("<?= base_url() ?>/service/auth.php", {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                  action: 'order',
                  cartData: cartItems,
                  memberPhone: memberPhone
                })
              })
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  // Sukses order, redirect ke halaman konfirmasi
                  window.location.href = 'confirm_transaction.php?order_id=' + data.order_id;
                } else {
                  alert(data.error || 'Terjadi kesalahan saat proses transaksi');
                }
              })
              .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim order.');
              });
          } else {
            alert(data.message || 'Verifikasi gagal. Pastikan nomor member benar.');
            // Sembunyikan modal jika gagal verifikasi
            // document.getElementById('modal').classList.add('hidden');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Terjadi kesalahan saat verifikasi.');
          document.getElementById('modal').classList.add('hidden');
        });
    }



    //update total price
    function updateTotal(row) {
      let qty = parseInt(row.querySelector("#qty").value) || 0;
      let price = parseInt(row.querySelector("#itemPrice").textContent.replace(',', '')) || 0;
      row.querySelector('#total').textContent = (qty * price).toLocaleString();
    }

    //update sum total price and item
    function updateOrderSummary() {
      let sumTotal = 0;
      let sumItems = 0;
      const cartData = document.getElementById('cartData').querySelectorAll('tr');

      cartData.forEach(row => {
        let qty = parseInt(row.querySelector("#qty").value) || 0;
        let price = parseInt(row.querySelector("#itemPrice").textContent.replace(',', '')) || 0;
        sumTotal += qty * price;
        sumItems += qty;
      });

      document.getElementById('sumTotal').textContent = sumTotal.toLocaleString();
      document.getElementById('sumItems').textContent = sumItems;
    }

    function onFail(error) {
      //kosong 
    }


    let html5QrcodeScanner = new Html5QrcodeScanner(
      "reader", {
        fps: 10,
        qrbox: {
          width: 300,
          height: 200
        }
      },
      false);
    html5QrcodeScanner.render(onScanSuccess, onFail);
  </script>
</body>

</html>