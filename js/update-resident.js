// Check if the URL contains the "update-resident" query parameter
document.addEventListener("DOMContentLoaded", function () {
  const urlParams = new URLSearchParams(window.location.search);
  const updateResident = urlParams.get("update-resident");
  // If the "update-resident" query parameter is present, show the success modal
  if (updateResident === "success") {
    const successModal = new bootstrap.Modal(
      document.getElementById("successModal")
    );
    const successMsg = document.querySelector(
      ".modal-body.mb-resident-update-msg"
    );
    successMsg.textContent = "Resident updated successfully";
    successModal.show();
  } else if (updateResident === "error") {
    const errorModal = new bootstrap.Modal(
      document.getElementById("successModal")
    );
    const errorMsg = document.querySelector(
      ".modal-body.mb-resident-update-msg"
    );
    errorMsg.textContent =
      "An error occurred while updating resident information";
    errorModal.show();
  }
});

// Close the Modal by clicking on the X mark or close button
$(document).ready(function () {
  // Add click event handler for the close button and close-btn
  $("#successModal .close, #successModal .close-btn").click(function () {
    $("#successModal").modal("hide");
  });
});
// Setting the chosen resident id to the value of the hidden input in update modal
document.addEventListener("DOMContentLoaded", function () {
  document.addEventListener("click", function (event) {
    if (event.target.closest(".update-btn-resident")) {
      let residentId = event.target
        .closest(".update-btn-resident")
        .getAttribute("data-resident-id");
      document.getElementById("residentIdInput").value = residentId;
    }
  });
});
