
$(function () {

  $(".button-select-affilate").click(function () {
    $(".button-select-affilate").removeClass("active");
    $(this).addClass("active");

    showResult();
  });

  let timer = 0;
  $("#keyword").keyup(function () {
    clearTimeout(timer);
    timer = setTimeout(showResult, 500);
  });

  $(document).ready(function () {
    showResult();
  });

  function showResult() {

    let keyword = $("#keyword").val();
    let affiliate = $(".button-select-affilate.active").val();

    $.ajax({
      type: "POST",
      url: "search.php",
      data: {
        "keyword": keyword,
        "affiliate": affiliate
      },
      success: function (result) {
        $("#result").html(result);
      }
    });

  }

  $(".button-users").click(function () {
    showUsers();
  });

  function showUsers() {

    $.ajax({
      url: "users.php",
      success: function (result) {
        $("#result").html(result);
      }
    });

  }

  $("#result").on("click", ".button-edit-user", function () {

    let id = $(this).val();

    $.ajax({
      type: "POST",
      url: "editUser.php",
      data: {
        "id": id
      },
      success: function (result) {
        $("#result").html(result);
      }
    });

  });

});
