<?php
session_start();

require_once('../../../service/utility.php');
require_once('../../../service/connection.php');

rememberMe($conn);

if (isset($_SESSION['loggedIn']) == True) {
    header('location: ../dashboard/index.php');
    exit();
}
?>

<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fill Token</title>
    <link rel="icon" href="../../assets/images/logo/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/output.css">
</head>

<body>
    <section class="bg-gray-50 dark:bg-gray-900 font-ubuntu">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                Kasir SMKN 71 Jakarta
            </a>
            <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Masukkan password baru
                    </h1>
                    <div class="space-y-4 md:space-y-6" action="../../../service/auth.php" method="POST">
                        <div>
                            <label for="token" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">password baru</label>
                            <input type="password" name="token" id="np" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="0000" required="">
                        </div>
                        <button onclick="verify()" type="submit" name="action" value="verify" class="w-full text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Verify</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function verify() {
            const password = document.getElementById('np').value;
            const email = new URLSearchParams(window.location.search).get('email');
            if (password === '') {
                alert('New password required');
                return;
            }
            console.log(password);

            fetch('<?= base_url() ?>/service/auth.php', {
                method: 'POST',
                body: JSON.stringify({
                    action: 'NPW',
                    password:password,
                    email: email
                })
            })
            .then(response=>response.json())
            .then(response => {
            if (response.status === 'success') {
            // Contoh notifikasi
            alert(response.message);
            window.location.href = './index.php';
          } else {
            alert("Gagal membuat password baru: " + response.message);
          }
          })
          .catch(error => {
            console.error("Error:", error);
            alert("An error occurred while sending password.");
          })
        }
    </script>
</body>

</html>