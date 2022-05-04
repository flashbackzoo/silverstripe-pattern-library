{
  components: { $ComponentName },
  setup() {
    return { args };
  },
  template: '<$ComponentName v-bind="args" />',
}
