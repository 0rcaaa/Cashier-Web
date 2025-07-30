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
  <title>Edit Member</title>
  <link href="../../assets/images/logo/logo_white.png" rel="icon">
  <link href="../../css/output.css" rel="stylesheet">
</head>

<body
  x-data="{ page: 'view_members', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
                Edit Member
              </h2>

              <nav>
                <ol class="flex items-center gap-2">
                  <li>
                    <a class="font-medium hover:text-meta-5" href="index.php">Dashboard /</a>
                  </li>
                  <li>
                    <a class="font-medium hover:text-meta-5" href="index.php">View Members /</a>
                  </li>
                  <li class="font-medium text-primary">Edit Member</li>
                </ol>
              </nav>
            </div>
            <!-- Breadcrumb End -->

            <!-- ====== Settings Section Start -->
            <form action="" method="POST" enctype="multipart/form-data" class="grid grid-cols-5 gap-8">
              <div class="col-span-5 xl:col-span-3">
                <div
                  class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                  <div
                    class="border-b border-stroke px-7 py-4 dark:border-strokedark">
                    <h3 class="font-medium text-black dark:text-white">
                      Member Information
                    </h3>
                  </div>
                  <div class="p-7">

                    <div class="mb-5.5">
                      <div class="w-full">
                        <label
                          class="mb-3 block text-sm font-medium text-black dark:text-white"
                          for="username">Name</label>
                        <input
                          class="w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                          type="text"
                          placeholder="Nama member"
                          name="username"
                          id="username" />
                      </div>
                    </div>

                    <div class="mb-5.5">
                      <div class="w-full">
                        <label
                          class="mb-3 block text-sm font-medium text-black dark:text-white"
                          for="exp_at">Expired at</label>
                        <input
                          class="w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                          type="datetime-local"
                          name="exp_at"
                          id="exp_at" />
                      </div>
                    </div>

                    <div class="mb-5.5 flex flex-col gap-5.5 sm:flex-row">
                      <div class="w-full sm:w-1/2">
                        <label
                          class="mb-3 block text-sm font-medium text-black dark:text-white"
                          for="phone">Phone</label>
                        <input
                          class=" w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                          type="text"
                          placeholder="+62 000-0000-000"
                          name="phone"
                          id="phone" />
                      </div>

                      <div class="w-full sm:w-1/2">
                        <label
                          class="mb-3 block text-sm font-medium text-black dark:text-white"
                          for="points">Points</label>
                        <input
                          class="w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                          type="number"
                          name="points"
                          placeholder="0"
                          id="points" />
                      </div>
                    </div>
                    <div class="mb-5.5 flex flex-col gap-5.5 sm:flex-row">
                      <div class="w-full sm:w-1/2">
                        <label
                          class="mb-3 block text-sm font-medium text-black dark:text-white"
                          for="password">Password</label>
                        <input
                          class=" w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                          type="password"
                          placeholder="Masukkan kata sandi jika ingin mengubahnya"
                          name="password"
                          id="password" />
                      </div>

                      <div class="w-full sm:w-1/2">
                        <label
                          class="mb-3 block text-sm font-medium text-black dark:text-white"
                          for="cpass">Confirm Password</label>
                        <input
                          class="[&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none 
                              [&::-moz-appearance:textfield] w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                          type="password"
                          name="cpass"
                          placeholder="Masukkan ulang kata sandi"
                          id="cpass" />
                      </div>
                    </div>

                    <div class="flex justify-end gap-4.5 mt-6">
                      <button
                        class="flex justify-center rounded border border-stroke px-6 py-2 font-medium text-black hover:shadow-1 dark:border-strokedark dark:text-white"
                        type="reset">
                        Reset All
                      </button>
                      <button
                        class="flex justify-center rounded bg-primary px-6 py-2 font-medium text-gray hover:bg-opacity-90"
                        type="submit" value="upMember" name="action">
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
    document.addEventListener("DOMContentLoaded", function() {
      const url = `<?= base_url() ?>/service/api.php?action=getMemberDetails&memId=<?= $_GET['member'] ?>`;
      let originalData = {};

      fetch(url)
        .then(res => res.json())
        .then(data => {
          if (!Array.isArray(data) || data.length === 0) {
            alert("Akun tidak ditemukan");
            window.location.href = "./view_members.php";
            return;
          }
          const mem = data[0];

          originalData = {
            memberId: mem.id,
            name: mem.name || '',
            phone: mem.phone || '',
            points: mem.points || '',
            exp_at : mem.exp_at || ''
          };

          // Isi form input
          document.getElementById('username').value = mem.name || '';
          document.getElementById('phone').value = mem.phone || '';
          document.getElementById('points').value = mem.points || '';
          document.querySelector('input[name="exp_at"]').value = mem.exp_at || '';
        }).catch(err => {
          console.error("Fetch error:", err);
          alert("Terjadi kesalahan saat mengambil data.");
        });

      document.querySelectorAll("#phone").forEach(input => {
        input.addEventListener("input", (e) => {
          e.target.value = e.target.value.replace(/\D/g, "");
        });
      });

      const form = document.querySelector("form");
      const submitButton = form.querySelector('button[type="submit"]');

      form.addEventListener("submit", function(e) {
        e.preventDefault(); // Hentikan form default submit

        const updatedData = {
          name: document.getElementById('username').value.trim(),
          phone: document.getElementById('phone').value.trim(),
          points: parseInt(document.getElementById('points').value),
          exp_at: document.getElementById('exp_at').value
        };

        const pw = document.getElementById('password');
        const cPw = document.getElementById('cpass');
        // Buat FormData dan tambahkan hanya field yang berubah
        const formData = new FormData();
        formData.append('action', 'Member');
        formData.append('memId', <?= $_GET['member'] ?>);

        let hasChanges = false;
        Object.keys(updatedData).forEach(key => {
          if (updatedData[key] !== originalData[key]) {
            formData.append(key, updatedData[key]);
            hasChanges = true;
          }
        });

        // Jika password diisi dan valid, tambahkan
         if (pw.value !== cPw.value) {
          alert("pw gk sama");
          return;
        } else if (pw.value === cPw.value && pw.value !== '') {
          formData.append('password', pw.value);
        }

        if (!hasChanges) {
          alert("Tidak ada perubahan data untuk disimpan.");
          return;
        }

        // Disable tombol
        submitButton.disabled = true;
        submitButton.innerText = "Saving...";

        fetch('<?= base_url() ?>/service/edit.php', {
            method: "POST",
            body: formData
          })
          .then(response => response.json())
          .then(response => {
            console.log(response.message);
            alert(response.message);
            submitButton.disabled = false;
            submitButton.innerText = "Save";
          })
          .catch(error => {
            console.error("Fetch error:", error);
            alert("An error occurred while updating the member. " + error.message);
            submitButton.disabled = false;
            submitButton.innerText = "Save";
          });
      });
    });
  </script>

  <script defer src="../../js/bundle.js"></script>


</body>

</html>