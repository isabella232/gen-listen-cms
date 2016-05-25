(function($) {

    $(".share-twitter").click(function(e) {
        e.stopPropagation();
        var msg = $('#share-text').val();
        tabOpen("http://twitter.com/intent/tweet?text=" + encodeURIComponent(msg));
        return false;
    });

    $(".share-fb").click(function(e) {
        e.stopPropagation();
        tabOpen("https://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(window.location.href));
        return false;
    });

    var tabOpen = function(url) {
        window.open(url, '_blank', 'height=600,width=800,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
    };

}(jQuery));