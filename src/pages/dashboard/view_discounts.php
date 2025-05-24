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
  x-data="{ page: 'view_discounts', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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

                <div class="flex flex-wrap justify-between items-end gap-4 mb-4">
                  <!-- Search -->
                  <div class="flex items-end gap-2">
                    <div class="flex flex-col">
                      <label for="search">Search Discount</label>
                      <input name="search "type="text" id="search" placeholder="Search Title" class="border rounded p-2" />
                    </div>
                    <button onclick="search()" class="bg-primary text-white px-4 py-2 rounded">Search</button>
                  </div>

                  <!-- Filters -->
                  <div class="flex flex-wrap items-end gap-4">
                    

                    <div class="flex flex-col">
                      <label for="filter_status">Filter Status</label>
                      <select id="filter_status" name="filter_status" class="border rounded p-2 w-30">
                        <option value="">All Status</option>
                        <option value="1">Available</option>
                        <option value="0">Expired</option>
                      </select>
                    </div>

                    <div class="flex flex-col">
                      <label for="minPoints">Min Points</label>
                      <input type="number" placeholder="0" name="min" id="minPoints" class="border rounded p-2 w-30" />
                    </div>

                    <div class="flex flex-col">
                      <label for="maxPoints">Max Points</label>
                      <input type="number" placeholder="0" name="max" id="maxPoints" class="border rounded p-2 w-30" />
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
                    <th class="text-center px-4 py-4 font-medium text-black dark:text-white">
                      Title
                    </th>
                    <th class="text-center px-4 py-4 font-medium text-black dark:text-white">
                      Percentage
                    </th>
                    <th class="text-center px-4 py-4 font-medium text-black dark:text-white">
                      Points Required
                    </th>
                    <th class="text-center px-4 py-4 font-medium text-black dark:text-white">
                      Created at
                    </th>
                    <th class="text-center px-4 py-4 font-medium text-black dark:text-white">
                      Expired at
                    </th>
                    <th class="text-center px-4 py-4 font-medium text-black dark:text-white">
                      Used
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
    document.addEventListener('DOMContentLoaded', function(){
      dataTable();
    });
    //fetch data from api
        function fetchData(params = {}){
      const query = new URLSearchParams(params).toString();
      return fetch(`<?= base_url()?>/service/api.php?action=getDiscounts&${query}`)
        .then(response =>{
          if (!response.ok){
            throw new Error('Network response was not daijoubu');
          }
          return response.json();
        })
    }
    //ambil data
    function dataTable(filters = {}, page = 1){
      const params ={
        ...filters,
        page,
        limit: 3
      }
      fetchData(params)
        .then(response => {
          renderTable(response.data);
          renderPagination(response.current_page, response.total_pages, newPage =>{
            dataTable(filters, newPage);
          });
        })
        .catch(error=> console.error('Error fetching data:', error));
    }
    //buat record table
    function renderTable(data){
      const TB = document.getElementById('tb');
      TB.innerHTML = '';
      data.forEach(item => {
        TB.insertAdjacentHTML('beforeend', `
              <tr>
                  <td class="text-center px-4 py-2">${item.Title}</td>
                  <td class="text-center px-4 py-2">${item.Percentage}%</td>
                  <td class="text-center px-4 py-2">${item.PR}</td>
                  <td class="text-center px-4 py-2">${item.CAT}</td>
                  <td class="text-center px-4 py-2">${item.Exp}</td>
                  <td class="text-center px-4 py-2">${item.Used}</td>
                  <td class="text-center px-4 py-2"><button onclick="document.location='edit_discount.php?discount=${item.id}'" class="bg-primary text-white px-3 py-1 rounded">Edit</button></td>
              </tr>
          `);
        
      });
    }
    //buat pagination
    function renderPagination(currentPage, totalPages, onChangePage){
      const pagination = document.getElementById('pagination');
      pagination.innerHTML = '';

      if(totalPages <= 1)return;

       const createButton = (label, page, disabled = false, active = false) => {
        const btn = document.createElement('button');
        btn.textContent = label;
        btn.className = `px-3 py-1 mx-1 rounded border 
                         ${active ? 'bg-blue-500 text-white' : 'bg-white text-gray-700'} 
                         ${disabled ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-100'}`;
        btn.disabled = disabled;
        if (!disabled) {
          btn.addEventListener('click', () => onChangePage(page));
        }
        return btn;
      };

      pagination.appendChild(createButton('Prev', currentPage - 1, currentPage === 1));

      for (let i = 1; i <= totalPages; i++) {
        pagination.appendChild(createButton(i, i, false, i === currentPage));
      }

      pagination.appendChild(createButton('Next', currentPage + 1, currentPage === totalPages));
    }
    // Helper get value
    function getValue(id) {
      return document.getElementById(id).value;
    }
    // searcg function
    function search(){
      const keyword = getValue('search').trim();
      dataTable({search:keyword},1);
    }
    //apply filter
    function applyFilter(){
      const filters = {
        status: getValue('filter_status'),
        min:getValue('minPoints'),
        max:getValue('maxPoints')
      }
       dataTable(cleanObject(filters), 1);
    }
    //reset filter
    function resetFilter(){
      ['filter_status','minPoints','maxPoints'].forEach(id=>{
        document.getElementById(id).value ='';
      })
      dataTable({},1);
    }
    // Helper clean params (buang null/empty)
    function cleanObject(obj) {
      return Object.fromEntries(Object.entries(obj).filter(([_, v]) => v !== '' && v !== null));
    }
  </script>
</body>

</html>