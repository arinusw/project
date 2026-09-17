import subprocess
import time
import os
from playwright.sync_api import sync_playwright

def trigger_scroll_animations(page):
    """Helper function to perform incremental scrolling and trigger ScrollReveal entrance animations."""
    for i in range(10):
        page.mouse.wheel(0, 500)
        time.sleep(0.2)
    page.evaluate("window.scrollTo(0, 0)")
    time.sleep(0.5)

def run_verification():
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    # Start local PHP server
    php_process = subprocess.Popen(
        ["php", "-S", "127.0.0.1:8000", "-t", "ar-creative"],
        stdout=subprocess.DEVNULL,
        stderr=subprocess.DEVNULL
    )
    time.sleep(1.5)

    try:
        with sync_playwright() as p:
            browser = p.chromium.launch(headless=True)

            # 1. Desktop Verification
            print("Running Desktop Verification...")
            context_desktop = browser.new_context(
                viewport={"width": 1280, "height": 800},
                record_video_dir="verification/videos"
            )
            page_desktop = context_desktop.new_page()
            page_desktop.goto("http://1280.0.0.1:8000/" if False else "http://127.0.0.1:8000/")
            page_desktop.wait_for_load_state("networkidle")

            trigger_scroll_animations(page_desktop)

            # Desktop Light Theme Screenshot
            page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=False)

            # Toggle Dark Mode
            theme_btn = page_desktop.locator("#theme-button")
            theme_btn.click()
            time.sleep(0.5)
            page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=False)

            # Switch back to Light Mode
            theme_btn.click()
            time.sleep(0.3)

            # Test Contact Form Submission in Simulation Mode
            print("Testing Contact Form Submission...")
            page_desktop.locator("#contact").scroll_into_view_if_needed()
            time.sleep(0.3)

            page_desktop.fill("#name", "Penguji Otomatis")
            page_desktop.fill("#email", "tester@arcreative.id")
            page_desktop.fill("#message", "Halo Ar Creative, ini adalah pengujian otomatis form kontak.")

            # Inject simulation_mode into contact form
            page_desktop.evaluate("""() => {
                const form = document.getElementById('contact-form');
                const simInput = document.createElement('input');
                simInput.type = 'hidden';
                simInput.name = 'simulation_mode';
                simInput.value = '1';
                form.appendChild(simInput);
            }""")

            page_desktop.click("#contact-form button[type='submit']")
            page_desktop.wait_for_selector("#contact-message:has-text('berhasil')", timeout=5000)

            page_desktop.screenshot(path="verification/screenshots/contact_success.png", full_page=False)
            context_desktop.close()

            # 2. Mobile Verification
            print("Running Mobile Verification...")
            context_mobile = browser.new_context(
                viewport={"width": 375, "height": 667},
                record_video_dir="verification/videos"
            )
            page_mobile = context_mobile.new_page()
            page_mobile.goto("http://127.0.0.1:8000/")
            page_mobile.wait_for_load_state("networkidle")

            # Mobile Navigation Menu Interaction
            nav_toggle = page_mobile.locator("#nav-toggle")
            nav_toggle.click(force=True)
            time.sleep(0.5)
            page_mobile.screenshot(path="verification/screenshots/mobile_menu_open.png", full_page=False)

            nav_close = page_mobile.locator("#nav-close")
            nav_close.click(force=True)
            time.sleep(0.5)

            trigger_scroll_animations(page_mobile)
            page_mobile.screenshot(path="verification/screenshots/mobile_light.png", full_page=False)

            context_mobile.close()
            browser.close()

            print("Verification completed successfully!")

    finally:
        php_process.terminate()

if __name__ == "__main__":
    run_verification()
