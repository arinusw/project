import os
from playwright.sync_api import sync_playwright

def run_cuj(page):
    page.set_viewport_size({"width": 1280, "height": 800})
    page.goto("http://127.0.0.1:8000")
    page.wait_for_timeout(1000)

    # Trigger scroll animations down the page
    for i in range(1, 6):
        page.mouse.wheel(0, 500)
        page.wait_for_timeout(600)

    # Click Theme toggle
    page.click("#theme-button")
    page.wait_for_timeout(800)

    # Scroll back up to contact form
    page.evaluate("window.scrollTo(0, document.body.scrollHeight)")
    page.wait_for_timeout(800)

    # Fill and submit form
    page.fill("#user_name", "Ahmad Rizky")
    page.wait_for_timeout(400)
    page.fill("#user_email", "ahmad@example.com")
    page.wait_for_timeout(400)
    page.fill("#user_project", "Pengembangan Website & Branding UI/UX")
    page.wait_for_timeout(400)

    # Set simulation mode hidden field if not present or submit directly
    page.evaluate('''() => {
        const form = document.getElementById("contact-form");
        if (!form.querySelector('input[name="simulation_mode"]')) {
            const hidden = document.createElement("input");
            hidden.type = "hidden";
            hidden.name = "simulation_mode";
            hidden.value = "1";
            form.appendChild(hidden);
        }
    }''')

    page.click('#contact-form button[type="submit"]')
    page.wait_for_timeout(1500)

    # Take final screenshot
    os.makedirs("/home/jules/verification/screenshots", exist_ok=True)
    os.makedirs("/home/jules/verification/videos", exist_ok=True)
    page.screenshot(path="/home/jules/verification/screenshots/cuj_final.png", full_page=True)
    page.wait_for_timeout(1000)

if __name__ == "__main__":
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(
            record_video_dir="/home/jules/verification/videos"
        )
        page = context.new_page()
        try:
            run_cuj(page)
        finally:
            context.close()
            browser.close()
