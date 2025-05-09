$(document).ready(function () {
  $(".faq-form").on("submit", function (e) {
    e.preventDefault(); // Prevent form reload

    var name = $("#name").val().trim();
    var phone = $("#phone").val().trim();
    var email = $("#email").val().trim();
    var message = $("#message").val().trim();

    // Client-side validation
    if (!name || !phone || !email || !message) {
      alert("❗ Semua field wajib diisi.");
      return;
    }

    if (message.length > 200) {
      alert("❗ Pesan terlalu panjang. Maksimal 200 karakter.");
      return;
    }

    // Get the absolute path to the PHP file
    var currentPath = window.location.pathname;
    var directoryPath = currentPath.substring(0, currentPath.lastIndexOf("/"));
    var phpFilePath = directoryPath + "/process_form.php";

    // AJAX form submission with error handling
    $.ajax({
      type: "POST",
      url: "process_form.php", // Use the direct filename if in same directory
      data: $(this).serialize(),
      dataType: "json",
      success: function (response) {
        alert(response.message);
        if (response.status) {
          $(".faq-form")[0].reset(); // Reset form on success
        }
      },
      error: function (xhr, status, error) {
        console.log("AJAX Error:", xhr.responseText);
        alert(
          "❗ Terjadi kesalahan saat mengirim data. Lihat console untuk detail."
        );
      },
    });
  });
});
