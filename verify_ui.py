import time
import subprocess
from playwright.sync_api import sync_playwright

def trigger_scroll_animations(page):
    # Scroll down incrementally to ensure ScrollReveal animations trigger
    for i in range(1, 6):
        page.mouse.wheel(0, 800)
        time.sleep(0.3)
    page.evaluate("window.scrollTo(0, 0)")
    time.sleep(0.5)

def run_verification():
    # Start PHP development server
    php_proc = subprocess.Popen(["php", "-S", "127.0.0.1:8000", "-t", "ar-creative"])
    time.sleep(1.5)

    try:
        with sync_playwright() as p:
            browser = p.chromium.launch(headless=True)

            # --- DESKTOP LIGHT MODE ---
            page_desktop = browser.new_page(viewport={"width": 1280, "height": 800})
            page_desktop.goto("http://127.0.0.1:8000")
            page_desktop.wait_for_selector("#home")
            trigger_scroll_animations(page_desktop)
            page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)

            # Toggle dark theme
            page_desktop.click("#theme-button")
            time.sleep(0.5)
            page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)

            # Test Contact Form Submission in Simulation Mode
            page_desktop.fill("#user_name", "Budi Santoso")
            page_desktop.fill("#user_email", "budi@example.com")
            page_desktop.fill("#user_project", "Halo Ar Creative, saya berminat membuat website studio kreatif dan modul edukasi.")

            # Inject simulation_mode input field
            page_desktop.evaluate("""
                const form = document.getElementById('contact-form');
                const simInput = document.createElement('input');
                simInput.type = 'hidden';
                simInput.name = 'simulation_mode';
                simInput.value = '1';
                form.appendChild(simInput);
            """)

            page_desktop.click("#contact-submit")
            page_desktop.locator("#contact-message").wait_for(state="visible")
            time.sleep(1)
            page_desktop.screenshot(path="verification/screenshots/contact_submitted.png")

            page_desktop.close()

            # --- MOBILE LIGHT MODE ---
            page_mobile = browser.new_page(viewport={"width": 375, "height": 667})
            page_mobile.goto("http://127.0.0.1:8000")
            page_mobile.wait_for_selector("#home")
            trigger_scroll_animations(page_mobile)

            # Open Mobile Drawer
            page_mobile.click("#nav-toggle", force=True)
            time.sleep(0.5)
            page_mobile.screenshot(path="verification/screenshots/mobile_menu.png")

            # Close Mobile Drawer
            page_mobile.click("#nav-close")
            time.sleep(0.5)
            page_mobile.screenshot(path="verification/screenshots/mobile_light.png", full_page=True)

            page_mobile.close()
            browser.close()

            print("Visual verification completed successfully!")

    finally:
        php_proc.terminate()

if __name__ == "__main__":
    run_verification()
