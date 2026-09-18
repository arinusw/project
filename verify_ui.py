import time
import subprocess
from playwright.sync_api import sync_playwright

def trigger_scroll_animations(page):
    """Helper function to trigger ScrollReveal animations across the page."""
    for i in range(10):
        page.mouse.wheel(0, 800)
        page.wait_for_timeout(200)
    page.evaluate("window.scrollTo(0, 0)")
    page.wait_for_timeout(300)

def verify_ui():
    # Start PHP local server if not running
    php_process = subprocess.Popen(
        ["php", "-S", "127.0.0.1:8000", "-t", "ar-creative"],
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE
    )
    time.sleep(1)

    try:
        with sync_playwright() as p:
            # 1. Desktop Verification
            browser = p.chromium.launch(headless=True)
            context = browser.new_context(
                viewport={"width": 1280, "height": 800},
                record_video_dir="verification/videos"
            )
            page = context.new_page()
            page.goto("http://127.0.0.1:8000")
            page.wait_for_load_state("networkidle")

            # Trigger scroll animations and capture light mode
            trigger_scroll_animations(page)
            page.screenshot(path="verification/screenshots/desktop_light.png", full_page=False)

            # Test Dark Theme Toggle
            page.locator("#theme-button").click()
            page.wait_for_timeout(500)
            page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=False)

            # Contact Form AJAX Submission
            page.locator("#name").fill("Budi Santoso")
            page.locator("#email").fill("budi@example.com")
            page.locator("#subject").fill("Konsultasi Website")
            page.locator("#message").fill("Halo Ar Creative, saya ingin berdiskusi mengenai proyek pembuatan website studio kami.")

            # Submit form
            page.locator(".contact__submit-btn").click()

            # Wait for success message
            message_locator = page.locator("#contact-message")
            message_locator.wait_for(state="visible")
            page.wait_for_timeout(500)
            page.screenshot(path="verification/screenshots/desktop_contact_success.png", full_page=False)

            context.close()

            # 2. Mobile Verification
            mobile_context = browser.new_context(
                viewport={"width": 375, "height": 667},
                is_mobile=True,
                record_video_dir="verification/videos"
            )
            mobile_page = mobile_context.new_page()
            mobile_page.goto("http://127.0.0.1:8000")
            mobile_page.wait_for_load_state("networkidle")

            trigger_scroll_animations(mobile_page)
            mobile_page.screenshot(path="verification/screenshots/mobile_light.png", full_page=False)

            # Test Mobile Navigation Menu Drawer
            mobile_page.locator("#nav-toggle").click(force=True)
            mobile_page.wait_for_timeout(500)
            mobile_page.screenshot(path="verification/screenshots/mobile_menu_open.png", full_page=False)

            mobile_context.close()
            browser.close()

        print("UI Verification completed successfully!")
    finally:
        php_process.terminate()

if __name__ == "__main__":
    verify_ui()
