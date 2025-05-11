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
        Details
    </title>
    <link href="../../css/output.css" rel="stylesheet">
    <link href="../../assets/images/logo/logo_white.png" rel="icon">
</head>

<body
    x-data="{ page: 'Details', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
                            Details
                        </h2>

                        <nav>
                            <ol class="flex items-center gap-2">
                                <li>
                                    <a class="font-medium hover:text-meta-5" href="index.php">Dashboard /</a>
                                </li>
                                <li class="font-medium text-primary">Details</li>
                            </ol>
                        </nav>
                    </div>


                    <!-- fill content here -->
                    <div
                        class="rounded-sm border border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">

                        <h4 class="text-xl mb-4 font-bold text-black dark:text-white "><?= $_GET['view'] ?></h4>

                        <div class="max-w-full overflow-x-auto">
                            <?php if ($_GET['view'] == 'Product'): ?>
                                <table class="w-full table-auto">

                                    <div class="flex flex-wrap justify-between gap-4 mb-4">
                                        <div class="">
                                            <input type="text" id="search_order" placeholder="Search Order No" class="border rounded p-2 mr-2" />
                                            <button onclick="searchProduct()" class="bg-primary text-white px-4 py-2 rounded">Search</button>
                                        </div>

                                        <div class="flex flex-wrap gap-4 mb-4">
                                            <select id="filter_brand" class="border rounded p-2">
                                                <option value="">All Brands</option>
                                                <!-- Populate via PHP/JS kalau perlu -->
                                            </select>

                                            <select id="filter_category" class="border rounded p-2">
                                                <option value="">All Categories</option>
                                            </select>

                                            <input type="number" id="filter_min_price" placeholder="Min Price" class="border rounded p-2 w-[100px]">
                                            <input type="number" id="filter_max_price" placeholder="Max Price" class="border rounded p-2 w-[100px]">

                                            <button onclick="applyProductFilter()" class="bg-primary text-white px-4 py-2 rounded">Apply</button>
                                            <button onclick="resetFilter()" class="bg-gray-500 text-white px-4 py-2 rounded">Reset</button>
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
                            <?php elseif ($_GET['view'] == 'Brand'): ?>
                                <table class="w-full table-auto">
                                    <div class="mb-5">
                                        <input type="text" id="search_order" placeholder="Search Order No" class="border rounded p-2 mr-2" />
                                        <button onclick="searchOrder()" class="bg-primary text-white px-4 py-2 rounded">Search</button>
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
                                                Delete
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tb_product">
                                        <tr>

                                        </tr>
                                    </tbody>
                                </table>
                            <?php elseif ($_GET['view'] == 'Category'): ?>
                                <table class="w-full table-auto">
                                    <div class="mb-5">
                                        <input type="text" id="search_order" placeholder="Search Order No" class="border rounded p-2 mr-2" />
                                        <button onclick="searchOrder()" class="bg-primary text-white px-4 py-2 rounded">Search</button>
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
                                                Delete
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tb_product">
                                        <tr>

                                        </tr>
                                    </tbody>
                                </table>
                            <?php endif; ?>
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
        document.addEventListener('DOMContentLoaded', function() {
            dataTable();
            loadFilterOptions();
        });

        // Konstanta global
        const BASE_URL = '<?= base_url() ?>/service/api.php?action=';
        const VIEW = '<?= $_GET['view'] ?>';
        const ENDPOINTS = {
            Product: 'getProduct',
            Category: 'getCategory',
            Brand: 'getBrands'
        };

        // Main Data Loader
        function dataTable(filters = {}) {
            const endpoint = ENDPOINTS[VIEW];
            if (!endpoint) return console.error('Invalid view');

            fetchData(endpoint, filters)
                .then(data => renderTable(data))
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

            if (VIEW === 'Product') {
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
                    <td class="text-center px-4 py-2"><button class="bg-primary text-white px-3 py-1 rounded">Edit</button></td>
                </tr>
            `);
                });
            } else {
                data.forEach(item => {
                    const relation = VIEW === 'Category' ? item.relate_c : item.relate_b;
                    tbody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td class="text-center px-4 py-2">${item.id}</td>
                    <td class="text-center px-4 py-2">${item.name}</td>
                    <td class="text-center px-4 py-2">${relation}</td>
                    <td class="text-center px-4 py-2"><button class="bg-red-500 text-white px-3 py-1 rounded">Delete</button></td>
                </tr>
            `);
                });
            }
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
            });
        }

        // Apply filter
        function applyProductFilter() {
            const filters = {
                brand: getValue('filter_brand'),
                category: getValue('filter_category'),
                min_price: getValue('filter_min_price'),
                max_price: getValue('filter_max_price')
            };
            dataTable(cleanObject(filters));
        }

        // Reset filter
        function resetFilter() {
            ['search_order', 'filter_brand', 'filter_category', 'filter_min_price', 'filter_max_price'].forEach(id => {
                document.getElementById(id).value = '';
            });
            dataTable();
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