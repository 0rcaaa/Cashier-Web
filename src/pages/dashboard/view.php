<?php
session_start();
require '../../../service/utility.php';
require '../../../service/connection.php';

if (!isset($_SESSION['loggedIn'])) {
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
  <title>Product Tables</title>
  <link href="../../assets/images/logo/logo_white.png" rel="icon">
  <link href="../../css/output.css" rel="stylesheet">
</head>

<body
  x-data="{ page: 'view', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
          <!-- Breadcrumb Start -->
          <div
            class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
              Tables
            </h2>

            <nav>
              <ol class="flex items-center gap-2">
                <li>
                  <a class="font-medium hover:text-meta-5" href="index.php">Dashboard /</a>
                </li>
                <li class="font-medium text-primary">Tables</li>
              </ol>
            </nav>
          </div>
          <!-- Breadcrumb End -->

          <!-- ====== Table Section Start -->
          <div class="flex flex-col gap-10">
            <!-- ====== Table Three Start -->
            <div
              class="rounded-sm border border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
              <div class="px-4 py-6 md:px-6 xl:px-7.5 flex items-center justify-between">
                <h4 class="text-xl font-bold text-black dark:text-white">Products</h4>
                <a href="details.php?view=Product" class="font-medium curpointer hover:text-primary">See More</a>
              </div>
              <div class="max-w-full overflow-x-auto">

                <table class="w-full table-auto">
                  <thead>
                    <tr class="bg-gray-2 text-left dark:bg-meta-4">
                      <th class="text-center max-w-[80px] px-4 py-4 font-medium text-black dark:text-white xl:pl-11">
                        Image
                      </th>
                      <th class="text-center min-w-[100px] px-4 py-4 font-medium text-black dark:text-white">
                        Name
                      </th>
                      <th class="text-center min-w-[50px] px-4 py-4 font-medium text-black dark:text-white">
                        Brand
                      </th>
                      <th class="text-center max-w-[50px] px-4 py-4 font-medium text-black dark:text-white">
                        Category
                      </th>
                      <th class="text-center min-w-[120px] px-4 py-4 font-medium text-black dark:text-white">
                        Price
                      </th>
                      <th class="text-center px-4 py-4 font-medium text-black dark:text-white">
                        Sold
                      </th>
                      <th class="text-center px-4 py-4 font-medium text-black dark:text-white">
                        Profit
                      </th>
                      <th class="text-center px-4 py-4 font-medium text-black dark:text-white">
                        action
                      </th>
                    </tr>
                  </thead>
                  <tbody id="tb_product">
                    <tr>

                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- ====== Table Three End -->

            <!-- ====== Table Two Start -->
            <div class="flex flex-row justify-around gap-[1rem] ">
              <div class="rounded-sm border border-stroke bg-white min-w-[49%] shadow-default dark:border-strokedark dark:bg-boxdark">
                <div class="px-4 py-6 md:px-6 xl:px-7.5 flex items-center justify-between">
                  <h4 class="text-xl font-bold text-black dark:text-white">Categories</h4>
                  <a href="details.php?view=Category" class="font-medium curpointer hover:text-primary">See More</a>
                </div>
                <div class="grid grid-cols-6 border-t border-stroke justify-items-center px-4 py-4.5 dark:border-strokedark  md:px-6 2xl:px-7.5">
                  <div class="col-span-1 flex items-center">
                    <p class="font-medium ">UID</p>
                  </div>
                  <div class="col-span-2 items-center sm:flex">
                    <p class="font-medium ">Name</p>
                  </div>
                  <div class="col-span-2 flex items-center">
                    <p class="font-medium ">Number of Relation</p>
                  </div>
                  <div class="col-span-1 flex items-center">
                    <p class="font-medium ">Delete</p>
                  </div>
                </div>

                <div id="container-categories">
                </div>
              </div>

              <div class="rounded-sm border border-stroke bg-white min-w-[49%] shadow-default dark:border-strokedark dark:bg-boxdark">
                <div class="px-4 py-6 md:px-6 xl:px-7.5 flex items-center justify-between">
                  <h4 class="text-xl font-bold text-black dark:text-white">Brands</h4>
                  <a href="details.php?view=Brand" class="font-medium curpointer hover:text-primary">See More</a>
                </div>
                <div
                  class="grid grid-cols-6 border-t border-stroke justify-items-center px-4 py-4.5 dark:border-strokedark  md:px-6 2xl:px-7.5">
                  <div class="col-span-1 flex items-center">
                    <button id="sort-id" class="font-medium cursor-pointer hover:text-primary">UID</button>
                  </div>
                  <div class="col-span-2 items-center sm:flex">
                    <button id="sort-name" class="font-medium cursor-pointer hover:text-primary">Name</button>
                  </div>
                  <div class="col-span-2 flex items-center">
                    <button id="sort-relation" class="font-medium cursor-pointer hover:text-primary">Number of Relation</button>
                  </div>
                  <div class="col-span-1 flex items-center">
                    <button class="font-medium ">Delete</button>
                  </div>
                </div>
                <div id="container-brand">
                </div>
              </div>
            </div>
            <!-- ====== Table Two End -->
          </div>
          <!-- ====== Table Section End -->
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
    <!-- ===== Content Area End ===== -->
  </div>
  <!-- ===== Page Wrapper End ===== -->
  <script defer src="../../js/bundle.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      Promise.all([
          fetch('<?= base_url() ?>/service/api.php?action=getProduct').then(response => response.json()),
          fetch('<?= base_url() ?>/service/api.php?action=getCategory').then(response => response.json()),
          fetch('<?= base_url() ?>/service/api.php?action=getBrands').then(response => response.json())
        ]).then(([products, kategori, brand]) => {



          //products data
          const tb = document.getElementById('tb_product');
          if (products.length === 0) {
            tb.innerHTML = `<td colspan="8" class="p-6 text-center text-gray-500 dark:text-gray-400">
          Tidak ada produk tersedia. <a href="add_product.php" class="text-blue-500 hover:underline">Tambah produk?</a>
          <td />`;
            return;
          }
          products.forEach(row => {
            tb.innerHTML += `
          <tr>
          <td class="flex justify-center items-center border-b border-[#eee] px-4 py-5 pl-9 dark:border-strokedark xl:pl-11">
          <div class="h-12.5 w-15 rounded-md">
          <img src="<?= base_url() ?>/${row.img}" alt="Product" class="rounded w-10 h-auto" />
          </div>
          </td>
          <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark"><p class="text-sm text-center font-medium text-black dark:text-white">${row.product_name}</p></td>
          <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark"><p class="text-sm text-center font-medium text-black dark:text-white">${row.brand}</p></td>
          <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark"><p class="text-sm text-center font-medium text-black dark:text-white">${row.category_name}</p></td>
          <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark"><p class="text-sm text-center font-medium text-black dark:text-white">Rp ${new Intl.NumberFormat('id-ID').format(row.product_price)}</p></td>
          <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark"><p class="text-sm text-center font-medium text-black dark:text-white">${row.total_sold}</p></td>
          <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
          <p class="text-sm text-center font-medium text-meta-3">Rp ${new Intl.NumberFormat('id-ID').format(row.profit)}</p>
          </td>
          <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
          <div class="flex justify-center items-center space-x-3.5">
          <a href="edit.php?id=${row.product_id}" class="hover:text-primary cursor-pointer">
          <svg
          class="fill-current"
                                    width="18"
                                    height="18"
                                    viewBox="0 0 18 18"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                      d="M8.99981 14.8219C3.43106 14.8219 0.674805 9.50624 0.562305 9.28124C0.47793 9.11249 0.47793 8.88749 0.562305 8.71874C0.674805 8.49374 3.43106 3.20624 8.99981 3.20624C14.5686 3.20624 17.3248 8.49374 17.4373 8.71874C17.5217 8.88749 17.5217 9.11249 17.4373 9.28124C17.3248 9.50624 14.5686 14.8219 8.99981 14.8219ZM1.85605 8.99999C2.4748 10.0406 4.89356 13.5562 8.99981 13.5562C13.1061 13.5562 15.5248 10.0406 16.1436 8.99999C15.5248 7.95936 13.1061 4.44374 8.99981 4.44374C4.89356 4.44374 2.4748 7.95936 1.85605 8.99999Z"
                                      fill="" />
                                      <path
                                      d="M9 11.3906C7.67812 11.3906 6.60938 10.3219 6.60938 9C6.60938 7.67813 7.67812 6.60938 9 6.60938C10.3219 6.60938 11.3906 7.67813 11.3906 9C11.3906 10.3219 10.3219 11.3906 9 11.3906ZM9 7.875C8.38125 7.875 7.875 8.38125 7.875 9C7.875 9.61875 8.38125 10.125 9 10.125C9.61875 10.125 10.125 9.61875 10.125 9C10.125 8.38125 9.61875 7.875 9 7.875Z"
                                      fill="" />
                                      </svg>
                                      </a>
                                      <a href="delete.php?id=${row.product_id}&type=product" class="hover:text-primary cursor-pointer">
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
                                      </a>
                                      </div>
                                      </td>
                                      </tr>
                                      `;
          });

          //categories data
          let kateTb = document.getElementById('container-categories');
          if (kategori.length === 0) {
            kateTb.innerHTML = `<div class="p-6 text-center text-gray-500 dark:text-gray-400">
            No category exist. <a href="add_category.php" class="text-blue-500 hover:underline">Add category?</a>
            </div>`;
          }
          kategori.forEach(row => {
            kateTb.innerHTML += `
            <div class="grid grid-cols-6 border-t border-stroke justify-items-center px-4 py-4.5 dark:border-strokedark md:px-6 2xl:px-7.5">
            <div class="col-span-1 flex items-center">
            <p class="text-sm font-medium text-black dark:text-white">${row.id}</p>
                    </div>
                    <div class="col-span-2 flex items-center">
                      <p class="text-sm font-medium text-black dark:text-white">${row.name}</p>
                    </div>
                    <div class="col-span-2 flex items-center">
                      <p class="text-sm font-medium text-meta-3">${row.relate_c}</p>
                    </div>
                    <div class="col-span-1 flex items-center">
                      <a href="delete.php?id=${row.id}&type=category" class="hover:text-primary cursor-pointer">
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
                                </a>
                    </div>`;
          });


          //brand handler
          let brandTb = document.getElementById('container-brand');

          if (brand.length === 0) {
            brandTb.innerHTML = `<div class="p-6 text-center text-gray-500 dark:text-gray-400">
                                 No brand exist. <a href="add_brand.php" class="text-blue-500 hover:underline">Add brand?</a>
                                </div>`;
            return;
          }

          brand.forEach(row => {
            brandTb.innerHTML += `
            <div class="grid grid-cols-6 border-t border-stroke justify-items-center px-4 py-4.5 dark:border-strokedark md:px-6 2xl:px-7.5">
                    <div class="col-span-1 flex items-center">
                      <p class="text-sm font-medium text-black dark:text-white">${row.id}</p>
                    </div>
                    <div class="col-span-2 flex items-center">
                      <p class="text-sm font-medium text-black dark:text-white">${row.name}</p>
                    </div>
                    <div class="col-span-2 flex items-center">
                      <p class="text-sm font-medium text-meta-3">${row.relate_b}</p>
                    </div>
                    <div class="col-span-1 flex items-center">
                      <a href="delete.php?id=${row.id}&type=product" class="hover:text-primary cursor-pointer">
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
                                </a>
                    </div>`;
          });

        })
        .catch(error => {
          console.error('error di :', error)
        });
    })
  </script>
</body>

</html>