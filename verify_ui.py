import time
import subprocess
from playwright.sync_api import sync_playwright

def verify():
    # Start local PHP server
    server_process = subprocess.Popen(["php", "-S", "127.0.0.1:8000", "-t", "ar-creative"])
    time.sleep(2) # Give server time to bind

    try:
        with sync_playwright() as p:
            browser = p.chromium.launch(headless=True)

            # --- DESKTOP VERIFICATION ---
            desktop_context = browser.new_context(viewport={'width': 1280, 'height': 800})
            desktop_page = desktop_context.new_page()
            desktop_page.goto("http://127.0.0.1:8000/")
            desktop_page.wait_for_load_state("networkidle")

            # Capture desktop light theme
            desktop_page.screenshot(path="verification/screenshots/desktop_light.png")

            # Toggle dark theme
            theme_btn = desktop_page.locator("#theme-button")
            theme_btn.click()
            desktop_page.wait_for_timeout(500)
            desktop_page.screenshot(path="verification/screenshots/desktop_dark.png")

            # Test contact form submission
            desktop_page.locator("#contact-name").fill("Budi Santoso")
            desktop_page.locator("#contact-email").fill("budi@example.com")
            desktop_page.locator("#contact-message").fill("Halo Ar Creative, saya berminat dengan jasa pembuatan website.")

            # Append simulation_mode input dynamically to verify simulation response
            desktop_page.evaluate("""
                const form = document.getElementById('contact-form');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'simulation_mode';
                input.value = '1';
                form.appendChild(input);
            """)

            desktop_page.locator("#contact-form button[type='submit']").click()
            desktop_page.wait_for_selector("#contact-message-response.success")
            desktop_page.screenshot(path="verification/screenshots/desktop_contact_success.png")

            desktop_context.close()

            # --- MOBILE VERIFICATION ---
            mobile_context = browser.new_context(
                viewport={'width': 375, 'height': 667},
                user_agent="Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1"
            )
            mobile_page = mobile_context.new_page()
            mobile_page.goto("http://127.0.0.1:8000/")
            mobile_page.wait_for_load_state("networkidle")

            # Capture mobile home
            mobile_page.screenshot(path="verification/screenshots/mobile_light.png")

            # Toggle mobile nav menu
            mobile_page.locator("#nav-toggle").click(force=True)
            mobile_page.wait_for_timeout(500)
            mobile_page.screenshot(path="verification/screenshots/mobile_menu_open.png")

            mobile_context.close()
            browser.close()

            print("UI Verification Completed Successfully!")

    finally:
        server_process.terminate()

if __name__ == "__main__":
    verify()
