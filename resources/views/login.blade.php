<!DOCTYPE html> <!-- Created by CodingLab |www.youtube.com/c/CodingLabYT-->
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8"> <!--<title> Login and Registration Form in HTML & CSS | CodingLab </title>-->
    <link rel="stylesheet" href="/css/style.css"> <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container"> <input type="checkbox" id="flip">
        <div class="cover">
            <div class="front">
                <img src="https://imigrasitanjungbalai.org/V1/assets/img/hero-bg.jpg" alt="">
                <div class="text">
                    <span class="text-1">
                        Layanan Keimigrasian<br>Profesional dan Humanis
                    </span>
                    <span class="text-2">
                        Kantor Imigrasi Kelas II TPI Tanjung Balai Asahan
                    </span>
                </div>
            </div>

            <div class="back">
                <img class="backImg" src="https://imigrasitanjungbalai.org/V1/assets/img/hero-bg.jpg" alt="">
                <div class="text">
                    <span class="text-1">
                        Melayani dengan Integritas<br>Transparansi dan Inovasi
                    </span>
                    <span class="text-2">
                        Bersama Imigrasi, Kita Maju Bersama
                    </span>
                </div>
            </div>
        </div>

        <div class="forms">
            <div class="form-content">

                <!-- ========= LOGIN ========= -->
                <div class="login-form">
                    <div class="title">Login</div>

                    <form method="POST" action="/">
                        @csrf
                        <div class="input-boxes">

                            <!-- error login dari server -->
                            @if ($errors->has('login'))
                                <div class="alert"
                                    style="background:#ffe5e5;color:#b91c1c;border-radius:8px;padding:10px 12px;margin-bottom:12px;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span style="margin-left:8px;">{{ $errors->first('login') }}</span>
                                </div>
                            @endif

                            <div class="input-box">
                                <i class="fas fa-id-badge"></i>
                                <input type="text" name="username" placeholder="Username admin atau NIP" required>
                            </div>

                            <div class="input-box">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="password" placeholder="Password" required>
                            </div>

                            <div class="text" style="display:flex;gap:8px;flex-wrap:wrap;">
                                
                                <span class="hint" style="font-size:12px;color:#64748b;">
                                    Pegawai & Pimpinan: <b>NIP / password</b>
                                </span>
                            </div>

                            <div class="button input-box">
                                <input type="submit" value="Masuk">
                            </div>

                            <div class="text sign-up-text">
                                Butuh panduan? <label for="flip">Lihat cara login</label>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- ========= “SIGNUP” DIGANTI PANDUAN ========= -->
                <div class="signup-form">
                    <div class="title">Panduan Login</div>
                    <div class="input-boxes">

                        <div class="input-box" style="display:flex;align-items:flex-start;gap:10px;">
                           
                            <div>
                                <div><b>Admin</b></div>
                                <div style="font-size:13px;color:#64748b;">Hubungi Support untuk akunnya></div>
                            </div>
                        </div>

                        <div class="input-box" style="display:flex;align-items:flex-start;gap:10px;">
                            
                            <div>
                                <div><b>Pegawai</b></div>
                                <div style="font-size:13px;color:#64748b;">Masuk dengan <b>NIP</b> & password yang telah
                                    ditentukan</div>
                            </div>
                        </div>

                        <div class="input-box" style="display:flex;align-items:flex-start;gap:10px;">
                          
                            <div>
                                <div><b>Pimpinan</b></div>
                                <div style="font-size:13px;color:#64748b;">Masuk dengan <b>NIP</b> & password, lalu
                                    lakukan verifikasi SPD</div>
                            </div>
                        </div>

                        <div class="text sign-up-text">
                            Sudah paham? <label for="flip">Kembali ke Login</label>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</body>

</html>
