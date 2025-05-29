@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">3D Odontogram Viewer</h1>
        <a href="{{ url()->previous() }}" 
           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div id="tooth3dViewer" style="width: 100%; height: 600px;"></div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/GLTFLoader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const odontogramData = @json($odontogramData);
    const type = @json($type);
    
    // Initialize Three.js scene
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0xf0f0f0);
    
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.z = 5;
    
    const renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(document.getElementById('tooth3dViewer').clientWidth, 
                    document.getElementById('tooth3dViewer').clientHeight);
    document.getElementById('tooth3dViewer').appendChild(renderer.domElement);
    
    // Lighting
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
    scene.add(ambientLight);
    
    const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
    directionalLight.position.set(1, 1, 1);
    scene.add(directionalLight);
    
    // Controls
    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.25;
    
    const conditionColors = {
        'healthy': 0xffffff,
        'caries': 0xff0000,
        'filling': 0x0000ff,
        'extracted': 0x888888,
        'root_canal': 0x00ff00,
        'crown': 0xffff00
    };
    
    const loader = new THREE.GLTFLoader();
    
    // Function to load tooth model
    async function loadToothModel(toothNumber, condition, gvBlackClass = null) {
        if (condition === 'extracted') return null;
        
        try {
            let modelName;
            
            if (condition === 'caries' && gvBlackClass) {
                modelName = `${toothNumber}_class_${gvBlackClass}`;
            } else {
                modelName = toothNumber.toString();
            }
            
            const gltf = await new Promise((resolve, reject) => {
                loader.load(`/models/${modelName}.glb`, resolve, undefined, reject);
            });
            
            const model = gltf.scene;
            model.userData.toothNumber = toothNumber;
            model.userData.isTooth = true;
            
            model.traverse((child) => {
                if (child.isMesh) {
                    child.material.color.setHex(conditionColors[condition] || 0xffffff);
                }
            });
            
            return model;
        } catch (error) {
            console.error(`Error loading model for tooth ${toothNumber}:`, error);
            return null;
        }
    }
    
    // Position tooth model based on tooth number
    function positionToothModel(model, toothNumber) {
        const num = parseInt(toothNumber);
        
        if (num >= 11 && num <= 28) { // Upper permanent
            const posX = (num - 19.5) * 0.3;
            model.position.set(posX, 1, 0);
        } else if (num >= 31 && num <= 48) { // Lower permanent
            const posX = (num - 39.5) * 0.3;
            model.position.set(posX, -1, 0);
        } else if (num >= 51 && num <= 65) { // Upper primary
            const posX = ((num - 50) - 5.5) * 0.25;
            model.position.set(posX, 1.5, 0);
            model.scale.set(0.8, 0.8, 0.8);
        } else if (num >= 71 && num <= 85) { // Lower primary
            const posX = ((num - 70) - 5.5) * 0.25;
            model.position.set(posX, -1.5, 0);
            model.scale.set(0.8, 0.8, 0.8);
        }
        
        model.rotation.y = Math.PI;
    }
    
    // Load all teeth models
    async function loadAllTeeth() {
        for (const [toothNumber, data] of Object.entries(odontogramData)) {
            if (data.condition !== 'extracted') {
                const model = await loadToothModel(
                    toothNumber, 
                    data.condition, 
                    data.condition === 'caries' ? data.gv_black_class : null
                );
                if (model) {
                    scene.add(model);
                    positionToothModel(model, toothNumber);
                }
            }
        }
        
        // Add gums based on type
        await addGumModels(type);
    }
    
    // Add gum models
    async function addGumModels(type) {
        try {
            const loader = new THREE.GLTFLoader();
            
            if (type === 'adult') {
                // Load adult gums
                const upperGum = await new Promise((resolve, reject) => {
                    loader.load('/models/gusi_atas_dewasa.glb', resolve, undefined, reject);
                });
                upperGum.scene.position.set(0, 1.2, 0);
                scene.add(upperGum.scene);
                
                const lowerGum = await new Promise((resolve, reject) => {
                    loader.load('/models/gusi_bawah_dewasa.glb', resolve, undefined, reject);
                });
                lowerGum.scene.position.set(0, -1.2, 0);
                scene.add(lowerGum.scene);
            } else {
                // Load child gums
                const upperGum = await new Promise((resolve, reject) => {
                    loader.load('/models/gusi_atas_anak.glb', resolve, undefined, reject);
                });
                upperGum.scene.position.set(0, 1.2, 0);
                scene.add(upperGum.scene);
                
                const lowerGum = await new Promise((resolve, reject) => {
                    loader.load('/models/gusi_bawah_anak.glb', resolve, undefined, reject);
                });
                lowerGum.scene.position.set(0, -1.2, 0);
                scene.add(lowerGum.scene);
            }
        } catch (error) {
            console.error('Error loading gum models:', error);
        }
    }
    
    // Animation loop
    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
    }
    
    // Start loading and animation
    loadAllTeeth();
    animate();
    
    // Handle window resize
    window.addEventListener('resize', () => {
        camera.aspect = document.getElementById('tooth3dViewer').clientWidth / 
                         document.getElementById('tooth3dViewer').clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(document.getElementById('tooth3dViewer').clientWidth, 
                        document.getElementById('tooth3dViewer').clientHeight);
    });
});
</script>
@endpush
@endsection