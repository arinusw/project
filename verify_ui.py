import os
import time
from playwright.sync_api import sync_playwright

def trigger_scroll_animations(page):
    # Scroll incrementally to trigger ScrollReveal entrance animations
    for y in range(0, 3000, 300):
        page.evaluate(f"window.scrollTo(0, {y})")
        time.sleep(0.1)
    page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
    time.sleep(0.3)

def run_ui_verification():
    os.makedirs("verification/screenshots", exist_ok=True)

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)

        # --- DESKTOP VERIFICATION ---
        page = browser.new_page(viewport={"width": 1280, "height": 800})
        page.goto("http://127.0.0.1:8000/index.php")
        page.wait_for_load_state("networkidle")
        trigger_scroll_animations(page)

        # Screenshot Light Theme Desktop
        page.screenshot(path="verification/screenshots/desktop_light.png")
        print("Captured desktop_light.png")

        # Toggle Dark Theme
        theme_btn = page.locator("#theme-button")
        theme_btn.click()
        time.sleep(0.3)
        page.screenshot(path="verification/screenshots/desktop_dark.png")
        print("Captured desktop_dark.png")

        # Toggle back to light
        theme_btn.click()
        time.sleep(0.3)

        # Fill Contact Form (Simulation Mode)
        page.fill("#name", "Budi Santoso")
        page.fill("#email", "budi@example.com")
        page.fill("#subject", "Permintaan Penawaran Digital Studio")
        page.fill("#message", "Halo Ar Creative, saya tertarik untuk menggunakan jasa pembuatan website dan desain branding.")

        # Enable simulation mode for form testing
        page.evaluate("document.getElementById('simulation_mode').value = '1'")

        page.click(".contact__submit-btn")

        # Wait for success message
        msg_locator = page.locator("#contact-message")
        msg_locator.wait_for(state="visible", timeout=5000)
        print("Form submission message:", msg_locator.text_content())
        page.screenshot(path="verification/screenshots/desktop_form_submitted.png")

        page.close()

        # --- MOBILE VERIFICATION ---
        mobile_page = browser.new_page(viewport={"width": 375, "height": 667})
        mobile_page.goto("http://127.0.0.1:8000/index.php")
        mobile_page.wait_for_load_state("networkidle")
        trigger_scroll_animations(mobile_page)

        mobile_page.screenshot(path="verification/screenshots/mobile_light.png")
        print("Captured mobile_light.png")

        # Open mobile navigation menu
        nav_toggle = mobile_page.locator("#nav-toggle")
        nav_toggle.click(force=True)
        time.sleep(0.3)
        mobile_page.screenshot(path="verification/screenshots/mobile_menu_open.png")
        print("Captured mobile_menu_open.png")

        mobile_page.close()
        browser.close()

if __name__ == "__main__":
    run_ui_verification()
