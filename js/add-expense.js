// sending request to add-expense.inc.php to add expense and show modal
// Get the form element
const form = document.getElementById("add-expense-form");

// Attach event listener to the form's submit event
form.addEventListener("submit", (e) => {
  e.preventDefault(); // Prevent the default form submission

  // Create a new FormData object
  const formData = new FormData(form);

  // Send an AJAX request using fetch
  fetch("includes/add-expense.inc.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      // Handle the response from the server
      if (data.success) {
        showModal(data.success);
        form.reset();
      } else {
        showModal(data.success);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("An error occurred. Please try again.");
    });
});
// Modal function
function showModal(success) {
  var modalElement = document.getElementById("successModal");
  var modal = new bootstrap.Modal(modalElement);
  if (success)
    document.querySelector(".modal .modal-body").textContent =
      "Expense added successfully";
  else
    document.querySelector(".modal .modal-body").textContent =
      "An error occured please try again";
  // Show the modal
  modal.show();

  // Close the modal when clicked outside of it
  modalElement.addEventListener("click", function (event) {
    if (event.target === modalElement) {
      modal.hide();
    }
  });
}
