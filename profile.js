// Menunggu hingga seluruh konten halaman HTML selesai dimuat
document.addEventListener("DOMContentLoaded", () => {
  // Memanggil fungsi untuk mengambil data profil dan karya
  fetchProfileData();
  fetchWorksData();
});

/**
 * Fungsi untuk mengatur animasi scroll.
 * Mengamati elemen dengan kelas .reveal-on-scroll dan menambahkan kelas .visible saat masuk ke layar.
 */
function setupScrollAnimation() {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible");
          observer.unobserve(entry.target); // Berhenti mengamati setelah animasi berjalan
        }
      });
    },
    {
      threshold: 0.1, // Memicu saat 10% elemen terlihat
    }
  );

  const elementsToAnimate = document.querySelectorAll(".reveal-on-scroll");
  elementsToAnimate.forEach((el) => observer.observe(el));
}

/**
 * Mengambil data profil dari API dan menampilkannya di sidebar.
 */
function fetchProfileData() {
  fetch("api/get_profil.php")
    .then((response) => {
      if (!response.ok) throw new Error("Gagal mengambil data profil");
      return response.json();
    })
    .then((data) => {
      document.getElementById("profile-avatar").src =
        data.avatar_url || "https://placehold.co/120x120/00ffff/0a0a0a?text=A";
      document.getElementById("profile-name").textContent =
        data.nama_lengkap || "Nama Pengguna";
      document.getElementById("profile-title").textContent =
        data.jabatan || "Jabatan";
      document.getElementById("profile-bio").textContent =
        data.bio || "Bio pengguna...";
    })
    .catch((error) => {
      console.error("Error fetching profile:", error);
      // Anda bisa menambahkan pesan error di UI jika mau
    });
}

/**
 * Mengambil data karya dari API dan menampilkannya di grid portofolio.
 */
function fetchWorksData() {
  const portfolioGrid = document.getElementById("portfolio-grid");
  if (!portfolioGrid) {
    console.error("Elemen #portfolio-grid tidak ditemukan!");
    return;
  }

  fetch("api/get_karya.php")
    .then((response) => {
      if (!response.ok) throw new Error("Network response was not ok");
      return response.json();
    })
    .then((data) => {
      portfolioGrid.innerHTML = ""; // Bersihkan placeholder "Memuat..."
      if (data.length === 0) {
        portfolioGrid.innerHTML = "<p>Belum ada karya untuk ditampilkan.</p>";
        return;
      }

      data.forEach((work) => {
        const workItem = document.createElement("a");
        workItem.href = `detail-karya.html?id=${work.id}`;
        workItem.className = "portfolio-item reveal-on-scroll";
        workItem.innerHTML = `
                    <img src="${work.url_gambar}" alt="${work.judul}" onerror="this.onerror=null;this.src='https://placehold.co/600x450/1a1a1a/888888?text=Gagal+Muat';">
                    <div class="overlay">
                        <h3>${work.judul}</h3>
                        <p>${work.deskripsi_singkat}</p>
                    </div>
                `;
        portfolioGrid.appendChild(workItem);
      });

      // Memanggil fungsi animasi SETELAH semua elemen karya dibuat
      setupScrollAnimation();
    })
    .catch((error) => {
      console.error("Error fetching works:", error);
      portfolioGrid.innerHTML =
        '<p class="error-message">Gagal memuat karya. Silakan coba lagi nanti.</p>';
    });
}
