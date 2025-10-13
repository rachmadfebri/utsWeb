document
  .getElementById("loginForm")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const errorMessage = document.getElementById("errorMessage");

    if (email.trim() === "" || password.trim() === "") {
      errorMessage.style.display = "block";
    } else {
      errorMessage.style.display = "none";
      // Jika valid, arahkan ke halaman profil.
      window.location.href = "profile.html";
    }
  });
