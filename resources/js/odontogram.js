document.addEventListener('DOMContentLoaded', function() {
    // Elemen DOM
    const teeth = document.querySelectorAll('.tooth');
    const toothDetails = document.getElementById('selected-tooth');
    const toothForm = document.getElementById('tooth-form');
    const mainForm = document.getElementById('patientForm');
    
    // Simpan data gigi sementara
    const teethData = {};

    // Inisialisasi data gigi
    teeth.forEach(tooth => {
        const toothNumber = tooth.getAttribute('data-number');
        teethData[toothNumber] = {
            condition: 'healthy',
            surface: 'whole',
            notes: ''
        };
        
        tooth.addEventListener('click', function() {
            toothDetails.textContent = toothNumber;
            
            if (toothForm) {
                document.getElementById('condition').value = teethData[toothNumber].condition;
                document.getElementById('surface').value = teethData[toothNumber].surface;
                document.getElementById('notes').value = teethData[toothNumber].notes;
            }
        });
    });

    // Handle form submission untuk simpan per gigi
    if (toothForm) {
        toothForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const toothNumber = toothDetails.textContent;
            if (!toothNumber) return;
            
            const condition = document.getElementById('condition').value;
            const surface = document.getElementById('surface').value;
            const notes = document.getElementById('notes').value;
            
            teethData[toothNumber] = { condition, surface, notes };
            updateToothAppearance(toothNumber, condition);
            toothForm.reset();
            showToast('Data gigi berhasil disimpan!');
        });
    }

    // Handle form submission utama
    if (mainForm) {
        mainForm.addEventListener('submit', function(e) {
            const odontogramData = Object.keys(teethData).map(toothNumber => ({
                number: parseInt(toothNumber),
                condition: teethData[toothNumber].condition,
                surface: teethData[toothNumber].surface,
                notes: teethData[toothNumber].notes
            }));

            document.getElementById('odontogram_data').value = JSON.stringify(odontogramData);
            
            // Fixed object comparison logic
            const hasModifiedTeeth = Object.values(teethData).some(tooth => {
                return tooth.condition !== 'healthy' || 
                       tooth.surface !== 'whole' || 
                       tooth.notes !== '';
            });
            
            if (!hasModifiedTeeth) {
                e.preventDefault();
                showToast('Error: Pilih minimal 1 gigi untuk diupdate!', 'error');
            }
        });
    }

    // Fungsi untuk update tampilan gigi
    function updateToothAppearance(toothNumber, condition) {
        const toothElement = document.querySelector(`.tooth[data-number="${toothNumber}"]`);
        if (!toothElement) return;

        // Reset class
        toothElement.className = 'tooth flex items-center justify-center h-8 w-8 m-1 rounded border-2 cursor-pointer';
        
        // Fixed template literal syntax
        const colorMap = {
            healthy: 'bg-green-100 border-green-500',
            caries: 'bg-red-100 border-red-500',
            filling: 'bg-yellow-100 border-yellow-500',
            extracted: 'bg-gray-200 border-gray-500',
            root_canal: 'bg-blue-100 border-blue-500',
            crown: 'bg-purple-100 border-purple-500'
        };
        
        if (colorMap[condition]) {
            toothElement.classList.add(...colorMap[condition].split(' '));
        }
    }

    // Fungsi notifikasi
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-4 py-2 rounded-md text-white ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    document.getElementById('patientForm').addEventListener('submit', async (e) => {
        e.preventDefault();
    
    // 1. Validasi
    if (!validateForm()) return; 
    
    // 2. Kumpulkan data
    const formData = new FormData(e.target);
    const odontogramData = collectTeethData(); // Dari implementasi sebelumnya
    
    // 3. Kirim ke backend
    try {
        const response = await fetch('/api/patients', {
            method: 'POST',
            body: JSON.stringify({ ...Object.fromEntries(formData), odontogramData }),
            headers: { 'Content-Type': 'application/json' }
        });
        
        if (response.ok) {
            alert('Data tersimpan!');
            // Redirect atau reset form
        }
    } catch (error) {
        console.error('Error:', error);
    }
})
});