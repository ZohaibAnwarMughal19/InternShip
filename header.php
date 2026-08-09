<style>
.bgnu-header {
    width: 95%;
    max-width: 1200px;
    margin: 15px auto 10px auto;
    background: linear-gradient(135deg, #ffffff 0%, #faf5ef 100%);
    border-radius: 14px;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 10px 25px -5px rgba(76, 29, 149, 0.1);
    border: 1px solid #e9d5ff;
    position: relative;
    overflow: hidden;
}

.bgnu-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #3b0764 0%, #7e22ce 50%, #f59e0b 100%);
}

.bgnu-logo {
    width: 55px;
    height: 60px;
    object-fit: contain;
    filter: drop-shadow(0 3px 5px rgba(76,29,149,0.15));
}

.bgnu-header-title {
    flex: 1;
    text-align: center;
    overflow: hidden;
}

.bgnu-header-title h2 {
    margin: 0;
    background: linear-gradient(135deg, #3b0764 0%, #6b21a8 50%, #d97706 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 24px;
    font-weight: 800;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

@media (max-width: 600px) {
    .bgnu-header {
        flex-direction: column;
        padding: 12px;
        gap: 8px;
    }
    .bgnu-logo-right {
        display: none;
    }
    .bgnu-header-title h2 {
        font-size: 18px;
        letter-spacing: 0.5px;
    }
}
</style>

<header> 
    <div class="bgnu-header">
        <img class="bgnu-logo" src="Uni Logo.png" alt="BGNU Logo">
        <div class="bgnu-header-title">  
            <marquee behavior="alternate" direction="">            
                <h2>BABA GURU NANAK UNIVERSITY !</h2>
            </marquee>
        </div>
        <img class="bgnu-logo bgnu-logo-right" src="Uni Logo.png" alt="BGNU Logo">
    </div>
</header>