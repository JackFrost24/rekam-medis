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
            <select id="condition" name="condition" class="w-full p-2 border rounded">
                <option value="healthy">Sehat</option>
                <option value="caries">Karies</option>
                <option value="filling">Tambalan</option>
                <option value="extracted">Dicabut</option>
                <option value="root_canal">Perawatan Saluran Akar</option>
                <option value="crown">Mahkota</option>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const teeth = document.querySelectorAll('.tooth');
    const selectedToothEl = document.getElementById('selected-tooth');
    const toothForm = document.getElementById('tooth-form');
    const toothDetails = document.getElementById('toothDetails');
    const closeDetailsBtn = document.getElementById('close-details');
    const saveOdontogramBtn = document.getElementById('save-odontogram');
    const show3dBtn = document.getElementById('show-3d');
    const odontogramDataInput = document.getElementById('odontogram_data');
    
    // Store teeth data
    const teethData = {};
    
    // Initialize teeth data
    teeth.forEach(tooth => {
        const toothNumber = tooth.getAttribute('data-number');
        teethData[toothNumber] = {
            condition: 'healthy',
            surface: 'whole',
            notes: ''
        };
        
        tooth.addEventListener('click', function() {
            // Show selected tooth details
            selectedToothEl.textContent = toothNumber;
            toothDetails.classList.remove('hidden');
            
            // Fill form with existing data
            document.getElementById('condition').value = teethData[toothNumber].condition;
            document.getElementById('surface').value = teethData[toothNumber].surface;
            document.getElementById('notes').value = teethData[toothNumber].notes;
            
            // Scroll to details
            toothDetails.scrollIntoView({ behavior: 'smooth' });
        });
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
        show3dBtn.classList.remove('hidden');
    });
    
    // Handle 3D view button
    show3dBtn.addEventListener('click', function() {
        if (!odontogramDataInput.value) {
            showToast('Simpan odontogram terlebih dahulu!', 'error');
            return;
        }
        window.location.href = `/3d-model?data=${encodeURIComponent(odontogramDataInput.value)}`;
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
        toast.className = `fixed top-4 right-4 px-4 py-2 rounded text-white toast-${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
});
</script>
@endpush