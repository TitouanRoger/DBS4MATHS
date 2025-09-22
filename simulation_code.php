<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulateur de Code</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/theme/dracula.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/xml/xml.min.js"></script>
    <link rel="stylesheet" href="css/simulation_code.css">
</head>

<body class="simulationcode-body">

    <main class="simulationcode-main">
        <div class="simulationcode-editor-container">
            <div class="simulationcode-section-title">HTML</div>
            <textarea id="htmlCode"></textarea>
        </div>

        <div class="simulationcode-editor-container">
            <div class="simulationcode-section-title">CSS</div>
            <textarea id="cssCode"></textarea>
        </div>

        <div class="simulationcode-editor-container">
            <div class="simulationcode-section-title">JavaScript</div>
            <textarea id="jsCode"></textarea>
        </div>

        <button class="simulationcode-button" onclick="runCode()">Exécuter le Code</button>

        <div class="simulationcode-screen-mode">
            <button class="simulationcode-button" onclick="changeScreenMode('desktop')">Ordinateur</button>
            <button class="simulationcode-button" onclick="changeScreenMode('tablet')">Tablette</button>
            <button class="simulationcode-button" onclick="changeScreenMode('phone')">Téléphone</button>
        </div>
        <iframe id="output" class="simulationcode-iframe" src="about:blank"></iframe>
    </main>

    <script>
        var htmlEditor = CodeMirror.fromTextArea(document.getElementById("htmlCode"), {
            mode: "xml",
            theme: "dracula",
            lineNumbers: true,
            matchBrackets: true,
            lineWrapping: true,
        });

        var cssEditor = CodeMirror.fromTextArea(document.getElementById("cssCode"), {
            mode: "css",
            theme: "dracula",
            lineNumbers: true,
            matchBrackets: true,
            lineWrapping: true,
        });

        var jsEditor = CodeMirror.fromTextArea(document.getElementById("jsCode"), {
            mode: "javascript",
            theme: "dracula",
            lineNumbers: true,
            matchBrackets: true,
            lineWrapping: true,
        });

        function runCode() {
            var html = htmlEditor.getValue();
            var css = cssEditor.getValue();
            var js = jsEditor.getValue();

            var iframe = document.getElementById("output");

            var doc = iframe.contentDocument || iframe.contentWindow.document;

            doc.open();
            doc.write(`
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <style>${css}</style>
            </head>
            <body>
                ${html}
                <script>${js}<\/script>
            </body>
            </html>
        `);
            doc.close();
        }

        function changeScreenMode(mode) {
            var iframe = document.getElementById("output");

            switch (mode) {
                case "desktop":
                    iframe.style.width = "1200px";
                    iframe.style.height = "800px";
                    break;
                case "tablet":
                    iframe.style.width = "768px";
                    iframe.style.height = "1024px";
                    break;
                case "phone":
                    iframe.style.width = "320px";
                    iframe.style.height = "568px";
                    break;
            }
        }
    </script>

</body>

</html>