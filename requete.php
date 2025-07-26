<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions et Réponses</title>
    <style>
        /* Style pour la page */
/* Style global */
body {
font-family: 'Poppins', sans-serif;
    background-color: #f9f9f9;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    height: 100vh;
    margin: 0;
    padding: 0;
}

/* Titre */
.titre {
    width: 60%;
    margin-top: 50px;
    font-weight: 700;
    text-transform: uppercase; /* Transforme le texte en majuscules */
    font-size: 3em; /* Taille du texte plus grande */
    background: linear-gradient(90deg, #ff6f61, #fbc531); /* Dégradé de couleurs */
    color: transparent; /* Rend la couleur de fond transparente */
    text-align: center;
    letter-spacing: 2px; /* Espacement entre les lettres */
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2); /* Ombre légère pour donner de la profondeur */
    font-style: italic;
    text-decoration: underline;
   

}


/* Conteneur des questions */
.container {
    width: 80%;
    margin-top: 50px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    

}

/* Style des boîtes de questions */
.question-box {
    background-color: #ff6f61;
    color: white;
    padding: 20px;
    border-radius: 12px;
    cursor: pointer;
    transition: transform 0.2s ease, background-color 0.3s ease;
    text-align: center;
    font-size: 1.2em;
    font-weight: 600;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    
}

.question-box:hover {
    background-color: #e63a28;
    transform: scale(1.05);
}

/* Style pour afficher les réponses */
.answer-container {
    display: none;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    font-size: 1em;
    transition: opacity 0.3s ease;
}

/* Effet d'apparition des réponses */
.answer-container.show {
    display: block;
    opacity: 1;
}

.answer-container.hide {
    display: none;
    opacity: 0;
}

/* Animation de la réponse au clic */
.answer-container p {
    font-size: 1em;
    color: #333;
    line-height: 1.6;
    margin: 0;
}


    </style>
