import os
import sys
import time
import subprocess
from playwright.sync_api import sync_playwright

def trigger_scroll_animations(page):
    """Programmatically trigger ScrollReveal animations across page sections."""
    viewport_height = page.viewport_size['height'] if page.viewport_size else 800
    total_height = page.evaluate("document.body.scrollHeight")
    current_scroll = 0
    while current_scroll < total_height:
        page.mouse.wheel(0, 600)
        current_scroll += 600
        page.wait_for_timeout(200)
    page.evaluate("window.scrollTo(0, 0)")
    page.wait_for_timeout(300)

def main():
    print("Starting PHP built-in server for verification...")
    server_process = subprocess.Popen(
        ["php", "-S", "127.0.0.1:8000", "-t", "ar-creative"],
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE
    )
    time.sleep(1.5)

    try:
        with sync_playwright() as p:
            browser = p.chromium.launch(headless=True)

            # --- 1. Desktop Test ---
            print("Running Desktop UI verification (1280x800)...")
            context_desktop = browser.new_context(
                viewport={"width": 1280, "height": 800},
                record_video_dir="verification/videos/"
            )
            page_desktop = context_desktop.new_page()
            page_desktop.goto("http://1280.0.0.1:8000" if False else "http://127.0.0.1:8000")
            page_desktop.wait_for_load_state("networkidle")

            trigger_scroll_animations(page_desktop)

            # Take desktop light mode screenshot
            page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)

            # Test Dark Theme Toggle
            theme_btn = page_desktop.locator("#theme-button")
            theme_btn.click()
            page_desktop.wait_for_timeout(500)
            page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)

            # Test Contact Form Submission in Simulation Mode
            page_desktop.locator("#contact").scroll_into_view_if_needed()
            page_desktop.fill("#user_name", "Budi Santoso")
            page_desktop.fill("#user_email", "budi@example.com")
            page_desktop.fill("#user_project", "Ingin membuat website landing page untuk studio musik.")

            # Append simulation_mode hidden field or evaluate JS fetch
            page_desktop.evaluate("""
                const form = document.getElementById('contact-form');
                let simInput = document.createElement('input');
                simInput.type = 'hidden';
                simInput.name = 'simulation_mode';
                simInput.value = '1';
                form.appendChild(simInput);
            """)

            page_desktop.click("#contact-form button[type='submit']")
            page_desktop.locator("#contact-message").wait_for(state="visible")
            page_desktop.wait_for_timeout(1000)
            page_desktop.screenshot(path="verification/screenshots/desktop_contact_success.png")

            context_desktop.close()

            # --- 2. Mobile Test ---
            print("Running Mobile UI verification (375x667)...")
            context_mobile = browser.new_context(
                viewport={"width": 375, "height": 667},
                is_mobile=True,
                has_touch=True,
                record_video_dir="verification/videos/"
            )
            page_mobile = context_mobile.new_page()
            page_mobile.goto("http://127.0.0.1:8000")
            page_mobile.wait_for_load_state("networkidle")

            # Open Mobile Drawer Menu
            page_mobile.click("#nav-toggle", force=True)
            page_mobile.wait_for_timeout(500)
            page_mobile.screenshot(path="verification/screenshots/mobile_menu_open.png")

            # Close Menu
            page_mobile.click("#nav-close")
            page_mobile.wait_for_timeout(300)

            trigger_scroll_animations(page_mobile)

            # Mobile full page screenshot
            page_mobile.screenshot(path="verification/screenshots/mobile_full_light.png", full_page=True)

            context_mobile.close()

            browser.close()
            print("Verification completed successfully!")

    finally:
        server_process.terminate()
        server_process.wait()

if __name__ == "__main__":
    main()
