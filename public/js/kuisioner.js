/* Questionnaire Progress Logic */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('questionnaire-form');
    if (!form) return;
    
    const progressBar = document.getElementById('survey-progress');
    const progressText = document.getElementById('progress-text');
    
    // Total fields to answer: 2 numbers + 15 Likerts = 17
    const totalQuestions = 17;

    function updateProgress() {
        let answered = 0;

        // 1. Check Jam Tidur
        const jamTidurField = document.getElementById('jam_tidur');
        if (jamTidurField && jamTidurField.value !== '') answered++;

        // 2. Check Screen Time
        const screenTimeField = document.getElementById('screen_time');
        if (screenTimeField && screenTimeField.value !== '') answered++;

        // 3. Check 15 Clinical Psychological Likert questions
        const fields = [
            // Positive variables
            'kualitas_tidur', 'kepuasan_hidup', 'regulasi_emosi',
            // Negative variables
            'kelelahan_mental', 'gangguan_konsentrasi', 'mood_rendah', 
            'kecemasan', 'kewalahan', 'dampak_screen_time', 
            'kehilangan_motivasi', 'dampak_emosi', 'beban_mental',
            'overthinking', 'sulit_rileks', 'gejala_fisik_stres'
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
