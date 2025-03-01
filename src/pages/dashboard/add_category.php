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
  <title>Add Product</title>
  <link href="../../assets/images/logo/logo_white.png" rel="icon">
  <link href="../../css/output.css" rel="stylesheet">
</head>

<body
  x-data="{ page: 'add_category', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
          <div class="mx-auto max-w-270">
            <!-- Breadcrumb Start -->
            <div
              class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
              <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Add Product
              </h2>

              <nav>
                <ol class="flex items-center gap-2">
                  <li>
                    <a class="font-medium hover:text-meta-5" href="index.php">Dashboard /</a>
                  </li>
                  <li class="font-medium text-primary">Add Product</li>
                </ol>
              </nav>
            </div>
            <!-- Breadcrumb End -->

            <!-- ====== Settings Section Start -->
            <form action="<?= base_url() ?>/service/auth.php" method="POST" class="grid grid-cols-5 gap-8">
              <div class="col-span-5 xl:col-span-3">
                <div
                  class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                  <div
                    class="border-b border-stroke px-7 py-4 dark:border-strokedark">
                    <h3 class="font-medium text-black dark:text-white">
                      Product Information
                    </h3>
                  </div>
                  <div class="p-7">

                    <div class="mb-5.5 flex flex-col gap-5.5 sm:flex-row">
                      <div class="w-full sm:w-1/2">
                        <label
                          class="mb-3 block text-sm font-medium text-black dark:text-white"
                          for="productName">Product Name</label>
                        <input
                          class="w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                          type="text"
                          name="productName"
                          id="productName" />
                      </div>

                      <div class="w-full sm:w-1/2">
                        <label
                          class="mb-3 block text-sm font-medium text-black dark:text-white"
                          for="stock">Stock</label>
                        <input
                          class="w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                          type="text"
                          name="stock"
                          id="stock" />
                      </div>
                    </div>

                    <div class="mb-5.5 flex flex-col gap-5.5 sm:flex-row">
                      <div class="w-full sm:w-1/2">
                        <label
                          class="mb-3 block text-sm font-medium text-black dark:text-white"
                          for="price">Starting Price</label>
                        <input
                          class="w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                          type="text"
                          placeholder="Sebelum margin"
                          name="price"
                          id="price" />
                      </div>

                      <div class="w-full sm:w-1/2">
                        <label
                          class="mb-3 block text-sm font-medium text-black dark:text-white"
                          for="margin">Margin</label>
                        <input
                          class="[&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none 
                              [&::-moz-appearance:textfield] w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                          type="number"
                          name="margin"
                          placeholder="Max 3 Digit"
                          id="margin" />
                      </div>
                    </div>

                    <div class="mb-5.5">
                      <label
                        class="mb-3 block text-sm font-medium text-black dark:text-white"
                        for="kategori">Category</label>
                      <select
                        class=" w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                        name="kategori"
                        id="kategori">
                        <option value="" class="dark:text-white">--- Pilih kategori ---</option>
                      </select>
                    </div>

                    <div>
                      <label
                        class="mb-3 block text-sm font-medium text-black dark:text-white"
                        for="Detail">Details</label>
                      <div class="relative">
                        <span class="absolute left-4.5 top-4">
                          <svg
                            class="fill-current"
                            width="20"
                            height="20"
                            viewBox="0 0 20 20"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g
                              opacity="0.8"
                              clip-path="url(#clip0_88_10224)">
                              <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M1.56524 3.23223C2.03408 2.76339 2.66997 2.5 3.33301 2.5H9.16634C9.62658 2.5 9.99967 2.8731 9.99967 3.33333C9.99967 3.79357 9.62658 4.16667 9.16634 4.16667H3.33301C3.11199 4.16667 2.90003 4.25446 2.74375 4.41074C2.58747 4.56702 2.49967 4.77899 2.49967 5V16.6667C2.49967 16.8877 2.58747 17.0996 2.74375 17.2559C2.90003 17.4122 3.11199 17.5 3.33301 17.5H14.9997C15.2207 17.5 15.4326 17.4122 15.5889 17.2559C15.7452 17.0996 15.833 16.8877 15.833 16.6667V10.8333C15.833 10.3731 16.2061 10 16.6663 10C17.1266 10 17.4997 10.3731 17.4997 10.8333V16.6667C17.4997 17.3297 17.2363 17.9656 16.7674 18.4344C16.2986 18.9033 15.6627 19.1667 14.9997 19.1667H3.33301C2.66997 19.1667 2.03408 18.9033 1.56524 18.4344C1.0964 17.9656 0.833008 17.3297 0.833008 16.6667V5C0.833008 4.33696 1.0964 3.70107 1.56524 3.23223Z"
                                fill="" />
                              <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M16.6664 2.39884C16.4185 2.39884 16.1809 2.49729 16.0056 2.67253L8.25216 10.426L7.81167 12.188L9.57365 11.7475L17.3271 3.99402C17.5023 3.81878 17.6008 3.5811 17.6008 3.33328C17.6008 3.08545 17.5023 2.84777 17.3271 2.67253C17.1519 2.49729 16.9142 2.39884 16.6664 2.39884ZM14.8271 1.49402C15.3149 1.00622 15.9765 0.732178 16.6664 0.732178C17.3562 0.732178 18.0178 1.00622 18.5056 1.49402C18.9934 1.98182 19.2675 2.64342 19.2675 3.33328C19.2675 4.02313 18.9934 4.68473 18.5056 5.17253L10.5889 13.0892C10.4821 13.196 10.3483 13.2718 10.2018 13.3084L6.86847 14.1417C6.58449 14.2127 6.28409 14.1295 6.0771 13.9225C5.87012 13.7156 5.78691 13.4151 5.85791 13.1312L6.69124 9.79783C6.72787 9.65131 6.80364 9.51749 6.91044 9.41069L14.8271 1.49402Z"
                                fill="" />
                            </g>
                            <defs>
                              <clipPath id="clip0_88_10224">
                                <rect width="20" height="20" fill="white" />
                              </clipPath>
                            </defs>
                          </svg>
                        </span>

                        <textarea
                          class="w-full rounded border border-stroke bg-gray py-3 pl-11.5 pr-4.5 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                          name="Detail"
                          id="Detail"
                          rows="6"
                          placeholder="Description"></textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-span-5 xl:col-span-2">
                <div class="rounded-sm border mb-5.5 border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                  <div
                    class="border-b border-stroke px-7 py-4 dark:border-strokedark">
                    <h3 class="font-medium text-2xl text-center text-black dark:text-white">
                      Product Image
                    </h3>
                  </div>
                  <div class="p-7">
                    <div
                      id="FileUpload"
                      accept="image/*"
                      class="relative mb-5.5 block w-full cursor-pointer appearance-none rounded border border-dashed border-primary bg-gray px-4 py-4 dark:bg-meta-4 sm:py-7.5">
                      <input
                        type="file"
                        name="imageInput"
                        accept="image/*"
                        class="absolute inset-0 z-50 m-0 h-full w-full cursor-pointer p-0 opacity-0 outline-none" />
                      <div class="flex flex-col items-center justify-center space-y-3">
                        <div id="uploadedImg" class="hidden flex-col items-center justify-center space-y-1">
                          <img id="previewImage" src="" alt="" class="mb-5">
                          <button
                            class="flex bg-primary justify-center rounded border border-stroke px-[30%] py-2 font-medium text-black hover:shadow-1 dark:border-strokedark dark:text-white"
                            onclick="resetImg()">
                            Cari Gambar Lain
                          </button>
                        </div>
                        <div id="pImageTitles" class="flex flex-col items-center justify-center space-y-1">
                          <span
                            class="flex h-10 w-10 items-center justify-center rounded-full border border-stroke bg-white dark:border-strokedark dark:bg-boxdark">
                            <svg
                              width="16"
                              height="16"
                              viewBox="0 0 16 16"
                              fill="none"
                              xmlns="http://www.w3.org/2000/svg">
                              <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M1.99967 9.33337C2.36786 9.33337 2.66634 9.63185 2.66634 10V12.6667C2.66634 12.8435 2.73658 13.0131 2.8616 13.1381C2.98663 13.2631 3.1562 13.3334 3.33301 13.3334H12.6663C12.8431 13.3334 13.0127 13.2631 13.1377 13.1381C13.2628 13.0131 13.333 12.8435 13.333 12.6667V10C13.333 9.63185 13.6315 9.33337 13.9997 9.33337C14.3679 9.33337 14.6663 9.63185 14.6663 10V12.6667C14.6663 13.1971 14.4556 13.7058 14.0806 14.0809C13.7055 14.456 13.1968 14.6667 12.6663 14.6667H3.33301C2.80257 14.6667 2.29387 14.456 1.91879 14.0809C1.54372 13.7058 1.33301 13.1971 1.33301 12.6667V10C1.33301 9.63185 1.63148 9.33337 1.99967 9.33337Z"
                                fill="#3C50E0" />
                              <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M7.5286 1.52864C7.78894 1.26829 8.21106 1.26829 8.4714 1.52864L11.8047 4.86197C12.0651 5.12232 12.0651 5.54443 11.8047 5.80478C11.5444 6.06513 11.1223 6.06513 10.8619 5.80478L8 2.94285L5.13807 5.80478C4.87772 6.06513 4.45561 6.06513 4.19526 5.80478C3.93491 5.54443 3.93491 5.12232 4.19526 4.86197L7.5286 1.52864Z"
                                fill="#3C50E0" />
                              <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M7.99967 1.33337C8.36786 1.33337 8.66634 1.63185 8.66634 2.00004V10C8.66634 10.3682 8.36786 10.6667 7.99967 10.6667C7.63148 10.6667 7.33301 10.3682 7.33301 10V2.00004C7.33301 1.63185 7.63148 1.33337 7.99967 1.33337Z"
                                fill="#3C50E0" />
                            </svg>
                          </span>
                          <p class="text-sm font-medium">
                            <span class="text-primary">Click to upload</span>
                            or drag and drop
                          </p>
                          <p class="mt-1.5 text-sm font-medium">
                            SVG, PNG, JPG
                          </p>
                          <p class="text-sm font-medium">
                            (max, 800px X 800px)
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="flex justify-end gap-4.5">
                  <button
                    class="flex justify-center rounded border border-stroke px-6 py-2 font-medium text-black hover:shadow-1 dark:border-strokedark dark:text-white"
                    type="reset">
                    Reset All
                  </button>
                  <button
                    class="flex justify-center rounded bg-primary px-6 py-2 font-medium text-gray hover:bg-opacity-90"
                    type="submit" value="addProduct" name="action">
                    Save
                  </button>
                </div>
              </div>
              <form />
              <!-- ====== Settings Section End -->
          </div>
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
    <!-- ===== Content Area End ===== -->
  </div>
  <!-- ===== Page Wrapper End ===== -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      populateCategoryDropdown();
      setupImageUpload();
      setupMarginInput();
    });

    function populateCategoryDropdown() {
      const kategori = document.getElementById("kategori");

      fetch("<?= base_url() ?>/service/api.php?action=getCategory")
        .then(response => response.json())
        .then(data => {
          data.forEach(item => {
            const option = document.createElement("option");
            option.value = item.id;
            option.textContent = item.name;
            kategori.appendChild(option);
          });
        });
    }

    document.getElementById("price").addEventListener("input", function(event) {
      let inputValue = event.target.value.replace(/[^\d,]/g, ""); // Hanya angka dan koma
      inputValue = inputValue.replace(/,/g, "."); // Ganti koma dengan titik (opsional)

      let formattedValue = formatRupiah(inputValue);
      event.target.value = formattedValue;
    });

    function formatRupiah(angka) {
      let numberString = angka.replace(/\D/g, "").toString();
      let split = numberString.split(",");
      let sisa = split[0].length % 3;
      let rupiah = split[0].substr(0, sisa);
      let ribuan = split[0].substr(sisa).match(/\d{3}/g);

      if (ribuan) {
        let separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
      }

      return "Rp. " + rupiah + (split[1] ? "," + split[1] : "");
    }

    // Saat form dikirim, format angka ke bentuk yang bisa diterima oleh backend
    document.querySelector("form").addEventListener("submit", function() {
      let priceInput = document.getElementById("price");
      priceInput.value = priceInput.value.replace(/[^0-9,]/g, "").replace(/\./g, ""); // Hapus "Rp." & titik pemisah ribuan
    });

    function resetImage() {
      const img = document.getElementById('previewImage');
      const uploadedImg = document.getElementById('uploadedImg');
      const pImageTitles = document.getElementById('pImageTitles');

      img.src = '';
      uploadedImg.classList.add('hidden');
      pImageTitles.style.display = 'flex';
    }


    function setupImageUpload() {
      const fileUpload = document.getElementById('FileUpload');
      fileUpload.addEventListener('change', function() {
        const file = this.querySelector('input[type="file"]').files[0];
        const img = this.querySelector('#previewImage');
        const pImageTitles = this.querySelector('#pImageTitles');
        const uploadedImg = this.querySelector('#uploadedImg');

        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            img.src = e.target.result;
            uploadedImg.classList.remove('hidden');
            uploadedImg.classList.add('flex');
            pImageTitles.style.display = 'none';
          }
          reader.readAsDataURL(file);
        }
      });
    }

    function setupMarginInput() {
      const marginInput = document.getElementById("margin");

      marginInput.addEventListener("input", function() {
        // Hapus karakter selain angka
        this.value = this.value.replace(/\D/g, "");

        // Batasi hanya 3 digit
        if (this.value.length > 3) {
          this.value = this.value.slice(0, 3);
        }
        if (this.value > 100) {
          this.value = 100;
        }

        if (this.value <= 0) {
          this.value = 0;
        }
      });
    }
  </script>
  <script defer src="../../js/bundle.js"></script>


</body>

</html>