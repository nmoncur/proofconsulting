jQuery('.visitorModal').on('shown.bs.modal', function () {
  jQuery('#visitor-name').focus()
})

function visitorLogin() {
  document.getElementById("feedback").innerHTML = "";
  var email = document.getElementById("visitor-email").value;
  var atpos = email.indexOf("@");
    var dotpos = email.lastIndexOf(".");
    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length) {
      document.getElementById("feedback").innerHTML = "Please enter a valid email";
      return false;
    }
    else {
      document.getElementById("submit-visitor-info").click();
      console.log("headed to visitor");
    }
}
