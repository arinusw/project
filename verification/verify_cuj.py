import os
import time
from playwright.sync_api import sync_playwright

def run_cuj():
    os.makedirs("verification/videos", exist_ok=True)
    os.makedirs("verification/screenshots", exist_ok=True)

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos/"
        )
        page = context.new_page()

        page.goto("http://127.0.0.1:8000/index.php")
        page.wait_for_load_state("networkidle")
        time.sleep(1)

        # 1. Scroll through sections
        sections = ["#about", "#services", "#education", "#social", "#contact"]
        for sec in sections:
            page.locator(f".nav__menu a[href='{sec}']").click()
            time.sleep(1.2)

        # 2. Toggle Theme
        page.locator("#theme-button").click()
        time.sleep(1)
        page.locator("#theme-button").click()
        time.sleep(1)

        # 3. Fill Contact Form
        page.locator("#contact").scroll_into_view_if_needed()
        time.sleep(0.5)
        page.fill("#name", "Siti Rahma")
        page.fill("#email", "siti@example.com")
        page.fill("#subject", "Konsultasi Edukasi Teknologi")
        page.fill("#message", "Halo Ar Creative, saya ingin berdiskusi mengenai program pelatihan literasi digital.")
        page.evaluate("document.getElementById('simulation_mode').value = '1'")

        page.click(".contact__submit-btn")
        time.sleep(1.5)

        # 4. Scroll back to top using scroll-up button
        page.locator("#scroll-up").click()
        time.sleep(1.5)

        page.screenshot(path="verification/screenshots/final_cuj.png")
        print("Captured final_cuj.png")

        context.close()
        browser.close()

if __name__ == "__main__":
    run_cuj()
