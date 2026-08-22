import os
import time
import subprocess
from playwright.sync_api import sync_playwright

def run_verification():
    # 1. Start local PHP server
    server_process = subprocess.Popen(
        ["php", "-S", "127.0.0.1:8000", "-t", "ar-creative"],
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE
    )
    time.sleep(1) # wait for server initialization

    try:
        with sync_playwright() as p:
            browser = p.chromium.launch(headless=True)

            # Helper for scroll animations
            def trigger_scroll_animations(page):
                for _ in range(5):
                    page.mouse.wheel(0, 800)
                    time.sleep(0.3)
                page.evaluate("window.scrollTo(0, 0)")
                time.sleep(0.3)

            # --- DESKTOP VERIFICATION ---
            desktop_context = browser.new_context(
                viewport={"width": 1280, "height": 800},
                record_video_dir="verification/videos/"
            )
            desktop_page = desktop_context.new_page()
            desktop_page.goto("http://1280.0.0.1:8000/index.html?simulation_mode=1" if False else "http://127.0.0.1:8000/index.html?simulation_mode=1")

            trigger_scroll_animations(desktop_page)
            desktop_page.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)

            # Toggle theme to Dark
            theme_btn = desktop_page.locator("#theme-button")
            theme_btn.click()
            time.sleep(0.5)
            desktop_page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)

            # Test Contact Form
            desktop_page.fill("#user_name", "Budi Pratama")
            desktop_page.fill("#user_email", "budi@example.com")
            desktop_page.fill("#user_project", "Ingin membuat website landing page produk UMKM.")
            desktop_page.click("button[type='submit']")

            # Wait for form response message
            desktop_page.locator("#contact-message").wait_for(state="visible")
            time.sleep(1)
            desktop_page.screenshot(path="verification/screenshots/desktop_form_submitted.png")

            desktop_context.close()

            # --- MOBILE VERIFICATION ---
            mobile_context = browser.new_context(
                viewport={"width": 375, "height": 667},
                is_mobile=True,
                user_agent="Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15",
                record_video_dir="verification/videos/"
            )
            mobile_page = mobile_context.new_page()
            mobile_page.goto("http://127.0.0.1:8000/index.html?simulation_mode=1")

            trigger_scroll_animations(mobile_page)
            mobile_page.screenshot(path="verification/screenshots/mobile_light.png", full_page=True)

            # Test Mobile Drawer Toggle
            nav_toggle = mobile_page.locator("#nav-toggle")
            nav_toggle.click(force=True)
            time.sleep(0.5)
            mobile_page.screenshot(path="verification/screenshots/mobile_menu_open.png")

            # Close Menu
            nav_close = mobile_page.locator("#nav-close")
            nav_close.click(force=True)
            time.sleep(0.5)

            mobile_context.close()
            browser.close()

            print("UI and functional verification completed successfully!")

    finally:
        server_process.terminate()

if __name__ == "__main__":
    run_verification()
