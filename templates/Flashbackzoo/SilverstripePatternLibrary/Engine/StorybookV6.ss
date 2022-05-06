$Adapter.Imports

export default {
    title: '$Title',
    <% if $Component %>
    component: $Component.Name,
    <% end_if %>
};

const Template = (args) => ($Adapter.Template);

<% if $Adapter.Args %>
export const Primary = Template.bind({});
Primary.args = $Adapter.Args
<% end_if %>
