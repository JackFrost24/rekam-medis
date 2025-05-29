<div class="odontogram-container">
    <!-- Permanent Upper Teeth -->
    <div class="jaw upper-jaw">
        @foreach(['18','17','16','15','14','13','12','11','21','22','23','24','25','26','27','28'] as $tooth)
        <div class="tooth healthy" data-number="{{ $tooth }}">{{ $tooth }}</div>
        @endforeach
    </div>
    
    <!-- Deciduous Upper Teeth -->
    <div class="jaw middle-upper-jaw">
        @foreach(['55','54','53','52','51','61','62','63','64','65'] as $tooth)
        <div class="tooth healthy" data-number="{{ $tooth }}">{{ $tooth }}</div>
        @endforeach
    </div>
    
    <!-- Deciduous Lower Teeth -->
    <div class="jaw middle-lower-jaw">
        @foreach(['85','84','83','82','81','71','72','73','74','75'] as $tooth)
        <div class="tooth healthy" data-number="{{ $tooth }}">{{ $tooth }}</div>
        @endforeach
    </div>
    
    <!-- Permanent Lower Teeth -->
    <div class="jaw lower-jaw">
        @foreach(['48','47','46','45','44','43','42','41','31','32','33','34','35','36','37','38'] as $tooth)
        <div class="tooth healthy" data-number="{{ $tooth }}">{{ $tooth }}</div>
        @endforeach
    </div>
</div>

<!-- Tooth Details Form -->
<div class="tooth-details hidden" id="toothDetails">
    <h3 class="font-medium text-lg mb-3">Detail Gigi: <span id="selected-tooth" class="font-bold">-</span></h3>
    <form id="tooth-form" class="space-y-4">
        <div class="form-group">
            <label for="condition" class="block">Kondisi:</label>
            <select id="condition" name="condition" class="w-full p-2 border rounded kondisi-select">
                <option value="healthy">Sehat</option>
                <option value="caries">Karies</option>
                <option value="filling">Tambalan</option>
                <option value="extracted">Dicabut</option>
                <option value="root_canal">Perawatan Saluran Akar</option>
                <option value="crown">Mahkota</option>
            </select>
        </div>
        
        <div class="form-group gv-black-container hidden">
            <label for="gv_black_class" class="block">Klasifikasi GV Black:</label>
            <select id="gv_black_class" name="gv_black_class" class="w-full p-2 border rounded">
                <option value="">Pilih Kelas</option>
                <option value="1">Kelas 1</option>
                <option value="2">Kelas 2</option>
                <option value="3">Kelas 3</option>
                <option value="4">Kelas 4</option>
                <option value="5">Kelas 5</option>
                <option value="6">Kelas 6</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="surface" class="block">Permukaan:</label>
            <select id="surface" name="surface" class="w-full p-2 border rounded">
                <option value="whole">Seluruh Gigi</option>
                <option value="buccal">Buccal</option>
                <option value="lingual">Lingual</option>
                <option value="occlusal">Occlusal</option>
                <option value="mesial">Mesial</option>
                <option value="distal">Distal</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="notes" class="block">Catatan:</label>
            <textarea id="notes" name="notes" class="w-full p-2 border rounded" rows="3"></textarea>
        </div>
        
        <div class="flex space-x-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Simpan Perubahan
            </button>
            <button type="button" id="close-details" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                Tutup
            </button>
        </div>
    </form>
</div>

<!-- 3D View Buttons -->
<div class="hidden mt-4" id="show3dButtons">
    <button id="show3dAdult" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 mr-2">
        Tampilkan 3D Dewasa
    </button>
    <button id="show3dChild" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
        Tampilkan 3D Anak
    </button>
</div>