</head>
<body>
       
  <div class="titre">
    <!-- Rectangle de le titre -->
        <div class="question-box" >
             Foire aux Questions
        </div>
    </div>
     <div class="container">
        <!-- Rectangle de la question 1 -->
        <div class="question-box" onclick="toggleAnswer(1)">
            Afficher les noms des cadeaux disponibles et leur poids,
        </div>
        <div id="answer-1" class="answer-container">
            <p id="answer-text-1">Réponse à la question 1</p>
        </div>

        <!-- Rectangle de la question 2 -->
        <div class="question-box" onclick="toggleAnswer(2)">
            Lister les noms et prénoms des enfants qui ont choisi une poupée comme jouet
        </div>
        <div id="answer-2" class="answer-container">
            <p id="answer-text-2">Réponse à la question 2 </p>
        </div>

        <!-- Rectangle de la question 3 -->
        <div class="question-box" onclick="toggleAnswer(3)">
            Afficher les informations sur les enfants
        </div>
        <div id="answer-3" class="answer-container">
            <p id="answer-text-3">Réponse à la question 3 </p>
        </div>
        <!-- Rectangle de la question 4 -->
        <div class="question-box" onclick="toggleAnswer(4)">
            Afficher les noms des cadeaux fabriqués à partir de la matière "bois"
        </div>
        <div id="answer-4" class="answer-container">
            <p id="answer-text-4">Réponse à la question 4 </p>
        </div>
        <!-- Rectangle de la question 5 -->
        <div class="question-box" onclick="toggleAnswer(5)">
            Afficher les noms des équipes qui regroupent des elfes ayant un rôle de Menuisier et de Logistique
        </div>
        <div id="answer-5" class="answer-container">
            <p id="answer-text-5">Réponse à la question 5 </p>
        </div>
        <!-- Rectangle de la question 6 -->
        <div class="question-box" onclick="toggleAnswer(6)">
            Afficher les noms des entrepôts qui ont reçu un traîneau à la date du 25/12/01 ou à la date du 2025/12/03
        </div>
        <div id="answer-6" class="answer-container">
            <p id="answer-text-6">Réponse à la question 6 </p>
        </div>
        <!-- Rectangle de la question 7 -->
        <div class="question-box" onclick="toggleAnswer(7)">
            Afficher les noms des traîneaux dont le poids est compris entre 400 et 800
        </div>
        <div id="answer-7" class="answer-container">
            <p id="answer-text-7">Réponse à la question 7 </p>
        </div>
        <!-- Rectangle de la question 8 -->
        <div class="question-box" onclick="toggleAnswer(8)">
            Afficher les identifiants des entrepôts situés dans les régions Europe, Asie et Afrique
        </div>
        <div id="answer-8" class="answer-container">
            <p id="answer-text-8">Réponse à la question 8 </p>
        </div>
        <!-- Rectangle de la question 9 -->
        <div class="question-box" onclick="toggleAnswer(9)">
            Afficher les noms qui ne regroupent pas des elfes ayant un rôle d Emballage
        </div>
        <div id="answer-9" class="answer-container">
            <p id="answer-text-9">Réponse à la question 9 </p>
        </div>
        <!-- Rectangle de la question 10 -->
        <div class="question-box" onclick="toggleAnswer(10)">
            Afficher les identifiants des enfants dont le nom commence par "D" et dont le prénom se termine par "l"
        </div>
        <div id="answer-10" class="answer-container">
            <p id="answer-text-10">Réponse à la question 10 </p>
        </div>
        <!-- Rectangle de la question 11 -->
        <div class="question-box" onclick="toggleAnswer(11)">
            Afficher les noms des enfants dont le prénom ne contient pas de voyelle en deuxième position
        </div>
        <div id="answer-11" class="answer-container">
            <p id="answer-text-11">Réponse à la question 11 </p>
        </div>
        <!-- Rectangle de la question 12 -->
        <div class="question-box" onclick="toggleAnswer(12)">
            Afficher toutes les informations sur les entrepôts et les intermittents associés, où l'identifiant de l'entrepôt correspond à celui de l'intermittent
        </div>
        <div id="answer-12" class="answer-container">
            <p id="answer-text-12">Réponse à la question 12 </p>
        </div>
        <!-- Rectangle de la question 13 -->
        <div class="question-box" onclick="toggleAnswer(13)">
            Afficher les noms des intermittents qui sont affectés à un entrepôt situé en Amérique
        </div>
        <div id="answer-13" class="answer-container">
            <p id="answer-text-13">Réponse à la question 13 </p>
        </div>
        <!-- Rectangle de la question 14 -->
        <div class="question-box" onclick="toggleAnswer(14)">
            Afficher les noms des rennes qui ont une couleur de nez verte ou rouge
        </div>
        <div id="answer-14" class="answer-container">
            <p id="answer-text-14">Réponse à la question 14 </p>
        </div>
        <!-- Rectangle de la question 15 -->
        <div class="question-box" onclick="toggleAnswer(15)">
            Afficher les noms des rennes qui tirent un traîneau de poids 500
        </div>
        <div id="answer-15" class="answer-container">
            <p id="answer-text-15">Réponse à la question 15 </p>
        </div>
        <!-- Rectangle de la question 16 -->
        <div class="question-box" onclick="toggleAnswer(16)">
            Afficher les noms des enfants dont la taille de l'adresse ne dépasse pas 30 caractères
        </div>
        <div id="answer-16" class="answer-container">
            <p id="answer-text-16">Réponse à la question 16 </p>
        </div>
        <!-- Rectangle de la question 17 -->
        <div class="question-box" onclick="toggleAnswer(17)">
            Afficher tous les rennes (identifiant et nom) avec, si ils existent, les traîneaux qui les concernent (qu'ils tirent)
        </div>
        <div id="answer-17" class="answer-container">
            <p id="answer-text-17">Réponse à la question 17 </p>
        </div>
        <!-- Rectangle de la question 18 -->
        <div class="question-box" onclick="toggleAnswer(18)">
            Afficher les identifiants des intermittents qui ont participé à toutes les tournées
        </div>
        <div id="answer-18" class="answer-container">
            <p id="answer-text-18">Réponse à la question 18 </p>
        </div>
        <!-- Rectangle de la question 19 -->
        <div class="question-box" onclick="toggleAnswer(19)">
            Afficher les identifiants des intermittents qui ont participé à toutes les tournées d'Europe
        </div>
        <div id="answer-19" class="answer-container">
            <p id="answer-text-19">Réponse à la question 19 </p>
        </div>
        <!-- Rectangle de la question 20 -->
        <div class="question-box" onclick="toggleAnswer(20)">
            Afficher le poids total des traîneaux
        </div>
        <div id="answer-20" class="answer-container">
            <p id="answer-text-20">Réponse à la question 20 </p>
        </div>
        <!-- Rectangle de la question 21 -->
        <div class="question-box" onclick="toggleAnswer(21)">
            Afficher la somme du poids des traîneaux ayant une capacité de 8
        </div>
        <div id="answer-21" class="answer-container">
            <p id="answer-text-21">Réponse à la question 21 </p>
        </div>
        <!-- Rectangle de la question 22 -->
        <div class="question-box" onclick="toggleAnswer(22)">
            Afficher le total du poids des traîneaux envoyés à chaque entrepôt
        </div>
        <div id="answer-22" class="answer-container">
            <p id="answer-text-22">Réponse à la question 22 </p>
        </div>
        <!-- Rectangle de la question 23 -->
        <div class="question-box" onclick="toggleAnswer(23)">
            Afficher les identifiants des traîneaux envoyés par un seul entrepôt
        </div>
        <div id="answer-23" class="answer-container">
            <p id="answer-text-23">Réponse à la question 23 </p>
        </div>
        <!-- Rectangle de la question 24 -->
        <div class="question-box" onclick="toggleAnswer(24)">
            Afficher le nombre de traîneaux ayant une capacité de 8
        </div>
        <div id="answer-24" class="answer-container">
            <p id="answer-text-24">Réponse à la question 24 </p>
        </div>
        <!-- Rectangle de la question 25 -->
        <div class="question-box" onclick="toggleAnswer(25)">
            Afficher le poids minimal parmi les traîneaux ayant une capacité de 8
        </div>
        <div id="answer-25" class="answer-container">
            <p id="answer-text-25">Réponse à la question 25 </p>
        </div>
        <!-- Rectangle de la question 26 -->
        <div class="question-box" onclick="toggleAnswer(26)">
            Afficher le poids maximal parmi les traîneaux ayant une capacité de 8
        </div>
        <div id="answer-26" class="answer-container">
            <p id="answer-text-26">Réponse à la question 26 </p>
        </div>
        <!-- Rectangle de la question 27 -->
        <div class="question-box" onclick="toggleAnswer(27)">
            Afficher les spécialités ayant moins de 2 elfes affectés à une spécialité secondaire
        </div>
        <div id="answer-27" class="answer-container">
            <p id="answer-text-27">Réponse à la question 27 </p>
        </div>
        <!-- Rectangle de la question 28 -->
        <div class="question-box" onclick="toggleAnswer(28)">
            Afficher une table provisoire contenant la liste des enfants avec le total des jouets qu'ils ont commandés
        </div>
        <div id="answer-28" class="answer-container">
            <p id="answer-text-28">Réponse à la question 28 </p>
        </div>
        <!-- Rectangle de la question 29 -->
        <div class="question-box" onclick="toggleAnswer(29)">
            Afficher la charge de chaque traîneau afin de s’assurer qu’ils soient dans la capacité définie pour le traîneau (somme des capacités de chaque renne)
        </div>
        <div id="answer-29" class="answer-container">
            <p id="answer-text-29">Réponse à la question 29 </p>
        </div>
        <!-- Rectangle de la question 30 -->
        <div class="question-box" onclick="toggleAnswer(30)">
            Afficher quels elfes s’occupent de quel traîneau/renne
        </div>
        <div id="answer-30" class="answer-container">
            <p id="answer-text-30">Réponse à la question 30 </p>
        </div>
        <!-- Rectangle de la question 31 -->
        <div class="question-box" onclick="toggleAnswer(31)">
            Afficher le jouet le plus produit
        </div>
        <div id="answer-31" class="answer-container">
            <p id="answer-text-31">Réponse à la question 31 </p>
        </div>
    </div>

   <script>
    // Fonction pour afficher ou masquer la réponse à une question
    function toggleAnswer(questionNumber) {
        // Récupère l'élément contenant la réponse (en utilisant l'ID de l'élément correspondant à la question)
        const answerContainer = document.getElementById(`answer-${questionNumber}`);
        // Récupère l'élément contenant le texte de la réponse (en utilisant l'ID de l'élément spécifique à la question)
        const answerText = document.getElementById(`answer-text-${questionNumber}`);

        // Vérifie si la réponse est actuellement affichée (si l'élément a un display de type "block")
        if (answerContainer.style.display === "block") {
            // Si la réponse est affichée, on la cache en changeant son style d'affichage à "none"
            answerContainer.style.display = "none";
        } else {
            // Si la réponse est cachée, on l'affiche en changeant son style d'affichage à "block"
            answerContainer.style.display = "block";

            // Bloc switch pour déterminer le contenu à afficher en fonction du numéro de la question
            switch (questionNumber) {
                case 1:
                    // Si la question est la 1ère, afficher les cadeaux disponibles
                    answerText.innerHTML = "Les cadeaux disponibles sont : <br> 1. Poupée, Poids: 3kg <br> 2. Voiture, Poids: 2kg";
                    break;
                case 2:
                    // Si la question est la 2ème, afficher les enfants ayant choisi une poupée
                    answerText.innerHTML = "Les enfants ayant choisi une poupée sont : <br> 1. Alice, 2. Bob";
                    break;
                case 3:
                    // Si la question est la 3ème, afficher les informations des enfants
                    answerText.innerHTML = "Les informations des enfants sont : <br> 1. Alice, 2. Bob";
                    break;
                case 4:
                    // Si la question est la 4ème, afficher les cadeaux fabriqués à partir de bois
                    answerText.innerHTML = "Les cadeaux fabriqués à partir de bois sont : <br> 1. Maison en bois, Poids: 5kg";
                    break;
                case 5:
                    // Si la question est la 5ème, afficher les équipes avec des rôles d'elfes spécifiés
                    answerText.innerHTML = "Les équipes regroupant des elfes avec les rôles spécifiés sont : <br> 1. Équipe A";
                    break;
                case 6:
                    // Si la question est la 6ème, afficher les entrepôts ayant reçu un traîneau à la date demandée
                    answerText.innerHTML = "Les entrepôts ayant reçu un traîneau à la date demandée sont : <br> 1. Entrepôt 1";
                    break;
                case 7:
                    // Si la question est la 7ème, afficher les traîneaux avec un poids entre 400 et 800 kg
                    answerText.innerHTML = "Les traîneaux avec poids entre 400 et 800kg sont : <br> 1. Traîneau A";
                    break;
                case 8:
                    // Si la question est la 8ème, afficher les entrepôts situés dans les régions Europe, Asie et Afrique
                    answerText.innerHTML = "Les entrepôts situés dans les régions Europe, Asie et Afrique sont : <br> 1. Entrepôt X, 2. Entrepôt Y";
                    break;
                case 9:
                    // Si la question est la 9ème, afficher les équipes sans les elfes dans le rôle d'Emballage
                    answerText.innerHTML = "Les équipes ne regroupant pas des elfes avec le rôle Emballage sont : <br> 1. Équipe Z";
                    break;
                case 10:
                    // Si la question est la 10ème, afficher les enfants dont le nom commence par 'D' et prénom termine par 'l'
                    answerText.innerHTML = "Les enfants dont le nom commence par 'D' et le prénom se termine par 'l' sont : <br> 1. Daniel";
                    break;
                case 11:
                    // Si la question est la 11ème, afficher les enfants avec une voyelle non présente en 2ème position dans leur prénom
                    answerText.innerHTML = "Les enfants dont le prénom ne contient pas de voyelle en deuxième position sont : <br> 1. Tom";
                    break;
                case 12:
                    // Si la question est la 12ème, afficher les informations sur les entrepôts et les intermittents associés
                    answerText.innerHTML = "Les informations sur les entrepôts et les intermittents associés sont : <br> 1. Entrepôt A";
                    break;
                case 13:
                    // Si la question est la 13ème, afficher les intermittents affectés à un entrepôt en Amérique
                    answerText.innerHTML = "Les intermittents affectés à un entrepôt situé en Amérique sont : <br> 1. Elfie";
                    break;
                case 14:
                    // Si la question est la 14ème, afficher les rennes avec un nez vert ou rouge
                    answerText.innerHTML = "Les rennes avec un nez vert ou rouge sont : <br> 1. Dasher, 2. Prancer";
                    break;
                case 15:
                    // Si la question est la 15ème, afficher les rennes tirant un traîneau de poids 500 kg
                    answerText.innerHTML = "Les rennes tirant un traîneau de poids 500 sont : <br> 1. Donner";
                    break;
                case 16:
                    // Si la question est la 16ème, afficher les enfants dont la taille de l'adresse ne dépasse pas 30 caractères
                    answerText.innerHTML = "Les enfants dont la taille de l'adresse ne dépasse pas 30 caractères sont : <br> 1. Emma";
                    break;
                case 17:
                    // Si la question est la 17ème, afficher les rennes avec les traîneaux qu'ils tirent
                    answerText.innerHTML = "Les rennes avec les traîneaux qu'ils tirent sont : <br> 1. Rudolph (Traîneau A)";
                    break;
                case 18:
                    // Si la question est la 18ème, afficher les intermittents ayant participé à toutes les tournées
                    answerText.innerHTML = "Les intermittents ayant participé à toutes les tournées sont : <br> 1. Jack";
                    break;
                case 19:
                    // Si la question est la 19ème, afficher les intermittents ayant participé à toutes les tournées d'Europe
                    answerText.innerHTML = "Les intermittents ayant participé à toutes les tournées d'Europe sont : <br> 1. Emily";
                    break;
                case 20:
                    // Si la question est la 20ème, afficher le poids total des traîneaux
                    answerText.innerHTML = "Le poids total des traîneaux est de 2500 kg";
                    break;
                case 21:
                    // Si la question est la 21ème, afficher la somme du poids des traîneaux ayant une capacité de 8
                    answerText.innerHTML = "La somme du poids des traîneaux ayant une capacité de 8 est de 1500 kg";
                    break;
                case 22:
                    // Si la question est la 22ème, afficher le total du poids des traîneaux envoyés à chaque entrepôt
                    answerText.innerHTML = "Le total du poids des traîneaux envoyés à chaque entrepôt est : <br> 1. Entrepôt A: 500kg, 2. Entrepôt B: 600kg";
                    break;
                case 23:
                    // Si la question est la 23ème, afficher les traîneaux envoyés par un seul entrepôt
                    answerText.innerHTML = "Les traîneaux envoyés par un seul entrepôt sont : <br> 1. Traîneau A";
                    break;
                case 24:
                    // Si la question est la 24ème, afficher le nombre de traîneaux ayant une capacité de 8
                    answerText.innerHTML = "Le nombre de traîneaux ayant une capacité de 8 est : 5";
                    break;
                case 25:
                    // Si la question est la 25ème, afficher le poids minimal parmi les traîneaux ayant une capacité de 8
                    answerText.innerHTML = "Le poids minimal parmi les traîneaux ayant une capacité de 8 est de 200 kg";
                    break;
                case 26:
                    // Si la question est la 26ème, afficher le poids maximal parmi les traîneaux ayant une capacité de 8
                    answerText.innerHTML = "Le poids maximal parmi les traîneaux ayant une capacité de 8 est de 700 kg";
                    break;
                case 27:
                    // Si la question est la 27ème, afficher les spécialités ayant moins de 2 elfes affectés à une spécialité secondaire
                    answerText.innerHTML = "Les spécialités ayant moins de 2 elfes affectés à une spécialité secondaire sont : <br> 1. Menuiserie";
                    break;
                case 28:
                    // Si la question est la 28ème, afficher la table provisoire des enfants avec le total des jouets
                    answerText.innerHTML = "Table provisoire des enfants avec le total des jouets : <br> 1. Alice : 3 jouets, 2. Bob : 2 jouets";
                    break;
                case 29:
                    // Si la question est la 29ème, afficher la charge de chaque traîneau
                    answerText.innerHTML = "La charge de chaque traîneau est de 400 kg";
                    break;
                case 30:
                    // Si la question est la 30ème, afficher les elfes s'occupant de certains traîneaux/rennes
                    answerText.innerHTML = "Les elfes s'occupent de quels traîneaux/rennes : <br> 1. Elfie : Traîneau A, 2. Jack : Traîneau B";
                    break;
                case 31:
                    // Si la question est la 31ème, afficher le jouet le plus produit
                    answerText.innerHTML = "Le jouet le plus produit est : <br> 1. Poupée";
                    break;
                default:
                    // Si la question ne correspond à aucune des cases ci-dessus, afficher "Réponse inconnue."
                    answerText.innerHTML = "Réponse inconnue.";
            }
        }
    }
</script>

</body>
</html>

	

