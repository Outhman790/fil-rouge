// Global variable to store DataTable instance
let residentsDataTable = null;

window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki

    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        residentsDataTable = new simpleDatatables.DataTable(datatablesSimple);
    }
});
