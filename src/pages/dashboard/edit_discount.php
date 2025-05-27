<?php
session_start();
require '../../../service/utility.php';

if (isset($_SESSION['loggedIn']) == False) {
  header('location: ../auth/index.php');
  exit();
}

if (isset($_SESSION['success'])) {
  echo "<script>alert('$_SESSION[success]');</script>";
  unset($_SESSION['success']);
} else if (isset($_SESSION['err'])) {
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
  <title>Add Discount</title>
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
          <div class="mx-auto max-w-270">
            <!-- Breadcrumb Start -->
            <div
              class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
              <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Edit Discount
              </h2>

              <nav>
                <ol class="flex items-center gap-2">
                  <li>
                    <a class="font-medium hover:text-meta-5" href="index.php">Dashboard /</a>
                  </li>
                  <li>
                    <a class="font-medium hover:text-meta-5" href="index.php">View Discounts /</a>
                  </li>
                  <li class="font-medium text-primary">Edit Discount</li>
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
                      Discount Information
                    </h3>
                  </div>
                  <div class="p-7">

                    <div class="mb-5.5">
                      <label
                        class="mb-3 block text-sm font-medium text-black dark:text-white"
                        for="title">Title</label>
                      <input
                        class=" w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                        name="title"
                        id="title"
                        type="text"
                        placeholder="Title" />
                    </div>

                    <div class="mb-5.5 flex flex-col gap-5.5 sm:flex-row">
                      <div class="w-full sm:w-1/2">
                        <label
                          class="mb-3 block text-sm font-medium text-black dark:text-white"
                          for="PR">Points Required</label>
                        <input
                          class="only-numeric w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                          type="text"
                          name="PR"
                          inputmode="numeric"
                          pattern="\d*"
                          id="pr"
                          placeholder="Points Required" />
                      </div>

                      <div class="w-full sm:w-1/2">
                        <label
                          class="mb-3 block text-sm font-medium text-black dark:text-white"
                          for="percentage">Percentage</label>
                        <input
                          class="only-decimal w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                          type="text"
                          name="percentage"
                          inputmode="decimal"
                          id="percentage"
                          max="100"
                          placeholder="In percentage (0-100)" />
                      </div>
                    </div>

                    <div class="mb-5.5">
                      <label
                        class="mb-3 block text-sm font-medium text-black dark:text-white"
                        for="exp">Expiration Date</label>
                      <input
                        class="w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                        name="exp"
                        id="exp"
                        type="datetime-local"
                        value="" />
                    </div>

                    <div class="flex justify-end gap-4.5 mt-6">
                      <button
                        class="flex justify-center rounded border border-stroke px-6 py-2 font-medium text-black hover:shadow-1 dark:border-strokedark dark:text-white"
                        type="reset">
                        Reset All
                      </button>
                      <button
                        class="flex justify-center rounded bg-primary px-6 py-2 font-medium text-gray hover:bg-opacity-90"
                        type="submit" value="upDiscount" name="submit">
                        Add
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
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const url = `<?= base_url() ?>/service/api.php?action=getDiscountDetails&disId=<?= $_GET['discount'] ?>`;
      fetch(url)
        .then(res => res.json())
        .then(data => {
          if (!Array.isArray(data) || data.length === 0) {
            alert("Akun tidak ditemukan");
            window.location.href = "./view_discounts.php";
            return;
          }
          const dis = data[0];

          originalData = {
            title: dis.title || '',
            pr: dis.points_required || '',
            percentage: dis.percentage || '',
            exp: dis.exp_at || '',
          }
          // Isi form input
          document.getElementById('title').value = dis.title || '';
          document.getElementById('pr').value = dis.points_required || '';
          document.getElementById('percentage').value = dis.percentage || '';
          // const dateOnly = new Date(dis.exp_at).toISOString().split('T')[0];
          document.querySelector('input[name="exp"]').value = dis.exp_at || '';

        }).catch(err => {
          console.error("Fetch error:", err);
          alert("Terjadi kesalahan saat mengambil data.");
        });
    })
    const form = document.querySelector("form");

    // Prevent non-numeric input
    document.querySelectorAll(".only-numeric").forEach(input => {
      input.addEventListener("input", (e) => {
        e.target.value = e.target.value.replace(/\D/g, "");
      });
    });

    document.querySelectorAll(".only-decimal").forEach(input => {
      input.addEventListener("input", (e) => {
        let value = e.target.value.replace(/[^0-9.]/g, "");
        if (parseFloat(value) > 100) value = "100";
        e.target.value = value;
      });
    });


    // Tangkap tombol submit (optional, kalau mau disable selama proses)
    const submitButton = form.querySelector('button[type="submit"]'); 

    // Convert types before submission
    form.addEventListener("submit", function(e) {
      e.preventDefault();

      const pr = document.querySelector("#pr");
      const percentage = document.querySelector("#percentage");

      if (percentage.value.trim() !== "") {
        let percentValue = parseFloat(percentage.value);
        if (percentValue > 100) percentValue = 100;
        percentage.value = percentValue;
      }

      const updatedData = {
        title: document.getElementById('title').value.trim(),
        pr: pr.value = pr.value,
        percentage: percentage.value,
        exp: document.querySelector('input[name="exp"]').value
      };
      const isChanged = Object.keys(updatedData).some(key => updatedData[key] !== originalData[key]);
      if (!isChanged) {
        alert('No Changes Made');
        return;
      }

      const formData = new FormData();
      formData.append('action', 'Discount');
      formData.append('DisId', '<?= $_GET['discount'] ?>');
      formData.append('title', updatedData.title);
      formData.append('pr', updatedData.pr);
      formData.append('percentage', updatedData.percentage);
      formData.append('exp', updatedData.exp);

      console.log(formData);
      // Optional: Disable tombol biar ga double submit
      submitButton.disabled = true;
      submitButton.innerText = "Saving...";

      // Kirim pakai Fetch
      fetch('<?= base_url() ?>/service/edit.php', {
          method: "POST",
          body: formData
        })
        .then(response => response.json()) // Asumsikan response JSON (ubah kalau beda)
        .then(response => {
          // Response sukses
          console.log(response.message);
          // Contoh notifikasi
          alert(response.message);
          // Aktifkan tombol lagi
          submitButton.disabled = false;
          submitButton.innerText = "Save";
        })
        .catch(error => {
          console.log(error);
          alert("An error occurred while creating the account." + error.message);

          // Aktifkan tombol lagi
          submitButton.disabled = false;
          submitButton.innerText = "Save";
        });
    });
  </script>
  <script defer src="../../js/bundle.js"></script>


</body>

</html>