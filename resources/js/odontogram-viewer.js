import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';

const canvas = document.querySelector('#viewer3d');
const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
renderer.setSize(window.innerWidth, window.innerHeight);
renderer.shadowMap.enabled = true;

const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 100);
camera.position.set(0, 10, 20);

const light = new THREE.DirectionalLight(0xffffff, 1);
light.position.set(10, 20, 10);
light.castShadow = true;
scene.add(light);
scene.add(new THREE.AmbientLight(0x404040));

const loader = new GLTFLoader();

const MATERIALS = {
    healthy: new THREE.MeshStandardMaterial({ color: 0xffffff }),
    caries_top: new THREE.MeshStandardMaterial({ color: 0xff0000 }),
    caries_left: new THREE.MeshStandardMaterial({ color: 0xff4500 }),
    caries_right: new THREE.MeshStandardMaterial({ color: 0xff4500 }),
    caries_center: new THREE.MeshStandardMaterial({ color: 0x8b0000 }),
    caries_root: new THREE.MeshStandardMaterial({ color: 0xa52a2a })
};

function positionTooth(tooth, number) {
    const isUpper = number < 30;
    const index = parseInt(number.toString().slice(-1));
    const quadrant = parseInt(number.toString().slice(0, 1));
    const x = (index - 4.5) * 1.2;
    const y = isUpper ? 0 : -2;
    const z = quadrant <= 2 ? 0 : 2;

    tooth.position.set(x, y, z);
    tooth.rotation.y = (quadrant % 2 === 0) ? Math.PI : 0;
}

async function loadTooth(number, condition) {
    if (condition === 'extracted') return null;

    const validConditions = ['healthy', 'caries_top', 'caries_left', 'caries_right', 'caries_center', 'caries_root'];
    if (!validConditions.includes(condition)) {
        condition = 'healthy';
    }

    const prefix = number >= 30 ? 'bot' : 'top';
    const modelName = `${prefix}${number}`;

    try {
        const gltf = await loader.loadAsync(`/models/${modelName}.glb`);
        const tooth = gltf.scene;

        tooth.traverse(child => {
            if (child.isMesh) {
                child.material = MATERIALS[condition] || MATERIALS.healthy;
                child.castShadow = true;
            }
        });

        positionTooth(tooth, number);
        scene.add(tooth);
        return tooth;

    } catch (error) {
        console.error(`Error loading model ${modelName}:`, error);
        return null;
    }
}

async function init() {
    const odontogramData = JSON.parse(localStorage.getItem('odontogramData') || '{}');

    for (const number in odontogramData) {
        const { condition } = odontogramData[number];
        await loadTooth(parseInt(number), condition);
    }

    animate();
}

function animate() {
    requestAnimationFrame(animate);
    renderer.render(scene, camera);
}

init();
