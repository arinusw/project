import os
import time
from playwright.sync_api import sync_playwright

def run_cuj_video():
    print("Starting CUJ Video capture...")
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        # Setup context with video recording
        context = browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="/home/jules/verification/videos"
        )
        page = context.new_page()

        try:
            page.goto("http://127.0.0.1:8000")
            page.wait_for_timeout(1000)

            # Incremental scroll to trigger animations smoothly in video
            for offset in range(0, 4000, 500):
                page.evaluate(f"window.scrollTo(0, {offset})")
                page.wait_for_timeout(300)

            page.evaluate("window.scrollTo(0, 0)")
            page.wait_for_timeout(500)

            # Toggle Dark Theme
            theme_btn = page.locator("#theme-button")
            theme_btn.click()
            page.wait_for_timeout(1000)

            # Scroll to Contact Section
            contact_section = page.locator("#contact")
            contact_section.scroll_into_view_if_needed()
            page.wait_for_timeout(800)

            # Fill form
            page.evaluate("window.simulation_mode = true;")
            page.fill("#user-name", "Andi Pratama")
            page.wait_for_timeout(300)
            page.fill("#user-email", "andi.pratama@example.com")
            page.wait_for_timeout(300)
            page.fill("#user-project", "Membangun ekosistem belajar pemrograman digital untuk pemuda desa.")
            page.wait_for_timeout(500)

            # Submit
            submit_btn = page.locator("#contact-form button[type='submit']")
            submit_btn.click()
            page.wait_for_timeout(2000) # Hold final state with response message visible

        finally:
            context.close()
            browser.close()

if __name__ == "__main__":
    run_cuj_video()
