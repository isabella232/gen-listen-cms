<fieldset class="form-minor-section social-url-container">
    <label for="{$ID}_isprimary" class="social-radio-label">$Title</label>
    <% if $Description %><span class="description">$Description</span><% end_if %>
    <% if $Message %><span class="message $MessageType">$Message</span><% end_if %>
    <% if $LeftTitle %>
        <div class="social-input-wrapper"><span>$LeftTitle</span>
    <% end_if %>
        $Field
    <% if $LeftTitle %>
        </div>
    <% end_if %>
</fieldset>