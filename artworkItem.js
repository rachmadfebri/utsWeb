document.addEventListener("DOMContentLoaded", () => {
  // Ambil ID karya dari URL
  const params = new URLSearchParams(window.location.search);
  const karyaId = params.get("id");

  if (!karyaId) {
    document.body.innerHTML =
      "<h1>Error: ID Karya tidak ditemukan.</h1><a href='profile.html'>Kembali</a>";
    return;
  }

  fetchData(karyaId);
});

function fetchData(id) {
  fetch(`api/get_detail_karya.php?id=${id}`)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Respon network tidak ok");
      }
      return response.json();
    })
    .then((data) => {
      if (data.error) {
        throw new Error(data.error);
      }
      // Panggil fungsi untuk mengisi halaman dengan data
      populatePage(data);
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
      document.body.innerHTML = `<h1>Error: ${error.message}</h1><a href='profile.html'>Kembali</a>`;
    });
}

function populatePage(data) {
  // Set judul tab browser
  document.title = data.judul;

  // Set elemen-elemen di halaman
  document.getElementById("artwork-title").textContent = data.judul;
  document.getElementById("artwork-image").src = data.url_gambar;
  document.getElementById("artwork-image").alt = data.judul;

  document.getElementById("creator-name").textContent = data.nama_lengkap;
  document.getElementById("creator-title").textContent = data.jabatan;
  document.getElementById("creator-avatar").src =
    data.avatar_url || "https://placehold.co/48x48/00ffff/0a0a0a?text=A"; // Gambar placeholder

  document.getElementById("artwork-description").textContent =
    data.deskripsi_lengkap;
}
