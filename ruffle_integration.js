/**
 * RUFFLE INTEGRATION
 * Remplace automatiquement Adobe Flash Player par Ruffle
 * Ruffle est un émulateur Flash open-source compatible avec les navigateurs modernes
 */

// Charger Ruffle depuis le CDN
window.RufflePlayer = window.RufflePlayer || {};
window.addEventListener("DOMContentLoaded", () => {
    // Charger Ruffle
    const script = document.createElement("script");
    script.src = "https://unpkg.com/@ruffle-rs/ruffle";
    document.head.appendChild(script);

    console.log("Ruffle Flash emulator loaded successfully!");
});
