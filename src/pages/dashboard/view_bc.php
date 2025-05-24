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
  x-data="{ page: 'view_<?=$_GET['view']?>', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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

          <!-- ====== Table Three Start -->
          <div
            class="rounded-sm border border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
            <div class="max-w-full overflow-x-auto">

              <table class="w-full table-auto">

                <div class="flex flex-wrap justify-between gap-4 mb-4">
                  <div class="">
                    <input type="text" id="search_order" placeholder="Search Order No" class="border rounded p-2 mr-2" />
                    <button onclick="search()" class="bg-primary text-white px-4 py-2 rounded cursor-pointer">Search</button>
                  </div>
                </div>

                <thead>
                  <tr class="bg-gray-2 text-left dark:bg-meta-4">
                    <th class="text-center max-w-[80px] px-4 py-4 font-medium text-black dark:text-white xl:pl-11">
                      UID
                    </th>
                    <th class="text-center min-w-[100px] px-4 py-4 font-medium text-black dark:text-white">
                      Name
                    </th>
                    <th class="text-center min-w-[50px] px-4 py-4 font-medium text-black dark:text-white">
                      Number of Relation
                    </th>
                    <th class="text-center px-4 py-4 font-medium text-black dark:text-white">
                      action
                    </th>
                  </tr>
                </thead>
                <tbody id="tb">
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
    });

    // Konstanta global
    const BASE_URL = '<?= base_url() ?>/service/api.php?action=';
    const VIEW = '<?=$_GET['view']?>';
    const ENDPOINTS = {
      brands: 'getBrands',
      categories: 'getCategory'
    };

    // Main Data Loader
    function dataTable(filters = {}, page = 1) {
      const endpoint = ENDPOINTS[VIEW];
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
  const tbody = document.getElementById('tb');
  tbody.innerHTML = '';

  data.forEach(item => {
    const relation = VIEW === 'categories' ? item.relate_c : item.relate_b;
    const endpoint = VIEW.toLowerCase();
    const id = item.id;

    tbody.insertAdjacentHTML('beforeend', `
      <tr>
          <td class="text-center px-4 py-2">${id}</td>
          <td class="text-center px-4 py-2">${item.name}</td>
          <td class="text-center px-4 py-2">${relation}</td>
          <td class="text-center px-4 py-2">
              <button onclick="document.location='edit_bc.php?type=${endpoint}&id=${id}'" class="bg-primary text-white px-3 py-1 rounded">Edit</button>
              <button onclick="if(confirm('Yakin?')) location.href='delete_bc.php?type=${endpoint}&id=${id}'" class="bg-red-500 text-white px-3 py-1 rounded">Delete</button>
          </td>
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

    // Search function (for Product)
    function search() {
      const keyword = document.getElementById('search_order').value.trim();
      dataTable({
        search: keyword
      }, 1);
    }
  </script>
</body>

</html>