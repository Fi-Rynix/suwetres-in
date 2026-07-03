document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('questionnaire-form');
    if (!form) return;

    const progressBar = document.getElementById('survey-progress');
    const progressText = document.getElementById('progress-text');
    const errorBox = document.getElementById('validation-error-box');
    const topHeader = document.getElementById('survey-top-header');
    const progressHeader = document.getElementById('step-progress-header');

    let currentStep = 0;

    const stepFields = {
        1: ['jam_tidur', 'screen_time'],
        2: ['kualitas_tidur', 'mood_rendah', 'kepuasan_hidup', 'regulasi_emosi'],
        3: ['kecemasan', 'kewalahan', 'sulit_rileks', 'gejala_fisik_stres'],
        4: ['kelelahan_mental', 'gangguan_konsentrasi', 'overthinking', 'kehilangan_motivasi', 'beban_mental'],
        5: ['dampak_screen_time', 'dampak_emosi']
    };

    function getRadioValue(fieldName) {
        const radios = document.getElementsByName(fieldName);
        for (let i = 0; i < radios.length; i++) {
            if (radios[i].checked) return radios[i].value;
        }
        return '-';
    }

    function validateCurrentStep() {
        if (currentStep === 0 || currentStep === 6) return true;

        const fields = stepFields[currentStep];
        if (!fields) return true;

        let allValid = true;
        fields.forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                if (input.value.trim() === '') allValid = false;
            } else if (getRadioValue(field) === '-') {
                allValid = false;
            }
        });

        return allValid;
    }

    function updateProgress() {
        if (currentStep < 1 || currentStep > 5) return;

        const stepTitles = {
            1: "Bagian 1 dari 5: DAILY ACTIVITIES",
            2: "Bagian 2 dari 5: MOOD & EMOTIONAL WELL-BEING",
            3: "Bagian 3 dari 5: ANXIETY & STRESS LEVEL",
            4: "Bagian 4 dari 5: BURNOUT & MENTAL FATIGUE",
            5: "Bagian 5 dari 5: DIGITAL IMPACT"
        };

        const activeTitleEl = document.getElementById('active-step-title');
        if (activeTitleEl) activeTitleEl.textContent = stepTitles[currentStep];

        const percentage = currentStep * 20;
        if (progressBar) progressBar.style.width = percentage + '%';
        if (progressText) progressText.textContent = percentage + '% Selesai';

        if (progressBar) {
            if (percentage < 40) progressBar.style.backgroundColor = 'var(--primary)';
            else if (percentage < 80) progressBar.style.backgroundColor = 'var(--secondary)';
            else progressBar.style.backgroundColor = 'var(--green)';
        }

        document.querySelectorAll('.step-progress-item').forEach(item => {
            const stepNum = parseInt(item.getAttribute('data-progress-step'), 10);
            item.classList.remove('active', 'completed');
            if (stepNum === currentStep) item.classList.add('active');
            else if (stepNum < currentStep) item.classList.add('completed');
        });
    }

    function populateSummary() {
        const jamTidur = document.getElementById('jam_tidur')?.value || '-';
        const screenTime = document.getElementById('screen_time')?.value || '-';
        const moodRendah = getRadioValue('mood_rendah');
        const kecemasan = getRadioValue('kecemasan');
        const overthinking = getRadioValue('overthinking');

        const summaryJamTidur = document.getElementById('summary-jam-tidur');
        if (summaryJamTidur) summaryJamTidur.textContent = jamTidur + " Jam";

        const summaryScreenTime = document.getElementById('summary-screen-time');
        if (summaryScreenTime) summaryScreenTime.textContent = screenTime + " Jam";

        const summaryMoodRendah = document.getElementById('summary-mood-rendah');
        if (summaryMoodRendah) summaryMoodRendah.textContent = moodRendah + "/10";

        const summaryKecemasan = document.getElementById('summary-kecemasan');
        if (summaryKecemasan) summaryKecemasan.textContent = kecemasan + "/10";

        const summaryOverthinking = document.getElementById('summary-overthinking');
        if (summaryOverthinking) summaryOverthinking.textContent = overthinking + "/10";
    }

    function showStep(step, direction) {
        document.querySelectorAll('.wizard-step').forEach(s => {
            s.classList.remove('active', 'fade-slide-next', 'fade-slide-prev');
        });

        if (errorBox) errorBox.style.display = 'none';

        currentStep = step;

        if (currentStep >= 1 && currentStep <= 5) {
            if (topHeader) topHeader.style.display = 'flex';
            if (progressHeader) progressHeader.style.display = 'block';
            updateProgress();
        } else {
            if (topHeader) topHeader.style.display = 'none';
            if (progressHeader) progressHeader.style.display = 'none';
        }

        const targetStep = document.querySelector(`.wizard-step[data-step="${currentStep}"]`);
        if (targetStep) {
            targetStep.classList.add('active');
            if (direction === 'next') targetStep.classList.add('fade-slide-next');
            else if (direction === 'prev') targetStep.classList.add('fade-slide-prev');
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });

        if (currentStep === 6) populateSummary();

        if (window.lucide) window.lucide.createIcons();
    }

    function nextStep() {
        if (validateCurrentStep()) {
            showStep(currentStep + 1, 'next');
        } else if (errorBox) {
            errorBox.style.display = 'flex';
            errorBox.style.animation = 'none';
            errorBox.offsetHeight;
            errorBox.style.animation = null;
            errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    function prevStep() {
        if (currentStep > 0) showStep(currentStep - 1, 'prev');
    }

    form.addEventListener('click', function(e) {
        const target = e.target;
        if (target.classList.contains('btn-next') || target.closest('.btn-next')) {
            e.preventDefault();
            nextStep();
        }
        if (target.classList.contains('btn-prev') || target.closest('.btn-prev')) {
            e.preventDefault();
            prevStep();
        }
    });

    const btnStart = document.getElementById('btn-start');
    if (btnStart) {
        btnStart.addEventListener('click', function(e) {
            e.preventDefault();
            showStep(1, 'next');
        });
    }

    form.addEventListener('submit', function(e) {
        if (currentStep !== 6) {
            e.preventDefault();
            nextStep();
        }
    });

    if (window.lucide) window.lucide.createIcons();
});