<input type="hidden" id="odontogram_data" name="odontogram_data">

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const teeth = document.querySelectorAll('.tooth');
    const selectedToothEl = document.getElementById('selected-tooth');
    const toothForm = document.getElementById('tooth-form');
    const toothDetails = document.getElementById('toothDetails');
    const closeDetailsBtn = document.getElementById('close-details');
    const saveOdontogramBtn = document.getElementById('save-odontogram');
    const show3dButtons = document.getElementById('show3dButtons');
    const odontogramDataInput = document.getElementById('odontogram_data');
    
    // Store teeth data
    const teethData = {};
    
    // Initialize teeth data
    teeth.forEach(tooth => {
        const toothNumber = tooth.getAttribute('data-number');
        teethData[toothNumber] = {
            condition: 'healthy',
            gv_black_class: '',
            surface: 'whole',
            notes: ''
        };
        
        tooth.addEventListener('click', function() {
            // Show selected tooth details
            selectedToothEl.textContent = toothNumber;
            toothDetails.classList.remove('hidden');
            
            // Fill form with existing data
            document.getElementById('condition').value = teethData[toothNumber].condition;
            document.getElementById('gv_black_class').value = teethData[toothNumber].gv_black_class;
            document.getElementById('surface').value = teethData[toothNumber].surface;
            document.getElementById('notes').value = teethData[toothNumber].notes;
            
            // Toggle GV Black dropdown based on condition
            toggleGVBlackDropdown(teethData[toothNumber].condition);
            
            // Scroll to details
            toothDetails.scrollIntoView({ behavior: 'smooth' });
        });
    });
    
    // Toggle GV Black dropdown
    function toggleGVBlackDropdown(condition) {
        const gvContainer = document.querySelector('.gv-black-container');
        if (condition === 'caries') {
            gvContainer.classList.remove('hidden');
        } else {
            gvContainer.classList.add('hidden');
        }
    }
    
    // Handle condition change
    document.getElementById('condition').addEventListener('change', function() {
        toggleGVBlackDropdown(this.value);
    });
    
    // Close details form
    closeDetailsBtn.addEventListener('click', function() {
        toothDetails.classList.add('hidden');
    });
    
    // Handle tooth form submission
    toothForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const toothNumber = selectedToothEl.textContent;
        if (toothNumber === '-') return;
        
        // Update teeth data
        teethData[toothNumber] = {
            condition: document.getElementById('condition').value,
            gv_black_class: document.getElementById('condition').value === 'caries' 
                ? document.getElementById('gv_black_class').value 
                : '',
            surface: document.getElementById('surface').value,
            notes: document.getElementById('notes').value
        };
        
        // Update tooth appearance
        updateToothAppearance(toothNumber, teethData[toothNumber].condition);
        
        // Show success feedback
        showToast('Data gigi berhasil disimpan', 'success');
    });
    
    // Handle save odontogram
    saveOdontogramBtn.addEventListener('click', function() {
        odontogramDataInput.value = JSON.stringify(teethData);
        showToast('Odontogram tersimpan!', 'success');
        show3dButtons.classList.remove('hidden');
    });
    
    // Handle 3D Adult view button
    document.getElementById('show3dAdult').addEventListener('click', function() {
        if (!odontogramDataInput.value) {
            showToast('Simpan odontogram terlebih dahulu!', 'error');
            return;
        }
        window.location.href = `/3d-model?data=${encodeURIComponent(odontogramDataInput.value)}&type=adult`;
    });
    
    // Handle 3D Child view button
    document.getElementById('show3dChild').addEventListener('click', function() {
        if (!odontogramDataInput.value) {
            showToast('Simpan odontogram terlebih dahulu!', 'error');
            return;
        }
        window.location.href = `/3d-model?data=${encodeURIComponent(odontogramDataInput.value)}&type=child`;
    });
    
    // Helper function to update tooth appearance
    function updateToothAppearance(toothNumber, condition) {
        const tooth = document.querySelector(`.tooth[data-number="${toothNumber}"]`);
        
        // Remove all condition classes
        tooth.classList.remove('healthy', 'caries', 'filling', 'extracted', 'root_canal', 'crown');
        
        // Add new condition class
        tooth.classList.add(condition);
    }
    
    // Helper function to show toast messages
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-4 py-2 rounded text-white ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 'bg-blue-500'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
});
</script>
@endpush