import os
import time
from playwright.sync_api import sync_playwright

def trigger_scroll_animations(page):
    # Perform smooth incremental scrolling to trigger ScrollReveal animations
    for i in range(10):
        page.mouse.wheel(0, 500)
        page.wait_for_timeout(200)
    # Scroll back to top instantly for clean screenshots
    page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
    page.wait_for_timeout(300)

def verify_ui():
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)

        # ---------------- DESKTOP TESTING (1280x800) ----------------
        context_desktop = browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos/"
        )
        page_desktop = context_desktop.new_page()
        page_desktop.goto("http://127.0.0.1:8000")
        page_desktop.wait_for_load_state("networkidle")

        trigger_scroll_animations(page_desktop)

        # Desktop Light Theme Screenshot
        page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=False)

        # Toggle Dark Theme
        theme_btn = page_desktop.locator("#theme-button")
        theme_btn.click()
        page_desktop.wait_for_timeout(300)
        page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=False)

        # Scroll down to Contact Form and fill details
        page_desktop.locator('#nav-menu a[href="#contact"]').click()
        page_desktop.wait_for_timeout(500)

        page_desktop.fill("#name", "Budi Santoso")
        page_desktop.fill("#email", "budi.santoso@example.com")
        page_desktop.fill("#subject", "Permintaan Penawaran Website")
        page_desktop.fill("#message", "Halo Ar Creative, saya tertarik untuk menggunakan jasa pembuatan website portofolio studio kami.")

        # Append simulation_mode hidden field or submit directly
        page_desktop.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'simulation_mode';
            hidden.value = '1';
            form.appendChild(hidden);
        }""")

        page_desktop.click('#contact-form button[type="submit"]')
        page_desktop.locator("#contact-message").wait_for(state="visible")
        page_desktop.wait_for_timeout(500)
        page_desktop.screenshot(path="verification/screenshots/contact_success.png", full_page=False)

        context_desktop.close()

        # ---------------- MOBILE TESTING (375x667) ----------------
        context_mobile = browser.new_context(
            viewport={"width": 375, "height": 667},
            is_mobile=True,
            has_touch=True
        )
        page_mobile = context_mobile.new_page()
        page_mobile.goto("http://127.0.0.1:8000")
        page_mobile.wait_for_load_state("networkidle")

        trigger_scroll_animations(page_mobile)

        # Open Mobile Navigation Menu
        nav_toggle = page_mobile.locator("#nav-toggle")
        nav_toggle.click(force=True)
        page_mobile.wait_for_timeout(300)
        page_mobile.screenshot(path="verification/screenshots/mobile_menu_open.png", full_page=False)

        # Navigate to About section via Mobile Menu
        nav_about = page_mobile.locator('#nav-menu a[href="#about"]')
        nav_about.click()
        page_mobile.wait_for_timeout(500)
        page_mobile.screenshot(path="verification/screenshots/mobile_about.png", full_page=False)

        context_mobile.close()
        browser.close()

    print("UI verification script completed successfully!")

if __name__ == "__main__":
    verify_ui()
