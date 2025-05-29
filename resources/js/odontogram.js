import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls';

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi variabel
    let selectedTooth = null;
    const odontogramData = {};
    let scene, camera, renderer, toothModels = {};
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

    // Fungsi untuk mengupdate data odontogram
    function updateOdontogramData() {
        if (!selectedTooth) {
            showToast('error', 'Silakan pilih gigi terlebih dahulu');
            return;
        }
        
        const condition = document.getElementById('condition').value;
        const surface = document.getElementById('surface').value;
        const notes = document.getElementById('notes').value;
        
        odontogramData[selectedTooth] = {
            condition,
            surface,
            notes,
            updated_at: new Date().toISOString()
        };
        
        updateToothAppearance(selectedTooth, condition);
        document.getElementById('odontogram_data').value = JSON.stringify(odontogramData);
        showToast('success', `Data gigi ${selectedTooth} berhasil disimpan`);
        
        // Tampilkan tombol 3D jika ada data
        document.getElementById('show3dBtn').classList.remove('hidden');
    }

    // Fungsi untuk mengupdate tampilan gigi
    function updateToothAppearance(toothNumber, condition) {
        const toothElement = document.querySelector(`.tooth[data-number="${toothNumber}"]`);
        toothElement.classList.remove('healthy', 'caries', 'filling', 'extracted', 'root_canal', 'crown');
        toothElement.classList.add(condition);
        
        // Update tampilan permukaan gigi
        toothElement.style.backgroundImage = 'none';
        if (condition !== 'healthy' && condition !== 'extracted') {
            const surface = odontogramData[toothNumber]?.surface || 'whole';
            applySurfaceStyle(toothElement, surface);
        }
    }

    // Fungsi untuk menambahkan style permukaan gigi
    function applySurfaceStyle(element, surface) {
        const colors = {
            'buccal': 'linear-gradient(to right, transparent 50%, currentColor 50%)',
            'lingual': 'linear-gradient(to left, transparent 50%, currentColor 50%)',
            'occlusal': 'linear-gradient(to bottom, transparent 50%, currentColor 50%)',
            'mesial': 'linear-gradient(135deg, transparent 50%, currentColor 50%)',
            'distal': 'linear-gradient(-135deg, transparent 50%, currentColor 50%)'
        };
        
        if (surface !== 'whole' && colors[surface]) {
            element.style.backgroundImage = colors[surface];
        }
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
                // Untuk gigi susu atau lainnya
                modelPath = `/models/tooth_${toothNumber}.glb`;
            }

            // Gunakan GLTFLoader untuk memuat model
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
                    
                    // Tambahkan efek untuk permukaan tertentu
                    if (odontogramData[toothNumber]?.surface && odontogramData[toothNumber].surface !== 'whole') {
                        // Implementasi efek permukaan bisa ditambahkan di sini
                    }
                }
            });

            return model;
        } catch (error) {
            console.error(`Error loading model for tooth ${toothNumber}:`, error);
            return null;
        }
    }

    // Menampilkan model 3D berdasarkan data odontogram
    async function show3DModels() {
        const container = document.getElementById('tooth3dViewer') || init3DScene();
        container.style.display = 'block';

        // Hapus model yang ada
        scene.children.filter(obj => obj.userData.isTooth).forEach(obj => scene.remove(obj));

        // Muat dan tampilkan model untuk setiap gigi
        for (const [toothNumber, data] of Object.entries(odontogramData)) {
            if (data.condition !== 'extracted') {
                const model = await loadToothModel(toothNumber, data.condition);
                if (model) {
                    scene.add(model);
                    
                    // Posisikan model berdasarkan jenis gigi
                    positionToothModel(model, toothNumber);
                }
            }
        }

        // Tambahkan gusi jika diperlukan
        await addGumModels();
    }

    // Posisikan model gigi berdasarkan nomor gigi
    function positionToothModel(model, toothNumber) {
        // Implementasi logika penempatan gigi di scene
        // Ini adalah contoh sederhana, perlu disesuaikan dengan kebutuhan
        const num = parseInt(toothNumber);
        
        if (num >= 11 && num <= 28) { // Gigi atas
            const posX = (num - 19.5) * 0.3; // Posisi horizontal
            model.position.set(posX, 1, 0);
        } else if (num >= 31 && num <= 48) { // Gigi bawah
            const posX = (num - 39.5) * 0.3; // Posisi horizontal
            model.position.set(posX, -1, 0);
        }
        
        model.rotation.y = Math.PI; // Putar gigi agar menghadap ke depan
    }

    // Tambahkan model gusi
    async function addGumModels() {
        // Muat model gusi atas
        try {
            const loader = new THREE.GLTFLoader();
            const upperGum = await new Promise((resolve, reject) => {
                loader.load('/models/gusi_atas.glb', resolve, undefined, reject);
            });
            upperGum.scene.position.set(0, 1.2, 0);
            scene.add(upperGum.scene);

            // Muat model gusi bawah
            const lowerGum = await new Promise((resolve, reject) => {
                loader.load('/models/gusi_bawah.glb', resolve, undefined, reject);
            });
            lowerGum.scene.position.set(0, -1.2, 0);
            scene.add(lowerGum.scene);
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
            
            if (odontogramData[selectedTooth]) {
                document.getElementById('condition').value = odontogramData[selectedTooth].condition;
                document.getElementById('surface').value = odontogramData[selectedTooth].surface;
                document.getElementById('notes').value = odontogramData[selectedTooth].notes || '';
            } else {
                document.getElementById('condition').value = 'healthy';
                document.getElementById('surface').value = 'whole';
                document.getElementById('notes').value = '';
            }
        });
    });

    // Event listener untuk simpan detail gigi
    document.querySelector('.tooth-details button[type="submit"]').addEventListener('click', function(e) {
        e.preventDefault();
        updateOdontogramData();
    });

    // Event listener untuk tombol tampilkan 3D
    document.getElementById('show3dBtn').addEventListener('click', function() {
        if (Object.keys(odontogramData).length === 0) {
            showToast('error', 'Tidak ada data odontogram untuk ditampilkan');
            return;
        }
        show3DModels();
    });

    // Event listener untuk submit form utama
    document.getElementById('patientForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!validateForm()) return;
        
        document.getElementById('odontogram_data').value = JSON.stringify(odontogramData);
        
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Menyimpan...';
        
        try {
            const formData = new FormData(this);
            
            // Convert checkboxes to proper values
            formData.set('heart_disease', document.getElementById('heart_disease').checked ? '1' : '0');
            formData.set('diabetes', document.getElementById('diabetes').checked ? '1' : '0');
            formData.set('hepatitis', document.getElementById('hepatitis').checked ? '1' : '0');
            
            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (response.ok) {
                showToast('success', 'Data pasien berhasil disimpan!');
                setTimeout(() => {
                    window.location.href = result.redirect || '/patients';
                }, 2000);
            } else {
                showToast('error', result.message || 'Gagal menyimpan data pasien');
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Submit Patient Data';
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('error', 'Terjadi kesalahan jaringan');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Submit Patient Data';
        }
    });

    // Fungsi untuk validasi form
    function validateForm() {
        const name = document.getElementById('name').value.trim();
        const contact = document.getElementById('contact').value.trim();
        
        if (!name) {
            showToast('error', 'Nama pasien harus diisi');
            return false;
        }
        
        if (!contact) {
            showToast('error', 'Nomor kontak harus diisi');
            return false;
        }
        
        return true;
    }

    // Inisialisasi 3D viewer
    init3DScene();
    showToast('info', 'Silakan pilih gigi untuk mulai mengisi data');
});