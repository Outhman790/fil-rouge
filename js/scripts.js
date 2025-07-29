// Toggle the side navigation
window.addEventListener("DOMContentLoaded", (event) => {
  // Sidebar toggle functionality
  const sidebarToggle = document.body.querySelector("#sidebarToggle");
  if (sidebarToggle) {
    // If localStorage has stored toggle state, apply it
    if (localStorage.getItem("sb|sidebar-toggle") === "true") {
      document.body.classList.add("sb-sidenav-toggled");
    }
    
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
