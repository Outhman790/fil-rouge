$(document).ready(function () {
  $('a[data-toggle="modal"]').on("click", function () {
    var imageSrc = $(this).find("img").attr("src");
    $("#modalImage").attr("src", imageSrc);
  });
});
