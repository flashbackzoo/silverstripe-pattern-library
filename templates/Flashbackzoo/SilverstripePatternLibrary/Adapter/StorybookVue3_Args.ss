{
  <% if $Args %>
    <% loop $Args %>
      $Key: $Value.RAW,
    <% end_loop %>
  <% end_if %>
};
