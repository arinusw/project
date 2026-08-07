import os
import time
from playwright.sync_api import sync_playwright

def trigger_scroll_animations(page):
    """Programmatically trigger ScrollReveal animations across the page sections."""
    # Incremental scrolling simulation
    for offset in range(0, 4000, 400):
        page.evaluate(f"window.scrollTo(0, {offset})")
        time.sleep(0.15)
    # Scroll back to top
    page.evaluate("window.scrollTo(0, 0)")
    time.sleep(0.5)

def run_desktop_verification():
    print("Starting Desktop Layout Verification...")
    with sync_playwright() as p:
        browser = p.chromium.launch()
        # Desktop screen configuration: 1280x800
        page = browser.new_page(viewport={"width": 1280, "height": 800})
        page.goto("http://127.0.0.1:8000")

        # Give some time for initial load
        time.sleep(1)

        # 1. Trigger ScrollReveal animations
        trigger_scroll_animations(page)

        # 2. Capture Default (Light Theme) Desktop Screenshot
        screenshot_path_desktop_light = "verification/screenshots/desktop_light_theme.png"
        page.screenshot(path=screenshot_path_desktop_light, full_page=True)
        print(f"Captured Desktop Light Theme screenshot at {screenshot_path_desktop_light}")

        # 3. Toggle Dark Theme
        theme_btn = page.locator("#theme-button")
        theme_btn.click()
        time.sleep(0.5)

        # 4. Capture Dark Theme Desktop Screenshot
        screenshot_path_desktop_dark = "verification/screenshots/desktop_dark_theme.png"
        page.screenshot(path=screenshot_path_desktop_dark, full_page=True)
        print(f"Captured Desktop Dark Theme screenshot at {screenshot_path_desktop_dark}")

        # 5. Fill out and submit contact form with simulation_mode active
        page.evaluate("window.simulation_mode = true;")
        page.fill("#user-name", "Andi Pratama")
        page.fill("#user-email", "andi.pratama@example.com")
        page.fill("#user-project", "Saya tertarik untuk membuat website portofolio UMKM lokal di daerah kami.")

        # Click submit
        submit_btn = page.locator("#contact-form button[type='submit']")
        submit_btn.click()

        # Wait for the status/message response
        message_locator = page.locator("#contact-message")
        message_locator.wait_for(state="visible", timeout=5000)

        success_text = message_locator.text_content()
        print(f"Form submission message received: {success_text}")
        assert "Simulasi" in success_text or "Pesan" in success_text

        # Capture submitted state screenshot
        screenshot_path_desktop_form = "verification/screenshots/desktop_form_submitted.png"
        page.screenshot(path=screenshot_path_desktop_form)
        print(f"Captured form submission status at {screenshot_path_desktop_form}")

        browser.close()

def run_mobile_verification():
    print("Starting Mobile Layout Verification...")
    with sync_playwright() as p:
        browser = p.chromium.launch()
        # Mobile screen configuration: 375x667
        page = browser.new_page(
            viewport={"width": 375, "height": 667},
            user_agent="Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1"
        )
        page.goto("http://127.0.0.1:8000")

        # Open mobile navigation drawer using nav-toggle
        # Use force=True to bypass ScrollReveal transition overlaps
        nav_toggle = page.locator("#nav-toggle")
        nav_toggle.click(force=True)
        time.sleep(0.5)

        # Capture mobile menu active layout
        screenshot_path_mobile_menu = "verification/screenshots/mobile_menu_open.png"
        page.screenshot(path=screenshot_path_mobile_menu)
        print(f"Captured Mobile Drawer open state at {screenshot_path_mobile_menu}")

        # Close mobile menu
        nav_close = page.locator("#nav-close")
        nav_close.click(force=True)
        time.sleep(0.5)

        # Trigger ScrollReveal entrance animations
        trigger_scroll_animations(page)

        # Capture Mobile Light Theme Screenshot
        screenshot_path_mobile_light = "verification/screenshots/mobile_light_theme.png"
        page.screenshot(path=screenshot_path_mobile_light, full_page=True)
        print(f"Captured Mobile Light Theme screenshot at {screenshot_path_mobile_light}")

        # Toggle Dark Theme in Mobile
        theme_btn = page.locator("#theme-button")
        theme_btn.click()
        time.sleep(0.5)

        # Capture Mobile Dark Theme Screenshot
        screenshot_path_mobile_dark = "verification/screenshots/mobile_dark_theme.png"
        page.screenshot(path=screenshot_path_mobile_dark, full_page=True)
        print(f"Captured Mobile Dark Theme screenshot at {screenshot_path_mobile_dark}")

        browser.close()

if __name__ == "__main__":
    run_desktop_verification()
    run_mobile_verification()
    print("Verification execution completed successfully!")
