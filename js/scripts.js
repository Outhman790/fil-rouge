// Toggle the side navigation
window.addEventListener("DOMContentLoaded", (event) => {
  const sidebarToggle = document.body.querySelector("#sidebarToggle");
  if (sidebarToggle) {
    sidebarToggle.addEventListener("click", (event) => {
      event.preventDefault();
      document.body.classList.toggle("sb-sidenav-toggled");
      localStorage.setItem(
        "sb|sidebar-toggle",
        document.body.classList.contains("sb-sidenav-toggled")
      );
    });
  }
});

// Remove jQuery usage for error modal close button
// Use raw JS to close the modal if the X button is clicked

document.addEventListener("DOMContentLoaded", function () {
  var errorModal = document.getElementById("errorModal");
  if (errorModal) {
    var closeBtn = errorModal.querySelector(".btn-close");
    if (closeBtn) {
      closeBtn.addEventListener("click", function () {
        // Use Bootstrap 5's JS API to hide the modal
        var modalInstance = bootstrap.Modal.getOrCreateInstance(errorModal);
        modalInstance.hide();
      });
    }
  }
});
