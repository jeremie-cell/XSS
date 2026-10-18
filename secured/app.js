const messageForm = document.getElementById("messageForm");
const messageInput = document.getElementById("messageInput");
const messageOutput = document.getElementById("messageOutput");

const domInput = document.getElementById("domInput");
const domButton = document.getElementById("domButton");
const domOutput = document.getElementById("domOutput");

const status = document.getElementById("status");


function containsPayload(input) {

    const patterns = [
        /<\s*\/?\s*[a-z][a-z0-9-]*(?:\s[^<>]*)?\s*\/?>/i,
        /&lt;\s*\/?\s*[a-z][a-z0-9-]*/i,
        /\bon[a-z]+\s*=/i,
        /javascript\s*:/i,
        /data\s*:\s*text\/html/i
    ];

    return patterns.some(pattern => pattern.test(input));
}

messageForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const message = messageInput.value.trim();

    if (containsPayload(message)) {
        console.warn("Payload detected. Refreshing page...");

        window.location.reload();
        return;
    }

    messageOutput.textContent = message;
    status.textContent = "Normal text accepted and displayed.";
    status.className = "success";
});

domButton.addEventListener("click", function () {
    const message = domInput.value.trim();

    domOutput.textContent = "";

    if (containsPayload(message)) {
        console.warn("Payload detected. Output blocked.");
        status.textContent = "Payload detected. Nothing displayed.";
    } else {

        domOutput.textContent = message;
        status.textContent = "Normal text accepted and displayed.";
    }

    status.className = "success";
});