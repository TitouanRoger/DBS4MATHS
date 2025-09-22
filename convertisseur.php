<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convertisseur</title>
    <link rel="stylesheet" href="css/convertisseur.css">
</head>

<body class="convertisseur">
    <div class="convertisseur-converter">
        <select class="convertisseur-select" id="unit-type" onchange="updateUnitSelectors()">
            <option value="time">Temps</option>
            <option value="temperature">Température</option>
            <option value="angle">Angle</option>
            <option value="distance">Distance</option>
            <option value="mass">Masse</option>
            <option value="volume">Volume</option>
            <option value="superficie">Superficie</option>
            <option value="vitesse">Vitesse</option>
            <option value="frequency">Fréquence</option>
            <option value="pressure">Pression</option>
            <option value="energy">Energie</option>
        </select>
        <select class="convertisseur-select" id="unit-from-type"></select>
        <input type="number" id="unit-from" class="convertisseur-unit" placeholder="Valeur" required>
        <select class="convertisseur-select" id="unit-to-type"></select>
        <div class="convertisseur-buttons">
            <button class="convertisseur-button" onclick="convertUnit()">Convertir</button>
            <button class="convertisseur-button" onclick="clearConverter()">Effacer</button>
        </div>
        <h2 id="result"></h2>
    </div>
    <script>
        const unitOptions = {
            'time': ['Nanoseconde', 'Microseconde', 'Milliseconde', 'Seconde', 'Minute', 'Heure', 'Jour', 'Semaine', 'Mois', 'Année', 'Décennie', 'Siècle'],
            'temperature': ['Celsius', 'Fahrenheit', 'Kelvin'],
            'angle': ['Degré', 'Radian', 'Grade'],
            'distance': ['Nanomètre', 'Micromètre', 'Millimètre', 'Centimètre', 'Décimètre', 'Mètre', 'Décamètre', 'Hectomètre', 'Kilomètre', 'Mile', 'Yard', 'Pied', 'Pouce', 'Mille Marin'],
            'mass': ['Nanogramme', 'Microgramme', 'Milligramme', 'Centigramme', 'Décigramme', 'Gramme', 'Décagramme', 'Hectogramme', 'Kilogramme', 'Tone', 'Stone', 'Livre', 'Once'],
            'volume': ['Nanomètre cube', 'Micromètre cube', 'Millimètre cube', 'Centimètre cube', 'Décimètre cube', 'Mètre cube', 'Décamètre cube', 'Hectomètre cube', 'Kilomètre cube', 'Nanolitre', 'Microlitre', 'Millilitre', 'Centilitre', 'Décilitre', 'Litre', 'Décalitre', 'Hectolitre', 'Kilolitre', 'Pinte', 'Gallon', 'Baril'],
            'superficie': ['Nanomètre carré', 'Micromètre carré', 'Millimètre carré', 'Centimètre carré', 'Décimètre carré', 'Mètre carré', 'Décamètre carré', 'Hectomètre carré', 'Kilomètre carré'],
            'vitesse': ['Nanomètre/Seconde', 'Micromètre/Seconde', 'Millimètre/Seconde', 'Centimètre/Seconde', 'Décimètre/Seconde', 'Mètre/Seconde', 'Décamètre/Seconde', 'Hectomètre/Seconde', 'Kilomètre/Seconde', 'Nanomètre/Heure', 'Micromètre/Heure', 'Millimètre/Heure', 'Centimètre/Heure', 'Décimètre/Heure', 'Mètre/Heure', 'Décamètre/Heure', 'Hectomètre/Heure', 'Kilomètre/Heure'],
            'frequency': ['Nanohertz', 'Microhertz', 'Millihertz', 'Centihertz', 'Décihertz', 'Hertz', 'Décahertz', 'Hectohertz', 'KiloHertz'],
            'pressure': ['Pascal', 'Bar', 'PSI', 'Atmosphères'],
            'energy': ['Nanojoule', 'Microjoule', 'Millijoule', 'Centijoule', 'Décijoule', 'Joule', 'Décajoule', 'Hectojoule', 'Kilojoule']
        };

        document.addEventListener('DOMContentLoaded', () => {
            console.log('Page chargée, initialisation des menus déroulants...');
            updateUnitSelectors();
        });

        function updateUnitSelectors() {
            console.log('updateUnitSelectors() appelée');
            const unitType = document.getElementById('unit-type').value;
            const fromSelect = document.getElementById('unit-from-type');
            const toSelect = document.getElementById('unit-to-type');

            console.log('Type d\'unité sélectionné :', unitType);

            fromSelect.innerHTML = '';
            toSelect.innerHTML = '';

            if (unitOptions[unitType]) {
                console.log('Options disponibles pour ce type :', unitOptions[unitType]);
                unitOptions[unitType].forEach(unit => {
                    const fromOption = document.createElement('option');
                    fromOption.value = unit;
                    fromOption.text = unit;
                    fromSelect.add(fromOption);

                    const toOption = document.createElement('option');
                    toOption.value = unit;
                    toOption.text = unit;
                    toSelect.add(toOption);
                });

                toSelect.addEventListener('change', () => {
                    const selectedTo = toSelect.value;
                    Array.from(fromSelect.options).forEach(option => {
                        option.disabled = option.value === selectedTo;
                    });
                });
            } else {
                console.error(`Aucune unité trouvée pour le type : ${unitType}`);
            }
            fromSelect.addEventListener('change', () => {
                const selectedFrom = fromSelect.value;
                Array.from(toSelect.options).forEach(option => {
                    option.disabled = option.value === selectedFrom;
                });
            });


        }

        function clearConverter() {
            document.getElementById('unit-from').value = '';
            document.getElementById('result').innerHTML = '';
        }

        function convertUnit() {
            const unitType = document.getElementById('unit-type').value;
            const unitFrom = document.getElementById('unit-from').value;
            const unitFromType = document.getElementById('unit-from-type').value;
            const unitToType = document.getElementById('unit-to-type').value;

            try {
                const result = convert(unitType, unitFrom, unitFromType, unitToType);
                document.getElementById('result').innerHTML = result;
            } catch (error) {
                document.getElementById('result').innerHTML = 'Erreur';
            }
        }

        function convert(unitType, unitFrom, unitFromType, unitToType) {
            if (unitFromType === unitToType) return unitFrom;

            switch (unitType) {
                case 'time':
                    return convertTime(unitFrom, unitFromType, unitToType);
                case 'temperature':
                    return convertTemperature(unitFrom, unitFromType, unitToType);
                case 'angle':
                    return convertAngle(unitFrom, unitFromType, unitToType);
                case 'distance':
                    return convertDistance(unitFrom, unitFromType, unitToType);
                case 'mass':
                    return convertMass(unitFrom, unitFromType, unitToType);
                case 'volume':
                    return convertVolume(unitFrom, unitFromType, unitToType);
                case 'superficie':
                    return convertSuperficie(unitFrom, unitFromType, unitToType);
                case 'vitesse':
                    return convertVitesse(unitFrom, unitFromType, unitToType);
                case 'frequency':
                    return convertFrequency(unitFrom, unitFromType, unitToType);
                case 'pressure':
                    return convertPressure(unitFrom, unitFromType, unitToType);
                case 'energy':
                    return convertEnergy(unitFrom, unitFromType, unitToType);
            }
        }

        function convertTime(unitFrom, unitFromType, unitToType) {
            unitFrom = parseFloat(unitFrom);
            switch (unitFromType) {
                case 'Nanoseconde':
                    switch (unitToType) {
                        case 'Microseconde':
                            return unitFrom / 1000;
                        case 'Milliseconde':
                            return unitFrom / 1000000;
                        case 'Seconde':
                            return unitFrom / 1000000000;
                        case 'Minute':
                            return unitFrom / 60000000000;
                        case 'Heure':
                            return unitFrom / 3600000000000;
                        case 'Jour':
                            return unitFrom / 86400000000000;
                        case 'Semaine':
                            return unitFrom / 604800000000000;
                        case 'Mois':
                            return unitFrom / 2592000000000000;
                        case 'Année':
                            return unitFrom / 31536000000000000;
                        case 'Décennie':
                            return unitFrom / 315360000000000000;
                        case 'Siècle':
                            return unitFrom / 3153600000000000000;
                    }
                case 'Microseconde':
                    switch (unitToType) {
                        case 'Nanoseconde':
                            return unitFrom * 1000;
                        case 'Milliseconde':
                            return unitFrom / 1000;
                        case 'Seconde':
                            return unitFrom / 1000000;
                        case 'Minute':
                            return unitFrom / 6000000000;
                        case 'Heure':
                            return unitFrom / 3600000000000;
                        case 'Jour':
                            return unitFrom / 86400000000000;
                        case 'Semaine':
                            return unitFrom / 604800000000000;
                        case 'Mois':
                            return unitFrom / 2592000000000000;
                        case 'Année':
                            return unitFrom / 31536000000000000;
                        case 'Décennie':
                            return unitFrom / 315360000000000000;
                        case 'Siècle':
                            return unitFrom / 3153600000000000000;
                    }
                case 'Milliseconde':
                    switch (unitToType) {
                        case 'Nanoseconde':
                            return unitFrom * 1000000;
                        case 'Microseconde':
                            return unitFrom * 1000;
                        case 'Seconde':
                            return unitFrom / 1000;
                        case 'Minute':
                            return unitFrom / 6000000;
                        case 'Heure':
                            return unitFrom / 3600000000;
                        case 'Jour':
                            return unitFrom / 86400000000;
                        case 'Semaine':
                            return unitFrom / 604800000000;
                        case 'Mois':
                            return unitFrom / 2592000000000;
                        case 'Année':
                            return unitFrom / 31536000000000;
                        case 'Décennie':
                            return unitFrom / 315360000000000;
                        case 'Siècle':
                            return unitFrom / 3153600000000000;
                    }
                case 'Seconde':
                    switch (unitToType) {
                        case 'Nanoseconde':
                            return unitFrom * 1000000000;
                        case 'Microseconde':
                            return unitFrom * 1000000;
                        case 'Milliseconde':
                            return unitFrom * 1000;
                        case 'Minute':
                            return unitFrom / 60;
                        case 'Heure':
                            return unitFrom / 3600;
                        case 'Jour':
                            return unitFrom / 86400;
                        case 'Semaine':
                            return unitFrom / 604800;
                        case 'Mois':
                            return unitFrom / 2592000;
                        case 'Année':
                            return unitFrom / 31536000;
                        case 'Décennie':
                            return unitFrom / 315360000;
                        case 'Siècle':
                            return unitFrom / 3153600000;
                    }
                case 'Minute':
                    switch (unitToType) {
                        case 'Nanoseconde':
                            return unitFrom * 60000000000;
                        case 'Microseconde':
                            return unitFrom * 6000000;
                        case 'Milliseconde':
                            return unitFrom * 6000;
                        case 'Seconde':
                            return unitFrom * 60;
                        case 'Heure':
                            return unitFrom / 60;
                        case 'Jour':
                            return unitFrom / 1440;
                        case 'Semaine':
                            return unitFrom / 10080;
                        case 'Mois':
                            return unitFrom / 43800;
                        case 'Année':
                            return unitFrom / 525600;
                        case 'Décennie':
                            return unitFrom / 5256000;
                        case 'Siècle':
                            return unitFrom / 52560000;
                    }
                case 'Heure':
                    switch (unitToType) {
                        case 'Nanoseconde':
                            return unitFrom * 3600000000000;
                        case 'Microseconde':
                            return unitFrom * 3600000000;
                        case 'Milliseconde':
                            return unitFrom * 3600000;
                        case 'Seconde':
                            return unitFrom * 3600;
                        case 'Minute':
                            return unitFrom * 60;
                        case 'Jour':
                            return unitFrom / 24;
                        case 'Semaine':
                            return unitFrom / 168;
                        case 'Mois':
                            return unitFrom / 720;
                        case 'Année':
                            return unitFrom / 8760;
                        case 'Décennie':
                            return unitFrom / 87600;
                        case 'Siècle':
                            return unitFrom / 876000;
                    }
                case 'Jour':
                    switch (unitToType) {
                        case 'Nanoseconde':
                            return unitFrom * 86400000000000;
                        case 'Microseconde':
                            return unitFrom * 86400000000;
                        case 'Milliseconde':
                            return unitFrom * 86400000;
                        case 'Seconde':
                            return unitFrom * 86400;
                        case 'Minute':
                            return unitFrom * 1440;
                        case 'Heure':
                            return unitFrom * 24;
                        case 'Semaine':
                            return unitFrom / 7;
                        case 'Mois':
                            return unitFrom / 30;
                        case 'Année':
                            return unitFrom / 365;
                        case 'Décennie':
                            return unitFrom / 3650;
                        case 'Siècle':
                            return unitFrom / 36500;
                    }
                case 'Semaine':
                    switch (unitToType) {
                        case 'Nanoseconde':
                            return unitFrom * 604800000000000;
                        case 'Microseconde':
                            return unitFrom * 604800000000;
                        case 'Milliseconde':
                            return unitFrom * 604800000;
                        case 'Seconde':
                            return unitFrom * 604800;
                        case 'Minute':
                            return unitFrom * 10080;
                        case 'Heure':
                            return unitFrom * 168;
                        case 'Jour':
                            return unitFrom * 7;
                        case 'Mois':
                            return unitFrom / 4;
                        case 'Année':
                            return unitFrom / 52;
                        case 'Décennie':
                            return unitFrom / 520;
                        case 'Siècle':
                            return unitFrom / 5200;
                    }
                case 'Mois':
                    switch (unitToType) {
                        case 'Nanoseconde':
                            return unitFrom * 2592000000000000;
                        case 'Microseconde':
                            return unitFrom * 2592000000000;
                        case 'Milliseconde':
                            return unitFrom * 2592000000;
                        case 'Seconde':
                            return unitFrom * 2592000;
                        case 'Minute':
                            return unitFrom * 43800;
                        case 'Heure':
                            return unitFrom * 720;
                        case 'Jour':
                            return unitFrom * 30;
                        case 'Semaine':
                            return unitFrom * 4;
                        case 'Année':
                            return unitFrom / 12;
                        case 'Décennie':
                            return unitFrom / 120;
                        case 'Siècle':
                            return unitFrom / 1200;
                    }
                case 'Année':
                    switch (unitToType) {
                        case 'Nanoseconde':
                            return unitFrom * 31536000000000000;
                        case 'Microseconde':
                            return unitFrom * 31536000000000;
                        case 'Milliseconde':
                            return unitFrom * 3153600000;
                        case 'Seconde':
                            return unitFrom * 3153600;
                        case 'Minute':
                            return unitFrom * 525600;
                        case 'Heure':
                            return unitFrom * 8760;
                        case 'Jour':
                            return unitFrom * 365;
                        case 'Semaine':
                            return unitFrom * 52;
                        case 'Mois':
                            return unitFrom * 12;
                        case 'Décennie':
                            return unitFrom / 10;
                        case 'Siècle':
                            return unitFrom / 100;
                    }
                case 'Décennie':
                    switch (unitToType) {
                        case 'Nanoseconde':
                            return unitFrom * 315360000000000000;
                        case 'Microseconde':
                            return unitFrom * 315360000000000;
                        case 'Milliseconde':
                            return unitFrom * 31536000000;
                        case 'Seconde':
                            return unitFrom * 31536000;
                        case 'Minute':
                            return unitFrom * 5256000;
                        case 'Heure':
                            return unitFrom * 87600;
                        case 'Jour':
                            return unitFrom * 3650;
                        case 'Semaine':
                            return unitFrom * 520;
                        case 'Mois':
                            return unitFrom * 120;
                        case 'Année':
                            return unitFrom * 10;
                        case 'Siècle':
                            return unitFrom / 10;
                    }
                case 'Siècle':
                    switch (unitToType) {
                        case 'Nanoseconde':
                            return unitFrom * 3153600000000000000;
                        case 'Microseconde':
                            return unitFrom * 3153600000000000;
                        case 'Milliseconde':
                            return unitFrom * 315360000000;
                        case 'Seconde':
                            return unitFrom * 315360000;
                        case 'Minute':
                            return unitFrom * 52560000;
                        case 'Heure':
                            return unitFrom * 876000;
                        case 'Jour':
                            return unitFrom * 36500;
                        case 'Semaine':
                            return unitFrom * 5200;
                        case 'Mois':
                            return unitFrom * 1200;
                        case 'Année':
                            return unitFrom * 100;
                        case 'Décennie':
                            return unitFrom * 10;
                    }
            }
        }

        function convertTemperature(unitFrom, unitFromType, unitToType) {
            unitFrom = parseFloat(unitFrom);
            switch (unitFromType) {
                case 'Celsius':
                    switch (unitToType) {
                        case 'Fahrenheit':
                            return unitFrom * 1.8 + 32;
                        case 'Kelvin':
                            return unitFrom + 273.15;
                    }
                case 'Fahrenheit':
                    switch (unitToType) {
                        case 'Celsius':
                            return (unitFrom - 32) / 1.8;
                        case 'Kelvin':
                            return (unitFrom - 32) / 1.8 + 273.15;
                    }
                case 'Kelvin':
                    switch (unitToType) {
                        case 'Celsius':
                            return unitFrom - 273.15;
                        case 'Fahrenheit':
                            return (unitFrom - 273.15) * 1.8 + 32;
                    }
            }
        }

        function convertAngle(unitFrom, unitFromType, unitToType) {
            unitFrom = parseFloat(unitFrom);
            switch (unitFromType) {
                case 'Degré':
                    switch (unitToType) {
                        case 'Radian':
                            return unitFrom * Math.PI / 180;
                        case 'Grade':
                            return unitFrom * 200 / 180;
                    }
                case 'Radian':
                    switch (unitToType) {
                        case 'Degré':
                            return unitFrom * 180 / Math.PI;
                        case 'Grade':
                            return unitFrom * 200 / Math.PI;
                    }
                case 'Grade':
                    switch (unitToType) {
                        case 'Degré':
                            return unitFrom * 180 / 200;
                        case 'Radian':
                            return unitFrom * Math.PI / 200;
                    }
            }
        }

        function convertDistance(unitFrom, unitFromType, unitToType) {
            unitFrom = parseFloat(unitFrom);
            switch (unitFromType) {
                case 'Nanomètre':
                    switch (unitToType) {
                        case 'Micromètre':
                            return unitFrom / 1000;
                        case 'Millimètre':
                            return unitFrom / 1000000;
                        case 'Centimètre':
                            return unitFrom / 10000000;
                        case 'Décimètre':
                            return unitFrom / 100000000;
                        case 'Mètre':
                            return unitFrom / 1000000000;
                        case 'Décamètre':
                            return unitFrom / 10000000000;
                        case 'Hectomètre':
                            return unitFrom / 100000000000;
                        case 'Kilomètre':
                            return unitFrom / 1000000000000;
                        case 'Mile':
                            return unitFrom / 1609344000000000;
                        case 'Yard':
                            return unitFrom / 914400000000;
                        case 'Pied':
                            return unitFrom / 30480000000;
                        case 'Pouce':
                            return unitFrom / 25400000;
                        case 'Mille Marin':
                            return unitFrom / 1852000000000000;
                    }
                case 'Micromètre':
                    switch (unitToType) {
                        case 'Nanomètre':
                            return unitFrom * 1000;
                        case 'Millimètre':
                            return unitFrom / 1000;
                        case 'Centimètre':
                            return unitFrom / 10000;
                        case 'Décimètre':
                            return unitFrom / 100000;
                        case 'Mètre':
                            return unitFrom / 1000000;
                        case 'Décamètre':
                            return unitFrom / 10000000;
                        case 'Hectomètre':
                            return unitFrom / 100000000;
                        case 'Kilomètre':
                            return unitFrom / 1000000000;
                        case 'Mile':
                            return unitFrom / 1609344000000;
                        case 'Yard':
                            return unitFrom / 9144000000;
                        case 'Pied':
                            return unitFrom / 3048000;
                        case 'Pouce':
                            return unitFrom / 25400;
                        case 'Mille Marin':
                            return unitFrom / 1852000000000;
                    }
                case 'Millimètre':
                    switch (unitToType) {
                        case 'Nanomètre':
                            return unitFrom * 1000000;
                        case 'Micromètre':
                            return unitFrom * 1000;
                        case 'Centimètre':
                            return unitFrom / 10;
                        case 'Décimètre':
                            return unitFrom / 100;
                        case 'Mètre':
                            return unitFrom / 1000;
                        case 'Décamètre':
                            return unitFrom / 10000;
                        case 'Hectomètre':
                            return unitFrom / 100000;
                        case 'Kilomètre':
                            return unitFrom / 1000000;
                        case 'Mile':
                            return unitFrom / 1609340000;
                        case 'Yard':
                            return unitFrom / 914400;
                        case 'Pied':
                            return unitFrom / 30480;
                        case 'Pouce':
                            return unitFrom / 25.4;
                        case 'Mille Marin':
                            return unitFrom / 1852000;
                    }
                case 'Centimètre':
                    switch (unitToType) {
                        case 'Nanomètre':
                            return unitFrom * 10000000;
                        case 'Micromètre':
                            return unitFrom * 10000;
                        case 'Millimètre':
                            return unitFrom * 10;
                        case 'Décimètre':
                            return unitFrom / 10;
                        case 'Mètre':
                            return unitFrom / 100;
                        case 'Décamètre':
                            return unitFrom / 1000;
                        case 'Hectomètre':
                            return unitFrom / 10000;
                        case 'Kilomètre':
                            return unitFrom / 100000;
                        case 'Mile':
                            return unitFrom / 160934;
                        case 'Yard':
                            return unitFrom / 91.44;
                        case 'Pied':
                            return unitFrom / 30.48;
                        case 'Pouce':
                            return unitFrom / 2.54;
                        case 'Mille Marin':
                            return unitFrom / 185200;
                    }
                case 'Décimètre':
                    switch (unitToType) {
                        case 'Nanomètre':
                            return unitFrom * 100000000;
                        case 'Micromètre':
                            return unitFrom * 100000;
                        case 'Millimètre':
                            return unitFrom * 100;
                        case 'Centimètre':
                            return unitFrom * 10;
                        case 'Mètre':
                            return unitFrom / 10;
                        case 'Décamètre':
                            return unitFrom / 100;
                        case 'Hectomètre':
                            return unitFrom / 1000;
                        case 'Kilomètre':
                            return unitFrom / 10000;
                        case 'Mile':
                            return unitFrom / 16093.4;
                        case 'Yard':
                            return unitFrom / 9.144;
                        case 'Pied':
                            return unitFrom / 3.048;
                        case 'Pouce':
                            return unitFrom / 0.254;
                        case 'Mille Marin':
                            return unitFrom / 18520;
                    }
                case 'Mètre':
                    switch (unitToType) {
                        case 'Nanomètre':
                            return unitFrom * 1000000000;
                        case 'Micromètre':
                            return unitFrom * 1000000;
                        case 'Millimètre':
                            return unitFrom * 1000;
                        case 'Centimètre':
                            return unitFrom * 100;
                        case 'Décimètre':
                            return unitFrom * 10;
                        case 'Décamètre':
                            return unitFrom / 10;
                        case 'Hectomètre':
                            return unitFrom / 100;
                        case 'Kilomètre':
                            return unitFrom / 1000;
                        case 'Mile':
                            return unitFrom / 1609.34;
                        case 'Yard':
                            return unitFrom / 0.9144;
                        case 'Pied':
                            return unitFrom / 0.3048;
                        case 'Pouce':
                            return unitFrom / 0.0254;
                        case 'Mille Marin':
                            return unitFrom / 1852;
                    }
                case 'Décamètre':
                    switch (unitToType) {
                        case 'Nanomètre':
                            return unitFrom * 10000000000;
                        case 'Micromètre':
                            return unitFrom * 10000000;
                        case 'Millimètre':
                            return unitFrom * 10000;
                        case 'Centimètre':
                            return unitFrom * 1000;
                        case 'Décimètre':
                            return unitFrom * 100;
                        case 'Mètre':
                            return unitFrom * 10;
                        case 'Hectomètre':
                            return unitFrom / 10;
                        case 'Kilomètre':
                            return unitFrom / 100;
                        case 'Mile':
                            return unitFrom / 160.934;
                        case 'Yard':
                            return unitFrom / 9.144;
                        case 'Pied':
                            return unitFrom / 3.048;
                        case 'Pouce':
                            return unitFrom / 0.254;
                        case 'Mille Marin':
                            return unitFrom / 185.2;
                    }
                case 'Hectomètre':
                    switch (unitToType) {
                        case 'Nanomètre':
                            return unitFrom * 100000000000;
                        case 'Micromètre':
                            return unitFrom * 100000000;
                        case 'Millimètre':
                            return unitFrom * 100000;
                        case 'Centimètre':
                            return unitFrom * 10000;
                        case 'Décimètre':
                            return unitFrom * 1000;
                        case 'Mètre':
                            return unitFrom * 100;
                        case 'Décamètre':
                            return unitFrom * 10;
                        case 'Kilomètre':
                            return unitFrom / 10;
                        case 'Mile':
                            return unitFrom / 16.0934;
                        case 'Yard':
                            return unitFrom / 0.9144;
                        case 'Pied':
                            return unitFrom / 0.3048;
                        case 'Pouce':
                            return unitFrom / 0.0254;
                        case 'Mille Marin':
                            return unitFrom / 18.52;
                    }
                case 'Kilomètre':
                    switch (unitToType) {
                        case 'Nanomètre':
                            return unitFrom * 1000000000000;
                        case 'Micromètre':
                            return unitFrom * 1000000000;
                        case 'Millimètre':
                            return unitFrom * 1000000;
                        case 'Centimètre':
                            return unitFrom * 100000;
                        case 'Décimètre':
                            return unitFrom * 10000;
                        case 'Mètre':
                            return unitFrom * 1000;
                        case 'Décamètre':
                            return unitFrom * 100;
                        case 'Hectomètre':
                            return unitFrom * 10;
                        case 'Mile':
                            return unitFrom / 1.60934;
                        case 'Yard':
                            return unitFrom / 0.9144;
                        case 'Pied':
                            return unitFrom / 0.3048;
                        case 'Pouce':
                            return unitFrom / 0.0254;
                        case 'Mille Marin':
                            return unitFrom / 1.852;
                    }
                case 'Mile':
                    switch (unitToType) {
                        case 'Nanomètre':
                            return unitFrom * 1609340000000;
                        case 'Micromètre':
                            return unitFrom * 1609340000;
                        case 'Millimètre':
                            return unitFrom * 1609340;
                        case 'Centimètre':
                            return unitFrom * 16093.4;
                        case 'Décimètre':
                            return unitFrom * 1609.34;
                        case 'Mètre':
                            return unitFrom * 1609.34;
                        case 'Décamètre':
                            return unitFrom * 160.934;
                        case 'Hectomètre':
                            return unitFrom * 16.0934;
                        case 'Kilomètre':
                            return unitFrom / 1.60934;
                        case 'Yard':
                            return unitFrom / 0.000568181818;
                        case 'Pied':
                            return unitFrom / 0.000304800000;
                        case 'Pouce':
                            return unitFrom / 0.000025400000;
                        case 'Mille Marin':
                            return unitFrom / 0.868976;
                    }
                case 'Yard':
                    switch (unitToType) {
                        case 'Nanomètre':
                            return unitFrom * 914400000000;
                        case 'Micromètre':
                            return unitFrom * 9144000000;
                        case 'Millimètre':
                            return unitFrom * 9144000;
                        case 'Centimètre':
                            return unitFrom * 9144;
                        case 'Décimètre':
                            return unitFrom * 91.44;
                        case 'Mètre':
                            return unitFrom * 0.9144;
                        case 'Décamètre':
                            return unitFrom / 10.9361;
                        case 'Hectomètre':
                            return unitFrom / 109.36;
                        case 'Kilomètre':
                            return unitFrom / 1093.61;
                        case 'Mile':
                            return unitFrom / 0.000568181818;
                        case 'Pied':
                            return unitFrom / 0.000304800000;
                        case 'Pouce':
                            return unitFrom / 0.000025400000;
                        case 'Mille Marin':
                            return unitFrom / 0.994059;
                    }
                case 'Pied':
                    switch (unitToType) {
                        case 'Nanomètre':
                            return unitFrom * 304800000000;
                        case 'Micromètre':
                            return unitFrom * 3048000000;
                        case 'Millimètre':
                            return unitFrom * 3048000;
                        case 'Centimètre':
                            return unitFrom * 3048;
                        case 'Décimètre':
                            return unitFrom * 30.48;
                        case 'Mètre':
                            return unitFrom * 0.3048;
                        case 'Décamètre':
                            return unitFrom / 32.808399;
                        case 'Hectomètre':
                            return unitFrom / 328.08399;
                        case 'Kilomètre':
                            return unitFrom / 3280.8399;
                        case 'Mile':
                            return unitFrom / 0.000189393939;
                        case 'Yard':
                            return unitFrom / 0.000546806649;
                        case 'Pouce':
                            return unitFrom / 0.000083333333;
                        case 'Mille Marin':
                            return unitFrom / 0.994059;
                    }
                case 'Pouce':
                    switch (unitToType) {
                        case 'Nanomètre':
                            return unitFrom * 2540000000;
                        case 'Micromètre':
                            return unitFrom * 25400000;
                        case 'Millimètre':
                            return unitFrom * 254;
                        case 'Centimètre':
                            return unitFrom * 2.54;
                        case 'Décimètre':
                            return unitFrom * 0.0254;
                        case 'Mètre':
                            return unitFrom * 0.0254;
                        case 'Décamètre':
                            return unitFrom / 39.3700787;
                        case 'Hectomètre':
                            return unitFrom / 393.700787;
                        case 'Kilomètre':
                            return unitFrom / 3937.00787;
                        case 'Mile':
                            return unitFrom / 0.000015783;
                        case 'Yard':
                            return unitFrom / 0.000027777778;
                        case 'Pied':
                            return unitFrom / 0.083333333;
                        case 'Mille Marin':
                            return unitFrom / 0.998398398;
                    }
                case 'Mille Marin':
                    switch (unitToType) {
                        case 'Nanomètre':
                            return unitFrom * 2519999990000;
                        case 'Micromètre':
                            return unitFrom * 25199999900;
                        case 'Millimètre':
                            return unitFrom * 25199999;
                        case 'Centimètre':
                            return unitFrom * 25199.9999;
                        case 'Décimètre':
                            return unitFrom * 2.51999999;
                        case 'Mètre':
                            return unitFrom * 0.0251999999;
                        case 'Décamètre':
                            return unitFrom / 39.3700787;
                        case 'Hectomètre':
                            return unitFrom / 393.700787;
                        case 'Kilomètre':
                            return unitFrom / 3937.00787;
                        case 'Mile':
                            return unitFrom / 0.000015783;
                        case 'Yard':
                            return unitFrom / 0.000027777778;
                        case 'Pied':
                            return unitFrom / 0.083333333;
                        case 'Pouce':
                            return unitFrom / 0.998398398;
                    }
            }
        }

        function convertMass(unitFrom, unitFromType, unitToType) {
            unitFrom = parseFloat(unitFrom);
            switch (unitFromType) {
                case 'Nanogramme':
                    switch (unitToType) {
                        case 'Microgramme':
                            return unitFrom / 1000;
                        case 'Milligramme':
                            return unitFrom / 1000000;
                        case 'Centigramme':
                            return unitFrom / 10000000;
                        case 'Décigramme':
                            return unitFrom / 100000000;
                        case 'Gramme':
                            return unitFrom / 1000000000;
                        case 'Décagramme':
                            return unitFrom / 10000000000;
                        case 'Hectogramme':
                            return unitFrom / 100000000000;
                        case 'Kilogramme':
                            return unitFrom / 1000000000000;
                        case 'Tone':
                            return unitFrom / 1000000000000000;
                        case 'Stone':
                            return unitFrom / 6350293180000;
                        case 'Livre':
                            return unitFrom / 453592370000;
                        case 'Once':
                            return unitFrom / 28349523000;
                    }
                case 'Microgramme':
                    switch (unitToType) {
                        case 'Nanogramme':
                            return unitFrom * 1000;
                        case 'Milligramme':
                            return unitFrom / 1000;
                        case 'Centigramme':
                            return unitFrom / 10000;
                        case 'Décigramme':
                            return unitFrom / 100000;
                        case 'Gramme':
                            return unitFrom / 1000000;
                        case 'Décagramme':
                            return unitFrom / 10000000;
                        case 'Hectogramme':
                            return unitFrom / 100000000;
                        case 'Kilogramme':
                            return unitFrom / 1000000000;
                        case 'Tone':
                            return unitFrom / 1000000000000;
                        case 'Stone':
                            return unitFrom / 6350293180;
                        case 'Livre':
                            return unitFrom / 453592370;
                        case 'Once':
                            return unitFrom / 28349.523;
                    }
                case 'Milligramme':
                    switch (unitToType) {
                        case 'Nanogramme':
                            return unitFrom * 1000000;
                        case 'Microgramme':
                            return unitFrom * 1000;
                        case 'Centigramme':
                            return unitFrom / 10;
                        case 'Décigramme':
                            return unitFrom / 100;
                        case 'Gramme':
                            return unitFrom / 1000;
                        case 'Décagramme':
                            return unitFrom / 10000;
                        case 'Hectogramme':
                            return unitFrom / 100000;
                        case 'Kilogramme':
                            return unitFrom / 1000000;
                        case 'Tone':
                            return unitFrom / 1000000000;
                        case 'Stone':
                            return unitFrom / 635029318;
                        case 'Livre':
                            return unitFrom / 453592.37;
                        case 'Once':
                            return unitFrom / 2834.9523;
                    }
                case 'Centigramme':
                    switch (unitToType) {
                        case 'Nanogramme':
                            return unitFrom * 10000000;
                        case 'Microgramme':
                            return unitFrom * 10000;
                        case 'Milligramme':
                            return unitFrom * 10;
                        case 'Décigramme':
                            return unitFrom / 10;
                        case 'Gramme':
                            return unitFrom / 100;
                        case 'Décagramme':
                            return unitFrom / 1000;
                        case 'Hectogramme':
                            return unitFrom / 10000;
                        case 'Kilogramme':
                            return unitFrom / 100000;
                        case 'Tone':
                            return unitFrom / 100000000;
                        case 'Stone':
                            return unitFrom / 63502931.8;
                        case 'Livre':
                            return unitFrom / 45359.237;
                        case 'Once':
                            return unitFrom / 283.49523;
                    }
                case 'Décigramme':
                    switch (unitToType) {
                        case 'Nanogramme':
                            return unitFrom * 100000000;
                        case 'Microgramme':
                            return unitFrom * 100000;
                        case 'Milligramme':
                            return unitFrom * 100;
                        case 'Centigramme':
                            return unitFrom * 10;
                        case 'Gramme':
                            return unitFrom / 10;
                        case 'Décagramme':
                            return unitFrom / 100;
                        case 'Hectogramme':
                            return unitFrom / 1000;
                        case 'Kilogramme':
                            return unitFrom / 10000;
                        case 'Tone':
                            return unitFrom / 10000000;
                        case 'Stone':
                            return unitFrom / 6350293.18;
                        case 'Livre':
                            return unitFrom / 4535.9237;
                        case 'Once':
                            return unitFrom / 28.349523;
                    }
                case 'Gramme':
                    switch (unitToType) {
                        case 'Nanogramme':
                            return unitFrom * 1000000000;
                        case 'Microgramme':
                            return unitFrom * 1000000;
                        case 'Milligramme':
                            return unitFrom * 1000;
                        case 'Centigramme':
                            return unitFrom * 100;
                        case 'Décigramme':
                            return unitFrom * 10;
                        case 'Décagramme':
                            return unitFrom / 10;
                        case 'Hectogramme':
                            return unitFrom / 100;
                        case 'Kilogramme':
                            return unitFrom / 1000;
                        case 'Tone':
                            return unitFrom / 1000000;
                        case 'Stone':
                            return unitFrom / 6350.29318;
                        case 'Livre':
                            return unitFrom / 453.59237;
                        case 'Once':
                            return unitFrom / 28.349523;
                    }
                case 'Décagramme':
                    switch (unitToType) {
                        case 'Nanogramme':
                            return unitFrom * 10000000000;
                        case 'Microgramme':
                            return unitFrom * 10000000;
                        case 'Milligramme':
                            return unitFrom * 10000;
                        case 'Centigramme':
                            return unitFrom * 1000;
                        case 'Décigramme':
                            return unitFrom * 100;
                        case 'Gramme':
                            return unitFrom * 10;
                        case 'Hectogramme':
                            return unitFrom / 10;
                        case 'Kilogramme':
                            return unitFrom / 100;
                        case 'Tone':
                            return unitFrom / 100000;
                        case 'Stone':
                            return unitFrom / 63502.9318;
                        case 'Livre':
                            return unitFrom / 4535.9237;
                        case 'Once':
                            return unitFrom / 28.349523;
                    }
                case 'Hectogramme':
                    switch (unitToType) {
                        case 'Nanogramme':
                            return unitFrom * 100000000000;
                        case 'Microgramme':
                            return unitFrom * 100000000;
                        case 'Milligramme':
                            return unitFrom * 100000;
                        case 'Centigramme':
                            return unitFrom * 10000;
                        case 'Décigramme':
                            return unitFrom * 1000;
                        case 'Gramme':
                            return unitFrom * 100;
                        case 'Décagramme':
                            return unitFrom * 10;
                        case 'Kilogramme':
                            return unitFrom / 10;
                        case 'Tone':
                            return unitFrom / 10000;
                        case 'Stone':
                            return unitFrom / 635029.318;
                        case 'Livre':
                            return unitFrom / 45359.237;
                        case 'Once':
                            return unitFrom / 28.349523;
                    }
                case 'Kilogramme':
                    switch (unitToType) {
                        case 'Nanogramme':
                            return unitFrom * 1000000000000;
                        case 'Microgramme':
                            return unitFrom * 1000000000;
                        case 'Milligramme':
                            return unitFrom * 1000000;
                        case 'Centigramme':
                            return unitFrom * 100000;
                        case 'Décigramme':
                            return unitFrom * 10000;
                        case 'Gramme':
                            return unitFrom * 1000;
                        case 'Décagramme':
                            return unitFrom * 100;
                        case 'Hectogramme':
                            return unitFrom * 10;
                        case 'Tone':
                            return unitFrom / 1000;
                        case 'Stone':
                            return unitFrom / 63502.9318;
                        case 'Livre':
                            return unitFrom / 4535.9237;
                        case 'Once':
                            return unitFrom / 28.349523;
                    }
                case 'Tone':
                    switch (unitToType) {
                        case 'Nanogramme':
                            return unitFrom * 1000000000000000;
                        case 'Microgramme':
                            return unitFrom * 1000000000000;
                        case 'Milligramme':
                            return unitFrom * 1000000000;
                        case 'Centigramme':
                            return unitFrom * 10000000;
                        case 'Décigramme':
                            return unitFrom * 1000000;
                        case 'Gramme':
                            return unitFrom * 100000;
                        case 'Décagramme':
                            return unitFrom * 10000;
                        case 'Hectogramme':
                            return unitFrom * 1000;
                        case 'Kilogramme':
                            return unitFrom * 100;
                        case 'Stone':
                            return unitFrom / 6.35029318;
                        case 'Livre':
                            return unitFrom / 45.359237;
                        case 'Once':
                            return unitFrom / 2.8349523;
                    }
                case 'Stone':
                    switch (unitToType) {
                        case 'Nanogramme':
                            return unitFrom * 6350293180000;
                        case 'Microgramme':
                            return unitFrom * 6350293180;
                        case 'Milligramme':
                            return unitFrom * 635029.318;
                        case 'Centigramme':
                            return unitFrom * 6350.29318;
                        case 'Décigramme':
                            return unitFrom * 635.029318;
                        case 'Gramme':
                            return unitFrom * 6.35029318;
                        case 'Décagramme':
                            return unitFrom * 0.635029318;
                        case 'Hectogramme':
                            return unitFrom / 1.574730438;
                        case 'Kilogramme':
                            return unitFrom / 15.74730438;
                        case 'Tone':
                            return unitFrom / 157.4730438;
                        case 'Livre':
                            return unitFrom / 14;
                        case 'Once':
                            return unitFrom / 0.45359237;
                    }
                case 'Livre':
                    switch (unitToType) {
                        case 'Nanogramme':
                            return unitFrom * 453592370000;
                        case 'Microgramme':
                            return unitFrom * 453592370;
                        case 'Milligramme':
                            return unitFrom * 453592.37;
                        case 'Centigramme':
                            return unitFrom * 4535.9237;
                        case 'Décigramme':
                            return unitFrom * 453.59237;
                        case 'Gramme':
                            return unitFrom * 0.45359237;
                        case 'Décagramme':
                            return unitFrom / 2.20462262;
                        case 'Hectogramme':
                            return unitFrom / 22.0462262;
                        case 'Kilogramme':
                            return unitFrom / 220.462262;
                        case 'Tone':
                            return unitFrom / 2204.62262;
                        case 'Stone':
                            return unitFrom * 0.0714285714;
                        case 'Once':
                            return unitFrom / 16;
                    }
                case 'Once':
                    switch (unitToType) {
                        case 'Nanogramme':
                            return unitFrom * 28349523000;
                        case 'Microgramme':
                            return unitFrom * 28349523;
                        case 'Milligramme':
                            return unitFrom * 28349.523;
                        case 'Centigramme':
                            return unitFrom * 2834.9523;
                        case 'Décigramme':
                            return unitFrom * 283.49523;
                        case 'Gramme':
                            return unitFrom * 0.28349523;
                        case 'Décagramme':
                            return unitFrom / 8.928571429;
                        case 'Hectogramme':
                            return unitFrom / 89.28571429;
                        case 'Kilogramme':
                            return unitFrom / 892.8571429;
                        case 'Tone':
                            return unitFrom / 8928.571429;
                        case 'Stone':
                            return unitFrom * 0.0625;
                        case 'Livre':
                            return unitFrom * 0.0625;
                    }
            }
        }

        function convertVolume(unitFrom, unitFromType, unitToType) {
            unitFrom = parseFloat(unitFrom);
            switch (unitFromType) {
                case 'Nanomètre cube':
                    switch (unitToType) {
                        case 'Micromètre cube':
                            return unitFrom / 1e3;
                        case 'Millimètre cube':
                            return unitFrom / 1e9;
                        case 'Centimètre cube':
                            return unitFrom / 1e12;
                        case 'Décimètre cube':
                            return unitFrom / 1e15;
                        case 'Mètre cube':
                            return unitFrom / 1e18;
                        case 'Décamètre cube':
                            return unitFrom / 1e21;
                        case 'Hectomètre cube':
                            return unitFrom / 1e24;
                        case 'Kilomètre cube':
                            return unitFrom / 1e27;
                        case 'Nanolitre':
                            return unitFrom
                        case 'Microlitre':
                            return unitFrom / 1e3;
                        case 'Millilitre':
                            return unitFrom / 1e6;
                        case 'Centilitre':
                            return unitFrom / 1e7;
                        case 'Décilitre':
                            return unitFrom / 1e8;
                        case 'Litre':
                            return unitFrom / 1e9;
                        case 'Décalitre':
                            return unitFrom / 1e10;
                        case 'Hectolitre':
                            return unitFrom / 1e11;
                        case 'Kilolitre':
                            return unitFrom / 1e12;
                        case 'Pinte':
                            return unitFrom / (5.68261 * 1e11);
                        case 'Gallon':
                            return unitFrom / (4.54609 * 1e12);
                        case 'Baril':
                            return unitFrom / (1.59 * 1e14);
                    }
                case 'Micromètre cube':
                    switch (unitToType) {
                        case 'Millimètre cube':
                            return unitFrom / 1e6;
                        case 'Centimètre cube':
                            return unitFrom / 1e9;
                        case 'Décimètre cube':
                            return unitFrom / 1e12;
                        case 'Mètre cube':
                            return unitFrom / 1e15;
                        case 'Décamètre cube':
                            return unitFrom / 1e18;
                        case 'Hectomètre cube':
                            return unitFrom / 1e21;
                        case 'Kilomètre cube':
                            return unitFrom / 1e24;
                        case 'Nanolitre':
                            return unitFrom * 1e3;
                        case 'Microlitre':
                            return unitFrom;
                        case 'Millilitre':
                            return unitFrom / 1e3;
                        case 'Centilitre':
                            return unitFrom / 1e4;
                        case 'Décilitre':
                            return unitFrom / 1e5;
                        case 'Litre':
                            return unitFrom / 1e6;
                        case 'Décalitre':
                            return unitFrom / 1e7;
                        case 'Hectolitre':
                            return unitFrom / 1e8;
                        case 'Kilolitre':
                            return unitFrom / 1e9;
                        case 'Pinte':
                            return unitFrom / (5.68261 * 1e8);
                        case 'Gallon':
                            return unitFrom / (4.54609 * 1e9);
                        case 'Baril':
                            return unitFrom / (1.59 * 1e11);
                    }
                case 'Millimètre cube':
                    switch (unitToType) {
                        case 'Nanomètre cube':
                            return unitFrom * 1e9;
                        case 'Micromètre cube':
                            return unitFrom * 1e6;
                        case 'Centimètre cube':
                            return unitFrom / 1e3;
                        case 'Décimètre cube':
                            return unitFrom / 1e6;
                        case 'Mètre cube':
                            return unitFrom / 1e9;
                        case 'Décamètre cube':
                            return unitFrom / 1e12;
                        case 'Hectomètre cube':
                            return unitFrom / 1e15;
                        case 'Kilomètre cube':
                            return unitFrom / 1e18;
                        case 'Nanolitre':
                            return unitFrom * 1e6;
                        case 'Microlitre':
                            return unitFrom * 1e3;
                        case 'Millilitre':
                            return unitFrom;
                        case 'Centilitre':
                            return unitFrom / 10;
                        case 'Décilitre':
                            return unitFrom / 100;
                        case 'Litre':
                            return unitFrom / 1e3;
                        case 'Décalitre':
                            return unitFrom / 1e4;
                        case 'Hectolitre':
                            return unitFrom / 1e5;
                        case 'Kilolitre':
                            return unitFrom / 1e6;
                        case 'Pinte':
                            return unitFrom / 568.261;
                        case 'Gallon':
                            return unitFrom / 4546.09;
                        case 'Baril':
                            return unitFrom / 159000;
                    }
                case 'Centimètre cube':
                    switch (unitToType) {
                        case 'Nanomètre cube':
                            return unitFrom * 1e12;
                        case 'Micromètre cube':
                            return unitFrom * 1e9;
                        case 'Millimètre cube':
                            return unitFrom * 1e3;
                        case 'Décimètre cube':
                            return unitFrom / 1e3;
                        case 'Mètre cube':
                            return unitFrom / 1e6;
                        case 'Décamètre cube':
                            return unitFrom / 1e9;
                        case 'Hectomètre cube':
                            return unitFrom / 1e12;
                        case 'Kilomètre cube':
                            return unitFrom / 1e15;
                        case 'Nanolitre':
                            return unitFrom * 1e9;
                        case 'Microlitre':
                            return unitFrom * 1e6;
                        case 'Millilitre':
                            return unitFrom * 1e3;
                        case 'Centilitre':
                            return unitFrom * 100;
                        case 'Décilitre':
                            return unitFrom * 10;
                        case 'Litre':
                            return unitFrom / 1e3;
                        case 'Décalitre':
                            return unitFrom / 1e4;
                        case 'Hectolitre':
                            return unitFrom / 1e5;
                        case 'Kilolitre':
                            return unitFrom / 1e6;
                        case 'Pinte':
                            return unitFrom / 568.261;
                        case 'Gallon':
                            return unitFrom / 4546.09;
                        case 'Baril':
                            return unitFrom / 159000;
                    }
                case 'Décimètre cube':
                    switch (unitToType) {
                        case 'Nanomètre cube':
                            return unitFrom * 1e15;
                        case 'Micromètre cube':
                            return unitFrom * 1e12;
                        case 'Millimètre cube':
                            return unitFrom * 1e6;
                        case 'Centimètre cube': r
                            return unitFrom * 1e3;
                        case 'Mètre cube':
                            return unitFrom / 1e3;
                        case 'Décamètre cube':
                            return unitFrom / 1e6;
                        case 'Hectomètre cube':
                            return unitFrom / 1e9;
                        case 'Kilomètre cube':
                            return unitFrom / 1e12;
                        case 'Nanolitre':
                            return unitFrom * 1e12;
                        case 'Microlitre':
                            return unitFrom * 1e9;
                        case 'Millilitre':
                            return unitFrom * 1e6;
                        case 'Centilitre':
                            return unitFrom * 1e5;
                        case 'Décilitre':
                            return unitFrom * 1e4;
                        case 'Litre':
                            return unitFrom;
                        case 'Décalitre':
                            return unitFrom * 100;
                        case 'Hectolitre':
                            return unitFrom * 10;
                        case 'Kilolitre':
                            return unitFrom / 1e3;
                        case 'Pinte':
                            return unitFrom * 1.75975;
                        case 'Gallon':
                            return unitFrom * 0.219969;
                        case 'Baril':
                            return unitFrom / 158.987;
                    }
                case 'Mètre cube':
                    switch (unitToType) {
                        case 'Nanomètre cube':
                            return unitFrom * 1e18;
                        case 'Micromètre cube':
                            return unitFrom * 1e15;
                        case 'Millimètre cube':
                            return unitFrom * 1e9;
                        case 'Centimètre cube':
                            return unitFrom * 1e6;
                        case 'Décimètre cube':
                            return unitFrom * 1e3;
                        case 'Décamètre cube':
                            return unitFrom / 1e3;
                        case 'Hectomètre cube':
                            return unitFrom / 1e6;
                        case 'Kilomètre cube':
                            return unitFrom / 1e9;
                        case 'Nanolitre':
                            return unitFrom * 1e15;
                        case 'Microlitre':
                            return unitFrom * 1e12;
                        case 'Millilitre':
                            return unitFrom * 1e9;
                        case 'Centilitre':
                            return unitFrom * 1e8;
                        case 'Décilitre':
                            return unitFrom * 1e7;
                        case 'Litre':
                            return unitFrom * 1e3;
                        case 'Décalitre':
                            return unitFrom * 1e2;
                        case 'Hectolitre':
                            return unitFrom * 1e1;
                        case 'Kilolitre':
                            return unitFrom;
                        case 'Pinte':
                            return unitFrom * 1759.75;
                        case 'Gallon':
                            return unitFrom * 219.969;
                        case 'Baril':
                            return unitFrom * 6.28981;
                    }
                case 'Décamètre cube':
                    switch (unitToType) {
                        case 'Nanomètre cube':
                            return unitFrom * 1e21;
                        case 'Micromètre cube':
                            return unitFrom * 1e18;
                        case 'Millimètre cube':
                            return unitFrom * 1e12;
                        case 'Centimètre cube':
                            return unitFrom * 1e9;
                        case 'Décimètre cube':
                            return unitFrom * 1e6;
                        case 'Mètre cube':
                            return unitFrom * 1e3;
                        case 'Hectomètre cube':
                            return unitFrom / 1e3;
                        case 'Kilomètre cube':
                            return unitFrom / 1e6;
                        case 'Nanolitre':
                            return unitFrom * 1e18;
                        case 'Microlitre':
                            return unitFrom * 1e15;
                        case 'Millilitre':
                            return unitFrom * 1e12;
                        case 'Centilitre':
                            return unitFrom * 1e11;
                        case 'Décilitre':
                            return unitFrom * 1e10;
                        case 'Litre':
                            return unitFrom * 1e6;
                        case 'Décalitre':
                            return unitFrom * 1e5;
                        case 'Hectolitre':
                            return unitFrom * 1e4;
                        case 'Kilolitre':
                            return unitFrom * 1e3;
                        case 'Pinte':
                            return unitFrom * 1_759_750;
                        case 'Gallon':
                            return unitFrom * 219_969;
                        case 'Baril':
                            return unitFrom * 6_289.81;
                    }
                case 'Hectomètre cube':
                    switch (unitToType) {
                        case 'Nanomètre cube':
                            return unitFrom * 1e27;
                        case 'Micromètre cube':
                            return unitFrom * 1e24;
                        case 'Millimètre cube':
                            return unitFrom * 1e15;
                        case 'Centimètre cube':
                            return unitFrom * 1e12;
                        case 'Décimètre cube':
                            return unitFrom * 1e9;
                        case 'Mètre cube':
                            return unitFrom * 1e6;
                        case 'Décamètre cube':
                            return unitFrom * 1e3;
                        case 'Kilomètre cube':
                            return unitFrom / 1e3;
                        case 'Nanolitre':
                            return unitFrom * 1e24;
                        case 'Microlitre':
                            return unitFrom * 1e21;
                        case 'Millilitre':
                            return unitFrom * 1e18;
                        case 'Centilitre':
                            return unitFrom * 1e17;
                        case 'Décilitre':
                            return unitFrom * 1e16;
                        case 'Litre':
                            return unitFrom * 1e9;
                        case 'Décalitre':
                            return unitFrom * 1e8;
                        case 'Hectolitre':
                            return unitFrom * 1e7;
                        case 'Kilolitre':
                            return unitFrom * 1e6;
                        case 'Pinte':
                            return unitFrom * 1.75975e9;
                        case 'Gallon':
                            return unitFrom * 2.19969e8;
                        case 'Baril':
                            return unitFrom * 6.28981e6;
                    }
                case 'Kilomètre cube':
                    switch (unitToType) {
                        case 'Nanomètre cube':
                            return unitFrom * 1e30;
                        case 'Micromètre cube':
                            return unitFrom * 1e27;
                        case 'Millimètre cube':
                            return unitFrom * 1e18;
                        case 'Centimètre cube':
                            return unitFrom * 1e15;
                        case 'Décimètre cube':
                            return unitFrom * 1e12;
                        case 'Mètre cube':
                            return unitFrom * 1e9;
                        case 'Décamètre cube':
                            return unitFrom * 1e6;
                        case 'Hectomètre cube':
                            return unitFrom * 1e3;
                        case 'Nanolitre':
                            return unitFrom * 1e27;
                        case 'Microlitre':
                            return unitFrom * 1e24;
                        case 'Millilitre':
                            return unitFrom * 1e21;
                        case 'Centilitre':
                            return unitFrom * 1e20;
                        case 'Décilitre':
                            return unitFrom * 1e19;
                        case 'Litre':
                            return unitFrom * 1e12;
                        case 'Décalitre':
                            return unitFrom * 1e11;
                        case 'Hectolitre':
                            return unitFrom * 1e10;
                        case 'Kilolitre':
                            return unitFrom * 1e9;
                        case 'Pinte':
                            return unitFrom * 1.75975e12;
                        case 'Gallon':
                            return unitFrom * 2.19969e11;
                        case 'Baril':
                            return unitFrom * 6.28981e9;
                    }
                case 'Nanolitre':
                    switch (unitToType) {
                        case 'Nanomètre cube':
                            return unitFrom * 1e6;
                        case 'Micromètre cube':
                            return unitFrom * 1e3;
                        case 'Millimètre cube':
                            return unitFrom;
                        case 'Centimètre cube':
                            return unitFrom / 1e3;
                        case 'Décimètre cube':
                            return unitFrom / 1e6;
                        case 'Mètre cube':
                            return unitFrom / 1e12;
                        case 'Décamètre cube':
                            return unitFrom / 1e15;
                        case 'Hectomètre cube':
                            return unitFrom / 1e18;
                        case 'Kilomètre cube':
                            return unitFrom / 1e21;
                        case 'Microlitre':
                            return unitFrom / 1e3;
                        case 'Millilitre':
                            return unitFrom / 1e6;
                        case 'Centilitre':
                            return unitFrom / 1e7;
                        case 'Décilitre':
                            return unitFrom / 1e8;
                        case 'Litre':
                            return unitFrom / 1e9;
                        case 'Décalitre':
                            return unitFrom / 1e10;
                        case 'Hectolitre':
                            return unitFrom / 1e11;
                        case 'Kilolitre':
                            return unitFrom / 1e12;
                        case 'Pinte':
                            return unitFrom / 5.6826125e8;
                        case 'Gallon':
                            return unitFrom / 4.54609e9;
                        case 'Baril':
                            return unitFrom / 1.58987e11;
                    }
                case 'Microlitre':
                    switch (unitToType) {
                        case 'Nanomètre cube':
                            return unitFrom * 1e9;
                        case 'Micromètre cube':
                            return unitFrom * 1e6;
                        case 'Millimètre cube':
                            return unitFrom * 1e3;
                        case 'Centimètre cube':
                            return unitFrom;
                        case 'Décimètre cube':
                            return unitFrom / 1e3;
                        case 'Mètre cube':
                            return unitFrom / 1e9;
                        case 'Décamètre cube':
                            return unitFrom / 1e12;
                        case 'Hectomètre cube':
                            return unitFrom / 1e15;
                        case 'Kilomètre cube':
                            return unitFrom / 1e18;
                        case 'Nanolitre':
                            return unitFrom * 1e3;
                        case 'Millilitre':
                            return unitFrom / 1e3;
                        case 'Centilitre':
                            return unitFrom / 1e4;
                        case 'Décilitre':
                            return unitFrom / 1e5;
                        case 'Litre':
                            return unitFrom / 1e6;
                        case 'Décalitre':
                            return unitFrom / 1e7;
                        case 'Hectolitre':
                            return unitFrom / 1e8;
                        case 'Kilolitre':
                            return unitFrom / 1e9;
                        case 'Pinte':
                            return unitFrom / 568261.25;
                        case 'Gallon':
                            return unitFrom / 4546090;
                        case 'Baril':
                            return unitFrom / 1.58987e8;
                    }
                case 'Millilitre':
                    switch (unitToType) {
                        case 'Nanomètre cube':
                            return unitFrom * 1e12;
                        case 'Micromètre cube':
                            return unitFrom * 1e9;
                        case 'Millimètre cube':
                            return unitFrom * 1e6;
                        case 'Centimètre cube':
                            return unitFrom * 1e3;
                        case 'Décimètre cube':
                            return unitFrom;
                        case 'Mètre cube':
                            return unitFrom / 1e6;
                        case 'Décamètre cube':
                            return unitFrom / 1e9;
                        case 'Hectomètre cube':
                            return unitFrom / 1e12;
                        case 'Kilomètre cube':
                            return unitFrom / 1e15;
                        case 'Nanolitre':
                            return unitFrom * 1e6;
                        case 'Microlitre':
                            return unitFrom * 1e3;
                        case 'Centilitre':
                            return unitFrom / 1e1;
                        case 'Décilitre':
                            return unitFrom / 1e2;
                        case 'Litre':
                            return unitFrom / 1e3;
                        case 'Décalitre':
                            return unitFrom / 1e4;
                        case 'Hectolitre':
                            return unitFrom / 1e5;
                        case 'Kilolitre':
                            return unitFrom / 1e6;
                        case 'Pinte':
                            return unitFrom / 568.26125;
                        case 'Gallon':
                            return unitFrom / 4546.09;
                        case 'Baril':
                            return unitFrom / 158987.3;
                    }
                case 'Centilitre':
                    switch (unitToType) {
                        case 'Nanomètre cube':
                            return unitFrom * 1e13;
                        case 'Micromètre cube':
                            return unitFrom * 1e10;
                        case 'Millimètre cube':
                            return unitFrom * 1e7;
                        case 'Centimètre cube':
                            return unitFrom * 1e4;
                        case 'Décimètre cube':
                            return unitFrom * 1e1;
                        case 'Mètre cube':
                            return unitFrom / 1e5;
                        case 'Décamètre cube':
                            return unitFrom / 1e8;
                        case 'Hectomètre cube':
                            return unitFrom / 1e11;
                        case 'Kilomètre cube':
                            return unitFrom / 1e14;
                        case 'Nanolitre':
                            return unitFrom * 1e7;
                        case 'Microlitre':
                            return unitFrom * 1e4;
                        case 'Millilitre':
                            return unitFrom * 1e1;
                        case 'Décilitre':
                            return unitFrom / 1e1;
                        case 'Litre':
                            return unitFrom / 1e2;
                        case 'Décalitre':
                            return unitFrom / 1e3;
                        case 'Hectolitre':
                            return unitFrom / 1e4;
                        case 'Kilolitre':
                            return unitFrom / 1e5;
                        case 'Pinte':
                            return unitFrom / 56.826125;
                        case 'Gallon':
                            return unitFrom / 454.609;
                        case 'Baril':
                            return unitFrom / 15898.73;
                    }
                case 'Décilitre':
                    switch (unitToType) {
                        case 'Nanomètre cube':
                            return unitFrom * 1e14;
                        case 'Micromètre cube':
                            return unitFrom * 1e11;
                        case 'Millimètre cube':
                            return unitFrom * 1e8;
                        case 'Centimètre cube':
                            return unitFrom * 1e5;
                        case 'Décimètre cube':
                            return unitFrom * 1e2;
                        case 'Mètre cube':
                            return unitFrom / 1e4;
                        case 'Décamètre cube':
                            return unitFrom / 1e7;
                        case 'Hectomètre cube':
                            return unitFrom / 1e10;
                        case 'Kilomètre cube':
                            return unitFrom / 1e13;
                        case 'Nanolitre':
                            return unitFrom * 1e8;
                        case 'Microlitre':
                            return unitFrom * 1e5;
                        case 'Millilitre':
                            return unitFrom * 1e2;
                        case 'Centilitre':
                            return unitFrom * 1e1;
                        case 'Litre':
                            return unitFrom / 1e1;
                        case 'Décalitre':
                            return unitFrom / 1e2;
                        case 'Hectolitre':
                            return unitFrom / 1e3;
                        case 'Kilolitre':
                            return unitFrom / 1e4;
                        case 'Pinte':
                            return unitFrom / 5.6826125;
                        case 'Gallon':
                            return unitFrom / 45.4609;
                        case 'Baril':
                            return unitFrom / 1589.873;
                    }
                case 'Litre':
                    switch (unitToType) {
                        case 'Nanomètre cube':
                            return unitFrom * 1e15;
                        case 'Micromètre cube':
                            return unitFrom * 1e12;
                        case 'Millimètre cube':
                            return unitFrom * 1e9;
                        case 'Centimètre cube':
                            return unitFrom * 1e6;
                        case 'Décimètre cube':
                            return unitFrom * 1e3;
                        case 'Mètre cube':
                            return unitFrom / 1e3;
                        case 'Décamètre cube':
                            return unitFrom / 1e6;
                        case 'Hectomètre cube':
                            return unitFrom / 1e9;
                        case 'Kilomètre cube':
                            return unitFrom / 1e12;
                        case 'Nanolitre':
                            return unitFrom * 1e9;
                        case 'Microlitre':
                            return unitFrom * 1e6;
                        case 'Millilitre':
                            return unitFrom * 1e3;
                        case 'Centilitre':
                            return unitFrom * 1e2;
                        case 'Décilitre':
                            return unitFrom * 1e1;
                        case 'Décalitre':
                            return unitFrom / 1e1;
                        case 'Hectolitre':
                            return unitFrom / 1e2;
                        case 'Kilolitre':
                            return unitFrom / 1e3;
                        case 'Pinte':
                            return unitFrom / 0.56826125;
                        case 'Gallon':
                            return unitFrom / 4.54609;
                        case 'Baril':
                            return unitFrom / 158.9873;
                    }
                case 'Décalitre':
                    switch (unitToType) {
                        case 'Nanomètre cube':
                            return unitFrom * 1e16;
                        case 'Micromètre cube':
                            return unitFrom * 1e13;
                        case 'Millimètre cube':
                            return unitFrom * 1e10;
                        case 'Centimètre cube':
                            return unitFrom * 1e7;
                        case 'Décimètre cube':
                            return unitFrom * 1e4;
                        case 'Mètre cube':
                            return unitFrom / 1e2;
                        case 'Décamètre cube':
                            return unitFrom / 1e5;
                        case 'Hectomètre cube':
                            return unitFrom / 1e8;
                        case 'Kilomètre cube':
                            return unitFrom / 1e11;
                        case 'Nanolitre':
                            return unitFrom * 1e10;
                        case 'Microlitre':
                            return unitFrom * 1e7;
                        case 'Millilitre':
                            return unitFrom * 1e4;
                        case 'Centilitre':
                            return unitFrom * 1e3;
                        case 'Décilitre':
                            return unitFrom * 1e2;
                        case 'Litre':
                            return unitFrom * 1e1;
                        case 'Hectolitre':
                            return unitFrom / 1e1;
                        case 'Kilolitre':
                            return unitFrom / 1e2;
                        case 'Pinte':
                            return unitFrom / 0.056826125;
                        case 'Gallon':
                            return unitFrom / 0.454609;
                        case 'Baril':
                            return unitFrom / 15.89873;
                    }
                case 'Hectolitre':
                    switch (unitToType) {
                        case 'Nanomètre cube':
                            return unitFrom * 1e17;
                        case 'Micromètre cube':
                            return unitFrom * 1e14;
                        case 'Millimètre cube':
                            return unitFrom * 1e11;
                        case 'Centimètre cube':
                            return unitFrom * 1e8;
                        case 'Décimètre cube':
                            return unitFrom * 1e5;
                        case 'Mètre cube':
                            return unitFrom / 1e1;
                        case 'Décamètre cube':
                            return unitFrom / 1e4;
                        case 'Hectomètre cube':
                            return unitFrom / 1e7;
                        case 'Kilomètre cube':
                            return unitFrom / 1e10;
                        case 'Nanolitre':
                            return unitFrom * 1e11;
                        case 'Microlitre':
                            return unitFrom * 1e8;
                        case 'Millilitre':
                            return unitFrom * 1e5;
                        case 'Centilitre':
                            return unitFrom * 1e4;
                        case 'Décilitre':
                            return unitFrom * 1e3;
                        case 'Litre':
                            return unitFrom * 1e2;
                        case 'Décalitre':
                            return unitFrom * 1e1;
                        case 'Kilolitre':
                            return unitFrom / 1e1;
                        case 'Pinte':
                            return unitFrom / 0.0056826125;
                        case 'Gallon':
                            return unitFrom / 0.0454609;
                        case 'Baril':
                            return unitFrom / 1.589873;
                    }
                case 'Kilolitre':
                    switch (unitToType) {
                        case 'Nanomètre cube':
                            return unitFrom * 1e18;
                        case 'Micromètre cube':
                            return unitFrom * 1e15;
                        case 'Millimètre cube':
                            return unitFrom * 1e12;
                        case 'Centimètre cube':
                            return unitFrom * 1e9;
                        case 'Décimètre cube':
                            return unitFrom * 1e6;
                        case 'Mètre cube':
                            return unitFrom;
                        case 'Décamètre cube':
                            return unitFrom / 1e3;
                        case 'Hectomètre cube':
                            return unitFrom / 1e6;
                        case 'Kilomètre cube':
                            return unitFrom / 1e9;
                        case 'Nanolitre':
                            return unitFrom * 1e12;
                        case 'Microlitre':
                            return unitFrom * 1e9;
                        case 'Millilitre':
                            return unitFrom * 1e6;
                        case 'Centilitre':
                            return unitFrom * 1e5;
                        case 'Décilitre':
                            return unitFrom * 1e4;
                        case 'Litre':
                            return unitFrom * 1e3;
                        case 'Décalitre':
                            return unitFrom * 1e2;
                        case 'Hectolitre':
                            return unitFrom * 1e1;
                        case 'Pinte':
                            return unitFrom / 0.00056826125;
                        case 'Gallon':
                            return unitFrom / 0.00454609;
                        case 'Baril':
                            return unitFrom / 0.1589873;
                    }
                case 'Pinte':
                    switch (unitToType) {
                        case 'Nanomètre cube':
                            return unitFrom * 5.6826125e14;
                        case 'Micromètre cube':
                            return unitFrom * 5.6826125e11;
                        case 'Millimètre cube':
                            return unitFrom * 5.6826125e8;
                        case 'Centimètre cube':
                            return unitFrom * 5.6826125e5;
                        case 'Décimètre cube':
                            return unitFrom * 568.26125;
                        case 'Mètre cube':
                            return unitFrom / 1.75975e3;
                        case 'Décamètre cube':
                            return unitFrom / 1.75975e6;
                        case 'Hectomètre cube':
                            return unitFrom / 1.75975e9;
                        case 'Kilomètre cube':
                            return unitFrom / 1.75975e12;
                        case 'Nanolitre':
                            return unitFrom * 5.6826125e8;
                        case 'Microlitre':
                            return unitFrom * 5.6826125e5;
                        case 'Millilitre':
                            return unitFrom * 568.26125;
                        case 'Centilitre':
                            return unitFrom * 56.826125;
                        case 'Décilitre':
                            return unitFrom * 5.6826125;
                        case 'Litre':
                            return unitFrom / 1.75975;
                        case 'Décalitre':
                            return unitFrom / 17.5975;
                        case 'Hectolitre':
                            return unitFrom / 175.975;
                        case 'Kilolitre':
                            return unitFrom / 1759.75;
                        case 'Gallon':
                            return unitFrom / 8;
                        case 'Baril':
                            return unitFrom / 31.5;
                    }
                case 'Gallon':
                    switch (unitToType) {
                        case 'Nanomètre cube':
                            return unitFrom * 4.54609e15;
                        case 'Micromètre cube':
                            return unitFrom * 4.54609e12;
                        case 'Millimètre cube':
                            return unitFrom * 4.54609e9;
                        case 'Centimètre cube':
                            return
                    }
                case 'Baril':
                    switch (unitToType) {
                        case 'Nanomètre cube':
                            return unitFrom * 1.58987e14;
                        case 'Micromètre cube':
                            return unitFrom * 1.58987e11;
                        case 'Millimètre cube':
                            return unitFrom * 1.58987e8;
                        case 'Centimètre cube':
                            return unitFrom * 1.58987e5;
                        case 'Décimètre cube':
                            return unitFrom * 158.987;
                        case 'Mètre cube':
                            return unitFrom / 6.28981;
                        case 'Décamètre cube':
                            return unitFrom / 6.28981e3;
                        case 'Hectomètre cube':
                            return unitFrom / 6.28981e6;
                        case 'Kilomètre cube':
                            return unitFrom / 6.28981e9;
                        case 'Nanolitre':
                            return unitFrom * 1.58987e8;
                        case 'Microlitre':
                            return unitFrom * 1.58987e5;
                        case 'Millilitre':
                            return unitFrom * 158987.3;
                        case 'Centilitre':
                            return unitFrom * 15898.73;
                        case 'Décilitre':
                            return unitFrom * 1589.873;
                        case 'Litre':
                            return unitFrom * 158.9873;
                        case 'Décalitre':
                            return unitFrom * 15.89873;
                        case 'Hectolitre':
                            return unitFrom * 1.589873;
                        case 'Kilolitre':
                            return unitFrom / 6.28981;
                        case 'Pinte':
                            return unitFrom * 31.5;
                        case 'Gallon':
                            return unitFrom * 42;
                    }
            }
        }

        function convertSuperficie(unitFrom, unitFromType, unitToType) {
            unitFrom = parseFloat(unitFrom);
            switch (unitFromType) {
                case 'Nanomètre carré':
                    switch (unitToType) {
                        case 'Micromètre carré':
                            return unitFrom / 1e6;
                        case 'Millimètre carré':
                            return unitFrom / 1e12;
                        case 'Centimètre carré':
                            return unitFrom / 1e14;
                        case 'Décimètre carré':
                            return unitFrom / 1e16;
                        case 'Mètre carré':
                            return unitFrom / 1e18;
                        case 'Décamètre carré':
                            return unitFrom / 1e20;
                        case 'Hectomètre carré':
                            return unitFrom / 1e22;
                        case 'Kilomètre carré':
                            return unitFrom / 1e24;
                    }

                case 'Micromètre carré':
                    switch (unitToType) {
                        case 'Nanomètre carré':
                            return unitFrom * 1e6;
                        case 'Millimètre carré':
                            return unitFrom / 1e6;
                        case 'Centimètre carré':
                            return unitFrom / 1e8;
                        case 'Décimètre carré':
                            return unitFrom / 1e10;
                        case 'Mètre carré':
                            return unitFrom / 1e12;
                        case 'Décamètre carré':
                            return unitFrom / 1e14;
                        case 'Hectomètre carré':
                            return unitFrom / 1e16;
                        case 'Kilomètre carré':
                            return unitFrom / 1e18;
                    }

                case 'Millimètre carré':
                    switch (unitToType) {
                        case 'Nanomètre carré':
                            return unitFrom * 1e12;
                        case 'Micromètre carré':
                            return unitFrom * 1e6;
                        case 'Centimètre carré':
                            return unitFrom / 1e2;
                        case 'Décimètre carré':
                            return unitFrom / 1e4;
                        case 'Mètre carré':
                            return unitFrom / 1e6;
                        case 'Décamètre carré':
                            return unitFrom / 1e8;
                        case 'Hectomètre carré':
                            return unitFrom / 1e10;
                        case 'Kilomètre carré':
                            return unitFrom / 1e12;
                        default:
                            return unitFrom;
                    }

                case 'Centimètre carré':
                    switch (unitToType) {
                        case 'Nanomètre carré':
                            return unitFrom * 1e14;
                        case 'Micromètre carré':
                            return unitFrom * 1e8;
                        case 'Millimètre carré':
                            return unitFrom * 1e2;
                        case 'Décimètre carré':
                            return unitFrom / 1e2;
                        case 'Mètre carré':
                            return unitFrom / 1e4;
                        case 'Décamètre carré':
                            return unitFrom / 1e6;
                        case 'Hectomètre carré':
                            return unitFrom / 1e8;
                        case 'Kilomètre carré':
                            return unitFrom / 1e10;
                    }

                case 'Décimètre carré':
                    switch (unitToType) {
                        case 'Nanomètre carré':
                            return unitFrom * 1e16;
                        case 'Micromètre carré':
                            return unitFrom * 1e10;
                        case 'Millimètre carré':
                            return unitFrom * 1e4;
                        case 'Centimètre carré':
                            return unitFrom * 1e2;
                        case 'Mètre carré':
                            return unitFrom / 1e2;
                        case 'Décamètre carré':
                            return unitFrom / 1e4;
                        case 'Hectomètre carré':
                            return unitFrom / 1e6;
                        case 'Kilomètre carré':
                            return unitFrom / 1e8;
                    }

                case 'Mètre carré':
                    switch (unitToType) {
                        case 'Nanomètre carré':
                            return unitFrom * 1e18;
                        case 'Micromètre carré':
                            return unitFrom * 1e12;
                        case 'Millimètre carré':
                            return unitFrom * 1e6;
                        case 'Centimètre carré':
                            return unitFrom * 1e4;
                        case 'Décimètre carré':
                            return unitFrom * 1e2;
                        case 'Décamètre carré':
                            return unitFrom / 1e2;
                        case 'Hectomètre carré':
                            return unitFrom / 1e4;
                        case 'Kilomètre carré':
                            return unitFrom / 1e6;
                    }

                case 'Décamètre carré':
                    switch (unitToType) {
                        case 'Nanomètre carré':
                            return unitFrom * 1e20;
                        case 'Micromètre carré':
                            return unitFrom * 1e14;
                        case 'Millimètre carré':
                            return unitFrom * 1e8;
                        case 'Centimètre carré':
                            return unitFrom * 1e6;
                        case 'Décimètre carré':
                            return unitFrom * 1e4;
                        case 'Mètre carré':
                            return unitFrom * 1e2;
                        case 'Hectomètre carré':
                            return unitFrom / 1e2;
                        case 'Kilomètre carré':
                            return unitFrom / 1e4;
                    }

                case 'Hectomètre carré':
                    switch (unitToType) {
                        case 'Nanomètre carré':
                            return unitFrom * 1e22;
                        case 'Micromètre carré':
                            return unitFrom * 1e16;
                        case 'Millimètre carré':
                            return unitFrom * 1e10;
                        case 'Centimètre carré':
                            return unitFrom * 1e8;
                        case 'Décimètre carré':
                            return unitFrom * 1e6;
                        case 'Mètre carré':
                            return unitFrom * 1e4;
                        case 'Décamètre carré':
                            return unitFrom * 1e2;
                        case 'Kilomètre carré':
                            return unitFrom / 1e2;
                    }

                case 'Kilomètre carré':
                    switch (unitToType) {
                        case 'Nanomètre carré':
                            return unitFrom * 1e24;
                        case 'Micromètre carré':
                            return unitFrom * 1e18;
                        case 'Millimètre carré':
                            return unitFrom * 1e12;
                        case 'Centimètre carré':
                            return unitFrom * 1e10;
                        case 'Décimètre carré':
                            return unitFrom * 1e8;
                        case 'Mètre carré':
                            return unitFrom * 1e6;
                        case 'Décamètre carré':
                            return unitFrom * 1e4;
                        case 'Hectomètre carré':
                            return unitFrom * 1e2;

                    }
            }
        }

        function convertVitesse(unitFrom, unitFromType, unitToType) {
            unitFrom = parseFloat(unitFrom);
            switch (unitFromType) {
                case 'Nanomètre/Seconde':
                    switch (unitToType) {
                        case 'Micromètre/Seconde':
                            return unitFrom / 1e3;
                        case 'Millimètre/Seconde':
                            return unitFrom / 1e6;
                        case 'Centimètre/Seconde':
                            return unitFrom / 1e7;
                        case 'Décimètre/Seconde':
                            return unitFrom / 1e8;
                        case 'Mètre/Seconde':
                            return unitFrom / 1e9;
                        case 'Décamètre/Seconde':
                            return unitFrom / 1e10;
                        case 'Hectomètre/Seconde':
                            return unitFrom / 1e11;
                        case 'Kilomètre/Seconde':
                            return unitFrom / 1e12;
                        case 'Nanomètre/Heure':
                            return unitFrom * 3600;
                        case 'Micromètre/Heure':
                            return unitFrom * 3.6;
                        case 'Millimètre/Heure':
                            return unitFrom * 3.6e-3;
                        case 'Centimètre/Heure':
                            return unitFrom * 3.6e-4;
                        case 'Décimètre/Heure':
                            return unitFrom * 3.6e-5;
                        case 'Mètre/Heure':
                            return unitFrom * 3.6e-6;
                        case 'Décamètre/Heure':
                            return unitFrom * 3.6e-7;
                        case 'Hectomètre/Heure':
                            return unitFrom * 3.6e-8;
                        case 'Kilomètre/Heure':
                            return unitFrom * 3.6e-9;
                    }

                case 'Micromètre/Seconde':
                    switch (unitToType) {
                        case 'Nanomètre/Seconde':
                            return unitFrom * 1e3;
                        case 'Millimètre/Seconde':
                            return unitFrom / 1e3;
                        case 'Centimètre/Seconde':
                            return unitFrom / 1e4;
                        case 'Décimètre/Seconde':
                            return unitFrom / 1e5;
                        case 'Mètre/Seconde':
                            return unitFrom / 1e6;
                        case 'Décamètre/Seconde':
                            return unitFrom / 1e7;
                        case 'Hectomètre/Seconde':
                            return unitFrom / 1e8;
                        case 'Kilomètre/Seconde':
                            return unitFrom / 1e9;
                        case 'Nanomètre/Heure':
                            return unitFrom * 3.6e6;
                        case 'Micromètre/Heure':
                            return unitFrom * 3600;
                        case 'Millimètre/Heure':
                            return unitFrom * 3.6;
                        case 'Centimètre/Heure':
                            return unitFrom * 0.36;
                        case 'Décimètre/Heure':
                            return unitFrom * 0.036;
                        case 'Mètre/Heure':
                            return unitFrom * 3.6e-3;
                        case 'Décamètre/Heure':
                            return unitFrom * 3.6e-4;
                        case 'Hectomètre/Heure':
                            return unitFrom * 3.6e-5;
                        case 'Kilomètre/Heure':
                            return unitFrom * 3.6e-6;
                    }

                case 'Millimètre/Seconde':
                    switch (unitToType) {
                        case 'Nanomètre/Seconde':
                            return unitFrom * 1e6;
                        case 'Micromètre/Seconde':
                            return unitFrom * 1e3;
                        case 'Centimètre/Seconde':
                            return unitFrom / 1e1;
                        case 'Décimètre/Seconde':
                            return unitFrom / 1e2;
                        case 'Mètre/Seconde':
                            return unitFrom / 1e3;
                        case 'Décamètre/Seconde':
                            return unitFrom / 1e4;
                        case 'Hectomètre/Seconde':
                            return unitFrom / 1e5;
                        case 'Kilomètre/Seconde':
                            return unitFrom / 1e6;
                        case 'Nanomètre/Heure':
                            return unitFrom * 3.6e9;
                        case 'Micromètre/Heure':
                            return unitFrom * 3.6e6;
                        case 'Millimètre/Heure':
                            return unitFrom * 3600;
                        case 'Centimètre/Heure':
                            return unitFrom * 360;
                        case 'Décimètre/Heure':
                            return unitFrom * 36;
                        case 'Mètre/Heure':
                            return unitFrom * 3.6;
                        case 'Décamètre/Heure':
                            return unitFrom * 0.36;
                        case 'Hectomètre/Heure':
                            return unitFrom * 0.036;
                        case 'Kilomètre/Heure':
                            return unitFrom * 3.6e-3;
                    }

                case 'Centimètre/Seconde':
                    switch (unitToType) {
                        case 'Nanomètre/Seconde':
                            return unitFrom * 1e7;
                        case 'Micromètre/Seconde':
                            return unitFrom * 1e4;
                        case 'Millimètre/Seconde':
                            return unitFrom * 1e1;
                        case 'Décimètre/Seconde':
                            return unitFrom / 1e1;
                        case 'Mètre/Seconde':
                            return unitFrom / 1e2;
                        case 'Décamètre/Seconde':
                            return unitFrom / 1e3;
                        case 'Hectomètre/Seconde':
                            return unitFrom / 1e4;
                        case 'Kilomètre/Seconde':
                            return unitFrom / 1e5;
                        case 'Nanomètre/Heure':
                            return unitFrom * 3.6e10;
                        case 'Micromètre/Heure':
                            return unitFrom * 3.6e7;
                        case 'Millimètre/Heure':
                            return unitFrom * 3.6e4;
                        case 'Centimètre/Heure':
                            return unitFrom * 3600;
                        case 'Décimètre/Heure':
                            return unitFrom * 360;
                        case 'Mètre/Heure':
                            return unitFrom * 36;
                        case 'Décamètre/Heure':
                            return unitFrom * 3.6;
                        case 'Hectomètre/Heure':
                            return unitFrom * 0.36;
                        case 'Kilomètre/Heure':
                            return unitFrom * 0.036;
                    }

                case 'Décimètre/Seconde':
                    switch (unitToType) {
                        case 'Nanomètre/Seconde':
                            return unitFrom * 1e8;
                        case 'Micromètre/Seconde':
                            return unitFrom * 1e5;
                        case 'Millimètre/Seconde':
                            return unitFrom * 1e2;
                        case 'Centimètre/Seconde':
                            return unitFrom * 1e1;
                        case 'Mètre/Seconde':
                            return unitFrom / 1e1;
                        case 'Décamètre/Seconde':
                            return unitFrom / 1e2;
                        case 'Hectomètre/Seconde':
                            return unitFrom / 1e3;
                        case 'Kilomètre/Seconde':
                            return unitFrom / 1e4;
                        case 'Nanomètre/Heure':
                            return unitFrom * 3.6e11;
                        case 'Micromètre/Heure':
                            return unitFrom * 3.6e8;
                        case 'Millimètre/Heure':
                            return unitFrom * 3.6e5;
                        case 'Centimètre/Heure':
                            return unitFrom * 3.6e4;
                        case 'Décimètre/Heure':
                            return unitFrom * 3600;
                        case 'Mètre/Heure':
                            return unitFrom * 360;
                        case 'Décamètre/Heure':
                            return unitFrom * 36;
                        case 'Hectomètre/Heure':
                            return unitFrom * 3.6;
                        case 'Kilomètre/Heure':
                            return unitFrom * 0.36;
                    }

                case 'Mètre/Seconde':
                    switch (unitToType) {
                        case 'Nanomètre/Seconde':
                            return unitFrom * 1e9;
                        case 'Micromètre/Seconde':
                            return unitFrom * 1e6;
                        case 'Millimètre/Seconde':
                            return unitFrom * 1e3;
                        case 'Centimètre/Seconde':
                            return unitFrom * 1e2;
                        case 'Décimètre/Seconde':
                            return unitFrom * 1e1;
                        case 'Décamètre/Seconde':
                            return unitFrom / 1e1;
                        case 'Hectomètre/Seconde':
                            return unitFrom / 1e2;
                        case 'Kilomètre/Seconde':
                            return unitFrom / 1e3;
                        case 'Nanomètre/Heure':
                            return unitFrom * 3.6e12;
                        case 'Micromètre/Heure':
                            return unitFrom * 3.6e9;
                        case 'Millimètre/Heure':
                            return unitFrom * 3.6e6;
                        case 'Centimètre/Heure':
                            return unitFrom * 3.6e5;
                        case 'Décimètre/Heure':
                            return unitFrom * 3.6e4;
                        case 'Mètre/Heure':
                            return unitFrom * 3600;
                        case 'Décamètre/Heure':
                            return unitFrom * 360;
                        case 'Hectomètre/Heure':
                            return unitFrom * 36;
                        case 'Kilomètre/Heure':
                            return unitFrom * 3.6;
                    }

                case 'Décamètre/Seconde':
                    switch (unitToType) {
                        case 'Nanomètre/Seconde':
                            return unitFrom * 1e10;
                        case 'Micromètre/Seconde':
                            return unitFrom * 1e7;
                        case 'Millimètre/Seconde':
                            return unitFrom * 1e4;
                        case 'Centimètre/Seconde':
                            return unitFrom * 1e3;
                        case 'Décimètre/Seconde':
                            return unitFrom * 1e2;
                        case 'Mètre/Seconde':
                            return unitFrom * 1e1;
                        case 'Hectomètre/Seconde':
                            return unitFrom / 1e1;
                        case 'Kilomètre/Seconde':
                            return unitFrom / 1e2;
                        case 'Nanomètre/Heure':
                            return unitFrom * 3.6e13;
                        case 'Micromètre/Heure':
                            return unitFrom * 3.6e10;
                        case 'Millimètre/Heure':
                            return unitFrom * 3.6e7;
                        case 'Centimètre/Heure':
                            return unitFrom * 3.6e6;
                        case 'Décimètre/Heure':
                            return unitFrom * 3.6e5;
                        case 'Mètre/Heure':
                            return unitFrom * 3.6e4;
                        case 'Décamètre/Heure':
                            return unitFrom * 3600;
                        case 'Hectomètre/Heure':
                            return unitFrom * 360;
                        case 'Kilomètre/Heure':
                            return unitFrom * 36;
                    }

                case 'Hectomètre/Seconde':
                    switch (unitToType) {
                        case 'Nanomètre/Seconde':
                            return unitFrom * 1e11;
                        case 'Micromètre/Seconde':
                            return unitFrom * 1e8;
                        case 'Millimètre/Seconde':
                            return unitFrom * 1e5;
                        case 'Centimètre/Seconde':
                            return unitFrom * 1e4;
                        case 'Décimètre/Seconde':
                            return unitFrom * 1e3;
                        case 'Mètre/Seconde':
                            return unitFrom * 1e2;
                        case 'Décamètre/Seconde':
                            return unitFrom * 1e1;
                        case 'Kilomètre/Seconde':
                            return unitFrom / 1e1;
                        case 'Nanomètre/Heure':
                            return unitFrom * 3.6e14;
                        case 'Micromètre/Heure':
                            return unitFrom * 3.6e11;
                        case 'Millimètre/Heure':
                            return unitFrom * 3.6e8;
                        case 'Centimètre/Heure':
                            return unitFrom * 3.6e7;
                        case 'Décimètre/Heure':
                            return unitFrom * 3.6e6;
                        case 'Mètre/Heure':
                            return unitFrom * 3.6e5;
                        case 'Décamètre/Heure':
                            return unitFrom * 3.6e4;
                        case 'Hectomètre/Heure':
                            return unitFrom * 3600;
                        case 'Kilomètre/Heure':
                            return unitFrom * 360;
                    }

                case 'Kilomètre/Seconde':
                    switch (unitToType) {
                        case 'Nanomètre/Seconde':
                            return unitFrom * 1e12;
                        case 'Micromètre/Seconde':
                            return unitFrom * 1e9;
                        case 'Millimètre/Seconde':
                            return unitFrom * 1e6;
                        case 'Centimètre/Seconde':
                            return unitFrom * 1e5;
                        case 'Décimètre/Seconde':
                            return unitFrom * 1e4;
                        case 'Mètre/Seconde':
                            return unitFrom * 1e3;
                        case 'Décamètre/Seconde':
                            return unitFrom * 1e2;
                        case 'Hectomètre/Seconde':
                            return unitFrom * 1e1;
                        case 'Nanomètre/Heure':
                            return unitFrom * 3.6e15;
                        case 'Micromètre/Heure':
                            return unitFrom * 3.6e12;
                        case 'Millimètre/Heure':
                            return unitFrom * 3.6e9;
                        case 'Centimètre/Heure':
                            return unitFrom * 3.6e8;
                        case 'Décimètre/Heure':
                            return unitFrom * 3.6e7;
                        case 'Mètre/Heure':
                            return unitFrom * 3.6e6;
                        case 'Décamètre/Heure':
                            return unitFrom * 3.6e5;
                        case 'Hectomètre/Heure':
                            return unitFrom * 3.6e4;
                        case 'Kilomètre/Heure':
                            return unitFrom * 3600;
                    }

                case 'Nanomètre/Heure':
                    switch (unitToType) {
                        case 'Nanomètre/Seconde':
                            return unitFrom / 3600;
                        case 'Micromètre/Seconde':
                            return unitFrom / 3.6e6;
                        case 'Millimètre/Seconde':
                            return unitFrom / 3.6e9;
                        case 'Centimètre/Seconde':
                            return unitFrom / 3.6e10;
                        case 'Décimètre/Seconde':
                            return unitFrom / 3.6e11;
                        case 'Mètre/Seconde':
                            return unitFrom / 3.6e12;
                        case 'Décamètre/Seconde':
                            return unitFrom / 3.6e13;
                        case 'Hectomètre/Seconde':
                            return unitFrom / 3.6e14;
                        case 'Kilomètre/Seconde':
                            return unitFrom / 3.6e15;
                        case 'Micromètre/Heure':
                            return unitFrom / 1e3;
                        case 'Millimètre/Heure':
                            return unitFrom / 1e6;
                        case 'Centimètre/Heure':
                            return unitFrom / 1e7;
                        case 'Décimètre/Heure':
                            return unitFrom / 1e8;
                        case 'Mètre/Heure':
                            return unitFrom / 1e9;
                        case 'Décamètre/Heure':
                            return unitFrom / 1e10;
                        case 'Hectomètre/Heure':
                            return unitFrom / 1e11;
                        case 'Kilomètre/Heure':
                            return unitFrom / 1e12;
                    }

                case 'Micromètre/Heure':
                    switch (unitToType) {
                        case 'Nanomètre/Seconde':
                            return unitFrom * 1e3 / 3600;
                        case 'Micromètre/Seconde':
                            return unitFrom / 3600;
                        case 'Millimètre/Seconde':
                            return unitFrom / 3.6e6;
                        case 'Centimètre/Seconde':
                            return unitFrom / 3.6e7;
                        case 'Décimètre/Seconde':
                            return unitFrom / 3.6e8;
                        case 'Mètre/Seconde':
                            return unitFrom / 3.6e9;
                        case 'Décamètre/Seconde':
                            return unitFrom / 3.6e10;
                        case 'Hectomètre/Seconde':
                            return unitFrom / 3.6e11;
                        case 'Kilomètre/Seconde':
                            return unitFrom / 3.6e12;
                        case 'Nanomètre/Heure':
                            return unitFrom * 1e3;
                        case 'Millimètre/Heure':
                            return unitFrom / 1e3;
                        case 'Centimètre/Heure':
                            return unitFrom / 1e4;
                        case 'Décimètre/Heure':
                            return unitFrom / 1e5;
                        case 'Mètre/Heure':
                            return unitFrom / 1e6;
                        case 'Décamètre/Heure':
                            return unitFrom / 1e7;
                        case 'Hectomètre/Heure':
                            return unitFrom / 1e8;
                        case 'Kilomètre/Heure':
                            return unitFrom / 1e9;
                    }

                case 'Millimètre/Heure':
                    switch (unitToType) {
                        case 'Nanomètre/Seconde':
                            return unitFrom * 1e6 / 3600;
                        case 'Micromètre/Seconde':
                            return unitFrom * 1e3 / 3600;
                        case 'Millimètre/Seconde':
                            return unitFrom / 3600;
                        case 'Centimètre/Seconde':
                            return unitFrom / 3.6e4;
                        case 'Décimètre/Seconde':
                            return unitFrom / 3.6e5;
                        case 'Mètre/Seconde':
                            return unitFrom / 3.6e6;
                        case 'Décamètre/Seconde':
                            return unitFrom / 3.6e7;
                        case 'Hectomètre/Seconde':
                            return unitFrom / 3.6e8;
                        case 'Kilomètre/Seconde':
                            return unitFrom / 3.6e9;
                        case 'Nanomètre/Heure':
                            return unitFrom * 1e6;
                        case 'Micromètre/Heure':
                            return unitFrom * 1e3;
                        case 'Centimètre/Heure':
                            return unitFrom / 1e1;
                        case 'Décimètre/Heure':
                            return unitFrom / 1e2;
                        case 'Mètre/Heure':
                            return unitFrom / 1e3;
                        case 'Décamètre/Heure':
                            return unitFrom / 1e4;
                        case 'Hectomètre/Heure':
                            return unitFrom / 1e5;
                        case 'Kilomètre/Heure':
                            return unitFrom / 1e6;
                    }

                case 'Centimètre/Heure':
                    switch (unitToType) {
                        case 'Nanomètre/Seconde':
                            return unitFrom * 1e7 / 3600;
                        case 'Micromètre/Seconde':
                            return unitFrom * 1e4 / 3600;
                        case 'Millimètre/Seconde':
                            return unitFrom * 1e1 / 3600;
                        case 'Centimètre/Seconde':
                            return unitFrom / 3600;
                        case 'Décimètre/Seconde':
                            return unitFrom / 3.6e4;
                        case 'Mètre/Seconde':
                            return unitFrom / 3.6e5;
                        case 'Décamètre/Seconde':
                            return unitFrom / 3.6e6;
                        case 'Hectomètre/Seconde':
                            return unitFrom / 3.6e7;
                        case 'Kilomètre/Seconde':
                            return unitFrom / 3.6e8;
                        case 'Nanomètre/Heure':
                            return unitFrom * 1e7;
                        case 'Micromètre/Heure':
                            return unitFrom * 1e4;
                        case 'Millimètre/Heure':
                            return unitFrom * 1e1;
                        case 'Décimètre/Heure':
                            return unitFrom / 1e1;
                        case 'Mètre/Heure':
                            return unitFrom / 1e2;
                        case 'Décamètre/Heure':
                            return unitFrom / 1e3;
                        case 'Hectomètre/Heure':
                            return unitFrom / 1e4;
                        case 'Kilomètre/Heure':
                            return unitFrom / 1e5;
                    }

                case 'Décimètre/Heure':
                    switch (unitToType) {
                        case 'Nanomètre/Seconde':
                            return unitFrom * 1e8 / 3600;
                        case 'Micromètre/Seconde':
                            return unitFrom * 1e5 / 3600;
                        case 'Millimètre/Seconde':
                            return unitFrom * 1e2 / 3600;
                        case 'Centimètre/Seconde':
                            return unitFrom * 1e1 / 3600;
                        case 'Décimètre/Seconde':
                            return unitFrom / 3600;
                        case 'Mètre/Seconde':
                            return unitFrom / 3.6e4;
                        case 'Décamètre/Seconde':
                            return unitFrom / 3.6e5;
                        case 'Hectomètre/Seconde':
                            return unitFrom / 3.6e6;
                        case 'Kilomètre/Seconde':
                            return unitFrom / 3.6e7;
                        case 'Nanomètre/Heure':
                            return unitFrom * 1e8;
                        case 'Micromètre/Heure':
                            return unitFrom * 1e5;
                        case 'Millimètre/Heure':
                            return unitFrom * 1e2;
                        case 'Centimètre/Heure':
                            return unitFrom * 1e1;
                        case 'Mètre/Heure':
                            return unitFrom / 1e1;
                        case 'Décamètre/Heure':
                            return unitFrom / 1e2;
                        case 'Hectomètre/Heure':
                            return unitFrom / 1e3;
                        case 'Kilomètre/Heure':
                            return unitFrom / 1e4;
                    }

                case 'Mètre/Heure':
                    switch (unitToType) {
                        case 'Nanomètre/Seconde':
                            return unitFrom * 1e9 / 3600;
                        case 'Micromètre/Seconde':
                            return unitFrom * 1e6 / 3600;
                        case 'Millimètre/Seconde':
                            return unitFrom * 1e3 / 3600;
                        case 'Centimètre/Seconde':
                            return unitFrom * 1e2 / 3600;
                        case 'Décimètre/Seconde':
                            return unitFrom * 1e1 / 3600;
                        case 'Mètre/Seconde':
                            return unitFrom / 3600;
                        case 'Décamètre/Seconde':
                            return unitFrom / 3.6e4;
                        case 'Hectomètre/Seconde':
                            return unitFrom / 3.6e5;
                        case 'Kilomètre/Seconde':
                            return unitFrom / 3.6e6;
                        case 'Nanomètre/Heure':
                            return unitFrom * 1e9;
                        case 'Micromètre/Heure':
                            return unitFrom * 1e6;
                        case 'Millimètre/Heure':
                            return unitFrom * 1e3;
                        case 'Centimètre/Heure':
                            return unitFrom * 1e2;
                        case 'Décimètre/Heure':
                            return unitFrom * 1e1;
                        case 'Décamètre/Heure':
                            return unitFrom / 1e1;
                        case 'Hectomètre/Heure':
                            return unitFrom / 1e2;
                        case 'Kilomètre/Heure':
                            return unitFrom / 1e3;
                    }

                case 'Décamètre/Heure':
                    switch (unitToType) {
                        case 'Nanomètre/Seconde':
                            return unitFrom * 1e10 / 3600;
                        case 'Micromètre/Seconde':
                            return unitFrom * 1e7 / 3600;
                        case 'Millimètre/Seconde':
                            return unitFrom * 1e4 / 3600;
                        case 'Centimètre/Seconde':
                            return unitFrom * 1e3 / 3600;
                        case 'Décimètre/Seconde':
                            return unitFrom * 1e2 / 3600;
                        case 'Mètre/Seconde':
                            return unitFrom * 1e1 / 3600;
                        case 'Décamètre/Seconde':
                            return unitFrom / 3600;
                        case 'Hectomètre/Seconde':
                            return unitFrom / 3.6e4;
                        case 'Kilomètre/Seconde':
                            return unitFrom / 3.6e5;
                        case 'Nanomètre/Heure':
                            return unitFrom * 1e10;
                        case 'Micromètre/Heure':
                            return unitFrom * 1e7;
                        case 'Millimètre/Heure':
                            return unitFrom * 1e4;
                        case 'Centimètre/Heure':
                            return unitFrom * 1e3;
                        case 'Décimètre/Heure':
                            return unitFrom * 1e2;
                        case 'Mètre/Heure':
                            return unitFrom * 1e1;
                        case 'Hectomètre/Heure':
                            return unitFrom / 1e1;
                        case 'Kilomètre/Heure':
                            return unitFrom / 1e2;
                    }

                case 'Hectomètre/Heure':
                    switch (unitToType) {
                        case 'Nanomètre/Seconde':
                            return unitFrom * 1e11 / 3600;
                        case 'Micromètre/Seconde':
                            return unitFrom * 1e8 / 3600;
                        case 'Millimètre/Seconde':
                            return unitFrom * 1e5 / 3600;
                        case 'Centimètre/Seconde':
                            return unitFrom * 1e4 / 3600;
                        case 'Décimètre/Seconde':
                            return unitFrom * 1e3 / 3600;
                        case 'Mètre/Seconde':
                            return unitFrom * 1e2 / 3600;
                        case 'Décamètre/Seconde':
                            return unitFrom * 1e1 / 3600;
                        case 'Hectomètre/Seconde':
                            return unitFrom / 3600;
                        case 'Kilomètre/Seconde':
                            return unitFrom / 3.6e4;
                        case 'Nanomètre/Heure':
                            return unitFrom * 1e11;
                        case 'Micromètre/Heure':
                            return unitFrom * 1e8;
                        case 'Millimètre/Heure':
                            return unitFrom * 1e5;
                        case 'Centimètre/Heure':
                            return unitFrom * 1e4;
                        case 'Décimètre/Heure':
                            return unitFrom * 1e3;
                        case 'Mètre/Heure':
                            return unitFrom * 1e2;
                        case 'Décamètre/Heure':
                            return unitFrom * 1e1;
                        case 'Kilomètre/Heure':
                            return unitFrom / 1e1;
                    }

                case 'Kilomètre/Heure':
                    switch (unitToType) {
                        case 'Nanomètre/Seconde':
                            return unitFrom * 1e12 / 3600;
                        case 'Micromètre/Seconde':
                            return unitFrom * 1e9 / 3600;
                        case 'Millimètre/Seconde':
                            return unitFrom * 1e6 / 3600;
                        case 'Centimètre/Seconde':
                            return unitFrom * 1e5 / 3600;
                        case 'Décimètre/Seconde':
                            return unitFrom * 1e4 / 3600;
                        case 'Mètre/Seconde':
                            return unitFrom * 1e3 / 3600;
                        case 'Décamètre/Seconde':
                            return unitFrom * 1e2 / 3600;
                        case 'Hectomètre/Seconde':
                            return unitFrom * 1e1 / 3600;
                        case 'Kilomètre/Seconde':
                            return unitFrom / 3600;
                        case 'Nanomètre/Heure':
                            return unitFrom * 1e12;
                        case 'Micromètre/Heure':
                            return unitFrom * 1e9;
                        case 'Millimètre/Heure':
                            return unitFrom * 1e6;
                        case 'Centimètre/Heure':
                            return unitFrom * 1e5;
                        case 'Décimètre/Heure':
                            return unitFrom * 1e4;
                        case 'Mètre/Heure':
                            return unitFrom * 1e3;
                        case 'Décamètre/Heure':
                            return unitFrom * 1e2;
                        case 'Hectomètre/Heure':
                            return unitFrom * 1e1;
                    }
            }
        }

        function convertFrequency(unitFrom, unitFromType, unitToType) {
            unitFrom = parseFloat(unitFrom);
            switch (unitFromType) {
                case 'Nanohertz':
                    switch (unitToType) {
                        case 'Microhertz':
                            return unitFrom / 1e3;
                        case 'Millihertz':
                            return unitFrom / 1e6;
                        case 'Centihertz':
                            return unitFrom / 1e7;
                        case 'Décihertz':
                            return unitFrom / 1e8;
                        case 'Hertz':
                            return unitFrom / 1e9;
                        case 'Décahertz':
                            return unitFrom / 1e10;
                        case 'Hectohertz':
                            return unitFrom / 1e11;
                        case 'KiloHertz':
                            return unitFrom / 1e12;
                    }

                case 'Microhertz':
                    switch (unitToType) {
                        case 'Nanohertz':
                            return unitFrom * 1e3;
                        case 'Millihertz':
                            return unitFrom / 1e3;
                        case 'Centihertz':
                            return unitFrom / 1e4;
                        case 'Décihertz':
                            return unitFrom / 1e5;
                        case 'Hertz':
                            return unitFrom / 1e6;
                        case 'Décahertz':
                            return unitFrom / 1e7;
                        case 'Hectohertz':
                            return unitFrom / 1e8;
                        case 'KiloHertz':
                            return unitFrom / 1e9;
                    }

                case 'Millihertz':
                    switch (unitToType) {
                        case 'Nanohertz':
                            return unitFrom * 1e6;
                        case 'Microhertz':
                            return unitFrom * 1e3;
                        case 'Centihertz':
                            return unitFrom / 1e1;
                        case 'Décihertz':
                            return unitFrom / 1e2;
                        case 'Hertz':
                            return unitFrom / 1e3;
                        case 'Décahertz':
                            return unitFrom / 1e4;
                        case 'Hectohertz':
                            return unitFrom / 1e5;
                        case 'KiloHertz':
                            return unitFrom / 1e6;
                    }

                case 'Centihertz':
                    switch (unitToType) {
                        case 'Nanohertz':
                            return unitFrom * 1e7;
                        case 'Microhertz':
                            return unitFrom * 1e4;
                        case 'Millihertz':
                            return unitFrom * 1e1;
                        case 'Décihertz':
                            return unitFrom / 1e1;
                        case 'Hertz':
                            return unitFrom / 1e2;
                        case 'Décahertz':
                            return unitFrom / 1e3;
                        case 'Hectohertz':
                            return unitFrom / 1e4;
                        case 'KiloHertz':
                            return unitFrom / 1e5;
                    }

                case 'Décihertz':
                    switch (unitToType) {
                        case 'Nanohertz':
                            return unitFrom * 1e8;
                        case 'Microhertz':
                            return unitFrom * 1e5;
                        case 'Millihertz':
                            return unitFrom * 1e2;
                        case 'Centihertz':
                            return unitFrom * 1e1;
                        case 'Hertz':
                            return unitFrom / 1e1;
                        case 'Décahertz':
                            return unitFrom / 1e2;
                        case 'Hectohertz':
                            return unitFrom / 1e3;
                        case 'KiloHertz':
                            return unitFrom / 1e4;
                    }

                case 'Hertz':
                    switch (unitToType) {
                        case 'Nanohertz':
                            return unitFrom * 1e9;
                        case 'Microhertz':
                            return unitFrom * 1e6;
                        case 'Millihertz':
                            return unitFrom * 1e3;
                        case 'Centihertz':
                            return unitFrom * 1e2;
                        case 'Décihertz':
                            return unitFrom * 1e1;
                        case 'Décahertz':
                            return unitFrom / 1e1;
                        case 'Hectohertz':
                            return unitFrom / 1e2;
                        case 'KiloHertz':
                            return unitFrom / 1e3;
                    }

                case 'Décahertz':
                    switch (unitToType) {
                        case 'Nanohertz':
                            return unitFrom * 1e10;
                        case 'Microhertz':
                            return unitFrom * 1e7;
                        case 'Millihertz':
                            return unitFrom * 1e4;
                        case 'Centihertz':
                            return unitFrom * 1e3;
                        case 'Décihertz':
                            return unitFrom * 1e2;
                        case 'Hertz':
                            return unitFrom * 1e1;
                        case 'Hectohertz':
                            return unitFrom / 1e1;
                        case 'KiloHertz':
                            return unitFrom / 1e2;
                    }

                case 'Hectohertz':
                    switch (unitToType) {
                        case 'Nanohertz':
                            return unitFrom * 1e11;
                        case 'Microhertz':
                            return unitFrom * 1e8;
                        case 'Millihertz':
                            return unitFrom * 1e5;
                        case 'Centihertz':
                            return unitFrom * 1e4;
                        case 'Décihertz':
                            return unitFrom * 1e3;
                        case 'Hertz':
                            return unitFrom * 1e2;
                        case 'Décahertz':
                            return unitFrom * 1e1;
                        case 'KiloHertz':
                            return unitFrom / 1e1;
                    }

                case 'KiloHertz':
                    switch (unitToType) {
                        case 'Nanohertz':
                            return unitFrom * 1e12;
                        case 'Microhertz':
                            return unitFrom * 1e9;
                        case 'Millimètre carré':
                            return unitFrom * 1e6;
                        case 'Centihertz':
                            return unitFrom * 1e5;
                        case 'Décihertz':
                            return unitFrom * 1e4;
                        case 'Hertz':
                            return unitFrom * 1e3;
                        case 'Décahertz':
                            return unitFrom * 1e2;
                        case 'Hectohertz':
                            return unitFrom * 1e1;
                    }
            }
        }

        function convertPressure(unitFrom, unitFromType, unitToType) {
            unitFrom = parseFloat(unitFrom);
            switch (unitFromType) {
                case 'Pascal':
                    switch (unitToType) {
                        case 'Bar':
                            return unitFrom / 1e5;
                        case 'PSI':
                            return unitFrom / 6894.76;
                        case 'Atmosphères':
                            return unitFrom / 101325;
                    }

                case 'Bar':
                    switch (unitToType) {
                        case 'Pascal':
                            return unitFrom * 1e5;
                        case 'PSI':
                            return unitFrom * 14.5038;
                        case 'Atmosphères':
                            return unitFrom / 1.01325;
                    }

                case 'PSI':
                    switch (unitToType) {
                        case 'Pascal':
                            return unitFrom * 6894.76;
                        case 'Bar':
                            return unitFrom / 14.5038;
                        case 'Atmosphères':
                            return unitFrom / 14.6959;
                    }

                case 'Atmosphères':
                    switch (unitToType) {
                        case 'Pascal':
                            return unitFrom * 101325;
                        case 'Bar':
                            return unitFrom * 1.01325;
                        case 'PSI':
                            return unitFrom * 14.6959;
                    }
            }
        }

        function convertEnergy(unitFrom, unitFromType, unitToType) {
            unitFrom = parseFloat(unitFrom);
            switch (unitFromType) {
                case 'Nanojoule':
                    switch (unitToType) {
                        case 'Microjoule':
                            return unitFrom / 1e3;
                        case 'Millijoule':
                            return unitFrom / 1e6;
                        case 'Centijoule':
                            return unitFrom / 1e7;
                        case 'Décijoule':
                            return unitFrom / 1e8;
                        case 'Joule':
                            return unitFrom / 1e9;
                        case 'Décajoule':
                            return unitFrom / 1e10;
                        case 'Hectojoule':
                            return unitFrom / 1e11;
                        case 'Kilojoule':
                            return unitFrom / 1e12;
                    }

                case 'Microjoule':
                    switch (unitToType) {
                        case 'Nanojoule':
                            return unitFrom * 1e3;
                        case 'Millijoule':
                            return unitFrom / 1e3;
                        case 'Centijoule':
                            return unitFrom / 1e4;
                        case 'Décijoule':
                            return unitFrom / 1e5;
                        case 'Joule':
                            return unitFrom / 1e6;
                        case 'Décajoule':
                            return unitFrom / 1e7;
                        case 'Hectojoule':
                            return unitFrom / 1e8;
                        case 'Kilojoule':
                            return unitFrom / 1e9;
                    }

                case 'Millijoule':
                    switch (unitToType) {
                        case 'Nanojoule':
                            return unitFrom * 1e6;
                        case 'Microjoule':
                            return unitFrom * 1e3;
                        case 'Centijoule':
                            return unitFrom / 1e1;
                        case 'Décijoule':
                            return unitFrom / 1e2;
                        case 'Joule':
                            return unitFrom / 1e3;
                        case 'Décajoule':
                            return unitFrom / 1e4;
                        case 'Hectojoule':
                            return unitFrom / 1e5;
                        case 'Kilojoule':
                            return unitFrom / 1e6;
                    }

                case 'Centijoule':
                    switch (unitToType) {
                        case 'Nanojoule':
                            return unitFrom * 1e7;
                        case 'Microjoule':
                            return unitFrom * 1e4;
                        case 'Millijoule':
                            return unitFrom * 1e1;
                        case 'Décijoule':
                            return unitFrom / 1e1;
                        case 'Joule':
                            return unitFrom / 1e2;
                        case 'Décajoule':
                            return unitFrom / 1e3;
                        case 'Hectojoule':
                            return unitFrom / 1e4;
                        case 'Kilojoule':
                            return unitFrom / 1e5;
                    }

                case 'Décijoule':
                    switch (unitToType) {
                        case 'Nanojoule':
                            return unitFrom * 1e8;
                        case 'Microjoule':
                            return unitFrom * 1e5;
                        case 'Millijoule':
                            return unitFrom * 1e2;
                        case 'Centijoule':
                            return unitFrom * 1e1;
                        case 'Joule':
                            return unitFrom / 1e1;
                        case 'Décajoule':
                            return unitFrom / 1e2;
                        case 'Hectojoule':
                            return unitFrom / 1e3;
                        case 'Kilojoule':
                            return unitFrom / 1e4;
                    }

                case 'Joule':
                    switch (unitToType) {
                        case 'Nanojoule':
                            return unitFrom * 1e9;
                        case 'Microjoule':
                            return unitFrom * 1e6;
                        case 'Millijoule':
                            return unitFrom * 1e3;
                        case 'Centijoule':
                            return unitFrom * 1e2;
                        case 'Décijoule':
                            return unitFrom * 1e1;
                        case 'Décajoule':
                            return unitFrom / 1e1;
                        case 'Hectojoule':
                            return unitFrom / 1e2;
                        case 'Kilojoule':
                            return unitFrom / 1e3;
                    }

                case 'Décajoule':
                    switch (unitToType) {
                        case 'Nanojoule':
                            return unitFrom * 1e10;
                        case 'Microjoule':
                            return unitFrom * 1e7;
                        case 'Millijoule':
                            return unitFrom * 1e4;
                        case 'Centijoule':
                            return unitFrom * 1e3;
                        case 'Décijoule':
                            return unitFrom * 1e2;
                        case 'Joule':
                            return unitFrom * 1e1;
                        case 'Hectojoule':
                            return unitFrom / 1e1;
                        case 'Kilojoule':
                            return unitFrom / 1e2;
                    }

                case 'Hectojoule':
                    switch (unitToType) {
                        case 'Nanojoule':
                            return unitFrom * 1e11;
                        case 'Microjoule':
                            return unitFrom * 1e8;
                        case 'Millijoule':
                            return unitFrom * 1e5;
                        case 'Centijoule':
                            return unitFrom * 1e4;
                        case 'Décijoule':
                            return unitFrom * 1e3;
                        case 'Joule':
                            return unitFrom * 1e2;
                        case 'Décajoule':
                            return unitFrom * 1e1;
                        case 'Kilojoule':
                            return unitFrom / 1e1;
                    }

                case 'Kilojoule':
                    switch (unitToType) {
                        case 'Nanojoule':
                            return unitFrom * 1e12;
                        case 'Microjoule':
                            return unitFrom * 1e9;
                        case 'Millijoule':
                            return unitFrom * 1e6;
                        case 'Centijoule':
                            return unitFrom * 1e5;
                        case 'Décijoule':
                            return unitFrom * 1e4;
                        case 'Joule':
                            return unitFrom * 1e3;
                        case 'Décajoule':
                            return unitFrom * 1e2;
                        case 'Hectojoule':
                            return unitFrom * 1e1;
                    }
            }
        }
        updateUnitSelectors();
    </script>
</body>

</html>