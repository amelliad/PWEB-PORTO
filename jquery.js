$(document).ready(function () {
  $(".faq-form").on("submit", function (e) {
    e.preventDefault(); // Cegah reload form

    var name = $("#name").val().trim();
    var phone = $("#phone").val().trim();
    var email = $("#email").val().trim();
    var message = $("#message").val().trim();

    if (!name || !phone || !email || !message) {
      alert("❗ Semua field wajib diisi.");
      return;
    }

    if (message.length > 200) {
      alert("❗ Pesan terlalu panjang. Maksimal 200 karakter.");
      return;
    }

    alert("✅ Pesan berhasil dikirim!");
    $(".faq-form")[0].reset(); // Reset form
  });
});
