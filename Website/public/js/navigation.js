function generateGlitchedTitle() {
    const originalTitle = "ctANPR";
    const allowedSymbols = ["#", "$", "!", "?", "*", "&", "%"];
    let glitchedTitle = "";
    let correctLettersCount = 0;

    for (let i = 0; i < originalTitle.length; i++) {
        if (correctLettersCount < 4) {
            glitchedTitle += originalTitle[i];
            correctLettersCount++;
        } else {
            if (Math.random() > 0.5) {
                const randomSymbol = allowedSymbols[Math.floor(Math.random() * allowedSymbols.length)];
                glitchedTitle += randomSymbol;
            } else {
                glitchedTitle += originalTitle[i];
                correctLettersCount++;
            }
        }
    }

    return glitchedTitle;
}

function shuffleOriginalTitle() {
    const originalTitle = "ctANPR";
    const originalTitleArray = originalTitle.split('');
    for (let i = originalTitleArray.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [originalTitleArray[i], originalTitleArray[j]] = [originalTitleArray[j], originalTitleArray[i]];
    }
    return originalTitleArray.join('');
}

function updateGlitchedTitle() {
    const shuffledTitle = shuffleOriginalTitle();
    document.title = generateGlitchedTitle(shuffledTitle);
}

document.addEventListener("visibilitychange", function () {
    if (document.hidden) {
        updateGlitchedTitle();
    } else {
        document.title = "ctANPR"; // Restore the original title when the tab is visible
    }
});

updateGlitchedTitle();