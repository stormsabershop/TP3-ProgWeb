document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formulaire");
    const nameInput = document.getElementById("name");
    const minInput = document.getElementById("minimum");
    const maxInput = document.getElementById("maximum");

    if (form) {
        form.addEventListener("submit", (event) => {
            const errorMsgs = document.querySelectorAll(".error-message");
            errorMsgs.forEach(msg => msg.remove());

            let isValid = true;


            if (nameInput && nameInput.value.trim() === "") {
                isValid = false;
                alert("Le prénom est obligatoire.")
            }

            if (minInput && maxInput) {
                const minVal = parseInt(minInput.value, 10);
                const maxVal = parseInt(maxInput.value, 10);

                if (isNaN(minVal)) {
                    isValid = false;
                    alert("La valeur minimum est obligatoire");
                } if (isNaN(maxVal)){
                    isValid = false;
                    alert("La valeur maximum est obligatoire");
                } if (minVal < 0) {
                    isValid = false;
                    alert("La valeur minimum doit être plus grande ou égale à 0. ");
                } if (minVal >= maxVal) {
                    isValid = false;
                    alert("La valeur maximum doit être supérieure à la valeur minimum.");

                }
            }


            if (!isValid) {
                event.preventDefault();
                setTimeout(function() {
                    let myValue = document.getElementById("dataField").value;
                    let formData = new FormData();
                    formData.append('data', myValue);

                    fetch('execute.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.text())
                }, 1);
            }

        });
    }
});


