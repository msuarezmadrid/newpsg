$(document).ready( function(){
    $('#mySidebar a').on('click', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        if (url) {
            $('#mainContent').load(url);
        }
    });
});
function toggleSidebar() {
    $("#mySidebar").toggleClass("sidebar-hidden");
    $("#mainContent").toggleClass("main-content-expanded");
}