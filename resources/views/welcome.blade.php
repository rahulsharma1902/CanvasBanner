<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canvas Template</title>

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    <!-- Include Bootstrap JS (Popper.js and Bootstrap JS) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Include Fabric.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.js"
        integrity="sha512-hOJ0mwaJavqi11j0XoBN1PtOJ3ykPdP6lp9n29WVVVVZxgx9LO7kMwyyhaznGJ+kbZrDN1jFZMt2G9bxkOHWFQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Include Fabric.js i-text library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"
        integrity="sha512-hOJ0mwaJavqi11j0XoBN1PtOJ3ykPdP6lp9n29WVVVVZxgx9LO7kMwyyhaznGJ+kbZrDN1jFZMt2G9bxkOHWFQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

</head>
<style>
button {
    margin: 10px;
}
</style>

<body>
    <div class="container-fluid my-5">
        <div class="row">
            <div class="col-lg-3">
                <button onclick="changeBackgroundColor()">Change Background Color</button>
                <button onclick="addNewText()">Add New Text</button>
                <input type="text" id="newText" placeholder="Enter text">
                <input type="color" id="textColor" value="#000000"> <!-- Input for choosing text color -->
                <input type="file" id="imageUpload" accept="image/*">
                <button onclick="deleteSelected()">Delete Selected</button>
                <button onclick="changeSelectedTextColor()">Change Selected Text Color</button>
                <label for="fontSize">Font Size:</label>
                <input type="number" id="fontSize" value="24" min="1" max="200" step="1"
                    onchange="changeSelectedTextSize()">
                <label for="bold">Bold:</label>
                <input type="checkbox" id="bold" onchange="toggleSelectedTextBold()">
                <label for="italic">Italic:</label>
                <input type="checkbox" id="italic" onchange="toggleSelectedTextItalic()">
                <label for="underline">Underline:</label>
                <input type="checkbox" id="underline" onchange="toggleSelectedTextUnderline()">
                <label for="fontFamily">Font Family:</label>
                <select id="fontFamily" onchange="changeSelectedTextFontFamily()">
                    <option value="Arial">Arial</option>
                    <option value="Courier New">Courier New</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Times New Roman">Times New Roman</option>
                    <option value="Verdana">Verdana</option>
                    <option value="Impact">Impact</option>
                    <option value="Comic Sans MS">Comic Sans MS</option>
                    <option value="Tahoma">Tahoma</option>
                    <option value="Palatino Linotype">Palatino Linotype</option>
                    <option value="Lucida Sans Unicode">Lucida Sans Unicode</option>
                    <option value="Trebuchet MS">Trebuchet MS</option>
                    <option value="Arial Black">Arial Black</option>
                    <!-- Add more as needed -->
                </select>

                <button onclick="saveTemplate()">Save as Template</button>
                <!-- <button onclick="loadTemplate()">Load Template</button> -->

                <label for="templateSelect">Select Template:</label>
                <select id="templateSelect">
                    <option value="" disabled selected>Select a template</option>
                    @foreach($templates as $template)
                    <option value="{{ $template->id }}" data-template-data="{{ json_encode($template->templateData) }}">
                        {{ $template->name }}</option>
                    @endforeach
                </select>
                <button onclick="loadSelectedTemplate()">Load Selected Template</button>


                <!-- Add HTML buttons for shapes and shape color -->
                <button onclick="addCircle()">Add Circle</button>
                <button onclick="addRectangle()">Add Rectangle</button>
                <button onclick="addStar()">Add Star</button>
                <button onclick="addLine()">Add Line</button>
                <button onclick="addStraightLine()">Add Straight Line</button>
                <button onclick="addDashedLine()">Add Dashed Line</button>
                <button onclick="addTriangle()">Add Triangle</button>
                <button onclick="addRightArrow()">Add Right Arrow</button>
                <button onclick="addLeftArrow()">Add Left Arrow</button>

                <label for="shapeColor">Shape Color:</label>
                <input type="color" id="shapeColor" value="#000000">
                <button onclick="changeSelectedShapeColor()">Change Selected Shape Color</button>

                <label for="opacityRange">Opacity:</label>
                <input type="range" id="opacityRange" min="0" max="1" step="0.1" value="1" onchange="adjustOpacity()">
                <span id="opacityValue">1</span>

                <!-- Add buttons to send backward and bring forward -->
                <button onclick="sendBackward()">Send Backward</button>
                <button onclick="bringForward()">Bring Forward</button>


                <input type="file" id="svgUpload" accept=".svg">

                <button onclick="copySelected()">Copy</button>
                <button onclick="paste()">Paste</button>


                <button onclick="setTextAlignment('left')">Align Left</button>
                <button onclick="setTextAlignment('center')">Align Center</button>
                <button onclick="setTextAlignment('right')">Align Right</button>



                <button onclick="flipVertical()">Flip Vertical</button>
                <button onclick="flipHorizontal()">Flip Horizontal</button>
                <button onclick="rotate()">Rotate</button>

                <button onclick="downloadCanvasAsImage('png')">Download Image</button>

            </div>
            <div class="col-lg-9 ">
                <div class="" style="border: 1px solid;width: 65rem;padding: 1rem;">
                    <canvas id="customCanvas" width="1000" height="800"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
    var canvas; // Declare canvas as a global variable

    document.addEventListener("DOMContentLoaded", function() {
        canvas = new fabric.Canvas('customCanvas');

        // Add Fabric.js image customization logic
        fabric.Image.fromURL('./Downloads/YZF 250-450 - 2023-2025.png', function(img) {
            // Set image position and size
            img.set({
                left: 100,
                top: 100,
                scaleX: 0.5,
                scaleY: 0.5
            });
            canvas.add(img);
        });

        // Add Fabric.js i-text customization logic
        var text = new fabric.IText('Customize me!', {
            left: 400,
            top: 100,
            fontSize: 30,
            fill: 'black',
        });
        canvas.add(text);
    });
    document.getElementById('svgUpload').addEventListener('change', function(e) {
        var file = e.target.files[0];

        if (file) {
            var reader = new FileReader();
            reader.onload = function(event) {
                // Parse the SVG content and create a fabric.Path object
                fabric.loadSVGFromString(event.target.result, function(objects, options) {
                    var svgPath = fabric.util.groupSVGElements(objects, options);
                    svgPath.set({
                        left: 100,
                        top: 400,
                        scaleX: 0.5,
                        scaleY: 0.5,
                        fill: document.getElementById('shapeColor').value
                    });

                    canvas.add(svgPath);
                    canvas.renderAll();
                });
            };
            reader.readAsText(file);
        }
    });
    function downloadCanvasAsImage(format) {
        let canvas = document.getElementById('customCanvas');
        let canvasImage = canvas.toDataURL('image/' + format);

        // Create a file name based on the current timestamp
        let fileName = 'canvas_image_' + new Date().getTime() + '.' + format;

        // Create an anchor element to trigger the download
        let downloadLink = document.createElement('a');
        downloadLink.href = canvasImage;
        downloadLink.download = fileName;

        // Trigger the download
        downloadLink.click();
    }

    function flipVertical() {
        var activeObject = canvas.getActiveObject();

        if (activeObject) {
            activeObject.set({
                flipY: !activeObject.flipY
            });
            canvas.renderAll();
        }
    }

    function flipHorizontal() {
        var activeObject = canvas.getActiveObject();

        if (activeObject) {
            activeObject.set({
                flipX: !activeObject.flipX
            });
            canvas.renderAll();
        }
    }

    function rotate() {
        var activeObject = canvas.getActiveObject();

        if (activeObject) {
            // Toggle between rotating 180° and 360°
            activeObject.set({
                angle: (activeObject.angle === 180) ? 360 : 180
            });
            canvas.renderAll();
        }
    }

    function setTextAlignment(alignment) {
        var activeObject = canvas.getActiveObject();

        if (activeObject && activeObject.isType('i-text')) {
            activeObject.set({
                textAlign: alignment
            });
            canvas.renderAll();
        }
    }

    var copiedObjects = []; // Array to store copied objects

    function copySelected() {
        var activeObjects = canvas.getActiveObjects();

        if (activeObjects && activeObjects.length > 0) {
            copiedObjects = [];

            activeObjects.forEach(function(object) {
                // Clone the object and add it to the copiedObjects array
                copiedObjects.push(fabric.util.object.clone(object));
            });
        }
    }

    function paste() {
        if (copiedObjects && copiedObjects.length > 0) {
            // Offset to prevent pasted objects from overlapping with original objects
            var offsetX = 10;
            var offsetY = 10;

            copiedObjects.forEach(function(object) {
                var clonedObject = fabric.util.object.clone(object);

                // Adjust the position of the cloned object
                clonedObject.set({
                    left: clonedObject.left + offsetX,
                    top: clonedObject.top + offsetY,
                });

                canvas.add(clonedObject);
            });

            canvas.renderAll();
        }
    }

    function changeSelectedShapeColor() {
        var activeObject = canvas.getActiveObject();

        // Check if the active object is a circle, a rectangle, a path (star), or a triangle
        if (activeObject && (activeObject instanceof fabric.Polygon || activeObject instanceof fabric.Circle ||
                activeObject instanceof fabric.Rect || activeObject instanceof fabric.Path ||
                activeObject instanceof fabric.Triangle)) {
            var newColor = document.getElementById('shapeColor').value;
            activeObject.set({
                fill: newColor
            });
            canvas.renderAll();
        }
    }

    function adjustOpacity() {
        var opacityValue = parseFloat(document.getElementById('opacityRange').value);
        document.getElementById('opacityValue').innerText = opacityValue;

        var activeObjects = canvas.getActiveObjects();
        if (activeObjects && activeObjects.length > 0) {
            activeObjects.forEach(function(object) {
                object.set({
                    opacity: opacityValue
                });
            });
            canvas.renderAll();
        }
    }

    function sendBackward() {
        var activeObjects = canvas.getActiveObjects();
        if (activeObjects && activeObjects.length > 0) {
            activeObjects.forEach(function(object) {
                canvas.sendBackwards(object);
            });
            canvas.discardActiveObject(); // Deselect the active object
            canvas.renderAll();
            canvas.requestRenderAll();
        }
    }

    function bringForward() {
        var activeObjects = canvas.getActiveObjects();
        if (activeObjects && activeObjects.length > 0) {
            activeObjects.reverse().forEach(function(object) {
                canvas.bringForward(object);
            });
            canvas.discardActiveObject(); // Deselect the active object
            canvas.renderAll();
            canvas.requestRenderAll();
        }
    }






    function addStraightLine() {
        // Create a straight line from (50, 50) to (150, 150)
        var straightLine = new fabric.Line([100, 150, 150, 150], {
            stroke: document.getElementById('shapeColor').value,
            strokeWidth: 2, // Adjust the stroke width if necessary
            left: 400,
            fill: document.getElementById('shapeColor').value,
            top: 500
        });

        canvas.add(straightLine);
    }

    function addDashedLine() {
        // Create a dashed line from (50, 200) to (150, 300)
        var dashedLine = new fabric.Line([50, 200, 150, 300], {
            stroke: document.getElementById('shapeColor').value,
            strokeWidth: 2, // Adjust the stroke width if necessary
            strokeDashArray: [5, 5], // Set the dash pattern (adjust as needed)
            left: 500,
            fill: document.getElementById('shapeColor').value,
            top: 500
        });

        canvas.add(dashedLine);
    }


    function addLine() {
        // Create a line from (0, 0) to (50, 50)
        var line = new fabric.Line([0, 0, 50, 50], {
            stroke: document.getElementById('shapeColor').value,
            fill: document.getElementById('shapeColor').value,
            left: 400,
            top: 500
        });

        canvas.add(line);
    }

    function addTriangle() {
        // Create a triangle with specified coordinates
        var triangle = new fabric.Triangle({
            width: 50,
            height: 50,
            fill: document.getElementById('shapeColor').value,
            left: 600,
            top: 500
        });

        canvas.add(triangle);
    }

    function addStar() {
        var star = new fabric.Path('M 0 0 L 20 40 L 40 0 L 0 30 L 40 30 Z', {
            fill: document.getElementById('shapeColor').value,
            left: 300,
            top: 500,
            scaleX: 0.5,
            scaleY: 0.5
        });

        canvas.add(star);
    }

    function addCircle() {
        var circle = new fabric.Circle({
            radius: 30,
            fill: document.getElementById('shapeColor').value,
            left: 100,
            top: 500
        });
        canvas.add(circle);
    }

    function addRightArrow() {
        // Create a rightwards thick arrow
        var rightThickArrow = new fabric.Polygon([{
                x: 60,
                y: 0
            },
            {
                x: 60,
                y: 0
            },
            {
                x: 60,
                y: 20
            },
            {
                x: 120,
                y: 20
            },
            {
                x: 120,
                y: 60
            },
            {
                x: 60,
                y: 60
            },
            {
                x: 60,
                y: 80
            },
            {
                x: 0,
                y: 40
            } // Arrowhead
        ], {
            fill: document.getElementById('shapeColor').value,
            left: 900,
            top: 500
        });

        canvas.add(rightThickArrow);
    }

    function addLeftArrow() {
        var leftThickArrow = new fabric.Polygon([{
                x: 0,
                y: 20
            },
            {
                x: 40,
                y: 20
            },
            {
                x: 40,
                y: 0
            }, // Arrowhead
            {
                x: 80,
                y: 40
            },
            {
                x: 40,
                y: 80
            },
            {
                x: 40,
                y: 60
            },
            {
                x: 0,
                y: 60
            }
        ], {
            fill: document.getElementById('shapeColor').value,
            left: 900,
            top: 500
        });

        canvas.add(leftThickArrow);
    }


    function addTopArrow() {
        var topThickArrow = new fabric.Polygon([{
                x: 0,
                y: 60
            },
            {
                x: 0,
                y: 60
            },
            {
                x: 20,
                y: 60
            },
            {
                x: 20,
                y: 120
            },
            {
                x: 60,
                y: 120
            },
            {
                x: 60,
                y: 60
            },
            {
                x: 80,
                y: 60
            },
            {
                x: 40,
                y: 0
            } // Arrowhead
        ], {
            fill: document.getElementById('shapeColor').value,
            left: 900,
            top: 500
        });

        canvas.add(topThickArrow);
    }

    function addRectangle() {
        var rectangle = new fabric.Rect({
            width: 60,
            height: 40,
            fill: document.getElementById('shapeColor').value,
            left: 200,
            top: 500
        });
        canvas.add(rectangle);
    }

    function changeSelectedShapeColor() {
        var activeObject = canvas.getActiveObject();

        // Check if the active object is a circle, a rectangle, a path (star), or a triangle
        if (activeObject && (activeObject instanceof fabric.Polygon || activeObject instanceof fabric.Circle ||
                activeObject instanceof fabric.Rect || activeObject instanceof fabric.Path ||
                activeObject instanceof fabric.Triangle)) {
            var newColor = document.getElementById('shapeColor').value;
            activeObject.set({
                fill: newColor
            });
            canvas.renderAll();
        }
    }

    // Add an event listener for the shape color input
    document.getElementById('shapeColor').addEventListener('input', function() {
        changeSelectedShapeColor();
    });


    function changeBackgroundColor() {
        var newColor = prompt("Enter new background color (e.g., 'red', '#00ff00', 'rgb(255, 0, 0)'):");

        if (newColor !== null) {
            canvas.backgroundColor = newColor;
            canvas.renderAll();
        }
    }

    function addNewText() {
        var newTextValue = document.getElementById('newText').value;
        var textColor = document.getElementById('textColor').value;

        if (newTextValue.trim() !== '') {
            var newText = new fabric.IText(newTextValue, {
                left: 100,
                top: 300,
                fontSize: 20,
                fill: textColor, // Set text color
            });
            canvas.add(newText);
        }
    }

    function deleteSelected() {
        var activeObject = canvas.getActiveObject();

        if (activeObject) {
            canvas.remove(activeObject);
            canvas.discardActiveObject();
            canvas.renderAll();
        }
    }

    function changeSelectedTextColor() {
        var activeObject = canvas.getActiveObject();

        if (activeObject && activeObject.isType('i-text')) {
            var newColor = prompt("Enter new text color (e.g., 'red', '#00ff00', 'rgb(255, 0, 0)'):");

            if (newColor !== null) {
                activeObject.set({
                    fill: newColor
                });
                canvas.renderAll();
            }
        }
    }

    function changeSelectedTextSize() {
        var activeObject = canvas.getActiveObject();

        if (activeObject && activeObject.isType('i-text')) {
            var newSize = document.getElementById('fontSize').value;

            if (newSize !== null) {
                activeObject.set({
                    fontSize: parseInt(newSize)
                });
                canvas.renderAll();
            }
        }
    }

    function toggleSelectedTextBold() {
        var activeObject = canvas.getActiveObject();

        if (activeObject && activeObject.isType('i-text')) {
            var isBold = document.getElementById('bold').checked;

            activeObject.set({
                fontWeight: isBold ? 'bold' : 'normal'
            });
            canvas.renderAll();
        }
    }

    function toggleSelectedTextItalic() {
        var activeObject = canvas.getActiveObject();

        if (activeObject && activeObject.isType('i-text')) {
            var isItalic = document.getElementById('italic').checked;

            activeObject.set({
                fontStyle: isItalic ? 'italic' : 'normal'
            });
            canvas.renderAll();
        }
    }

    function toggleSelectedTextUnderline() {
        var activeObject = canvas.getActiveObject();

        if (activeObject && activeObject.isType('i-text')) {
            var isUnderline = document.getElementById('underline').checked;

            activeObject.set({
                underline: isUnderline
            });
            canvas.renderAll();
        }
    }

    function changeSelectedTextFontFamily() {
        var activeObject = canvas.getActiveObject();

        if (activeObject && activeObject.isType('i-text')) {
            var newFontFamily = document.getElementById('fontFamily').value;

            if (newFontFamily !== null) {
                activeObject.set({
                    fontFamily: newFontFamily
                });
                canvas.renderAll();
            }
        }
    }

    // function saveTemplate() {
    //     // Serialize the canvas and save it to localStorage
    //     var canvasData = JSON.stringify(canvas.toJSON());
    //     localStorage.setItem('template', canvasData);
    //     alert('Template saved successfully!');
    // }

    function saveTemplate() {
        // Prompt the user for a template name
        var templateName = prompt("Enter a name for the template:");

        if (templateName !== null) {
            // Serialize the canvas and prepare data
            var canvasData = JSON.stringify(canvas.toJSON());

            // Send an Ajax request to save the template
            $.ajax({
                type: 'POST',
                url: '/saveTemplate',
                data: {
                    _token: "{{ csrf_token() }}",
                    templateName: templateName,
                    templateData: canvasData
                },
                success: function(response) {
                    console.log(response);
                    alert('Template saved successfully!');
                },
                error: function(error) {
                    console.error('Error saving template:', error);
                }
            });
        } else {
            alert('Template saving canceled.');
        }
    }

    function loadSelectedTemplate() {
        var templateSelect = document.getElementById('templateSelect');
        var selectedOption = templateSelect.options[templateSelect.selectedIndex];
        var templateData = selectedOption.getAttribute('data-template-data');
        console.log(templateData);
        if (templateData !== null) {
            // Load the template data into the canvas
            canvas.loadFromJSON(JSON.parse(templateData), function() {
                canvas.renderAll();
                alert('Template loaded successfully!');
            });
        } else {
            alert('Please select a template.');
        }
    }
    // function loadTemplate() {
    //     // Load the template from localStorage and apply it to the canvas
    //     var savedData = localStorage.getItem('template');
    //     console.log(savedData);
    //     if (savedData) {
    //         canvas.loadFromJSON(savedData, function () {
    //             canvas.renderAll();
    //             alert('Template loaded successfully!');
    //         });
    //     } else {
    //         alert('No template found.');
    //     }
    // }


    document.getElementById('imageUpload').addEventListener('change', function(e) {
        var file = e.target.files[0];

        if (file) {
            var reader = new FileReader();
            reader.onload = function(event) {
                var img = new Image();
                img.src = event.target.result;
                img.onload = function() {
                    var fabricImg = new fabric.Image(img, {
                        left: 100,
                        top: 400,
                        scaleX: 0.5,
                        scaleY: 0.5
                    });
                    canvas.add(fabricImg);
                };
            };
            reader.readAsDataURL(file);
        }
    });
    </script>
</body>

</html>