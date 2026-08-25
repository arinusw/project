import time
import subprocess
from playwright.sync_api import sync_playwright

def run_verification():
    # Start PHP built-in server
    server_process = subprocess.Popen(
        ['php', '-S', '127.0.0.1:8000', '-t', 'ar-creative'],
        stdout=subprocess.DEVNULL,
        stderr=subprocess.DEVNULL
    )
    time.sleep(2)

    try:
        with sync_playwright() as p:
            browser = p.chromium.launch(headless=True)

            # --- Desktop Verification ---
            desktop_context = browser.new_context(
                viewport={"width": 1280, "height": 800},
                record_video_dir="verification/videos/"
            )
            desktop_page = desktop_context.new_page()
            desktop_page.goto("http://127.0.0.1:8000")
            desktop_page.wait_for_load_state("networkidle")

            # Trigger scroll reveal for all sections
            for _ in range(8):
                desktop_page.mouse.wheel(0, 600)
                desktop_page.wait_for_timeout(300)
            desktop_page.evaluate("window.scrollTo(0, 0)")
            desktop_page.wait_for_timeout(800)

            # Screenshot Desktop Light Mode
            desktop_page.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)

            # Toggle Dark Mode
            desktop_page.click("#theme-button")
            desktop_page.wait_for_timeout(500)

            # Screenshot Desktop Dark Mode
            desktop_page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)

            # Test Form Submission in Simulation Mode
            desktop_page.evaluate("window.simulationMode = true;")
            desktop_page.fill("#user_name", "Budi Santoso")
            desktop_page.fill("#user_email", "budi@example.com")
            desktop_page.fill("#user_project", "Ingin membuat website portofolio studio kreatif.")
            desktop_page.click("#contact-form button[type='submit']")

            # Wait for success message
            desktop_page.locator("#contact-message").wait_for(state="visible")
            desktop_page.wait_for_timeout(500)
            desktop_page.screenshot(path="verification/screenshots/form_submission.png")

            desktop_context.close()

            # --- Mobile Verification ---
            mobile_context = browser.new_context(
                viewport={"width": 375, "height": 667},
                is_mobile=True,
                has_touch=True,
                record_video_dir="verification/videos/"
            )
            mobile_page = mobile_context.new_page()
            mobile_page.goto("http://127.0.0.1:8000")
            mobile_page.wait_for_load_state("networkidle")

            # Screenshot Mobile Light Mode
            mobile_page.screenshot(path="verification/screenshots/mobile_light.png")

            # Open Mobile Drawer
            mobile_page.click("#nav-toggle")
            mobile_page.wait_for_timeout(500)
            mobile_page.screenshot(path="verification/screenshots/mobile_menu.png")

            mobile_context.close()
            browser.close()
            print("UI Verification completed successfully.")
    finally:
        server_process.terminate()

if __name__ == "__main__":
    run_verification()
