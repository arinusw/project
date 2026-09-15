import time
import subprocess
import os
from playwright.sync_api import sync_playwright

def run_verification():
    # Start PHP server
    server_process = subprocess.Popen(
        ["php", "-S", "127.0.0.1:8000", "-t", "ar-creative"],
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE
    )
    time.sleep(1.5)

    try:
        with sync_playwright() as p:
            # Desktop Verification (1280x800)
            browser = p.chromium.launch(headless=True)
            context = browser.new_context(
                viewport={"width": 1280, "height": 800},
                record_video_dir="verification/videos/"
            )
            page = context.new_page()
            page.goto("http://127.0.0.1:8000/?simulation_mode=1")
            page.wait_for_timeout(1000)

            # Trigger scroll animations incrementally
            for _ in range(5):
                page.mouse.wheel(0, 800)
                page.wait_for_timeout(300)

            # Scroll back to top for accurate screenshot
            page.evaluate("window.scrollTo(0, 0)")
            page.wait_for_timeout(500)
            page.evaluate("document.querySelector('#home').scrollIntoView()")
            page.wait_for_timeout(500)

            page.screenshot(path="verification/screenshots/desktop_light.png", full_page=False)

            # Toggle dark theme
            page.click("#theme-button")
            page.wait_for_timeout(500)
            page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=False)

            # Scroll to contact form and submit
            contact_form = page.locator("#contact-form")
            contact_form.scroll_into_view_if_needed()
            page.fill("#name", "Budi Santoso")
            page.fill("#email", "budi@example.com")
            page.fill("#subject", "Konsultasi Website Studio")
            page.fill("#message", "Halo Ar Creative, saya berminat membuat website portofolio untuk studio kami.")
            page.click(".contact__submit-btn")

            # Wait for success message
            success_msg = page.locator("#contact-message")
            success_msg.wait_for(state="visible")
            page.wait_for_timeout(1000)
            page.screenshot(path="verification/screenshots/desktop_contact_success.png", full_page=False)

            context.close()
            browser.close()

            # Mobile Verification (375x667)
            browser_mobile = p.chromium.launch(headless=True)
            context_mobile = browser_mobile.new_context(
                viewport={"width": 375, "height": 667},
                is_mobile=True,
                record_video_dir="verification/videos/"
            )
            page_m = context_mobile.new_page()
            page_m.goto("http://127.0.0.1:8000/?simulation_mode=1")
            page_m.wait_for_timeout(1000)

            # Screenshot mobile home
            page_m.screenshot(path="verification/screenshots/mobile_light.png", full_page=False)

            # Open Mobile Nav Drawer
            page_m.click("#nav-toggle", force=True)
            page_m.wait_for_timeout(500)
            page_m.screenshot(path="verification/screenshots/mobile_menu_open.png", full_page=False)

            # Close Mobile Nav Drawer
            page_m.click("#nav-close", force=True)
            page_m.wait_for_timeout(500)

            context_mobile.close()
            browser_mobile.close()

            print("UI Verification successfully completed!")
    finally:
        server_process.terminate()

if __name__ == "__main__":
    run_verification()
