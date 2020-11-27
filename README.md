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