<fieldset class="form-minor-section">
    <label for="{$ID}" class="station-picker-label">$Title</label>
    <% if $Description %><span class="description">$Description</span><% end_if %>
    <% if $Message %><span class="message $MessageType">$Message</span><% end_if %>
    $Field
    <button id="station-search-button" class="alt">search</button>
    <div id="station-search-result-area"></div>
    <input type="hidden" name="PrimaryStation" id="PrimaryStation">
</fieldset>