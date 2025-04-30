document.addEventListener('DOMContentLoaded', function() {
    const teeth = document.querySelectorAll('.tooth');
    const toothDetails = document.getElementById('selected-tooth');
    const toothForm = document.getElementById('tooth-form');
    const mainForm = document.querySelector('form'); // form utama pasien

mainForm.addEventListener('submit', function () {
    document.getElementById('odontogram_data').value = JSON.stringify(teethData);
});

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
        
        alert('Data gigi berhasil disimpan!');
    });
}

    
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
            // Tampilkan detail gigi yang dipilih
            toothDetails.textContent = toothNumber;
            
            // Isi form dengan data yang ada
            document.getElementById('condition').value = teethData[toothNumber].condition;
            document.getElementById('surface').value = teethData[toothNumber].surface;
            document.getElementById('notes').value = teethData[toothNumber].notes;
        });
    });
    
    // Handle form submission
    toothForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const toothNumber = toothDetails.textContent;
        if (!toothNumber) return;
        
        const condition = document.getElementById('condition').value;
        const surface = document.getElementById('surface').value;
        const notes = document.getElementById('notes').value;
        
        // Simpan data
        teethData[toothNumber] = {
            condition,
            surface,
            notes
        };
        
        // Update tampilan gigi
        updateToothAppearance(toothNumber, condition);
        
        // Reset form
        toothForm.reset();
        
        alert('Data gigi berhasil disimpan!');
    });
    
    // Fungsi untuk update tampilan gigi
    function updateToothAppearance(toothNumber, condition) {
        const toothElement = document.querySelector(`.tooth[data-number="${toothNumber}"]`);
        
        // Hapus semua kelas kondisi
        toothElement.classList.remove(
            'healthy', 'caries', 'filling', 'extracted', 'root_canal', 'crown'
        );
        
        // Tambahkan kelas kondisi baru
        toothElement.classList.add(condition);
    }
    
    // Simulasi data awal (bisa dihapus di produksi)
    teethData['16'] = { condition: 'caries', surface: 'occlusal', notes: 'Karies sedang' };
    teethData['36'] = { condition: 'filling', surface: 'whole', notes: 'Tambalan amalgam' };
    teethData['46'] = { condition: 'root_canal', surface: 'whole', notes: 'PSA selesai' };
    
    // Update tampilan awal
    Object.keys(teethData).forEach(toothNumber => {
        updateToothAppearance(toothNumber, teethData[toothNumber].condition);
    });
});