const contenedor = document.getElementById("card__container");
axios.get("https://rickandmortyapi.com/api/character")
.then(function(res){
    //console.log(res.data.results);
    const personajes = res.data.results;
    personajes.forEach(personaje => {
        //se crea un div
        const card = document.createElement("div");
        //al div se le añade una clase llamada card
        card.classList.add("card");

        card.innerHTML= `
                <div id="header" class="card__header">
                    <img src="${personaje.image}" alt="${personaje.name}" id="profile"  class="card__image">
                </div>
                <div class="card__body">
                <h2>${personaje.name}</h2>
                <h6>${personaje.status}</h6>
                <p>
                    ${personaje.location.name}
                </p>
            </div>`;
        contenedor.appendChild(card);
        
    });

})
.catch(function(error){

});
