import os
import time
from playwright.sync_api import sync_playwright

def run_cuj(page):
    # Navigate to local PHP server
    page.goto("http://127.0.0.1:8000")
    page.wait_for_timeout(500)

    # 1. Scroll through sections to display animated elements
    for i in range(5):
        page.mouse.wheel(0, 400)
        page.wait_for_timeout(500)

    # 2. Toggle Dark Theme
    theme_btn = page.locator("#theme-button")
    theme_btn.click()
    page.wait_for_timeout(1000)

    # 3. Scroll to Contact Form and Submit
    page.locator("#contact").scroll_into_view_if_needed()
    page.wait_for_timeout(500)

    page.evaluate("document.getElementById('simulation_mode').value = '1'")
    page.fill("#name", "Ahmad Fauzi")
    page.wait_for_timeout(300)
    page.fill("#email", "ahmad@example.com")
    page.wait_for_timeout(300)
    page.fill("#project", "Halo Ar Creative, saya tertarik bermitra untuk pembuatan produk digital.")
    page.wait_for_timeout(500)

    page.click(".contact__form-button")
    page.locator("#contact-message").wait_for(state="visible")
    page.wait_for_timeout(1000)

    # Take screenshot at the key moment
    os.makedirs("/home/jules/verification/screenshots", exist_ok=True)
    page.screenshot(path="/home/jules/verification/screenshots/verification.png")
    page.wait_for_timeout(1000)

if __name__ == "__main__":
    os.makedirs("/home/jules/verification/videos", exist_ok=True)
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir="/home/jules/verification/videos"
        )
        page = context.new_page()
        try:
            run_cuj(page)
        finally:
            context.close()
            browser.close()
    print("CUJ verification video & screenshot generated successfully.")
