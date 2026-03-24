
    /*
    var --> Es para variables globales, que persistirán aunque el proceso ya haya terminado
    let --> Es para variables locales, que sólo existirán en el bloque de código donde se definieron
    const --> Es para variables que no cambiarán su valor, son constantes 
    */
    const profile = document.getElementById("profile");

    const header = document.getElementById("header");

    const title = document.getElementById("title");

    const icons = document.querySelectorAll(".icon__element"); //Arreglo de todas las imagenes que contienen la palabra "icon__element"
    const message = document.getElementById("message");

    icons.forEach(icon => { 
        icon.addEventListener("click", function() {
            let alt = icon.alt;
            message.textContent = "Has dado click al elemento " + alt;
        });
    });

    title.addEventListener("mouseover", () => {
        if(title.textContent == "Página web") {
            title.textContent = "Página web con JS";
        } else {
            title.textContent = "Página web";
        }

    });

    profile.addEventListener("click", function() {
        //let alt = profile.alt;
        //alert(alt);
        header.style.backgroundColor = "#D2B48C";
    });
