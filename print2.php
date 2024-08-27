<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file upload
    if (isset($_FILES['stlFile']) && $_FILES['stlFile']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['stlFile']['tmp_name'];
        $fileName = $_FILES['stlFile']['name'];
        $destination = "uploads/" . $fileName;

        // Create uploads directory if it doesn't exist
        if (!is_dir('uploads')) {
            mkdir('uploads');
        }

        move_uploaded_file($fileTmpPath, $destination);

        // Provide the path to the uploaded file for JavaScript
        echo "<script>var uploadedFile = '$destination';</script>";
    } else {
        echo "<script>alert('There was an error uploading the file.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D Print Order Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .upload-section {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        #viewer {
            width: 200px;
            height: 200px;
            background: #e0e0e0;
            margin: 20px 0;
        }
        .form-group {
            margin: 10px 0;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group select, .form-group input[type="text"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .price {
            font-size: 20px;
            margin-top: 20px;
        }
        .submit-btn {
            background: #28a745;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .submit-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <form method="post" enctype="multipart/form-data">
        <div class="upload-section">
            <label for="file-upload">Upload STL File:</label>
            <input type="file" id="file-upload" name="stlFile" accept=".stl" required />
            <div id="viewer"></div>
            <p id="filename">No file selected</p>
        </div>

        <div class="form-group">
            <label for="printer">3D Printer</label>
            <select id="printer" name="printer">
                <option value="HP Jet Fusion 3D 4210 Printer">HP Jet Fusion 3D 4210 Printer</option>
                <!-- Add more printer options as needed -->
            </select>
        </div>

        <div class="form-group">
            <label for="material">Material</label>
            <select id="material" name="material">
                <option value="HP 3D High Reusability PA 12">HP 3D High Reusability PA 12</option>
                <!-- Add more material options as needed -->
            </select>
        </div>

        <div class="form-group">
            <label for="color">Color</label>
            <select id="color" name="color">
                <option value="Dyed Black">Dyed Black</option>
                <!-- Add more color options as needed -->
            </select>
        </div>

        <div class="form-group">
            <label for="infill">Infill</label>
            <select id="infill" name="infill">
                <option value="Strong">Strong</option>
                <!-- Add more infill options as needed -->
            </select>
        </div>

        <div class="form-group">
            <label for="layerHeight">Layer Height</label>
            <select id="layerHeight" name="layerHeight">
                <option value="Fine">Fine</option>
                <!-- Add more layer height options as needed -->
            </select>
        </div>

        <div class="form-group">
            <label for="finishing">Finishing</label>
            <select id="finishing" name="finishing">
                <option value="Dyed with Color Touch">Dyed with Color Touch</option>
                <!-- Add more finishing options as needed -->
            </select>
        </div>

        <div class="price">
            Price: $23.98
        </div>

        <button type="submit" class="submit-btn">Checkout</button>
    </form>
</div>

<!-- Include Three.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.rawgit.com/mrdoob/three.js/r128/examples/js/loaders/STLLoader.js"></script>

<script>
    document.getElementById('file-upload').addEventListener('change', function() {
        const fileName = this.files[0].name;
        document.getElementById('filename').textContent = fileName;

        // Load the STL file into the viewer
        const file = this.files[0];
        const reader = new FileReader();
        reader.onload = function(event) {
            const contents = event.target.result;
            const geometry = new THREE.STLLoader().parse(contents);
            const material = new THREE.MeshNormalMaterial();
            const mesh = new THREE.Mesh(geometry, material);

            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(75, 1, 0.1, 1000);
            const renderer = new THREE.WebGLRenderer();
            renderer.setSize(200, 200);
            document.getElementById('viewer').innerHTML = '';
            document.getElementById('viewer').appendChild(renderer.domElement);

            camera.position.z = 5;
            scene.add(mesh);

            const animate = function () {
                requestAnimationFrame(animate);
                mesh.rotation.x += 0.01;
                mesh.rotation.y += 0.01;
                renderer.render(scene, camera);
            };
            animate();
        };
        reader.readAsArrayBuffer(file);
    });
</script>

</body>
</html>
