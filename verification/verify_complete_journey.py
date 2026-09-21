import time
from playwright.sync_api import sync_playwright

def record_journey():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(
            record_video_dir="verification/videos/",
            record_video_size={"width": 1280, "height": 800},
            viewport={"width": 1280, "height": 800}
        )
        page = context.new_page()
        page.goto("http://127.0.0.1:8000/index.html")
        page.wait_for_load_state("networkidle")
        time.sleep(1)

        # Smooth scrolling through sections
        sections = ["#about", "#services", "#education", "#social", "#contact"]
        for sec in sections:
            page.evaluate(f"document.querySelector('{sec}').scrollIntoView({{ behavior: 'smooth' }})")
            time.sleep(1)

        # Toggle Dark Theme
        page.click("#theme-button")
        time.sleep(1)

        # Contact form fill & submit
        page.fill("#name", "Budi Santoso")
        page.fill("#email", "budi@example.com")
        page.fill("#message", "Saya tertarik menggunakan jasa pengembangan website Ar Creative.")

        page.evaluate("""
            const form = document.getElementById('contact-form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'simulation_mode';
            input.value = '1';
            form.appendChild(input);
        """)

        page.click('#contact-form button[type="submit"]')
        page.locator("#contact-message").wait_for(state="visible")
        time.sleep(2)

        context.close()
        browser.close()
    print("Video journey recorded successfully.")

if __name__ == "__main__":
    record_journey()
