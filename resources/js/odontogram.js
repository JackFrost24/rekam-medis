import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader';
document.addEventListener('DOMContentLoaded', function() {


    // ==================== INITIALIZATION ====================
    let selectedTooth = null; // Menyimpan gigi yang sedang dipilih
    const odontogramData = JSON.parse(document.getElementById('odontogram_data').value || '{}'); // Menyimpan semua data odontogram dalam format JSON
    let scene, camera, renderer; // Variabel Three.js
    
    // Warna untuk setiap kondisi gigi
    const toothConditions = {
        'healthy': 0xffffff,     // Putih
        'caries': 0xff0000,      // Merah
        'filling': 0x0000ff,     // Biru
        'extracted': 0x888888,   // Abu-abu
        'root_canal': 0x00ff00,  // Hijau
        'crown': 0xffff00        // Kuning
    };

    // ==================== UTILITY FUNCTIONS ====================
    
    /**
     * Menampilkan notifikasi toast
     * @param {string} type - Jenis toast (success, error, info)
     * @param {string} message - Pesan yang ditampilkan
     */
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

    // ==================== ODONTOGRAM FUNCTIONS ====================
    
    /**
     * Memperbarui data odontogram untuk gigi yang dipilih
     */
    function updateOdontogramData() {
        if (!selectedTooth) {
            showToast('error', 'Silakan pilih gigi terlebih dahulu');
            return;
        }
        
        const condition = document.getElementById('condition').value;
        const surface = document.getElementById('surface').value;
        const notes = document.getElementById('notes').value;
        
        // Simpan data ke objek odontogramData
        odontogramData[selectedTooth] = {
            condition,
            surface,
            notes,
            updated_at: new Date().toISOString()
        };
        
        updateToothAppearance(selectedTooth, condition);
        document.getElementById('odontogram_data').value = JSON.stringify(odontogramData);
        showToast('success', `Data gigi ${selectedTooth} berhasil disimpan`);
        document.getElementById('show3dBtn').classList.remove('hidden');
    }

    /**
     * Memperbarui tampilan visual gigi di odontogram 2D
     */
    function updateToothAppearance(toothNumber, condition) {
        const toothElement = document.querySelector(`.tooth[data-number="${toothNumber}"]`);
        
        // Reset semua class kondisi
        toothElement.classList.remove(
            'healthy', 'caries', 'filling', 
            'extracted', 'root_canal', 'crown'
        );
        
        // Tambahkan class kondisi baru
        toothElement.classList.add(condition);
        
        // Update tampilan permukaan gigi
        toothElement.style.backgroundImage = 'none';
        if (condition !== 'healthy' && condition !== 'extracted') {
            const surface = odontogramData[toothNumber]?.surface || 'whole';
            applySurfaceStyle(toothElement, surface);
        }
    }

    // ==================== 3D VISUALIZATION ====================
    
    /**
     * Inisialisasi scene Three.js
     */
    function init3DScene() {
        // Buat container untuk viewer 3D
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

        // 1. Buat scene
        scene = new THREE.Scene();
        scene.background = new THREE.Color(0xf0f0f0);

        // 2. Setup camera
        camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        camera.position.z = 5;

        // 3. Buat renderer
        renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        container.appendChild(renderer.domElement);

        // 4. Tambahkan lighting
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
        scene.add(ambientLight);

        const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
        directionalLight.position.set(1, 1, 1);
        scene.add(directionalLight);

        // 5. Tambahkan orbit controls
        const controls = new OrbitControls(camera, renderer.domElement);
        controls.enableDamping = true;
        controls.dampingFactor = 0.25;

        // 6. Tambahkan tombol close
        const closeBtn = document.createElement('button');
        closeBtn.textContent = '×';
        closeBtn.style.position = 'absolute';
        closeBtn.style.top = '20px';
        closeBtn.style.right = '20px';
        closeBtn.style.zIndex = '1001';
        closeBtn.addEventListener('click', () => {
            container.style.display = 'none';
        });
        container.appendChild(closeBtn);

        // 7. Animation loop
        function animate() {
            requestAnimationFrame(animate);
            controls.update();
            renderer.render(scene, camera);
        }
        animate();

        return container;
    }

    /**
     * Memuat model 3D untuk gigi tertentu
     */
    async function loadToothModel(toothNumber, condition) {
        if (condition === 'extracted') return null;

        try {
            // Tentukan path model berdasarkan nomor gigi
            let modelPath;
            if (toothNumber >= 11 && toothNumber <= 28) {
                modelPath = `/models/top_${toothNumber}.glb`;
            } else if (toothNumber >= 31 && toothNumber <= 48) {
                modelPath = `/models/bot_${toothNumber}.glb`;
            } else {
                modelPath = `/models/tooth_${toothNumber}.glb`;
            }

            // Muat model menggunakan GLTFLoader
            const loader = new THREE.GLTFLoader();
            const gltf = await new Promise((resolve, reject) => {
                loader.load(modelPath, resolve, undefined, reject);
            });

            const model = gltf.scene;
            model.userData.toothNumber = toothNumber;
            
            // Terapkan material berdasarkan kondisi
            model.traverse((child) => {
                if (child.isMesh) {
                    child.material.color.setHex(toothConditions[condition] || 0xffffff);
                }
            });

            return model;
        } catch (error) {
            console.error(`Gagal memuat model gigi ${toothNumber}:`, error);
            return null;
        }
    }

    /**
     * Menampilkan semua model gigi dalam 3D
     */
    async function show3DModels() {
    try {
        const container = document.getElementById('tooth3dViewer') || init3DScene();
        container.style.display = 'block';
        
        // Clear existing models
        while(scene.children.length > 0) { 
            scene.remove(scene.children[0]); 
        }

        // Add lights back
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
        scene.add(ambientLight);

        const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
        directionalLight.position.set(1, 1, 1);
        scene.add(directionalLight);

        // Load models
        for (const [toothNumber, data] of Object.entries(odontogramData)) {
            if (data.condition !== 'extracted') {
                try {
                    const model = await loadToothModel(toothNumber, data.condition);
                    if (model) {
                        scene.add(model);
                        positionToothModel(model, toothNumber);
                    }
                } catch (error) {
                    console.error(`Error loading tooth ${toothNumber}:`, error);
                }
            }
        }

        // Add gums
        await addGumModels();

    } catch (error) {
        console.error('Error in 3D viewer:', error);
        showToast('error', 'Gagal memuat model 3D: ' + error.message);
    }
}

    // ==================== EVENT LISTENERS ====================
    
    // Klik pada gigi di odontogram
    document.querySelectorAll('.tooth').forEach(tooth => {
        tooth.addEventListener('click', function() {
            // Highlight gigi yang dipilih
            document.querySelectorAll('.tooth').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            selectedTooth = this.getAttribute('data-number');
            document.getElementById('selected-tooth').textContent = selectedTooth;
            
            // Isi form dengan data yang ada
            if (odontogramData[selectedTooth]) {
                document.getElementById('condition').value = odontogramData[selectedTooth].condition;
                document.getElementById('surface').value = odontogramData[selectedTooth].surface;
                document.getElementById('notes').value = odontogramData[selectedTooth].notes || '';
            } else {
                // Reset form jika tidak ada data
                document.getElementById('condition').value = 'healthy';
                document.getElementById('surface').value = 'whole';
                document.getElementById('notes').value = '';
            }
        });
    });

    // Tombol simpan detail gigi
    document.querySelector('.tooth-details button[type="submit"]').addEventListener('click', function(e) {
        e.preventDefault();
        updateOdontogramData();
    });

    // Tombol tampilkan 3D
    document.getElementById('show3dBtn').addEventListener('click', function() {
        if (Object.keys(odontogramData).length === 0) {
            showToast('error', 'Tidak ada data odontogram untuk ditampilkan');
            return;
        }
        show3DModels();
    });

    // Inisialisasi scene 3D saat halaman dimuat
    init3DScene();
    showToast('info', 'Silakan pilih gigi untuk mulai mengisi data');
});