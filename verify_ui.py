import os
import time
from playwright.sync_api import sync_playwright

def trigger_scroll_animations(page):
    # Scroll down gradually to activate any observer or animation
    for _ in range(8):
        page.mouse.wheel(0, 600)
        time.sleep(0.15)
    # Scroll back to top instantly so screenshots are clean and top-aligned
    page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
    time.sleep(0.3)

def run_verification():
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    with sync_playwright() as p:
        browser = p.chromium.launch()

        # Desktop Verification
        context_desktop = browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos"
        )
        page = context_desktop.new_page()
        page.goto("http://127.0.0.1:8000")
        page.wait_for_selector("#home")
        trigger_scroll_animations(page)

        # Light theme full page screenshot
        page.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)

        # Theme toggle to dark mode
        page.click("#theme-button")
        time.sleep(0.5)
        page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)

        # Contact form submission with simulation_mode
        page.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'simulation_mode';
            hidden.value = '1';
            form.appendChild(hidden);
        }""")

        page.fill("#user_name", "Budi Santoso")
        page.fill("#user_email", "budi@example.com")
        page.fill("#user_project", "Halo Ar Creative, saya tertarik untuk kolaborasi pembuatan produk digital dan pelatihan web.")

        page.click("#contact-form button[type='submit']")
        page.locator("#contact-message").wait_for(state="visible")
        time.sleep(0.8)

        page.screenshot(path="verification/screenshots/contact_submitted.png")
        context_desktop.close()

        # Mobile Verification
        context_mobile = browser.new_context(
            viewport={"width": 375, "height": 667},
            is_mobile=True,
            record_video_dir="verification/videos"
        )
        mobile_page = context_mobile.new_page()
        mobile_page.goto("http://127.0.0.1:8000")
        mobile_page.wait_for_selector("#home")
        trigger_scroll_animations(mobile_page)

        mobile_page.screenshot(path="verification/screenshots/mobile_light.png", full_page=True)

        # Mobile menu open test
        mobile_page.click("#nav-toggle", force=True)
        time.sleep(0.5)
        mobile_page.screenshot(path="verification/screenshots/mobile_menu_open.png")

        # Mobile menu close
        mobile_page.click("#nav-close", force=True)
        time.sleep(0.5)

        context_mobile.close()
        browser.close()
        print("Playwright UI verification passed successfully!")

if __name__ == "__main__":
    run_verification()
