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
        if (data.success === true || data.success === 1 || data.success === "1") {
          // Get the add resident modal element
          const addModalEl = document.getElementById("addResidentModal");
          const addModal = bootstrap.Modal.getInstance(addModalEl);

          // Hide the add modal if instance exists
          if (addModal) {
            addModal.hide();
          }

          // Wait a bit for modal to start hiding, then clean up and show success
          setTimeout(() => {
            // Force close the add modal
            addModalEl.classList.remove('show');
            addModalEl.style.display = 'none';
            addModalEl.setAttribute('aria-hidden', 'true');
            addModalEl.removeAttribute('aria-modal');

            // Remove all backdrops
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());

            // Clean up body
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('padding-right');
            document.body.style.removeProperty('overflow');

            // Reset form
            form.reset();
            resetErrorMessages();

            // Show success modal
            showModal(true);

            // Reload the table data without refreshing the page
            setTimeout(() => {
              reloadTableData();
            }, 500);
          }, 150);

          console.log("success");
        } else {
          showModal(false);
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

// Function to reload table data without page refresh using AJAX
async function reloadTableData() {
  try {
    console.log('Fetching updated table data...');

    // Check if DataTable instance exists
    if (typeof residentsDataTable !== 'undefined' && residentsDataTable) {
      console.log('Destroying existing DataTable...');
      // Destroy the DataTable before updating content
      residentsDataTable.destroy();
    }

    // Fetch the entire page HTML
    const response = await fetch('index.php');
    const html = await response.text();

    // Parse the HTML
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');

    // Extract the new table content
    const newTableBody = doc.querySelector('#datatablesSimple tbody');

    if (newTableBody) {
      // Get current table body
      const currentTableBody = document.querySelector('#datatablesSimple tbody');

      if (currentTableBody) {
        console.log('Updating table content...');
        // Replace the table body content
        currentTableBody.innerHTML = newTableBody.innerHTML;

        // Reinitialize DataTable
        console.log('Reinitializing DataTable...');
        const tableElement = document.getElementById('datatablesSimple');
        residentsDataTable = new simpleDatatables.DataTable(tableElement);

        console.log('Table updated successfully with pagination!');
      }
    }

    // Update dashboard statistics
    console.log('Updating dashboard statistics...');

    // Find the resident count in the fetched HTML
    const residentCountCards = doc.querySelectorAll('.card.bg-success .card-footer p.small');
    if (residentCountCards.length > 0) {
      const newResidentCount = residentCountCards[0].textContent.trim();

      // Find and update the current resident count
      const currentCountElements = document.querySelectorAll('.card.bg-success .card-footer p.small');
      if (currentCountElements.length > 0) {
        currentCountElements[0].textContent = newResidentCount;
        console.log('Dashboard resident count updated to:', newResidentCount);
      }
    }

  } catch (error) {
    console.error('Error reloading table:', error);
    // Fallback to page reload if AJAX fails
    location.reload();
  }
}

// Check if the URL contains the "add-resident" query parameter
(function() {
  const urlParams = new URLSearchParams(window.location.search);
  const addResident = urlParams.get("add-resident");

  // If the "add-resident" query parameter is present, show the feedback modal
  if (addResident === "success") {
    const feedbackModal = new bootstrap.Modal(
      document.getElementById("successModal-add-resident")
    );
    const feedbackMsg = document.querySelector(".modal .mb-add-resident");
    feedbackMsg.textContent = "Resident added successfully";
    feedbackModal.show();

    // Clean up URL after showing message
    setTimeout(() => {
      window.history.replaceState({}, document.title, "index.php");
    }, 2000);
  }
})();
