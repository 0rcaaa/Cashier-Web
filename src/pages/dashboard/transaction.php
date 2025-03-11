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
              <h4 class="text-xl font-bold text-black dark:text-white py-4">Cart</h4>
              <div class="max-w-full overflow-x-auto">
                <table class="w-full table-auto">
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
                        action
                      </th>
                    </tr>
                  </thead>
                  <tbody id="tb_cart">
                    <tr>

                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
              <div class="w-full col-span-2 mx-auto bg-white  dark:border-strokedark dark:bg-boxdark p-5 rounded-lg shadow-md">
                <h2 class="text-center text-2xl font-semibold mb-4">QR Code Scanner</h2>
                <div id="reader" class="w-full"></div>
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
  <script src="<?= base_url() ?>/node_modules/html5-qrcode/html5-qrcode.min.js">
  </script>
  <script>
    function onScanSuccess(decodedText, decodedResult) {
      // handle the scanned code as you like, for example:
      console.log(`Code matched = ${decodedText}`, decodedResult);
    }

    function onFail(error){

    }


    let html5QrcodeScanner = new Html5QrcodeScanner(
      "reader", {
        fps: 10,
        qrbox: {
          width: 200,
          height: 200
        }
      },
      /* verbose= */
      false);
    html5QrcodeScanner.render(onScanSuccess,onFail);
  </script>
</body>

</html>