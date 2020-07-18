<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Cube Wave</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">
  <style>
    body { margin: 0; }
    canvas { width: 100vw; height: 100vh; }
  </style>
</head>
<body>
  <canvas id="canvas"></canvas>
  <script src="js/three.min.js"></script>
  <script>

    /***********************************************************************
    * Cube
    ***********************************************************************/
    const geometry = new THREE.BoxGeometry(1, 1, 1);
    const material = new THREE.MeshPhongMaterial();

    class Cube {
      constructor(x, y, offset) {
        this.offset = offset;
        this.mesh = new THREE.Mesh(geometry, material);
        this.mesh.position.set(x, 0, y);
        // Object shadow
        this.mesh.receiveShadow = true;
        this.mesh.castShadow = true;
      }

      update(t) {
        this.mesh.scale.y = map(Math.sin(t + this.offset), -1, 1, 1, 20);
      }
    }

    /***********************************************************************
    * ThreeJS
    ***********************************************************************/

    let scene, camera, renderer;

    let width = window.innerWidth;
    let height = window.innerHeight;

    // Set up scene
    scene = new THREE.Scene();

    // Set up camera
    camera = new THREE.PerspectiveCamera(75, width / height);
    camera.position.set(20, 20, 20);
    camera.lookAt(0, 0, 0);

    // Set up renderer
    renderer = new THREE.WebGLRenderer({
        canvas: document.getElementById("canvas"),
        antialias: true
    });
    renderer.setSize(width, height);
    window.addEventListener("resize", onResize, false);

    // Set up directional light
    let directionalLight = new THREE.DirectionalLight();
    directionalLight.position.set(5, 5, 0);
    directionalLight.castShadow = true;
    scene.add(directionalLight);

    // Time
    let t = 0;

    // Set up cubes
    let size = 10;
    let cubes = [];
    for(let x = -size; x <= size; x++) {
      for(let y = -size; y <= size; y++) {
        // Distance from center
        let distance = Math.sqrt(x * x + y * y);
        // Interpolate offset from distance
        let offset = map(distance, 0, Math.sqrt(size * size * 2), 0, Math.PI);
        // Create cube
        let cube = new Cube(x, y, offset);
        cubes.push(cube);
        scene.add(cube.mesh);
      }
    }

    animate();

    /***********************************************************************
    * Animation Loop
    ***********************************************************************/
    function animate() {
      // Animate cubes
      cubes.forEach(cube => cube.update(t));
      // Update time
      t += 0.1;
      requestAnimationFrame(animate);
      renderer.render(scene, camera);
    }

    /***********************************************************************
    * Event Handlers
    ***********************************************************************/
    function onResize() {
      width = window.innerWidth;
      height = window.innerHeight;
      camera.aspect = width / height;
      camera.updateProjectionMatrix();
      renderer.setSize(width, height);
		}

    /***********************************************************************
    * Helper Functions
    ***********************************************************************/
    function map(val, min1, max1, min2, max2) {
      let diff1 = max1 - min1;
      let diff2 = max2 - min2;
      return (val - min1) / diff1 * diff2 + min2;
    }

  </script>
</body>
</html>
