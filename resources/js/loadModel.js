import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader';

// Fungsi untuk load model
export function loadToothModel(modelPath, containerId) {
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, 1, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ alpha: true });

    const container = document.getElementById(containerId);
    renderer.setSize(container.clientWidth, container.clientHeight);
    container.appendChild(renderer.domElement);

    const loader = new GLTFLoader();
    loader.load(modelPath, function (gltf) {
        scene.add(gltf.scene);
        camera.position.z = 5;
        renderer.render(scene, camera);

        // Animasi render
        function animate() {
            requestAnimationFrame(animate);
            gltf.scene.rotation.y += 0.01;
            renderer.render(scene, camera);
        }

        animate();
    });
}
