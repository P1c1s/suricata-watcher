// =====================
// 🐾 MEERKAT ALERT 403
// =====================
document.addEventListener("DOMContentLoaded", function () {
    if (document.body.id === "page-403") {
        const alerts = [
            "Steer clear!",
            "Keep your distance!",
            "Stay away…",
            "You can’t pass here.",
            "Off limits!",
            "Back off!",
            "Don’t come any closer.",
            "Mind your own business.",
            "Don’t cross the line.",
            "Give me some space.",
            "Keep out of this.",
            "Don’t get too close.",
            "No entry beyond this point.",
            "This area is restricted.",
            "Don’t go any further.",
            "Stop right there.",
            "You’re not allowed in.",
            "Access denied.",
            "Hold up, you can’t proceed.",
            "This is private property.",
            "I’m watching you.",
            "I’ve got my eye on you.",
            "Don’t think I won’t notice.",
            "I’m keeping tabs on you.",
            "I see what you’re doing.",
            "I’m keeping an eye out.",
            "You’re under observation.",
            "I know what you’re up to.",
            "I’m monitoring you.",
            "Don’t try anything funny."

        ];

        const alertDiv = document.getElementById("meerkat-alert-403");
        if (alertDiv) {
            alertDiv.textContent = alerts[Math.floor(Math.random() * alerts.length)];
        }
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
            "Watch out! I hear footsteps nearby - stay low!",
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
            "Stay sharp; the silence feels too heavy tonight… ",
            "Quick, everyone! Take cover; it could be an ambush!",
            "I detected a strange scent in the breeze… stay prepared!",
            "Who's moving over there? My eyes are scanning the perimeter!"

        ];

        const alertDiv = document.getElementById("meerkat-alert-404");
        if (alertDiv) {
            alertDiv.textContent = alerts[Math.floor(Math.random() * alerts.length)];
        }
    }
});