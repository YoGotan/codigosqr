$(function() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    scannerTranslator(document.querySelector("#reader"));

    function onScanSuccess(decodedText, decodedResult) {
        // handle the scanned code as you like, for example:
        console.log(`Code matched = ${decodedText}`, decodedResult);
        html5QrcodeScanner.clear();
        // Extraer el token de la URL
        const url = new URL(decodedText);
        const token = url.searchParams.get("token");
        // Validar el token con el servidor
        axios
            .post("/cupones/validar", { token: token })
            .then((response) => {
                const data = response.data;
                if (data.valido) {
                    // Guarda el ID del cupón en un elemento oculto o en una variable
                    $("#idCupon").val(data.cupon.id);
                    // Poner el descuento en el modal
                    $("#modal_qr .modal-body .tipo-cupon").text(data.cupon.codigo.rango_cupon.descuento);
                    // Si el cupón es válido y no ha sido usado, mostrar el modal para introducir el gasto
                    $('#modal_qr').modal("show");
                    // Puedes también rellenar campos necesarios aquí, como el ID del cupón
                    $(`#gasto_${data.cupon.id}`).val(data.cupon.gasto);
                } else {
                    // Mostrar mensaje de error
                    $("#error_qr").text(data.mensaje);
                }
            })
            .catch((error) => {
                console.error(error);
            });
    }

    // function onScanFailure(error) {
    //     // handle scan failure, usually better to ignore and keep scanning.
    //     // for example:
    //     console.warn(`Code scan error = ${error}`);
    // }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", {
            fps: 10, // Frames por segundo
            qrbox: {
                width: 550,
                height: 550,
            }, // Tamaño del cuadro de escaneo
            experimentalFeatures: {
                useBarCodeDetectorIfSupported: true, // Utilizar detector de códigos de barras nativo si está disponible
            },
            aspectRatio: 1.0, // Relación de aspecto óptima para códigos QR
        },
        true
    );
    // html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    html5QrcodeScanner.render(onScanSuccess);
});

function cambiarEstadoCupon(btn) {
    const idCupon = $("#idCupon").val(); // Obtener el ID del cupón
    const gasto = $("#gasto").val(); // Obtener el gasto del input

    axios
        .post(`https://quebec.diseniummedia.es/cupones/${idCupon}/actualizar`, {
            gasto: gasto
        })
        .then((response) => {
            const data = response.data;
            $('#modal_qr').modal("hide");
            if (data.guardado) {
                alert("Cupón actualizado correctamente.");
            } else {
                alert("No se pudo actualizar el cupón.");
            }

        })
        .catch((error) => {
            console.error(error);
        });
}

