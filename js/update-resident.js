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
  $("#successModal .btn-close, #successModal .close-btn").click(function () {
    $("#successModal").modal("hide");
  });
});
// Setting the chosen resident id to the value of the hidden input in update modal
document.addEventListener("DOMContentLoaded", function () {
  document.addEventListener("click", function (event) {
    let updateButton = event.target.closest(".update-btn-resident");

    if (updateButton && updateButton.hasAttribute('data-resident-id')) {
      console.log("Update button clicked!");
      let residentId = updateButton.getAttribute("data-resident-id");
      console.log("Resident ID:", residentId);

      let residentIdInput = document.getElementById("residentIdInput");
      if (residentIdInput) {
        residentIdInput.value = residentId;
      }

      // Fetch resident data and populate form
      fetch(`includes/get-resident.inc.php?resident_id=${residentId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            console.log("Resident data fetched successfully");
            // Populate form fields with resident data
            document.getElementById("fNameInput").value = data.data.fName;
            document.getElementById("lNameInput").value = data.data.lName;
            document.getElementById("email_Input").value = data.data.email;
            document.getElementById("username_Input").value = data.data.username;
            // Note: We don't populate password field for security reasons
          } else {
            console.error('Error fetching resident data:', data.error);
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }
  });
});
