document.addEventListener("DOMContentLoaded", () => {
  const registerForm = document.getElementById("register-form");
  const errorMessage = document.getElementById("error-message");

  if (registerForm) {
    registerForm.addEventListener("submit", (e) => {
      e.preventDefault();
      errorMessage.textContent = ""; // Kosongkan pesan error

      const namaLengkap = document.getElementById("nama_lengkap").value;
      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;

      const data = {
        nama_lengkap: namaLengkap,
        email: email,
        password: password,
      };

      fetch("api/register_action.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      })
        .then((response) => response.json())
        .then((result) => {
          if (result.success) {
            alert(result.message);
            // Arahkan ke halaman login setelah sukses
            window.location.href = "login.html";
          } else {
            errorMessage.textContent = result.message;
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          errorMessage.textContent = "Terjadi kesalahan. Silakan coba lagi.";
        });
    });
  }
});
