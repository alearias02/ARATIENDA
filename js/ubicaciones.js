document.addEventListener('DOMContentLoaded', function () {
    const provinciaSelect = document.getElementById('provincia');
    const cantonSelect = document.getElementById('canton');
    const distritoSelect = document.getElementById('distrito');

    // Verificamos si el select de provincia ya tiene opciones precargadas (por PHP)
    const provinciasPrecargadas = provinciaSelect.options.length > 1;

    // Si no tiene opciones, intentamos cargar desde el backend
    if (!provinciasPrecargadas) {
        fetch('/src/api/get_provincias.php')
            .then(res => {
                if (!res.ok) throw new Error("No se encontró get_provincias.php");
                return res.json();
            })
            .then(data => {
                provinciaSelect.innerHTML = '<option value="">Seleccione una provincia</option>';
                data.forEach(provincia => {
                    provinciaSelect.innerHTML += `<option value="${provincia.ID_PROVINCIA}">${provincia.NOMBRE_PROVINCIA}</option>`;
                });
            })
            .catch(err => {
                console.warn("Provincias no cargadas desde JS: " + err.message);
                // Las opciones se mantendrán si están renderizadas por PHP
            });
    }

    // Al cambiar provincia: cargar cantones
    provinciaSelect.addEventListener('change', function () {
        const provinciaID = this.value;
        cantonSelect.innerHTML = '<option value="">Cargando...</option>';
        distritoSelect.innerHTML = '<option value="">Seleccione un distrito</option>';

        fetch(`/src/api/get_cantones.php?provincia=${provinciaID}`)
            .then(res => res.json())
            .then(data => {
                cantonSelect.innerHTML = '<option value="">Seleccione un cantón</option>';
                data.forEach(canton => {
                    cantonSelect.innerHTML += `<option value="${canton.ID_CANTON}">${canton.NOMBRE_CANTON}</option>`;
                });
            })
            .catch(err => {
                console.error("Error al cargar cantones:", err);
                cantonSelect.innerHTML = '<option value="">Error al cargar cantones</option>';
            });
    });

    // Al cambiar cantón: cargar distritos
    cantonSelect.addEventListener('change', function () {
        const cantonID = this.value;
        distritoSelect.innerHTML = '<option value="">Cargando...</option>';

        fetch(`/src/api/get_distritos.php?canton=${cantonID}`)
            .then(res => res.json())
            .then(data => {
                distritoSelect.innerHTML = '<option value="">Seleccione un distrito</option>';
                data.forEach(distrito => {
                    distritoSelect.innerHTML += `<option value="${distrito.ID_DISTRITO}">${distrito.NOMBRE_DISTRITO}</option>`;
                });
            })
            .catch(err => {
                console.error("Error al cargar distritos:", err);
                distritoSelect.innerHTML = '<option value="">Error al cargar distritos</option>';
            });
    });
});