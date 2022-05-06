# Silverstripe Pattern Library

Framework agnostic pattern library generator for Silverstripe.

**This is a PoC, probably don't use it in real projects**

## Getting started

Add the repo to your `composer.json` file

```json
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:flashbackzoo/silverstripe-pattern-library.git"
    }
]
```

Install the module

```
$ composer require flashbackzoo/silverstripe-pattern-library
```

Assuming you have a Vue3 component like this

**themes/app/src/ExampleComponent.vue**

```js
<script>
import { defineComponent } from '@vue/composition-api';

export default defineComponent({
  props: {
    content: {
      type: String,
      required: true,
    },
  },
});
</script>

<template>
  <div>
    {{ content }}
  </div>
</template>
```

And a Silverstripe template for your component like this

**themes/app/templates/Includes/ExampleComponent.ss**

```html
<div>
    <h1>$Title</h1>
    <example-component :content="$Content.XML" />
</div>
```

You can add some config like this

**app/_config/pattern-library.yml**

```yaml
---
Name: app-pattern-library
After:
  - '#flashbackzoo-pattern-library'
---
Flashbackzoo\SilverstripePatternLibrary\PatternLibrary:
  engine: Flashbackzoo\SilverstripePatternLibrary\Engine\StorybookV6
  adapter: Flashbackzoo\SilverstripePatternLibrary\Adapter\StorybookVue3
  static_dir: ./themes/app/dist
  output: ../stories
  patterns:
    - component:
        name: ExampleComponent
        element: example-component
        path: ../themes/app/src/ExampleComponent.vue
      template:
        name: Includes\ExampleComponent
        data:
          Title: Hello world!
          Content:
            XML: args.content
      args:
        content: >
          '<p>This is my component.</p>'
```

Run the build task `/dev/tasks/Flashbackzoo-SilverstripePatternLibrary-GeneratePatternLibraryTask?flush=1`

This should generate a story in your output directory like

**stories/ExampleComponent.stories.js**

```js
import ExampleComponent from '../themes/app/src/ExampleComponent.vue';

export default {
  title: 'ExampleComponent',
  component: ExampleComponent,
};

const Template = (args) => ({
  components: {
    'example-component': ExampleComponent,
  },
  setup() {
    return { args };
  },
  template: `
    <div>
      <h1>Hello world!</h1>
      <example-component :content="args.content" />
    </div>
  `,
});

export const Primary = Template.bind({});
Primary.args = {
  content: '<p>This is my component.</p>',
};
```

The module supports a mix of generated and manually created stories in your project at the same time. This means you can gradually convert your existing stories over to generates ones. And you can still write manual stories if you need pattern library features the module doesn't support yet.
