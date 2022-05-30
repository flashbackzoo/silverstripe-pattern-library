# Silverstripe Pattern Library

Framework agnostic pattern library generator for Silverstripe.

## Getting started

Install the module

```
composer require flashbackzoo/silverstripe-pattern-library
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
  output: ./stories
  patterns:
    - ./app/pattern-library/example-component.yml
```

**app/pattern-library/example-component.yml**

```yaml
ExampleComponent:
  title: Components/ExampleComponent
  component:
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

Run the build task

```
/dev/tasks/Flashbackzoo-SilverstripePatternLibrary-GeneratePatternLibraryTask?flush=1
```

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

Not all components require JavaScript, for example footers are often just Silverstripe templates, without any complex
interactions. You can generate patterns for "Silverstripe only" components those too.

**app/pattern-library/footer.yml**

```yaml
Footer:
  title: Components/Footer
  template:
    name: Includes\Footer
    data:
      Columns:
        - Menu:
            MenuTitle: Column 1
            MenuItems:
              - Title: Example link 1
                Link: '#'
              - Title: Example link 2
                Link: '#'
        - Menu:
            MenuTitle: Column 2
            MenuItems:
              - Title: Example link 3
                Link: '#'
              - Title: Example link 4
                Link: '#'
```

The module supports having a mix of generated and manually created stories in your project. This means you can gradually
convert your existing stories to generates ones. And you can still write manual stories if required.
