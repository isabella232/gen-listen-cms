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

    $(".share-tumblr").click(function(e) {
        e.stopPropagation();
        var URL = "https://www.tumblr.com/widgets/share/tool/?posttype=photo";
        var title = $('#share-title').val();
        var text = $('#share-text').val();
        var shareURL = $('#share-url').val();
        var image = $('#share-image').val();

        if (title) {
            URL += "&title=" + title;
        }
        if (text) {
            URL += "&caption=" + text;
        }
        if (image) {
            URL += "&content=" + image;
        }
        if (shareURL) {
            URL += "&photo-clickthru=" + shareURL;
            URL += "&canonicalUrl=" + shareURL;
        }

        tabOpen(URL);
        return false;
    });

    var tabOpen = function(url) {
        window.open(url, '_blank', 'height=600,width=800,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
    };


}(jQuery));