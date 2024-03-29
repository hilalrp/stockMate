<?php

require_once '../koneksi.php';
session_start();

if (isset($_POST["daftar"])) {

    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $confirmpass = password_hash($_POST["cpass"], PASSWORD_DEFAULT);
    //cek form email dan password jika kosong
    if (empty($email)){
        $_SESSION['error'] = "Email harus diisi";
        header("Location: indexDaftar.php");
        exit();
    }else if(empty($password)){
        $_SESSION['error'] = "Password harus diisi";
        header("Location: indexDaftar.php");
        exit();
    }else if(empty($confirmpass)){
        $_SESSION['error'] = "Konfirmasi password berbeda";
        header("Location: indexDaftar.php");
        exit();
    }

    if ($_POST["password"] === $_POST["cpass"]) {
        echo "<script> alert(' Entered passwords are same') </script>";
     }
     else {
        echo "<script> alert(' Please enter same passwords ') </script>";
     }

    //cek jika email sudah digunakan
    $cek = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
    if (mysqli_fetch_assoc($cek)){
        $_SESSION['error'] = "Email sudah digunakan";
        header("Location: indexDaftar.php");
        exit();
    }


    // menambahkan user baru ke dalam database
    mysqli_query($conn, "INSERT INTO users (email, password)
                 VALUES('$email', '$password')");
                 
    // membuat variabel global untuk menampung id dari user yang daftar
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if ($row['email'] == $email) {
            $_SESSION['idUser'] = $row['id'];
        }
    }

    $_SESSION['success'] = "Akun berhasil dibuat, silahkan login";
    header("Location: ../daftarLogin/indexGudang.php");
    return mysqli_affected_rows($conn);
} 

?>
