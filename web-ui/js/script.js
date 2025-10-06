// =====================
// 🔒 LOCK SCREEN INDEX
// =====================
document.addEventListener("DOMContentLoaded", function () {
    if (document.body.id === "page-index") {
        let timeout;

        function resetTimer() {
            clearTimeout(timeout);
            timeout = setTimeout(logoutUser, 60 * 1000); // 60 seconds
        }

        function logoutUser() {
            const form = document.createElement("form");
            form.method = "POST";
            form.action = "index.php";
            form.id = "lock_screen";

            form.innerHTML = `
                <input type="hidden" name="lock_screen" value="true">
                <input type="hidden" name="csrf_token" value="${document.getElementById("csrf_token").value}">
            `;

            document.body.appendChild(form);
            form.submit();
        }

        // Eventi che indicano attività dell'utente
        ["load", "mousemove", "keypress", "scroll", "click", "touchstart"].forEach(event => {
            window.addEventListener(event, resetTimer);
        });

        resetTimer(); // Avvia subito
    }
});


// =====================
// 🐾 MEERKAT ALERT 404
// =====================
document.addEventListener("DOMContentLoaded", function () {
    if (document.body.id === "page-404") {
        const alerts = [
            "Hey! Someone is approaching! Everyone down!",
            "Alert! I saw something moving among the rocks!",
            "Don't get distracted, the sky is never too calm...",
            "Up, up! Everyone in position, could be a predator!",
            "I smelled something strange… stay alert!",
            "Who's making noise down there? I've got eyes on everything!",
            "Watch out! I hear footsteps nearby—stay low!",
            "Warning! A shadow just darted behind that tree!",
            "Keep your eyes peeled; the wind feels off tonight...",
            "Heads up! Everyone, take your places; danger could be near!",
            "I caught a whiff of something unusual… remain vigilant!",
            "Who's rustling through the leaves? I’m scanning the area!",
            "Listen closely! I just heard a branch snap - stay alert!",
            "Danger! I spotted movement near the edge of the clearing!",
            "Don’t let your guard down; the atmosphere feels charged...",
            "Everyone ready? Get to higher ground; we might be hunted!",
            "I sense something unusual in the air… keep your wits about you!",
            "Who's messing around over there? I've got a visual on everything!",
            "Heads up! I spotted something lurking in the shadows!",
            "Caution! I heard a low growl from the brush!",
            "Stay sharp; the silence feels too heavy tonight...",
            "Quick, everyone! Take cover; it could be an ambush!",
            "I detected a strange scent in the breeze… stay prepared!",
            "Who's moving over there? My eyes are scanning the perimeter!"

        ];

        const alertDiv = document.getElementById("meerkat-alert");
        if (alertDiv) {
            alertDiv.textContent = alerts[Math.floor(Math.random() * alerts.length)];
        }
    }
});
