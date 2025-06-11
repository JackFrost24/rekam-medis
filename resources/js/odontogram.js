document.addEventListener('DOMContentLoaded', function() {
    // Initialize odontogram data from hidden input if exists
    const odontogramData = document.getElementById('odontogram_data').value;
    if (odontogramData) {
        try {
            const data = JSON.parse(odontogramData);
            for (const [toothNumber, toothInfo] of Object.entries(data)) {
                const toothElement = document.querySelector(`.tooth[data-tooth="${toothNumber}"]`);
                if (toothElement) {
                    toothElement.className = `tooth ${toothInfo.condition}`;
                }
            }
            
            if (Object.keys(data).length > 0) {
                document.getElementById('show3dButtons').classList.remove('hidden');
            }
        } catch (e) {
            console.error('Error parsing odontogram data:', e);
        }
    }

    // 3D Viewer Buttons
    document.getElementById('show3dAdult')?.addEventListener('click', function() {
        // Implement 3D viewer for adult teeth
        alert('Adult 3D Viewer will be implemented here');
    });

    document.getElementById('show3dChild')?.addEventListener('click', function() {
        // Implement 3D viewer for child teeth
        alert('Child 3D Viewer will be implemented here');
    });
});