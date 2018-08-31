$(document).ready(printYear);

function printYear(){
  $("#year").text(new Date().getFullYear());
}