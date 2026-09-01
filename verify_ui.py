import time
import subprocess
import sys
from playwright.sync_api import sync_playwright

def run_tests():
    print("Starting local PHP server on http://127.0.0.1:8000...")
    server = subprocess.Popen(
        ["php", "-S", "127.0.0.1:8000", "-t", "ar-creative"],
        stdout=subprocess.DEVNULL,
        stderr=subprocess.DEVNULL
    )
    time.sleep(1.5)

    try:
        with sync_playwright() as p:
            browser = p.chromium.launch(headless=True)

            # --- DESKTOP TEST ---
            print("Testing Desktop Viewport (1280x800)...")
            context_desktop = browser.new_context(
                viewport={"width": 1280, "height": 800},
                record_video_dir="verification/videos/"
            )
            page_desktop = context_desktop.new_page()
            page_desktop.goto("http://127.0.0.1:8000")
            page_desktop.wait_for_selector("#home")

            # Scroll down incrementally to trigger entrance animations
            for _ in range(5):
                page_desktop.mouse.wheel(0, 500)
                page_desktop.wait_for_timeout(300)

            # Test dark theme
            theme_btn = page_desktop.locator("#theme-button")
            theme_btn.click()
            page_desktop.wait_for_timeout(500)
            page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)

            # Switch back to light theme
            theme_btn.click()
            page_desktop.wait_for_timeout(500)
            page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)

            # Test Contact Form Submission in Simulation Mode
            page_desktop.locator("#user_name").fill("Penguji Studio")
            page_desktop.locator("#user_email").fill("test@example.com")
            page_desktop.locator("#user_project").fill("Saya berminat untuk membuat sistem web interaktif bersama Ar Creative.")

            # Append simulation mode field dynamically or test form submission
            page_desktop.evaluate("""() => {
                const form = document.getElementById('contact-form');
                const simInput = document.createElement('input');
                simInput.type = 'hidden';
                simInput.name = 'simulation_mode';
                simInput.value = '1';
                form.appendChild(simInput);
            }""")

            page_desktop.locator("#contact-form button[type='submit']").click()
            page_desktop.wait_for_selector("#contact-message.success")
            print("Desktop form submission success message verified!")

            context_desktop.close()

            # --- MOBILE TEST ---
            print("Testing Mobile Viewport (375x667)...")
            context_mobile = browser.new_context(
                viewport={"width": 375, "height": 667},
                is_mobile=True,
                record_video_dir="verification/videos/"
            )
            page_mobile = context_mobile.new_page()
            page_mobile.goto("http://127.0.0.1:8000")
            page_mobile.wait_for_selector("#home")

            # Test mobile menu toggle
            nav_toggle = page_mobile.locator("#nav-toggle")
            nav_toggle.click(force=True)
            page_mobile.wait_for_selector(".nav__menu.show-menu")
            page_mobile.screenshot(path="verification/screenshots/mobile_menu_open.png")

            # Close menu by clicking nav link or evaluate click
            page_mobile.evaluate("() => document.getElementById('nav-close').click()")
            page_mobile.wait_for_timeout(300)

            # Scroll and capture mobile view
            for _ in range(6):
                page_mobile.mouse.wheel(0, 400)
                page_mobile.wait_for_timeout(200)

            page_mobile.screenshot(path="verification/screenshots/mobile_light.png", full_page=True)
            context_mobile.close()

            browser.close()
            print("All Playwright verification tests passed successfully!")

    finally:
        server.terminate()

if __name__ == "__main__":
    run_tests()
