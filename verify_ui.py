import os
import time
from playwright.sync_api import sync_playwright

def run_verification():
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    with sync_playwright() as p:
        # 1. Desktop Verification
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos"
        )
        page = context.new_page()
        page.goto("http://127.0.0.1:8000/index.html")
        page.wait_for_load_state("networkidle")

        # Scroll incrementally to trigger ScrollReveal
        for i in range(10):
            page.mouse.wheel(0, 500)
            time.sleep(0.1)

        page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
        time.sleep(0.5)

        # Light Theme Desktop Screenshot
        page.screenshot(path="verification/screenshots/desktop_light.png", full_page=False)

        # Toggle Dark Theme
        theme_btn = page.locator("#theme-button")
        theme_btn.click()
        time.sleep(0.5)
        page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=False)

        # Contact Form Test
        page.locator("#contact").scroll_into_view_if_needed()
        time.sleep(0.3)
        page.fill("#name", "Budi Santoso")
        page.fill("#email", "budi@example.com")
        page.fill("#message", "Halo Ar Creative, saya tertarik dengan layanan jasa pembuatan produk digital Anda.")

        # Execute contact submit in simulation mode
        page.evaluate("""
            const form = document.getElementById('contact-form');
            const simInput = document.createElement('input');
            simInput.type = 'hidden';
            simInput.name = 'simulation_mode';
            simInput.value = '1';
            form.appendChild(simInput);
        """)
        page.click("button[type='submit']")
        page.wait_for_selector(".contact__message.success", state="visible")
        page.screenshot(path="verification/screenshots/contact_success.png")

        context.close()
        browser.close()

        # 2. Mobile Verification
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(
            viewport={"width": 375, "height": 667},
            record_video_dir="verification/videos"
        )
        page = context.new_page()
        page.goto("http://127.0.0.1:8000/index.html")
        page.wait_for_load_state("networkidle")

        # Open mobile navigation menu
        nav_toggle = page.locator("#nav-toggle")
        nav_toggle.click(force=True)
        time.sleep(0.3)
        page.screenshot(path="verification/screenshots/mobile_menu.png")

        # Click a nav link
        nav_link = page.locator("#nav-menu a[href='#services']").first
        nav_link.click(force=True)
        time.sleep(0.5)
        page.screenshot(path="verification/screenshots/mobile_services.png")

        context.close()
        browser.close()

    print("Verification completed successfully!")

if __name__ == "__main__":
    run_verification()
