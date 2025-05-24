import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';



const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
const renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.setSize(window.innerWidth, window.innerHeight);
document.body.appendChild(renderer.domElement);

// Pencahayaan
const light = new THREE.DirectionalLight(0xffffff, 1);
light.position.set(0, 1, 1).normalize();
scene.add(light);

const ambientLight = new THREE.AmbientLight(0x404040); // cahaya ambient tambahan
scene.add(ambientLight);

// Kontrol kamera
const controls = new OrbitControls(camera, renderer.domElement);
camera.position.set(0, 0, 10);
controls.update();

const loader = new GLTFLoader();

// Daftar semua model gigi dan gusi yang akan dimuat
const models = [
    'top_11', 'top_12', 'top_13', 'top_14', 'top_15', 'top_16', 'top_17', 'top_18',
    'top_21', 'top_22', 'top_23', 'top_24', 'top_25', 'top_26', 'top_27', 'top_28',
    'bot_31', 'bot_32', 'bot_33', 'bot_34', 'bot_35', 'bot_36', 'bot_37', 'bot_38',
    'bot_41', 'bot_42', 'bot_43', 'bot_44', 'bot_45', 'bot_46', 'bot_47', 'bot_48',
    'gusi_atas', 'gusi_bawah'
];

// Load semua model
models.forEach((modelName) => {
    loader.load(`/models/${modelName}.glb`, function (gltf) {
        scene.add(gltf.scene);
    }, undefined, function (error) {
        console.error(`Gagal memuat model ${modelName}:`, error);
    });
});

// Animasi render loop
function animate() {
    requestAnimationFrame(animate);
    controls.update();
    renderer.render(scene, camera);
}
animate();