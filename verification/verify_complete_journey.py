import time
import os
from playwright.sync_api import sync_playwright

def record_journey():
    os.makedirs("verification/videos", exist_ok=True)
    os.makedirs("verification/screenshots", exist_ok=True)

    with sync_playwright() as p:
        browser = p.chromium.launch()
        context = browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos/"
        )
        page = context.new_page()

        # Navigate to homepage with simulation mode enabled
        page.goto("http://127.0.0.1:8000/?simulation_mode=1")
        page.wait_for_load_state("networkidle")
        time.sleep(1)

        # Scroll through sections smoothly
        sections = ["#home", "#about", "#services", "#education", "#social", "#contact"]
        for sec in sections:
            page.locator(f"a[href='{sec}']").first.click()
            time.sleep(1)

        # Toggle theme to Dark Mode
        page.locator("#theme-button").click()
        time.sleep(1)

        # Toggle theme back to Light Mode
        page.locator("#theme-button").click()
        time.sleep(1)

        # Fill & Submit Contact Form
        page.fill("#name", "Ahmad Rizky")
        page.fill("#email", "ahmad@arcreative.id")
        page.fill("#message", "Halo Ar Creative, mohon informasi lebih lanjut mengenai program edukasi dan jasa multimedia.")
        page.click("#contact-form button[type='submit']")

        page.locator("#contact-message").wait_for(state="visible")
        time.sleep(2)

        page.screenshot(path="verification/screenshots/journey_completed.png")

        context.close()
        browser.close()
        print("Recorded CUJ video and saved journey_completed.png.")

if __name__ == "__main__":
    record_journey()
