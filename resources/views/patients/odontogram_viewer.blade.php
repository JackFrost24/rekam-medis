<!DOCTYPE html>
<html>
<head>
    <title>Odontogram 3D Viewer</title>
    <!-- Load Three.js dari CDN -->
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/GLTFLoader.js"></script>
    
    <style>
        body { margin: 0; overflow: hidden; }
        #loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            background: rgba(0,0,0,0.7);
            padding: 20px;
            border-radius: 5px;
            font-family: Arial;
        }
    </style>
</head>
<body>
    <div id="loading">Memuat model 3D...</div>
    
    <script>
        // Data dari controller Blade
        const odontogramData = {!! json_encode($odontogramData) !!};
        const patientId = {{ $patient->id }};

        // Inisialisasi Three.js
        const scene = new THREE.Scene();
        scene.background = new THREE.Color(0xf0f0f0);
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth/innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.body.appendChild(renderer.domElement);

        // Lighting
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
        scene.add(ambientLight);
        const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
        directionalLight.position.set(1, 1, 1);
        scene.add(directionalLight);

        // Load model
        async function loadModel(name, position) {
            try {
                const loader = new THREE.GLTFLoader();
                const gltf = await loader.loadAsync(`/models/${name}.glb`);
                gltf.scene.position.set(...position);
                gltf.scene.name = name;
                scene.add(gltf.scene);
                return true;
            } catch (error) {
                console.error(`Gagal memuat ${name}:`, error);
                return false;
            }
        }

        // Main process
        async function init() {
            // 1. Load gusi
            await loadModel('gusi_atas', [0, 5, 0]);
            await loadModel('gusi_bawah', [0, -5, 0]);

            // 2. Load semua gigi sehat
            for (let i = 11; i <= 28; i++) await loadModel(`top${i}`, getPosition(i));
            for (let i = 31; i <= 48; i++) await loadModel(`bot${i}`, getPosition(i));

            // 3. Update kondisi gigi
            Object.entries(odontogramData).forEach(([number, data]) => {
                updateToothCondition(number, data.condition);
            });

            // Setup camera
            camera.position.z = 30;
            const controls = new THREE.OrbitControls(camera, renderer.domElement);
            controls.enableDamping = true;

            // Sembunyikan loading
            document.getElementById('loading').remove();

            // Animation loop
            function animate() {
                requestAnimationFrame(animate);
                controls.update();
                renderer.render(scene, camera);
            }
            animate();
        }

        // Helper functions
        function getPosition(number) {
            // Contoh posisi (sesuaikan dengan Blender)
            const x = (number % 10) * 1.5 - 7.5;
            const y = number >= 30 ? -5 : 5;
            return [x, y, 0];
        }

        function updateToothCondition(number, condition) {
            const prefix = number >= 30 ? 'bot' : 'top';
            const toothName = `${prefix}${number}`;
            const tooth = scene.getObjectByName(toothName);

            if (condition === 'caries') {
                tooth.visible = false;
                const holeModel = scene.getObjectByName(`${toothName}_L`);
                if (holeModel) holeModel.visible = true;
            } else if (condition === 'extracted') {
                tooth.visible = false;
            }
        }

        // Jalankan
        init();
    </script>
</body>
</html>