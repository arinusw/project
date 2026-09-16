import os
import time
from playwright.sync_api import sync_playwright

def trigger_scroll_animations(page):
    # Scroll down incrementally to trigger ScrollReveal animations
    for i in range(0, 4000, 500):
        page.mouse.wheel(0, 500)
        page.wait_for_timeout(300)
    page.evaluate("window.scrollTo(0, 0)")
    page.wait_for_timeout(500)

def run_verification():
    os.makedirs("verification/screenshots", exist_ok=True)

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)

        # 1. Desktop Verification
        print("Starting Desktop Verification...")
        context_desktop = browser.new_context(viewport={"width": 1280, "height": 800})
        page_desktop = context_desktop.new_page()
        page_desktop.goto("http://127.0.0.1:8000/index.html")
        page_desktop.wait_for_load_state("networkidle")

        trigger_scroll_animations(page_desktop)

        # Capture Desktop Light Mode
        page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=False)
        print("Saved desktop_light.png")

        # Toggle Dark Theme
        theme_btn = page_desktop.locator("#theme-button")
        theme_btn.click()
        page_desktop.wait_for_timeout(500)
        page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=False)
        print("Saved desktop_dark.png")

        # Test Contact Form Submission with simulation_mode
        # Inject hidden input simulation_mode to form
        page_desktop.evaluate("""
            const form = document.getElementById('contact-form');
            if (form) {
                const simInput = document.createElement('input');
                simInput.type = 'hidden';
                simInput.name = 'simulation_mode';
                simInput.value = '1';
                form.appendChild(simInput);
            }
        """)

        # Scroll to contact section
        page_desktop.locator("#contact").scroll_into_view_if_needed()
        page_desktop.wait_for_timeout(500)

        page_desktop.fill("#contact-name", "Budi Santoso")
        page_desktop.fill("#contact-email", "budi@example.com")
        page_desktop.fill("#contact-subject", "Konsultasi Website Studio")
        page_desktop.fill("#contact-message", "Halo Ar Creative, saya ingin berkonsultasi mengenai pembuatan website produk digital.")

        page_desktop.click('#contact-form button[type="submit"]')
        page_desktop.wait_for_timeout(1000)

        response_msg = page_desktop.locator("#contact-response-message").text_content()
        print(f"Contact Form Response: {response_msg}")
        assert "berhasil" in response_msg.lower() or "terima kasih" in response_msg.lower(), f"Form submission failed: {response_msg}"

        page_desktop.screenshot(path="verification/screenshots/desktop_contact_success.png", full_page=False)
        print("Saved desktop_contact_success.png")
        context_desktop.close()

        # 2. Mobile Verification
        print("Starting Mobile Verification...")
        context_mobile = browser.new_context(viewport={"width": 375, "height": 667})
        page_mobile = context_mobile.new_page()
        page_mobile.goto("http://127.0.0.1:8000/index.html")
        page_mobile.wait_for_load_state("networkidle")

        trigger_scroll_animations(page_mobile)

        # Mobile Home Screenshot
        page_mobile.screenshot(path="verification/screenshots/mobile_light.png", full_page=False)
        print("Saved mobile_light.png")

        # Toggle Mobile Menu
        nav_toggle = page_mobile.locator("#nav-toggle")
        nav_toggle.click(force=True)
        page_mobile.wait_for_timeout(500)

        # Verify Menu Visible
        nav_menu = page_mobile.locator("#nav-menu")
        assert "show-menu" in (nav_menu.get_attribute("class") or ""), "Mobile menu failed to open"

        page_mobile.screenshot(path="verification/screenshots/mobile_menu.png", full_page=False)
        print("Saved mobile_menu.png")

        # Close Mobile Menu
        nav_close = page_mobile.locator("#nav-close")
        nav_close.click(force=True)
        page_mobile.wait_for_timeout(500)
        assert "show-menu" not in (nav_menu.get_attribute("class") or ""), "Mobile menu failed to close"

        context_mobile.close()
        browser.close()
        print("UI Verification Completed Successfully!")

if __name__ == "__main__":
    run_verification()
