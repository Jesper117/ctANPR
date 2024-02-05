<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

    <link rel="icon" href="assets/logo_small.png" type="image/x-icon">
    <title>ctANPR - Automatische Kentekenherkenning</title>
</head>
<body>
<nav class="container-fluid">
    <ul>
        <li><strong>ctANPR</strong></li>
    </ul>
    <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">Inloggen</a></li>
        <li><a href="#" role="button">Contact</a></li>
    </ul>
</nav>

<main class="container">
    <div class="video-background">
        <div class="content-overlay">
            <img draggable="false" src="assets/logo.png" alt="ctANPR">
        </div>
    </div>
    <div class="angled-section">
        <div class="angled-section-content container">
            <h2>Onze ANPR Services</h2>
            <p>
                Wij specializeren ons in Automatic Number Plate Recognition (ANPR) voor fleetbeheer en toegangscontrole op bijvoorbeld campings of parkeergarageres. Onze oplossingen stroomlijnen voertuigregistratie en -tracking om de beveiliging en efficiëntie van onze klanten te verbeteren.
            </p>
            <p>
                Via onze makkelijk te integreren API kunnen onze klanten snel en eenvoudig aan de slag met onze ANPR oplossingen voor een breed aantal use-cases.
            </p>
        </div>
    </div>
    <!-- New section starts here -->
    <section class="additional-section">
        <div class="container">
            <h2>Onze diensten</h2>
            <div class="grid">
                <article>
                    <img draggable="false" src="assets/car-moving.png" alt="Bewegend voertuig">
                    <h3>Kentekenherkenning</h3>
                    <p>
                        Met ons uitgebreid getrainde machine learning model kunnen wij kentekens herkennen en verwerken in real-time.
                    </p>
                    <p>
                        Ons AI-model is getraind op duizenden kentekens en kan kentekens herkennen in alle lichtomstandigheden met minimale foutmarge, zelfs op afstand.

                    </p>
                </article>
                <article>
                    <img draggable="false" src="assets/rdw.png" alt="RDW Logo">
                    <h3>RDW Kentekenregister</h3>
                    <p>
                        Wij houden onze database met kentekens dagelijks up-to-date met het officiële RDW kentekenregister.
                    </p>
                    <p>
                        Via onze API kunnen onze klanten snel en eenvoudig alle publieke informatie over een kenteken opvragen, zoals merk, model, bouwjaar en nog veel meer.
                    </p>
                </article>
                <article>
                    <img draggable="false" src="assets/anpr-cameras.png" alt="ANPR Camera's">
                    <h3>Klaar voor integratie</h3>
                    <p>
                        Onze ANPR-oplossingen zijn makkelijk te integreren in bestaande systemen en software.
                    </p>
                    <p>
                        Na één simpele API-call naar ctANPR is er zo veel mogelijk.
                    </p>
                </article>
            </div>
        </div>
    </section>
    <!-- New section ends here -->

    <!-- New Live Demo Section Starts Here -->
    <section class="live-demo-section">
        <div class="container">
            <h2>Demonstratie</h2>
            <p>
                Hieronder kunt u een live demonstratie van onze ANPR-oplossing testen. Upload een afbeelding van een auto met Nederlands kenteken zichtbaar en deze demo herkent via onze API het kenteken met betreffende voertuiginformatie.
            </p>
            <div class="demo-container">
                <div class="image-upload-container">
                    <input type="file" id="car-image" name="car-image" accept="image/*" required hidden>
                    <label for="car-image" class="image-upload-label" id="image-upload-label">
                        <div class="image-preview" id="image-preview">
                            <img src="" alt="Auto met NL kentekenplaat" id="preview-img">
                            <span class="default-text">Klik hier om afbeelding te uploaden</span>
                        </div>
                    </label>
                </div>
                <div class="vehicle-info-container">
                    <div class="license-plate">
                        <div class="license-plate-country">
                            NL
                        </div>

                        <label class="license-plate-content">
                            Kenteken
                        </label>
                    </div>

                    <hr>

                    <div class="general-info-container">

                        <div class="general-info">
                            <h2>Handelsbenaming</h2>
                            <p>Bouwjaar</p>
                            <p>Kleur</p>
                        </div>

                        <div class="brand-logo-container">
                            <img draggable="false" src="assets/brands/audi.png" alt="Car Brand Logo" class="brand-logo">
                        </div>
                    </div>
                    <div class="detailed-info">
                        <table>
                            <tr>
                                <th>Extra</th>
                                <td>Informatie</td>
                            </tr>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- New Live Demo Section Ends Here -->
</main>

<script src="js/demo.js"></script>
<script src="js/navigation.js"></script>

</body>
</html>