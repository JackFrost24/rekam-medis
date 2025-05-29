import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls';

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi variabel
    let selectedTooth = null;
    const odontogramData = {};
    let scene, camera, renderer;
    const toothConditions = {
        'healthy': 0xffffff,
        'caries': 0xff0000,
        'filling': 0x0000ff,
        'extracted': 0x888888,
        'root_canal': 0x00ff00,
        'crown': 0xffff00
    };

    // Fungsi untuk menampilkan toast notification
    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Inisialisasi Three.js scene
    function init3DScene() {
        // Buat container untuk 3D viewer
        const container = document.createElement('div');
        container.id = 'tooth3dViewer';
        container.style.position = 'fixed';
        container.style.top = '0';
        container.style.left = '0';
        container.style.width = '100%';
        container.style.height = '100%';
        container.style.backgroundColor = 'rgba(0,0,0,0.8)';
        container.style.zIndex = '1000';
        container.style.display = 'none';
        document.body.appendChild(container);

        // Scene
        scene = new THREE.Scene();
        scene.background = new THREE.Color(0xf0f0f0);

        // Camera
        camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        camera.position.z = 5;

        // Renderer
        renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        container.appendChild(renderer.domElement);

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

        // Close button
        const closeBtn = document.createElement('button');
        closeBtn.textContent = '×';
        closeBtn.style.position = 'absolute';
        closeBtn.style.top = '20px';
        closeBtn.style.right = '20px';
        closeBtn.style.zIndex = '1001';
        closeBtn.style.background = 'red';
        closeBtn.style.color = 'white';
        closeBtn.style.border = 'none';
        closeBtn.style.borderRadius = '50%';
        closeBtn.style.width = '40px';
        closeBtn.style.height = '40px';
        closeBtn.style.fontSize = '20px';
        closeBtn.style.cursor = 'pointer';
        closeBtn.addEventListener('click', () => {
            container.style.display = 'none';
        });
        container.appendChild(closeBtn);

        // Animation loop
        function animate() {
            requestAnimationFrame(animate);
            controls.update();
            renderer.render(scene, camera);
        }
        animate();

        // Handle window resize
        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });

        return container;
    }

    // Memuat model 3D gigi
    async function loadToothModel(toothNumber, condition, gvBlackClass = null) {
        if (condition === 'extracted') return null;

        try {
            let modelName;
            
            // Tentukan nama model berdasarkan kondisi
            if (condition === 'caries' && gvBlackClass) {
                modelName = `${toothNumber}_class_${gvBlackClass}`;
            } else {
                // Untuk kondisi selain karies, gunakan model sehat
                modelName = toothNumber.toString();
            }

            // Gunakan GLTFLoader untuk memuat model
            const loader = new THREE.GLTFLoader();
            const gltf = await new Promise((resolve, reject) => {
                loader.load(`/models/${modelName}.glb`, resolve, undefined, (error) => {
                    // Jika model spesifik tidak ditemukan, coba gunakan model default
                    if (error && condition !== 'caries') {
                        loader.load(`/models/${toothNumber}.glb`, resolve, undefined, reject);
                    } else {
                        reject(error);
                    }
                });
            });

            const model = gltf.scene;
            model.userData.toothNumber = toothNumber;
            model.userData.isTooth = true;
            
            // Terapkan material berdasarkan kondisi
            model.traverse((child) => {
                if (child.isMesh) {
                    child.material.color.setHex(toothConditions[condition] || 0xffffff);
                }
            });

            return model;
        } catch (error) {
            console.error(`Error loading model for tooth ${toothNumber}:`, error);
            return null;
        }
    }

    // Menampilkan model 3D berdasarkan jenis gigi (dewasa/anak)
    async function show3DModels(type = 'adult') {
        const container = document.getElementById('tooth3dViewer') || init3DScene();
        container.style.display = 'block';

        // Hapus model gigi yang ada
        scene.children.filter(obj => obj.userData.isTooth).forEach(obj => scene.remove(obj));

        // Daftar gigi dewasa dan anak
        const adultTeeth = ['18','17','16','15','14','13','12','11',
                          '21','22','23','24','25','26','27','28',
                          '48','47','46','45','44','43','42','41',
                          '31','32','33','34','35','36','37','38'];
        
        const childTeeth = ['55','54','53','52','51','61','62','63','64','65',
                          '85','84','83','82','81','71','72','73','74','75'];

        // Filter gigi yang akan dirender berdasarkan jenis
        const teethToRender = type === 'adult' ? adultTeeth : childTeeth;
        const dataToRender = teethToRender.reduce((acc, tooth) => {
            if (odontogramData[tooth]) acc[tooth] = odontogramData[tooth];
            return acc;
        }, {});

        // Muat dan tampilkan model untuk setiap gigi yang sesuai
        for (const [toothNumber, data] of Object.entries(dataToRender)) {
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

        // Tambahkan gusi jika diperlukan
        await addGumModels();
    }

    // Posisikan model gigi berdasarkan nomor gigi
    function positionToothModel(model, toothNumber) {
        const num = parseInt(toothNumber);
        
        if (num >= 11 && num <= 28) { // Gigi atas
            const posX = (num - 19.5) * 0.3;
            model.position.set(posX, 1, 0);
        } else if (num >= 31 && num <= 48) { // Gigi bawah
            const posX = (num - 39.5) * 0.3;
            model.position.set(posX, -1, 0);
        } else if (num >= 51 && num <= 65) { // Gigi susu atas
            const posX = ((num - 50) - 5.5) * 0.25;
            model.position.set(posX, 1.5, 0);
            model.scale.set(0.8, 0.8, 0.8);
        } else if (num >= 71 && num <= 85) { // Gigi susu bawah
            const posX = ((num - 70) - 5.5) * 0.25;
            model.position.set(posX, -1.5, 0);
            model.scale.set(0.8, 0.8, 0.8);
        }
        
        model.rotation.y = Math.PI;
    }

    // Tambahkan model gusi
    async function addGumModels(type = 'adult') {
    try {
        const loader = new THREE.GLTFLoader();
        
        // Muat model gusi berdasarkan jenis (dewasa/anak)
        if (type === 'adult') {
            // Gusi dewasa atas
            const upperGum = await new Promise((resolve, reject) => {
                loader.load('/models/gusi_atas.glb', resolve, undefined, reject);
            });
            upperGum.scene.position.set(0, 1.2, 0);
            scene.add(upperGum.scene);

            // Gusi dewasa bawah
            const lowerGum = await new Promise((resolve, reject) => {
                loader.load('/models/gusi_bawah.glb', resolve, undefined, reject);
            });
            lowerGum.scene.position.set(0, -1.2, 0);
            scene.add(lowerGum.scene);
        } else {
            // Gusi anak atas (CGumTop)
            const upperGumChild = await new Promise((resolve, reject) => {
                loader.load('/models/CGumTop.glb', resolve, undefined, reject);
            });
            upperGumChild.scene.position.set(0, 1.5, 0);
            upperGumChild.scene.scale.set(0.8, 0.8, 0.8);
            scene.add(upperGumChild.scene);

            // Gusi anak bawah (CGumBottom)
            const lowerGumChild = await new Promise((resolve, reject) => {
                loader.load('/models/CGumBottom.glb', resolve, undefined, reject);
            });
            lowerGumChild.scene.position.set(0, -1.5, 0);
            lowerGumChild.scene.scale.set(0.8, 0.8, 0.8);
            scene.add(lowerGumChild.scene);
        }
    } catch (error) {
        console.error('Error loading gum models:', error);
    }
}

    // Event listener untuk klik gigi
    document.querySelectorAll('.tooth').forEach(tooth => {
        tooth.addEventListener('click', function() {
            document.querySelectorAll('.tooth').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            selectedTooth = this.getAttribute('data-number');
            document.getElementById('selected-tooth').textContent = selectedTooth;
            
            // Isi form dengan data yang ada
            if (odontogramData[selectedTooth]) {
                document.getElementById('condition').value = odontogramData[selectedTooth].condition;
                document.getElementById('gv_black_class').value = odontogramData[selectedTooth].gv_black_class || '';
                document.getElementById('surface').value = odontogramData[selectedTooth].surface || 'whole';
                document.getElementById('notes').value = odontogramData[selectedTooth].notes || '';
                
                // Tampilkan GV Black jika kondisi karies
                document.querySelector('.gv-black-container').classList.toggle(
                    'hidden', 
                    odontogramData[selectedTooth].condition !== 'caries'
                );
            } else {
                document.getElementById('condition').value = 'healthy';
                document.getElementById('gv_black_class').value = '';
                document.getElementById('surface').value = 'whole';
                document.getElementById('notes').value = '';
                document.querySelector('.gv-black-container').classList.add('hidden');
            }
        });
    });

    // Event listener untuk perubahan kondisi
    document.getElementById('condition').addEventListener('change', function() {
        document.querySelector('.gv-black-container').classList.toggle(
            'hidden', 
            this.value !== 'caries'
        );
    });

    // Event listener untuk tombol 3D Dewasa
    document.getElementById('show3dAdult')?.addEventListener('click', function() {
        if (Object.keys(odontogramData).length === 0) {
            showToast('error', 'Tidak ada data odontogram untuk ditampilkan');
            return;
        }
        show3DModels('adult');
    });

    // Event listener untuk tombol 3D Anak
    document.getElementById('show3dChild')?.addEventListener('click', function() {
        if (Object.keys(odontogramData).length === 0) {
            showToast('error', 'Tidak ada data odontogram untuk ditampilkan');
            return;
        }
        show3DModels('child');
    });

    // Inisialisasi 3D viewer
    init3DScene();
});