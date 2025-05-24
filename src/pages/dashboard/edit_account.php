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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
  <link href="../../assets/images/logo/logo_white.png" rel="icon">
  <link href="../../css/output.css" rel="stylesheet">
</head>

<body
  x-data="{ page: 'view_accounts', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
                  <li>
                    <a class="font-medium hover:text-meta-5" href="index.php">View Accounts /</a>
                  </li>
                  <li class="font-medium text-primary">Edit Account</li>
                </ol>
              </nav>
            </div>
            <!-- Breadcrumb End -->

            <!-- ====== Settings Section Start -->
            <form action="<?= base_url() ?>/service/auth.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-5 gap-8">
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

                    <div class="mb-5.5">
                      <div class="w-full">
                        <label
                          class="mb-3 block text-sm font-medium text-black dark:text-white"
                          for="username">Name</label>
                        <input
                          class="w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                          type="text"
                          name="username"
                          id="username" />
                      </div>
                    </div>

                    <div class="mb-5.5">
                      <div class="w-full">
                        <label
                          class="mb-3 block text-sm font-medium text-black dark:text-white"
                          for="email">Email</label>
                        <input
                          class="w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                          type="text"
                          name="email"
                          id="email" />
                      </div>
                    </div>

                    <div class="mb-5.5">
                      <label
                        class="mb-3 block text-sm font-medium text-black dark:text-white"
                        for="role">Role</label>
                      <select
                        class=" w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                        name="role"
                        id="role">
                        <option value="Cashier" class="dark:text-white">kasir</option>
                        <option value="Admin" class="dark:text-white">admin</option>
                      </select>
                    </div>

                    <div class="mb-5.5">
                      <div class="w-full">
                        <label
                          class="mb-3 block text-sm font-medium text-black dark:text-white"
                          for="password">Password</label>
                        <input
                          class="w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                          type="password"
                          name="password"
                          id="password" />
                      </div>
                    </div>

                    <div class="mb-5.5">
                      <div class="w-full">
                        <label
                          class="mb-3 block text-sm font-medium text-black dark:text-white"
                          for="password">Confirm Password</label>
                        <input
                          class="w-full rounded border border-stroke bg-gray px-4.5 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white dark:focus:border-primary"
                          type="password"
                          name="cpassword"
                          id="cpassword" />
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
                      User Profile
                    </h3>
                  </div>
                  <div class="p-7">
                    <div
                      id="FileUpload"
                      accept="image/*"
                      class="relative mb-5.5 block w-full cursor-pointer appearance-none rounded border border-dashed border-primary bg-gray px-4 py-4 dark:bg-meta-4 sm:py-7.5">
                      <input
                        type="file"
                        name="image"
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
                    type="submit" value="upAcc" name="action">
                    Save
                  </button>
                </div>
              </div>
            </form>
            <!-- ====== Settings Section End -->
            <!-- Modal Crop -->
            <div id="cropModal" class="hidden fixed inset-0 bg-gray-500/50 flex items-center justify-center z-100">
              <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h2 class="text-lg font-semibold mb-4">Crop Image</h2>
                <div class="w-full h-64 overflow-hidden mb-4">
                  <img id="cropImage" class="max-w-full max-h-full object-contain mx-auto" />
                </div>
                <div class="flex justify-end space-x-2">
                  <button onclick="closeCropModal()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</button>
                  <button onclick="cropImage()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Crop</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
    <!-- ===== Content Area End ===== -->
  </div>
  <!-- ===== Page Wrapper End ===== -->
  <script>
    let croppedImageBlob = null; // Simpan hasil crop di global scope

    document.addEventListener("DOMContentLoaded", function() {
      setupImageUpload();
      getData(<?=$_GET['account'] ?>);

      const form = document.querySelector("form");

      // Tangkap tombol submit (optional, kalau mau disable selama proses)
      const submitButton = form.querySelector('button[type="submit"]');

      // Intercept submit form
      form.addEventListener("submit", function(e) {
        e.preventDefault(); // Hentikan form default submit

        // Buat FormData dari form
        const formData = new FormData(form);
        formData.append("action", "new_acc");

        if (croppedImageBlob) {
          formData.delete('image'); // Hapus file asli dari input
          formData.append('image', croppedImageBlob, 'cropped.png'); // Tambah hasil crop
        }

        debugImageBeforeSubmit(formData);

        // Optional: Disable tombol biar ga double submit
        submitButton.disabled = true;
        submitButton.innerText = "Saving...";

        // Kirim pakai Fetch
        fetch('<?= base_url() ?>/service/auth.php', {
            method: "POST",
            body: formData
          })
          .then(response => response.json()) // Asumsikan response JSON (ubah kalau beda)
          .then(response => {
            // Response sukses
            console.log(response.message);

            // Contoh notifikasi
            alert(response.message);

            // Reset form kalau mau
            form.reset();
            resetImage();

            // Aktifkan tombol lagi
            submitButton.disabled = false;
            submitButton.innerText = "Save";
          })
          .catch(error => {
            console.error("Error:", error);
            alert("An error occurred while creating the account.");

            // Aktifkan tombol lagi
            submitButton.disabled = false;
            submitButton.innerText = "Save";
          });
      });
    });

    function getData(id) {
      const url = `<?= base_url() ?>/service/api.php?action=getAccountDetails&accId=${id}`;
      fetch(url)
        .then(res => res.json())
        .then(data => {
          if (!Array.isArray(data) || data.length === 0) {
            alert("Akun tidak ditemukan");
            window.location.href = "./view_accounts.php";
            return;
          }
          const acc = data[0];

          // Isi form input
          document.getElementById('username').value = acc.username || '';
          document.getElementById('email').value = acc.email || '';
          document.getElementById('role').value = acc.role || '';

          // Preview gambar
          if (acc.image) {
            const imgPath = '<?= base_url() ?>/' + acc.image;
            document.getElementById('previewImage').src = imgPath;
            document.getElementById('uploadedImg').classList.remove('hidden');
            document.getElementById('pImageTitles').classList.add('hidden');
          }
        }).catch(err => {
          console.error("Fetch error:", err);
          alert("Terjadi kesalahan saat mengambil data produk.");
        });
    }

    function debugImageBeforeSubmit(formData) {
      console.log("=== DEBUG: FormData Contents ===");
      for (let [key, value] of formData.entries()) {
        if (key === 'image') {
          console.log(`Image sent:`, value);
          if (value instanceof Blob) {
            console.log(`Image is a Blob (likely cropped). Size: ${value.size} bytes, Type: ${value.type}`);
            if (value instanceof File) {
              console.log(`(Note: It's a File Blob. Name: ${value.name})`);
            }
          } else if (value instanceof File) {
            console.log(`Image is a File (original upload). Name: ${value.name}, Size: ${value.size} bytes`);
          } else {
            console.log(`Image type: ${typeof value}`);
          }
        } else {
          console.log(`${key}: ${value}`);
        }
      }
      console.log("=== END DEBUG ===");
    }


    function resetImage() {
      const img = document.getElementById('previewImage');
      const uploadedImg = document.getElementById('uploadedImg');
      const pImageTitles = document.getElementById('pImageTitles');

      img.src = '';
      uploadedImg.classList.add('hidden');
      pImageTitles.style.display = 'flex';

      croppedImageBlob = null; // Reset hasil crop
    }

    let cropper;

    function setupImageUpload() {
      const fileInput = document.querySelector('#FileUpload input[type="file"]');
      const cropImage = document.getElementById('cropImage');
      fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
          const url = URL.createObjectURL(file);
          cropImage.src = url;
          document.getElementById('cropModal').style.display = 'flex';
          cropImage.onload = () => {
            if (cropper) cropper.destroy();
            cropper = new Cropper(cropImage, {
              aspectRatio: 1,
              viewMode: 1
            });
          };
        }
      });
    }

    function cropImage() {
      if (cropper) {
        cropper.getCroppedCanvas({
          width: 800,
          height: 800
        }).toBlob(blob => {
          croppedImageBlob = blob;

          const img = document.getElementById('previewImage');
          img.src = URL.createObjectURL(blob);
          document.getElementById('uploadedImg').classList.remove('hidden');
          document.getElementById('pImageTitles').style.display = 'none';

          closeCropModal();
        });
      }
    }

    function closeCropModal() {
      document.getElementById('cropModal').style.display = 'none';
    }
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
  <script defer src="../../js/bundle.js"></script>


</body>

</html>