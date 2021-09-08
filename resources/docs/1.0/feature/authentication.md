# Autentikasi

---

- [Perkenalan](#introduction)
- [Login](#login)
- [Lupa Password](#forgot-password)

<a name="introduction"></a>
## Perkenalan

Fitur autentikasi berguna untuk membatasi akses fitur dari user dengan sistem. User yang tidak ter-autentikasi tidak akan diijinkan untuk mengakses fitur lainnya yang tersedia

<a name="login"></a>
## Login

![image](/assets/images/docs/feature/authentication/login.png)

Fitur login adalah halaman yang bertujuan untuk memulai autentikasi. User yang memiliki akses data, diharapkan untuk meng-inputkan data username/email dan password pada field yang tersedia.

| Key | Value |
| : |   :-   |
| Url | <a href="{{ route('adm.login') }}">{{ route('adm.login') }}</a> |

<a name="forgot-password"></a>
## Lupa Password

![image](/assets/images/docs/feature/authentication/forgot-password.png)

Fitur Lupa Password berfungsi untuk melakukan reset password pada akun user dengan data terkait. User yang akan melakukan reset password, diharapkan untuk meng-inputkan data email yang di daftarkan pada field yang tersedia. Jika data yang di-inputkan valid, maka setelah beberapa saat sistem akan mengirimkan email untuk merubah password

| Key | Value |
| : |   :-   |
| Url | <a href="{{ route('adm.password.request') }}">{{ route('adm.password.request') }}</a> |

> {info.fa-info-circle} Selain dengan meng-akses url di atas, user juga dapat menuju ke halaman terkait dengan meng-klik url "I forgot my password" pada halaman <a href="{{ route('adm.login') }}">Login</a>
