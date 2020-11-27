# React in Drupal 9

This is small example of a simple approach when wanting to utilize React in Drupal.
This might not be suited for production but it might spark you imagination and your
interest into the rediscovered world of tools light development.

![React in Drupal example](https://raw.githubusercontent.com/reload/react-drupal-example/main/example_assets/react_in_drupal.gif)

```javascript

let html, useState;
const BLOCK_CLASS = "react-example";

(function (Drupal, drupalSettings) {
  Drupal.behaviors.reactExample = {
    attach: function (_context, _settings) {
      html = htm.bind(React.createElement);
      useState = React.useState;
      document.querySelectorAll(`.${BLOCK_CLASS}`).forEach((node) => {
        ReactDOM.render(html`<${App} ...${node.dataset} />`, node);
      });
    },
  };
})(Drupal, drupalSettings);

function App({ blockId, initialName }) {
  const [name, setName] = useState(initialName);

  return html`
    <div>
      <${Title} name=${name} id=${blockId} />
      <button onClick=${() => setName("React")}>
        Press me to draw!
      </button>
      ${name === "React" &&
      html`
        <div style=${{ border: "1px solid black" }}>
          <${SketchField}
            width="100%"
            height="500px"
            tool=${Tools.Pencil}
            lineColor="gold"
            lineWidth="3"
          />
        </div>
      `}
    </div>
  `;
}

function Title({ name, id }) {
  return html`<h1>This is block: ${id} with the name of ${name}!</h1> `;
}

```

## Take a look at

[modules/react_example/src/Form/SettingsForm.php](https://github.com/reload/react-drupal-example/blob/main/web/modules/react_example/src/Form/SettingsForm.php)
[modules/react_example/src/Plugin/Block/ReactExampleBlock.php](https://github.com/reload/react-drupal-example/blob/main/web/modules/react_example/src/Plugin/Block/ReactExampleBlock.php)
[modules/react_example/react_example.module](https://github.com/reload/react-drupal-example/blob/main/web/modules/react_example/react_example.module)
[modules/react_example/templates/react-example.html.twig](https://github.com/reload/react-drupal-example/blob/main/web/modules/react_example/templates/react-example.html.twig)
[modules/react_example/react_example.libraries.yml](https://github.com/reload/react-drupal-example/blob/main/web/modules/react_example/react_example.libraries.yml)
[modules/react_example/js/react-example.js](https://github.com/reload/react-drupal-example/blob/main/web/modules/react_example/js/react-example.js)

Writing modern web applications comes with a lot of assumptions.
There should be scoped styling (CSS modules, CSS-in-JS) included,
automatic browser target configuration, intelligent, tree-shake-able module
bundling and so on.

This example is not trying to address any of that. Those are quality of life
features that most web application developers have gotten used to and expect.

But sometimes you do not need or want to bring out the big guns but you need
a little more control than what is easily accessible in the browser provided
libraries. Previously one would grab for jQuery, which is still a great tool,
but the expectations of your web applications have changed and a library such as
React (or Vue, Preact etc.) is better suited to some of our highly interactive
web applications.

This is all well and good if you are good with generating your HTML markup on
the client. What if it's crucial that your markup is generated on the server?
One could go down the rabbit hole of getting React server side rendered but then
this example would loose it's simplicity.
An alternative is to let Drupal and the Twig template language do what it's
good at and utilize a project such as [Stimulus](https://stimulusjs.org/) that
takes existing HTML and injects it with a bit of state management power.