function scannerTranslator() {
    const traducciones = [
        // Html5QrcodeStrings
        {
            original: "QR code parse error, error =",
            traduccion: "Error al analizar el código QR, error =",
        },
        {
            original: "Error getting userMedia, error =",
            traduccion: "Error al obtener userMedia, error =",
        },
        {
            original: "The device doesn't support navigator.mediaDevices , only supported cameraIdOrConfig in this case is deviceId parameter (string).",
            traduccion: "El dispositivo no admite navigator.mediaDevices, en este caso sólo se admite cameraIdOrConfig como parámetro deviceId (cadena).",
        },
        {
            original: "Camera streaming not supported by the browser.",
            traduccion: "El navegador no admite la transmisión de la cámara.",
        },
        {
            original: "Unable to query supported devices, unknown error.",
            traduccion: "No se puede consultar los dispositivos compatibles, error desconocido.",
        },
        {
            original: "Camera access is only supported in secure context like https or localhost.",
            traduccion: "El acceso a la cámara sólo es compatible en un contexto seguro como https o localhost.",
        },
        {
            original: "Scanner paused",
            traduccion: "Escáner en pausa",
        },

        // Html5QrcodeScannerStrings
        {
            original: "Scanning",
            traduccion: "Escaneando",
        },
        {
            original: "Idle",
            traduccion: "Inactivo",
        },
        {
            original: "Error",
            traduccion: "Error",
        },
        {
            original: "Permission",
            traduccion: "Permiso",
        },
        {
            original: "No Cameras",
            traduccion: "Sin cámaras",
        },
        {
            original: "Last Match:",
            traduccion: "Última coincidencia:",
        },
        {
            original: "Code Scanner",
            traduccion: "Escáner de código",
        },
        {
            original: "Request Camera Permissions",
            traduccion: "Solicitar permisos de cámara",
        },
        {
            original: "Requesting camera permissions...",
            traduccion: "Solicitando permisos de cámara...",
        },
        {
            original: "No camera found",
            traduccion: "No se encontró ninguna cámara",
        },
        {
            original: "Stop Scanning",
            traduccion: "Detener escaneo",
        },
        {
            original: "Start Scanning",
            traduccion: "Iniciar escaneo",
        },
        {
            original: "Switch On Torch",
            traduccion: "Encender linterna",
        },
        {
            original: "Switch Off Torch",
            traduccion: "Apagar linterna",
        },
        {
            original: "Failed to turn on torch",
            traduccion: "Error al encender la linterna",
        },
        {
            original: "Failed to turn off torch",
            traduccion: "Error al apagar la linterna",
        },
        {
            original: "Launching Camera...",
            traduccion: "Iniciando cámara...",
        },
        {
            original: "Scan an Image File",
            traduccion: "Escanear un archivo de imagen",
        },
        {
            original: "Scan using camera directly",
            traduccion: "Escanear usando la cámara directamente",
        },
        {
            original: "Select Camera",
            traduccion: "Seleccionar cámara",
        },
        {
            original: "Choose Image",
            traduccion: "Elegir imagen",
        },
        {
            original: "Choose Another",
            traduccion: "Elegir otra",
        },
        {
            original: "No image choosen",
            traduccion: "Ninguna imagen seleccionada",
        },
        {
            original: "Anonymous Camera",
            traduccion: "Cámara anónima",
        },
        {
            original: "Or drop an image to scan",
            traduccion: "O arrastra una imagen para escanear",
        },
        {
            original: "Or drop an image to scan (other files not supported)",
            traduccion: "O arrastra una imagen para escanear (otros archivos no soportados)",
        },
        {
            original: "zoom",
            traduccion: "zoom",
        },
        {
            original: "Loading image...",
            traduccion: "Cargando imagen...",
        },
        {
            original: "Camera based scan",
            traduccion: "Escaneo basado en cámara",
        },
        {
            original: "Fule based scan",
            traduccion: "Escaneo basado en archivo",
        },

        // LibraryInfoStrings
        {
            original: "Powered by ",
            traduccion: "Desarrollado por ",
        },
        {
            original: "Report issues",
            traduccion: "Informar de problemas",
        },

        // Others
        {
            original: "NotAllowedError: Permission denied",
            traduccion: "Permiso denegado para acceder a la cámara",
        },
    ];

    // Función para traducir un texto
    function traducirTexto(texto) {
        const traduccion = traducciones.find((t) => t.original === texto);
        return traduccion ? traduccion.traduccion : texto;
    }

    // Función para traducir los nodos de texto
    function traducirNodosDeTexto(nodo) {
        if (nodo.nodeType === Node.TEXT_NODE) {
            nodo.textContent = traducirTexto(nodo.textContent.trim());
        } else {
            for (let i = 0; i < nodo.childNodes.length; i++) {
                traducirNodosDeTexto(nodo.childNodes[i]);
            }
        }
    }

    // Crear el MutationObserver
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === "childList") {
                mutation.addedNodes.forEach((nodo) => {
                    traducirNodosDeTexto(nodo);
                });
            }
        });
    });

    // Configurar y ejecutar el observer
    const config = {
        childList: true,
        subtree: true,
    };
    observer.observe(document.body, config);

    // Traducir el contenido inicial
    traducirNodosDeTexto(document.body);
}