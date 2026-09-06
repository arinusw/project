import time
import os
from playwright.sync_api import sync_playwright

def trigger_scroll_animations(page):
    # Scroll down step-by-step to trigger ScrollReveal animations
    for i in range(10):
        page.mouse.wheel(0, 500)
        time.sleep(0.2)
    # Scroll back to top
    page.evaluate("window.scrollTo(0, 0)")
    time.sleep(0.5)

def run():
    os.makedirs("verification/screenshots", exist_ok=True)

    with sync_playwright() as p:
        browser = p.chromium.launch()

        # --- DESKTOP TESTING ---
        page_desktop = browser.new_page(viewport={"width": 1280, "height": 800})
        page_desktop.goto("http://127.0.0.1:8000/?simulation_mode=1")
        page_desktop.wait_for_load_state("networkidle")
        trigger_scroll_animations(page_desktop)

        # Take desktop light theme screenshot
        page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)
        print("Captured desktop_light.png")

        # Toggle Dark Theme
        theme_btn = page_desktop.locator("#theme-button")
        theme_btn.click()
        time.sleep(0.5)
        page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)
        print("Captured desktop_dark.png")

        # Submit contact form in simulation mode
        page_desktop.fill("#name", "Budi Santoso")
        page_desktop.fill("#email", "budi@example.com")
        page_desktop.fill("#message", "Halo Ar Creative, saya tertarik untuk menggunakan jasa pembuatan website portfolio.")
        page_desktop.click("#contact-form button[type='submit']")

        # Wait for success message
        contact_msg = page_desktop.locator("#contact-message")
        contact_msg.wait_for(state="visible")
        time.sleep(1)
        print("Form submission result text:", contact_msg.text_content())
        page_desktop.screenshot(path="verification/screenshots/desktop_contact_submitted.png")

        page_desktop.close()

        # --- MOBILE TESTING ---
        page_mobile = browser.new_page(viewport={"width": 375, "height": 667})
        page_mobile.goto("http://127.0.0.1:8000/?simulation_mode=1")
        page_mobile.wait_for_load_state("networkidle")
        trigger_scroll_animations(page_mobile)

        # Mobile Home Screenshot
        page_mobile.screenshot(path="verification/screenshots/mobile_light.png", full_page=True)
        print("Captured mobile_light.png")

        # Open mobile drawer menu
        nav_toggle = page_mobile.locator("#nav-toggle")
        nav_toggle.click(force=True)
        time.sleep(0.5)
        page_mobile.screenshot(path="verification/screenshots/mobile_menu_open.png")
        print("Captured mobile_menu_open.png")

        page_mobile.close()
        browser.close()

if __name__ == "__main__":
    run()
