<?php
session_start();

if (isset($_SESSION['user_id'])) {
    session_unset();
    session_destroy();
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Merci d'avoir utilisé FreeBee</title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
            body {
                font-family: 'Poppins', sans-serif;
                margin: 0;
                padding: 0;
                overflow: hidden;
                background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                text-align: center;
            }
            .magic-container {
                position: relative;
                z-index: 10;
                background-color: rgba(255, 255, 255, 0.85);
                padding: 40px;
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
                max-width: 600px;
                backdrop-filter: blur(5px);
                animation: float 6s ease-in-out infinite;
            }
            h1 {
                color: #2c3e50;
                margin-bottom: 10px;
                font-size: 2.5rem;
                text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
            }
            p {
                color: #7f8c8d;
                font-size: 1.2rem;
                margin-bottom: 30px;
            }
            .return-link {
                display: inline-block;
                padding: 12px 30px;
                background: linear-gradient(45deg, #f6d365, #fda085);
                color: white;
                text-decoration: none;
                border-radius: 50px;
                font-weight: 600;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
            }
            .return-link:hover {
                transform: translateY(-3px);
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
                background: linear-gradient(45deg, #fda085, #f6d365);
            }
            .sun { /* Soleil magique */
                position: absolute;
                top: -50px;
                right: -50px;
                width: 200px;
                height: 200px;
                background: radial-gradient(circle, #ffde59 0%, #ff914d 100%);
                border-radius: 50%;
                filter: blur(10px);
                opacity: 0.8;
                z-index: -1;
                animation: pulse 4s infinite alternate;
            }
            .bee {
                position: absolute;
                width: 30px;
                height: 30px;
                background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path fill="%23FFC107" d="M50 20c-15 0-25 10-25 25s10 25 25 25 25-10 25-25-10-25-25-25z"/><path fill="%23333" d="M30 30c0 0 10-5 20-5s20 5 20 5-5 10-5 20 5 20 5 20-10-5-20-5-20 5-20 5 5-10 5-20-5-20-5-20z"/></svg>') no-repeat;
                background-size: contain;
                z-index: 1;
            }
            .flower {
                position: absolute;
                width: 40px;
                height: 40px;
                background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="%23FF69B4"/><circle cx="30" cy="30" r="15" fill="%23FFC0CB"/><circle cx="70" cy="30" r="15" fill="%23FFC0CB"/><circle cx="30" cy="70" r="15" fill="%23FFC0CB"/><circle cx="70" cy="70" r="15" fill="%23FFC0CB"/><circle cx="50" cy="50" r="10" fill="%23FFDE59"/></svg>') no-repeat;
                background-size: contain;
                z-index: 1;
            }
            .pollen {
                position: absolute;
                width: 8px;
                height: 8px;
                background-color: rgba(255, 255, 255, 0.8);
                border-radius: 50%;
                filter: blur(1px);
                z-index: 0;
            }
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-15px); }
                100% { transform: translateY(0px); }
            }
            @keyframes pulse {
                0% { transform: scale(1); opacity: 0.8; }
                100% { transform: scale(1.05); opacity: 1; }
            }
        </style>
    </head>
    <body>
        <div class="sun"></div>
        <div class="magic-container">
            <h1>Merci d'avoir visité FreeBee</h1>
            <p>Votre voyage avec nous a été aussi doux que le miel !</p>
            <a class="return-link" href="login.php">Revenir à l’accueil</a>
        </div>
        <script>
            function createBees() {
                for (let i = 0; i < 8; i++) {
                    const bee = document.createElement('div');
                    bee.className = 'bee';
                    bee.style.left = `${Math.random() * 100}vw`;
                    bee.style.top = `${Math.random() * 100}vh`;
                    bee.style.transform = `scale(${0.5 + Math.random()})`;
                    document.body.appendChild(bee);
                    animateBee(bee);
                }
            }
            function animateBee(bee) {
                const startX = Math.random() * window.innerWidth;
                const startY = Math.random() * window.innerHeight;
                const endX = Math.random() * window.innerWidth;
                const endY = Math.random() * window.innerHeight;
                bee.style.left = `${startX}px`;
                bee.style.top = `${startY}px`;
                const keyframes = [
                    { transform: `translate(0, 0) rotate(0deg)` },
                    { transform: `translate(${endX - startX}px, ${endY - startY}px) rotate(${360 + Math.random() * 360}deg)` }
                ];
                const options = {
                    duration: 5000 + Math.random() * 10000,
                    iterations: Infinity,
                    direction: 'alternate',
                    easing: 'ease-in-out'
                };
                bee.animate(keyframes, options);
            }
            function createFlowers() {
                for (let i = 0; i < 15; i++) {
                    const flower = document.createElement('div');
                    flower.className = 'flower';
                    flower.style.left = `${Math.random() * 100}vw`;
                    flower.style.bottom = `${10 + Math.random() * 10}px`;
                    flower.style.transform = `scale(${0.7 + Math.random() * 0.6})`;
                    document.body.appendChild(flower);
                }
            }
            function createPollen() {
                for (let i = 0; i < 50; i++) {
                    const pollen = document.createElement('div');
                    pollen.className = 'pollen';
                    pollen.style.left = `${Math.random() * 100}vw`;
                    pollen.style.top = `${Math.random() * 100}vh`;
                    pollen.style.opacity = 0.3 + Math.random() * 0.7;
                    pollen.style.width = `${3 + Math.random() * 7}px`;
                    pollen.style.height = pollen.style.width;
                    const duration = 5 + Math.random() * 10;
                    const delay = Math.random() * 5;
                    pollen.style.animation = `float ${duration}s ease-in-out ${delay}s infinite`;
                    document.body.appendChild(pollen);
                }
            }

            window.addEventListener('DOMContentLoaded', () => {
                createBees();
                createFlowers();
                createPollen();
            });
        </script>
    </body>
    </html>
    <?php
    exit();
}
?>
