document.addEventListener("DOMContentLoaded", function () {
  let deleteModal = document.getElementById("deleteModal");

  document.addEventListener("click", function (event) {
    let deleteButton = event.target.closest(
      ".btn.btn-danger.btn-sm.btn-icon.mr-2"
    );
    if (deleteButton) {
      let residentId = deleteButton.getAttribute("data-resident-id");
      console.log(residentId);
      let modal = deleteModal;
      let deleteButtonLink = modal.querySelector(".modal-footer button a");
      deleteButtonLink.href =
        "includes/delete-resident.inc.php?id=" + residentId;
    }
  });
});

// Check if the URL contains the "delete-resident" query parameter
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
