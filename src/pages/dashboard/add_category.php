<?php
session_start();
require '../../../service/utility.php';

if (isset($_SESSION['loggedIn']) == False) {
  header('location: ../auth/index.php');
  exit();
}

if(isset($_SESSION['success'])) {
  echo "<script>alert('$_SESSION[success]');</script>";
  unset($_SESSION['success']);
} else if(isset($_SESSION['err'])) {
  echo "<script>alert('$_SESSION[err]');</script>";
  unset($_SESSION['err']);
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
                Add New Category
              </h2>

              <nav>
                <ol class="flex items-center gap-2">
                  <li>
                    <a class="font-medium hover:text-meta-5" href="index.php">Dashboard /</a>
                  </li>
                  <li class="font-medium text-primary">Add New Category</li>
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
                      Category Information
                    </h3>
                  </div>
                  <div class="p-7">

                    <div class="mb-5.5">
                      <label
                        class="mb-3 block text-sm font-medium text-black dark:text-white"
                        for="categoryName">Name of Category</label>
                      <input
                        class=" w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                        name="categoryName"
                        id="categoryName"
                        type="text" />
                    </div>

                    <div class="flex justify-end gap-4.5 mt-6">
                      <button
                        class="flex justify-center rounded border border-stroke px-6 py-2 font-medium text-black hover:shadow-1 dark:border-strokedark dark:text-white"
                        type="reset">
                        Reset All
                      </button>
                      <button
                        class="flex justify-center rounded bg-primary px-6 py-2 font-medium text-gray hover:bg-opacity-90"
                        type="submit" value="addCategory" name="action">
                        Save
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            <!-- ====== Settings Section End -->
          </div>
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
    <!-- ===== Content Area End ===== -->
  </div>
  <!-- ===== Page Wrapper End ===== -->
  <script defer src="../../js/bundle.js"></script>

</body>

</html>