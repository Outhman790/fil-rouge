// Function to perform form validation
function validateForm() {
  let isValid = true;

  // Input field validation with regex patterns
  let firstNameInput = document.getElementById("firstNameInput");
  let lastNameInput = document.getElementById("lastNameInput");
  let emailInput = document.getElementById("emailInput");
  let passwordInput = document.getElementById("passwordInput");
  let usernameInput = document.getElementById("usernameInput");

  // Regular expression patterns
  let lettersOnlyPattern = /^[A-Za-z]+$/;
  let alphanumericPattern = /^(?=.*[a-zA-Z])[a-zA-Z0-9]+$/;

  if (firstNameInput.value.trim() === "") {
    showError(firstNameInput, "Please enter a first name");
    isValid = false;
  } else if (!lettersOnlyPattern.test(firstNameInput.value.trim())) {
    showError(firstNameInput, "Please enter a valid first name");
    isValid = false;
  }

  if (lastNameInput.value.trim() === "") {
    showError(lastNameInput, "Please enter a last name");
    isValid = false;
  } else if (!lettersOnlyPattern.test(lastNameInput.value.trim())) {
    showError(lastNameInput, "Please enter a valid last name");
    isValid = false;
  }

  if (emailInput.value.trim() === "") {
    showError(emailInput, "Please enter an email");
    isValid = false;
  }

  if (passwordInput.value.trim() === "") {
    showError(passwordInput, "Please enter a password");
    isValid = false;
  }

  if (usernameInput.value.trim() === "") {
    showError(usernameInput, "Please enter a username");
    isValid = false;
  } else if (!alphanumericPattern.test(usernameInput.value.trim())) {
    showError(usernameInput, "Please enter a valid username");
    isValid = false;
  }

  return isValid;
}
// Function to reset previous error messages
function resetErrorMessages() {
  var errorMessages = document.querySelectorAll(".error-message");
  errorMessages.forEach(function (errorMessage) {
    errorMessage.textContent = "";
  });
}

// Function to display error message below an input field
function showError(inputElement, message) {
  let errorElement = inputElement.parentElement.querySelector(
    ".form-text.text-danger"
  );
  if (errorElement) {
    errorElement.textContent = message;
  }
}

const form = document.getElementById("add-resident-form");
form.addEventListener("submit", (e) => {
  e.preventDefault();
  if (validateForm()) {
    const formData = new FormData(form);

    fetch("./includes/add-resident.inc.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showModal(data.success);
          console.log("success");
          form.reset();
        } else {
          showModal(data.success);
          console.log("error occured");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("An error occurred. Please try again.");
      });
  }
});
// Modal function
function showModal(success) {
  let modalElement = document.getElementById("successModal-add-resident");
  let modal = new bootstrap.Modal(modalElement);

  if (success) {
    document.querySelector(".modal .mb-add-resident").textContent =
      "Resident added successfully";
  } else {
    document.querySelector(".modal .mb-add-resident").textContent =
      "An error occurred. Please try again";
  }

  // Show the modal
  modal.show();

  // Close the modal when clicked outside of it or when the "Add" button is clicked
  function closeModal(event) {
    if (
      event.target === modalElement ||
      event.target.getAttribute("data-bs-dismiss") === "modal"
    ) {
      modal.hide();
      modalElement.removeEventListener("click", closeModal);
    }
  }

  modalElement.addEventListener("click", closeModal);
}
