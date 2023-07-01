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

const urlParams = new URLSearchParams(window.location.search);
const deleteResident = urlParams.get("delete-resident");
