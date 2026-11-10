const body = document.querySelector("body"),
      modeToggle = body.querySelector(".mode-toggle");
      sidebar = body.querySelector("nav");
      sidebarToggle = body.querySelector(".sidebar-toggle");

let getMode = localStorage.getItem("mode");
if(getMode && getMode ==="dark"){
    body.classList.toggle("dark");
}

let getStatus = localStorage.getItem("status");
if(getStatus && getStatus ==="close"){
    sidebar.classList.toggle("close");
}

modeToggle.addEventListener("click", () =>{
    body.classList.toggle("dark");
    if(body.classList.contains("dark")){
        localStorage.setItem("mode", "dark");
    }else{
        localStorage.setItem("mode", "light");
    }
});

sidebarToggle.addEventListener("click", () => {
    sidebar.classList.toggle("close");
    if(sidebar.classList.contains("close")){
        localStorage.setItem("status", "close");
    }else{
        localStorage.setItem("status", "open");
    }
})
document.addEventListener('DOMContentLoaded', function() {
    const clientLink = document.querySelector('.client-link');
    const afficherLink = document.querySelector('.afficher-link');
    const ajouterLink = document.querySelector('.ajouter-link');

    const clientSection = document.querySelector('.client');
    const afficherSection = document.querySelector('.afficher');
    const ajouterSection = document.querySelector('.ajouter');

    // Fonction pour masquer toutes les sections sauf celle spécifiée
    function hideAllExcept(sectionToShow) {
        const allSections = [clientSection, afficherSection, ajouterSection];
        allSections.forEach(section => {
            if (section !== sectionToShow) {
                section.classList.add('hidden');
            } else {
                section.classList.remove('hidden');
            }
        });
    }

    // Par défaut, masquer toutes les sections sauf la première (client)
    hideAllExcept(clientSection);

    // Gestion des clics sur les liens
    clientLink.addEventListener('click', function() {
        hideAllExcept(clientSection);
    });

    afficherLink.addEventListener('click', function() {
        hideAllExcept(afficherSection);
    });

    ajouterLink.addEventListener('click', function() {
        hideAllExcept(ajouterSection);
    });
});
