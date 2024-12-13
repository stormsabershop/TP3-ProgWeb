document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formulaire");
    const nameInput = document.getElementById("name");
    const minInput = document.getElementById("minimum");
    const maxInput = document.getElementById("maximum");

    if (form) {
        form.addEventListener("submit", (event) => {
            // Réinitialiser les messages d'erreur
            const errorMsgs = document.querySelectorAll(".error-message");
            errorMsgs.forEach(msg => msg.remove());

            let isValid = true;

            // Validation du nom
            if (nameInput && nameInput.value.trim() === "") {
                isValid = false;
                const errorMessage = document.createElement("p");
                errorMessage.classList.add("error-message");
                errorMessage.style.color = "red"; // Optional: Add custom styles
                errorMessage.textContent = "Le prénom est obligatoire.";
                document.body.prepend(errorMessage); // Insert the message after the input field
            }

            // Validation des valeurs minimum et maximum
            if (minInput && maxInput) {
                const minVal = parseInt(minInput.value, 10);
                const maxVal = parseInt(maxInput.value, 10);

                if (isNaN(minVal) || isNaN(maxVal)) {
                    isValid = false;
                    const errorMessage = document.createElement("p");
                    document.body.prepend(errorMessage);// Insert the message after the minimum input field
                } else if (minVal < 0) {
                    isValid = false;
                    const errorMessage = document.createElement("p");
                    errorMessage.classList.add("error-message");
                    errorMessage.style.color = "red"; // Optional: Add custom styles
                    errorMessage.textContent = "La valeur minimum doit être plus grande ou égale à 0.";
                    document.body.prepend(errorMessage); // Insert the message after the minimum input field
                } else if (minVal >= maxVal) {
                    isValid = false;
                    const errorMessage = document.createElement("p");
                    errorMessage.classList.add("error-message");
                    errorMessage.style.color = "red"; // Optional: Add custom styles
                    errorMessage.textContent = "La valeur maximum doit être supérieure à la valeur minimum.";
                    document.body.prepend(errorMessage); // Insert the message after the maximum input field
                }
            }
        });
    }
});