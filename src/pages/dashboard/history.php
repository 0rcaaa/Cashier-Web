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
    History
  </title>
  <link href="../../css/output.css" rel="stylesheet">
  <link href="../../assets/images/logo/logo_white.png" rel="icon">
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
              History
            </h2>

            <nav>
              <ol class="flex items-center gap-2">
                <li>
                  <a class="font-medium hover:text-meta-5" href="index.php">Dashboard /</a>
                </li>
                <li class="font-medium text-primary">History</li>
              </ol>
            </nav>
          </div>


          <!-- fill content here -->
          <div
            class="rounded-sm border border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
            <div class="px-4 py-6 md:px-6 xl:px-7.5 flex items-center justify-between">
              <h4 class="text-xl font-bold text-black dark:text-white">Transactions</h4>
            </div>
            <div class="max-w-full overflow-x-auto">
              <table class="w-full table-auto">

                <div class="flex flex-wrap justify-between items-end gap-4 mb-4">
                  <!-- Search -->
                  <div class="flex items-end gap-2">
                    <input type="text" id="search_order" placeholder="Search Order No" class="border rounded p-2" />
                    <button onclick="searchOrder()" class="bg-primary text-white px-4 py-2 rounded">Search</button>
                  </div>

                  <!-- Filters -->
                  <div class="flex flex-wrap items-end gap-4">
                    <div class="flex flex-col">
                      <label for="filter_member">Filter Member</label>
                      <select id="filter_member" name="filter_member" class="border rounded p-2 w-30">
                        <option value="">All Members</option>
                        <option value="registered">Registered</option>
                        <option value="unregistered">Unregistered</option>
                      </select>
                    </div>

                    <div class="flex flex-col">
                      <label for="filter_status">Filter Status</label>
                      <select id="filter_status" name="filter_status" class="border rounded p-2 w-30">
                        <option value="">All Status</option>
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                        <option value="declined">Declined</option>
                      </select>
                    </div>

                    <div class="flex flex-col">
                      <label for="filter_date_from">Date (From)</label>
                      <input type="date" name="date(from)" id="filter_date_from" class="border rounded p-2 w-30" />
                    </div>

                    <div class="flex flex-col">
                      <label for="filter_date_to">Date (To)</label>
                      <input type="date" name="date(to)" id="filter_date_to" class="border rounded p-2 w-30" />
                    </div>
                    <!-- Apply / Reset -->
                    <div class="flex items-end gap-2">
                      <button onclick="applyFilter()" class="bg-primary text-white px-4 py-2 rounded">Apply</button>
                      <button onclick="resetFilter()" class="bg-gray-500 text-white px-4 py-2 rounded">Reset</button>
                    </div>
                  </div>

                </div>

                <thead>
                  <tr class="bg-gray-2 text-left dark:bg-meta-4">
                    <th class="text-center max-w-[80px] px-4 py-4 font-medium text-black dark:text-white xl:pl-11">
                      No Order
                    </th>
                    <th class="text-center min-w-[100px] px-4 py-4 font-medium text-black dark:text-white">
                      Member
                    </th>
                    <th class="text-center min-w-[50px] px-4 py-4 font-medium text-black dark:text-white">
                      Qty
                    </th>
                    <th class="text-center max-w-[50px] px-4 py-4 font-medium text-black dark:text-white">
                      Total Price
                    </th>
                    <th class="text-center min-w-[120px] px-4 py-4 font-medium text-black dark:text-white">
                      Status
                    </th>
                    <th class="text-center px-4 py-4 font-medium text-black dark:text-white">
                      Created At
                    </th>
                    <th class="text-center px-4 py-4 font-medium text-black dark:text-white">
                      action
                    </th>
                  </tr>
                </thead>
                <tbody id="tb_product">

                </tbody>
              </table>
              <div id="pagination" class="flex gap-2 mt-4"></div>
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
  let allOrders = [];
  const ITEMS_PER_PAGE = 7;
  let currentPage = 1;

  document.addEventListener('DOMContentLoaded', function() {
    applyFilter();

    // Event listener search (Enter key)
    document.getElementById('search_order').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') searchOrder();
    });

    // Sinkronisasi tanggal filter
    document.getElementById('filter_date_from').addEventListener('change', function() {
      document.getElementById('filter_date_to').min = this.value;
    });
    document.getElementById('filter_date_to').addEventListener('change', function() {
      document.getElementById('filter_date_from').max = this.value;
    });
  });

  function getOrders(filters = {}) {
    const params = new URLSearchParams({ action: 'get_orders', ...filters });

    fetch(`<?= base_url() ?>/service/api.php?${params}`)
      .then(response => response.json())
      .then(data => {
        allOrders = Array.isArray(data) ? data : [];
        currentPage = 1;
        renderTable();
        renderPagination();
      })
      .catch(error => console.error('Error:', error));
  }

  function renderTable() {
    const tbody = document.getElementById('tb_product');
    tbody.innerHTML = '';

    const start = (currentPage - 1) * ITEMS_PER_PAGE;
    const end = start + ITEMS_PER_PAGE;
    const paginatedOrders = allOrders.slice(start, end);

    if (paginatedOrders.length > 0) {
      paginatedOrders.forEach(order => {
        tbody.innerHTML += `
          <tr>
            <td class="text-center px-4 py-2">${order.order_number}</td>
            <td class="text-center px-4 py-2">${order.member_name ?? 'Unregistered'}</td>
            <td class="text-center px-4 py-2">${order.qty}</td>
            <td class="text-center px-4 py-2">Rp ${Number(order.total_price).toLocaleString('id-ID')}</td>
            <td class="text-center px-4 py-2">${capitalize(order.status)}</td>
            <td class="text-center px-4 py-2">${formatDate(order.date)}</td>
            <td class="text-center px-4 py-2">
              <a href="transaction_details.php?order=${order.order_number}" class="text-primary hover:underline">View</a>
            </td>
          </tr>`;
      });
    } else {
      tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4">No data found</td></tr>`;
    }
  }

  function renderPagination() {
    let container = document.getElementById('pagination_controls');
    if (!container) {
      container = document.createElement('div');
      container.id = 'pagination_controls';
      container.className = 'flex justify-center mt-4';
      document.querySelector('main').appendChild(container);
    }

    container.innerHTML = '';
    const totalPages = Math.ceil(allOrders.length / ITEMS_PER_PAGE);
    if (totalPages <= 1) return;

    // Prev button
    container.innerHTML += `<button onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} class="px-2 py-1 border rounded mx-1 ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}">Prev</button>`;

    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
      container.innerHTML += `<button onclick="changePage(${i})" class="px-2 py-1 border rounded mx-1 ${i === currentPage ? 'bg-primary text-white' : ''}">${i}</button>`;
    }

    // Next button
    container.innerHTML += `<button onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} class="px-2 py-1 border rounded mx-1 ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}">Next</button>`;
  }

  function changePage(page) {
    const totalPages = Math.ceil(allOrders.length / ITEMS_PER_PAGE);
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    renderTable();
    renderPagination();
  }

  function capitalize(str) {
    return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
  }

  function formatDate(dateStr) {
    const d = new Date(dateStr);
    return d.toLocaleDateString('id-ID');
  }

  function applyFilter() {
    const filters = {
      member: document.getElementById('filter_member').value,
      status: document.getElementById('filter_status').value,
      date_from: document.getElementById('filter_date_from').value,
      date_to: document.getElementById('filter_date_to').value
    };
    getOrders(filters);
  }

  function resetFilter() {
    document.getElementById('filter_member').value = '';
    document.getElementById('filter_status').value = '';
    document.getElementById('filter_date_from').value = '';
    document.getElementById('filter_date_to').value = '';
    applyFilter();
  }

  function searchOrder() {
    const search = document.getElementById('search_order').value.trim();
    if (search) {
      getOrders({ search });
    } else {
      applyFilter();
    }
  }
</script>

</body>

</html>