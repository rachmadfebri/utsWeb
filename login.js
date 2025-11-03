document
  .getElementById("loginForm")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const errorMessage = document.getElementById("errorMessage");

    const loginData = { email: email, password: password };

    fetch("api/login.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(loginData),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          window.location.href = "profile.html";
        } else {
          errorMessage.textContent =
            data.message || "Email atau password salah.";
          errorMessage.style.display = "block";
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        errorMessage.textContent =
          "Terjadi kesalahan koneksi. Silakan coba lagi.";
        errorMessage.style.display = "block";
      });
  });
