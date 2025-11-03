// Menunggu hingga seluruh konten halaman HTML selesai dimuat
document.addEventListener("DOMContentLoaded", () => {
  // Ambil elemen-elemen penting
  const addWorkButton = document.querySelector(".add-artwork-btn");
  const modalOverlay = document.getElementById("work-modal-overlay");
  const cancelModalButton = document.getElementById("cancel-modal-btn");
  const workForm = document.getElementById("work-form");
  const portfolioGrid = document.getElementById("portfolio-grid");

  // Memanggil fungsi untuk mengambil data profil dan karya
  fetchProfileData();
  fetchWorksData();

  // --- EVENT LISTENERS ---

  // 1. Buka modal saat tombol "Tambah Karya" diklik
  addWorkButton.addEventListener("click", () => {
    openWorkModal("add");
  });

  // 2. Tutup modal saat tombol "Batal" atau area overlay diklik
  cancelModalButton.addEventListener("click", closeWorkModal);
  modalOverlay.addEventListener("click", (e) => {
    if (e.target === modalOverlay) {
      closeWorkModal();
    }
  });

  // 3. Handle submit form (untuk Create dan Update)
  workForm.addEventListener("submit", handleFormSubmit);

  // 4. Handle klik "Edit" dan "Hapus" pada grid
  portfolioGrid.addEventListener("click", (e) => {
    const target = e.target;
    if (target.classList.contains("btn-edit")) {
      const id = target.dataset.id;
      openWorkModal("edit", id);
    } else if (target.classList.contains("btn-delete")) {
      const id = target.dataset.id;
      handleDeleteClick(id);
    }
  });
});

/**
 * Membuka modal untuk mode 'add' (tambah) atau 'edit'.
 * @param {string} mode - 'add' or 'edit'
 * @param {string|null} id - ID karya (hanya untuk mode 'edit')
 */
function openWorkModal(mode, id = null) {
  const modalOverlay = document.getElementById("work-modal-overlay");
  const modalTitle = document.getElementById("modal-title");
  const workForm = document.getElementById("work-form");
  const karyaIdInput = document.getElementById("karya-id");

  workForm.reset(); // Selalu reset form

  if (mode === "add") {
    modalTitle.textContent = "Tambah Karya Baru";
    karyaIdInput.value = ""; // Pastikan ID kosong untuk mode 'add'
    modalOverlay.style.display = "flex";
  } else if (mode === "edit" && id) {
    modalTitle.textContent = "Edit Karya";
    karyaIdInput.value = id;

    // Ambil data lengkap karya dari API (termasuk deskripsi_lengkap)
    fetch(`api/get_karya.php?id=${id}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.error) throw new Error(data.error);
        // Isi form dengan data yang ada
        document.getElementById("judul").value = data.judul;
        document.getElementById("url_gambar").value = data.url_gambar;
        // 'deskripsi_singkat' mungkin tidak ada di get_detail_karya.php, sesuaikan jika perlu
        // Berdasarkan file Anda, get_detail_karya TIDAK mengambil deskripsi_singkat.
        // Mari kita asumsikan kita perlu mengambilnya dari file lain atau menambahkannya.
        // UNTUK SEMENTARA: Kita akan ambil dari get_karya.php (tapi ini tidak ideal)
        // Solusi terbaik: Tambahkan 'deskripsi_singkat' ke query di 'get_detail_karya.php'
        document.getElementById("deskripsi_lengkap").value =
          data.deskripsi_lengkap;
        // Tampilkan modal SETELAH data terisi
        modalOverlay.style.display = "flex";
      })
      .catch((error) => {
        console.error("Gagal mengambil detail karya:", error);
        alert("Gagal memuat data untuk diedit.");
      });
  }
}

/**
 * Menutup modal form.
 */
function closeWorkModal() {
  document.getElementById("work-modal-overlay").style.display = "none";
}

/**
 * Menangani submit form.
 * Membedakan antara CREATE dan UPDATE berdasarkan adanya 'karya-id'.
 * @param {Event} e - Event submit
 */
function handleFormSubmit(e) {
  e.preventDefault(); // Mencegah reload halaman

  const karyaId = document.getElementById("karya-id").value;
  const isEditing = !!karyaId; // true jika ada ID (mode edit), false jika tidak (mode add)

  // Kumpulkan data dari form
  const formData = {
    judul: document.getElementById("judul").value,
    url_gambar: document.getElementById("url_gambar").value,
    deskripsi_singkat: document.getElementById("deskripsi_singkat").value,
    deskripsi_lengkap: document.getElementById("deskripsi_lengkap").value,
  };

  let apiUrl;
  let method = "POST"; // Kita gunakan POST untuk create dan update

  if (isEditing) {
    apiUrl = "api/update_karya.php";
    formData.id = karyaId; // Tambahkan ID ke data yang dikirim
  } else {
    apiUrl = "api/create_karya.php";
  }

  // Kirim data ke API
  fetch(apiUrl, {
    method: method,
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(formData),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert(data.message);
        closeWorkModal();
        fetchWorksData(); // Muat ulang data karya di grid
      } else {
        throw new Error(data.message || "Terjadi kesalahan.");
      }
    })
    .catch((error) => {
      console.error("Error submitting form:", error);
      alert(`Gagal menyimpan: ${error.message}`);
    });
}

/**
 * Menangani klik tombol Hapus.
 * @param {string} id - ID karya yang akan dihapus
 */
function handleDeleteClick(id) {
  if (!confirm("Apakah Anda yakin ingin menghapus karya ini?")) {
    return; // Batalkan jika pengguna menekan 'Cancel'
  }

  fetch("api/delete_karya.php", {
    method: "POST", // Method DELETE juga bisa, tapi POST lebih sederhana untuk JSON
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ id: id }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert(data.message);
        fetchWorksData(); // Muat ulang data karya di grid
      } else {
        throw new Error(data.message || "Gagal menghapus.");
      }
    })
    .catch((error) => {
      console.error("Error deleting work:", error);
      alert(`Gagal menghapus: ${error.message}`);
    });
}

/**
 * Fungsi untuk mengatur animasi scroll.
 */
function setupScrollAnimation() {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible");
          observer.unobserve(entry.target);
        }
      });
    },
    {
      threshold: 0.1,
    }
  );

  const elementsToAnimate = document.querySelectorAll(".reveal-on-scroll");
  elementsToAnimate.forEach((el) => observer.observe(el));
}

/**
 * Mengambil data profil dari API (tidak berubah).
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
    });
}

/**
 * Mengambil data karya dari API dan menampilkannya di grid.
 * [DIMODIFIKASI] untuk menambahkan tombol Edit dan Hapus.
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
        // Buat container baru untuk item dan tombol-tombolnya
        const workContainer = document.createElement("div");
        workContainer.className = "portfolio-item-container reveal-on-scroll";

        workContainer.innerHTML = `
          <a href="artworkItem.html?id=${work.id}" class="portfolio-item">
            <img src="${work.url_gambar}" alt="${work.judul}" onerror="this.onerror=null;this.src='https://placehold.co/600x450/1a1a1a/888888?text=Gagal+Muat';">
            <div class="overlay">
              <h3>${work.judul}</h3>
              <p>${work.deskripsi_singkat}</p>
            </div>
          </a>
          <div class="portfolio-item-actions">
            <button class="btn-edit" data-id="${work.id}">Edit</button>
            <button class="btn-delete" data-id="${work.id}">Hapus</button>
          </div>
        `;
        portfolioGrid.appendChild(workContainer);
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
