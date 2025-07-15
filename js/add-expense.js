// sending request to add-expense.inc.php to add expense and show modal
// Get the form element
const form = document.getElementById("add-expense-form");

// Attach event listener to the form's submit event
form.addEventListener("submit", (e) => {
  e.preventDefault(); // Prevent the default form submission

  // Create a new FormData object
  const formData = new FormData(form);

  // Dynamically build the correct path to includes/add-expense.inc.php
  const basePath = window.location.pathname.substring(
    0,
    window.location.pathname.lastIndexOf("/")
  );
  const fetchUrl = basePath + "/includes/add-expense.inc.php";
  console.log("Fetching from:", fetchUrl);

  fetch(fetchUrl, {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      if (data.success) {
        showModal(data.success);
        form.reset();
      } else {
        showModal(data.success);
      }
    })
    .catch((error) => {
      console.error("Fetch Error:", error);
      alert("An error occurred. Please try again. Check console for details.");
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
