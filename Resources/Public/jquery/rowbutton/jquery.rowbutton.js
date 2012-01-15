$(document).ready(function(){
    $("table.rowbutton tr").find("> td > .btn").css({ "visibility": "hidden" });
    $("table.rowbutton tr").hover(
        function () {
            $(this).find("> td > .btn").css({"visibility": "visible"});
        },
        function () {
            $(this).find("> td > .btn").css({ "visibility": "hidden" });
        }
    );
});