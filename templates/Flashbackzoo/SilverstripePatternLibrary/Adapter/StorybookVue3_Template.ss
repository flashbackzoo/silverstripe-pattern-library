{
  <% if $Component %>
  components: {
    '$Component.Element': $Component.Name,
  },
  <% end_if %>
  setup() {
    return { args };
  },
  template: `
    $Template
  `,
}
