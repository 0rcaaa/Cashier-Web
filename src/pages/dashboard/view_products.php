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
  x-data="{ page: 'view_products', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
              View
            </h2>

            <nav>
              <ol class="flex items-center gap-2">
                <li>
                  <a class="font-medium hover:text-meta-5" href="index.php">Dashboard /</a>
                </li>
                <li class="font-medium text-primary">View Products</li>
              </ol>
            </nav>
          </div>
          <!-- Breadcrumb End -->

          <!-- ====== Table Three Start -->
          <div
            class="rounded-sm border border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
            <div class="max-w-full overflow-x-auto">

              <table class="w-full table-auto">

                <div class="flex flex-wrap justify-between items-end gap-4 mb-4">
                  <div class="flex items-end gap-2">
                    <div class="flex flex-col">
                      <label for="search">Search</label>
                      <input type="text" name="search" id="search_order" placeholder="Search by Name" class="border rounded p-2 mr-2" />
                    </div>
                    <button onclick="searchProduct()" class="bg-primary text-white px-4 py-2 rounded cursor-pointer">Search</button>
                  </div>

                  <div class="flex flex-wrap gap-4">
                    <div class="flex flex-col">
                      <label for="brand">Brand</label>
                      <select name="brand" id="filter_brand" class="border rounded p-2">
                        <option value="">All Brands</option>
                      </select>
                    </div>
                    <div class="flex flex-col">
                      <label for="category">Category</label>
                      <select name="category" id="filter_category" class="border rounded p-2">
                        <option value="">All Category</option>
                      </select>
                    </div>

                    <div class="flex flex-col">
                      <label for="min">Minimum Price</label>
                      <input type="number" name="min" id="filter_min_price" placeholder="Min Price" class="border rounded p-2 w-[100px]">
                    </div>
                    <div class="flex flex-col">
                      <label for="max">Maximum Price</label>
                      <input type="number" name="max" id="filter_max_price" placeholder="Max Price" class="border rounded p-2 w-[100px]">
                    </div>
                    <div class="flex items-end gap-2">
                        <button onclick="applyFilter()" class="bg-primary text-white px-4 py-2 rounded">Apply</button>
                      <button onclick="resetFilter()" class="bg-gray-500 text-white px-4 py-2 rounded">Reset</button>
                    </div>
                  </div>
                </div>

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
              <div id="pagination" class="flex gap-2 justify-center mt-4"></div>
            </div>
          </div>
          <!-- ====== Table Three -->
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
    <!-- ===== Content Area End ===== -->
  </div>
  <!-- ===== Page Wrapper End ===== -->
  <script defer src="../../js/bundle.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      dataTable();
      loadFilterOptions();
    });

    // Konstanta global
    const BASE_URL = '<?= base_url() ?>/service/api.php?action=';
    const ENDPOINTS = {
      Product: 'getProduct'
    };

    // Main Data Loader
    function dataTable(filters = {}, page = 1) {
      const endpoint = ENDPOINTS.Product;
      if (!endpoint) return console.error('Invalid view');

      const params = {
        ...filters,
        page,
        limit: 3 //limit per page
      };

      fetchData(endpoint, params)
        .then(res => {
          renderTable(res.data);
          renderPagination(res.current_page, res.total_pages, newPage => {
            dataTable(filters, newPage);
          });
        })
        .catch(err => console.error('Failed fetch:', err));
    }

    // Reusable fetch function
  function fetchData(action, params = {}) {
      const query = new URLSearchParams(params).toString();
      return fetch(`${BASE_URL}${action}&${query}`)
        .then(res => {
          if (!res.ok) throw new Error('Network error');
          return res.json();
        });
    }

    // Render Table
    function renderTable(data) {
      const tbody = document.getElementById('tb_product');
      tbody.innerHTML = '';

      data.forEach(item => {
        tbody.insertAdjacentHTML('beforeend', `
              <tr>
                  <td class="text-center px-4 py-2"><img src="<?= base_url() ?>/${item.img}" alt="${item.product_name}" class="h-12 mx-auto"></td>
                  <td class="text-center px-4 py-2">${item.product_name}</td>
                  <td class="text-center px-4 py-2">${item.brand}</td>
                  <td class="text-center px-4 py-2">${item.category_name}</td>
                  <td class="text-center px-4 py-2">Rp ${formatRupiah(item.product_price)}</td>
                  <td class="text-center px-4 py-2">${item.total_sold}</td>
                  <td class="text-center px-4 py-2">Rp ${formatRupiah(item.profit)}</td>
                  <td class="text-center px-4 py-2"><button onclick="document.location='edit_product.php?product=${item.product_id}'" class="bg-primary text-white px-3 py-1 rounded">Edit</button></td>
              </tr>
          `);
      });

    }
    
    //pagination
    function renderPagination(currentPage, totalPages, onPageClick) {
      const container = document.getElementById('pagination');
      container.innerHTML = '';

      if (totalPages <= 1) return;

      const createButton = (label, page, disabled = false, active = false) => {
        const btn = document.createElement('button');
        btn.textContent = label;
        btn.className = `px-3 py-1 mx-1 rounded border 
                         ${active ? 'bg-blue-500 text-white' : 'bg-white text-gray-700'} 
                         ${disabled ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-100'}`;
        btn.disabled = disabled;
        if (!disabled) {
          btn.addEventListener('click', () => onPageClick(page));
        }
        return btn;
      };

      container.appendChild(createButton('Prev', currentPage - 1, currentPage === 1));

      for (let i = 1; i <= totalPages; i++) {
        container.appendChild(createButton(i, i, false, i === currentPage));
      }

      container.appendChild(createButton('Next', currentPage + 1, currentPage === totalPages));
    }
    
    // Load filter dropdown (brand & category)
    function loadFilterOptions() {
      const filters = [{
          id: 'filter_brand',
          action: 'getBrands'
        },
        {
          id: 'filter_category',
          action: 'getCategory'
        }
      ];

      filters.forEach(f => {
        fetchData(f.action).then(data => {
          const select = document.getElementById(f.id);
          data.forEach(item => {
            select.insertAdjacentHTML('beforeend',
              `<option value="${item.name}">${item.name}</option>`);
          });
        });
      });
    }

    // Search function (for Product)
    function searchProduct() {
      const keyword = document.getElementById('search_order').value.trim();
      dataTable({
        search: keyword
      }, 1);
    }

    // Apply filter
    function applyProductFilter() {
      document.getElementById('search_order').value = '';
      const filters = {
        brand: getValue('filter_brand'),
        category: getValue('filter_category'),
        min_price: getValue('filter_min_price'),
        max_price: getValue('filter_max_price'),
      };
      dataTable(cleanObject(filters), 1);
    }

    // Reset filter
    function resetFilter() {
      ['search_order', 'filter_brand', 'filter_category', 'filter_min_price', 'filter_max_price'].forEach(id => {
        document.getElementById(id).value = '';
      });
      dataTable({}, 1);
    }

    // Helper clean params (buang null/empty)
    function cleanObject(obj) {
      return Object.fromEntries(Object.entries(obj).filter(([_, v]) => v !== '' && v !== null));
    }

    // Helper get value
    function getValue(id) {
      return document.getElementById(id).value;
    }

    // Format angka ke Rupiah
    function formatRupiah(number) {
      return new Intl.NumberFormat('id-ID').format(number);
    }
  </script>
</body>

</html>