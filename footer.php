<style>
.bgnu-footer-bar {
    width: 95%;
    max-width: 1200px;
    margin: 35px auto 20px auto;
    background: linear-gradient(135deg, #2e1065 0%, #4c1d95 50%, #6b21a8 100%);
    border-radius: 14px;
    padding: 14px 20px;
    display: flex;
    justify-content: space-around;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    box-shadow: 0 10px 25px -5px rgba(76, 29, 149, 0.2);
    border: 1px solid #c084fc;
}

.bgnu-footer-link {
    color: #ffffff;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    padding: 8px 18px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

.bgnu-footer-link:hover {
    background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
    color: #ffffff;
    border-color: #fbbf24;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(245, 158, 11, 0.4);
}

@media (max-width: 600px) {
    .bgnu-footer-bar {
        flex-direction: column;
        padding: 14px;
        gap: 10px;
    }
    .bgnu-footer-link {
        width: 100%;
        justify-content: center;
        text-align: center;
    }
}
</style>

<footer>
    <div class="bgnu-footer-bar">
        <a href="https://www.facebook.com/bgnunankana/" target="_blank" class="bgnu-footer-link">📘 BGNU Facebook</a>
        <a href="https://www.instagram.com/officialbgnu.nns/" target="_blank" class="bgnu-footer-link">📸 BGNU Instagram</a>
        <a href="https://pk.linkedin.com/company/officialbgnu" target="_blank" class="bgnu-footer-link">💼 BGNU LinkedIn</a>
        <a href="https://www.google.com/maps/dir//Baba+Guru+Nanak+University+(BGNU),+Main+Block,+Nankana+Sahib,+39100,+Pakistan/@31.4834944,73.6100352,12z/data=!3m1!4b1!4m8!4m7!1m0!1m5!1m1!1s0x391891c685a4a749:0x5bbfd63f9fba288!2m2!1d73.7250685!2d31.4941355?entry=ttu&g_ep=EgoyMDI2MDIxOC4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="bgnu-footer-link">📍 BGNU Location</a>
    </div>
</footer>