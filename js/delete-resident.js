console.log("DELETE-RESIDENT.JS LOADED!");

document.addEventListener("DOMContentLoaded", function () {
  console.log("DELETE-RESIDENT.JS: DOMContentLoaded fired!");

  // Test if table exists
  const table = document.getElementById('datatablesSimple');
  console.log("Table found:", table);

  // Use event delegation on document body instead of document
  document.body.addEventListener("click", function (event) {
    console.log("BODY CLICK detected on:", event.target);
    console.log("Target classes:", event.target.className);

    // Check if clicked element is or contains a delete button
    let deleteButton = event.target.closest("button.btn-danger");
    console.log("Delete button found:", deleteButton);

    if (deleteButton) {
      console.log("Has data-resident-id:", deleteButton.hasAttribute('data-resident-id'));

      if (deleteButton.hasAttribute('data-resident-id')) {
        console.log("✓ Delete button clicked!");
        let residentId = deleteButton.getAttribute("data-resident-id");
        console.log("✓ Resident ID:", residentId);

        // Get modal each time (in case DOM was updated)
        let deleteModal = document.getElementById("deleteModal");
        if (deleteModal) {
          let deleteButtonLink = deleteModal.querySelector(".modal-footer button a");
          if (deleteButtonLink) {
            deleteButtonLink.href = "includes/delete-resident.inc.php?id=" + residentId;
            console.log("✓ Delete link updated:", deleteButtonLink.href);
          } else {
            console.error("✗ Delete button link not found in modal");
          }
        } else {
          console.error("✗ Delete modal not found");
        }
      }
    }
  }, false);
});

console.log("DELETE-RESIDENT.JS: Event listener registered!");

// Check if the URL contains the "delete-resident" query parameter
(function() {
  const urlParams = new URLSearchParams(window.location.search);
  const deleteResident = urlParams.get("delete-resident");

  // If the "delete-resident" query parameter is present, show the feedback modal
  if (deleteResident === "success") {
    const feedbackModal = new bootstrap.Modal(
      document.getElementById("deleteFeedbackModal")
    );
    const feedbackMsg = document.querySelector(".modal-body.delete-feedback-msg");
    feedbackMsg.innerHTML =
      '<div class="text-center"><i class="fas fa-check-circle text-success fa-2x mb-2"></i><p class="text-success">Resident deleted successfully!</p></div>';
    feedbackModal.show();
  } else if (deleteResident === "error") {
    const feedbackModal = new bootstrap.Modal(
      document.getElementById("deleteFeedbackModal")
    );
    const feedbackMsg = document.querySelector(".modal-body.delete-feedback-msg");
    feedbackMsg.innerHTML =
      '<div class="text-center"><i class="fas fa-exclamation-triangle text-danger fa-2x mb-2"></i><p class="text-danger">An error occurred while deleting the resident.</p></div>';
    feedbackModal.show();
  }
})();
