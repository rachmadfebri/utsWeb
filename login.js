document
  .getElementById("loginForm")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // Mencegah form refresh halaman

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const errorMessage = document.getElementById("error-message");
    const loginBtn = document.querySelector(".login-btn");

    errorMessage.style.display = "none";
    loginBtn.textContent = "Memproses...";
    loginBtn.disabled = true;

    // Mengirim data ke API PHP menggunakan fetch
    fetch("api/login.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ email: email, password: password }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Jika berhasil, arahkan ke halaman profil
          window.location.href = "profile.html";
        } else {
          // Jika gagal, tampilkan pesan error
          errorMessage.textContent =
            data.message || "Email atau password salah.";
          errorMessage.style.display = "block";
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        errorMessage.textContent = "Terjadi kesalahan jaringan. Coba lagi.";
        errorMessage.style.display = "block";
      })
      .finally(() => {
        loginBtn.textContent = "Masuk";
        loginBtn.disabled = false;
      });
  });
