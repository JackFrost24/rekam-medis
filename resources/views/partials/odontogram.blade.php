<div class="odontogram-container">
    <!-- Adult Teeth -->
    <div class="jaw">
        @for($i = 18; $i <= 28; $i++)
            <div class="tooth healthy" data-tooth="{{ $i }}" onclick="showToothDetails({{ $i }})">
                {{ $i }}
            </div>
        @endfor
    </div>
    <div class="jaw">
        @for($i = 48; $i >= 38; $i--)
            <div class="tooth healthy" data-tooth="{{ $i }}" onclick="showToothDetails({{ $i }})">
                {{ $i }}
            </div>
        @endfor
    </div>

    <!-- Child Teeth -->
    <div class="jaw mt-8">
        <h3 class="text-center mb-2">Child Teeth</h3>
        @for($i = 55; $i <= 65; $i++)
            <div class="tooth healthy" data-tooth="{{ $i }}" onclick="showToothDetails({{ $i }})">
                {{ $i }}
            </div>
        @endfor
    </div>
    <div class="jaw">
        @for($i = 85; $i >= 75; $i--)
            <div class="tooth healthy" data-tooth="{{ $i }}" onclick="showToothDetails({{ $i }})">
                {{ $i }}
            </div>
        @endfor
    </div>

    <!-- Tooth Details Modal -->
    <div id="toothDetailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg max-w-md w-full">
            <h3 id="toothNumber" class="text-xl font-bold mb-4"></h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">Condition</label>
                    <select id="toothCondition" class="w-full p-2 border rounded">
                        <option value="healthy">Healthy</option>
                        <option value="caries">Caries</option>
                        <option value="filling">Filling</option>
                        <option value="extracted">Extracted</option>
                        <option value="root_canal">Root Canal</option>
                        <option value="crown">Crown</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Notes</label>
                    <textarea id="toothNotes" rows="3" class="w-full p-2 border rounded"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button onclick="closeToothDetails()" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                <button onclick="saveToothDetails()" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentTooth = null;
    const toothData = {};

    function showToothDetails(toothNumber) {
        currentTooth = toothNumber;
        document.getElementById('toothNumber').textContent = `Tooth ${toothNumber}`;
        
        // Load existing data if any
        if (toothData[toothNumber]) {
            document.getElementById('toothCondition').value = toothData[toothNumber].condition;
            document.getElementById('toothNotes').value = toothData[toothNumber].notes || '';
        } else {
            document.getElementById('toothCondition').value = 'healthy';
            document.getElementById('toothNotes').value = '';
        }
        
        document.getElementById('toothDetailsModal').classList.remove('hidden');
    }

    function closeToothDetails() {
        document.getElementById('toothDetailsModal').classList.add('hidden');
    }

    function saveToothDetails() {
        const condition = document.getElementById('toothCondition').value;
        const notes = document.getElementById('toothNotes').value;
        
        // Update tooth appearance
        const toothElement = document.querySelector(`.tooth[data-tooth="${currentTooth}"]`);
        toothElement.className = `tooth ${condition}`;
        toothElement.textContent = currentTooth;
        
        // Store data
        toothData[currentTooth] = { condition, notes };
        
        // Update hidden input
        document.getElementById('odontogram_data').value = JSON.stringify(toothData);
        
        // Show 3D buttons if not empty
        if (Object.keys(toothData).length > 0) {
            document.getElementById('show3dButtons').classList.remove('hidden');
        }
        
        closeToothDetails();
    }
</script>