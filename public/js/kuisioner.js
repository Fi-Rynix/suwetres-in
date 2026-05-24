/* Questionnaire Progress Logic */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('questionnaire-form');
    if (!form) return;
    
    const progressBar = document.getElementById('survey-progress');
    const progressText = document.getElementById('progress-text');
    
    // Total fields to answer: 2 numbers + 10 Likerts = 12
    const totalQuestions = 12;

    function updateProgress() {
        let answered = 0;

        // 1. Check Jam Tidur
        const jamTidurField = document.getElementById('jam_tidur');
        if (jamTidurField && jamTidurField.value !== '') answered++;

        // 2. Check Screen Time
        const screenTimeField = document.getElementById('screen_time');
        if (screenTimeField && screenTimeField.value !== '') answered++;

        // 3. Check 10 Likert questions
        const fields = [
            'fokus_belajar', 'kelelahan_setelah_istirahat', 'tekanan_tugas', 
            'keseimbangan_hidup', 'penurunan_produktivitas', 'kecemasan_deadline', 
            'dampak_screen_time', 'motivasi_kuliah', 'kelelahan_aktivitas', 'beban_mental'
        ];

        fields.forEach(function(field) {
            const radios = document.getElementsByName(field);
            let isChecked = false;
            for (let i = 0; i < radios.length; i++) {
                if (radios[i].checked) {
                    isChecked = true;
                    break;
                }
            }
            if (isChecked) answered++;
        });

        // Calculate percentage
        const percentage = Math.round((answered / totalQuestions) * 100);
        if (progressBar) progressBar.style.width = percentage + '%';
        if (progressText) progressText.textContent = percentage + '% Selesai';
        
        // Adjust bar colors dynamically
        if (progressBar) {
            if (percentage < 30) {
                progressBar.style.backgroundColor = 'var(--primary)'; // Pink
            } else if (percentage < 75) {
                progressBar.style.backgroundColor = 'var(--secondary)'; // Cyan
            } else {
                progressBar.style.backgroundColor = 'var(--green)'; // Green
            }
        }
    }

    // Listen for inputs
    form.addEventListener('input', updateProgress);
    form.addEventListener('change', updateProgress);

    // Run initial check (in case values are pre-filled or from old input)
    updateProgress();
});
