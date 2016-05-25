(function($) {

    var makeStationSelectOption = function(orgId, logo, call, title, name, frequency) {
        var $newStation = $('<div class="station-option">');

        var $newStationSelect = $('<div class="station-select">');
        var $newStationContent = $('<div class="station-content">');

        var $newStationImageContent = $('<div class="station-image-content">');
        var $stationImg = $('<img class="station-image" src="' + logo + '">');
        $newStationImageContent.append($stationImg);

        var $stationCall = $('<span class="station-call">');
        $stationCall.html(call);

        var $stationTitle = $('<span class="station-title description">');
        frequency = frequency || '';
        $stationTitle.html(title + " " + frequency);

        var $stationName = $('<span class="station-name">');
        $stationName.html(name);

        var $orgId = $('<input type="hidden" class="station-id">');
        $orgId.val(orgId);

        var $button = $('<a class="station-select-button link-button" href="#">');
        $button.html('select');

        $button.on('click', function(e){
            e.preventDefault();
            $oldStation = $(this).parent().parent();
            $oldStation.find(".station-select-button").remove();
            $('#station-search-result-area').empty();
            $('#station-search-result-area').html($oldStation);
            var orgId = $oldStation.find('.station-id').val();
            $('input[name="PrimaryStation"]').val(orgId);
        });

        if (logo != '' && logo.indexOf('logos/generic.gif') < 0) {
            $newStationContent.append($newStationImageContent)
        }

        $newStationContent.append($stationName).append($stationTitle).append($orgId);
        $newStationSelect.append($button);
        $newStation.append($newStationContent);
        $newStation.append($newStationSelect);

        return $newStation;
    }

    $('#station-search-button').on('click', function(e){
        e.preventDefault();
        $('#station-search-result-area').html('<img src="http://media.npr.org/chrome/loader.gif" />');
        $('input[name="PrimaryStation"]').val('');
        var searchTerm = encodeURI($('input[name="PrimaryStationPicker"]').val());
        if (searchTerm.length === 0) {
            $('#station-search-result-area').html('');
            return false;
        }
        var url = 'api/stations?q=' + searchTerm;

        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                $('#station-search-result-area').empty();

                if (data[0] == "No results found" || data.length == 0) {
                    $('#station-search-result-area').html("<strong>No results found</strong>");
                } else {
                    for (var i = 0; i < data.length; i++) {
                        $station = makeStationSelectOption(data[i].org_id, data[i].logo, data[i].call, data[i].title, data[i].name, data[i].frequency)
                        $('#station-search-result-area').append($station);
                    }
                }
            }
        });
    });

}(jQuery));