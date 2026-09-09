import os
import time
from playwright.sync_api import sync_playwright

def trigger_scroll_animations(page):
    # Scroll down incrementally to trigger ScrollReveal entrance animations
    for offset in range(0, 3000, 500):
        page.mouse.wheel(0, 500)
        page.wait_for_timeout(200)
    page.evaluate("window.scrollTo(0, 0)")
    page.wait_for_timeout(300)

def verify_ui():
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)

        # 1. Desktop Verification
        desktop_context = browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos"
        )
        desktop_page = desktop_context.new_page()
        desktop_page.goto("http://127.0.0.1:8000/")
        desktop_page.wait_for_load_state("networkidle")
        trigger_scroll_animations(desktop_page)

        desktop_page.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)
        print("Captured desktop_light.png")

        # Test Dark Theme Toggle on Desktop
        theme_btn = desktop_page.locator("#theme-button")
        theme_btn.click()
        desktop_page.wait_for_timeout(300)
        desktop_page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)
        print("Captured desktop_dark.png")

        desktop_context.close()

        # 2. Mobile Verification
        mobile_context = browser.new_context(
            viewport={"width": 375, "height": 667},
            is_mobile=True,
            record_video_dir="verification/videos"
        )
        mobile_page = mobile_context.new_page()
        mobile_page.goto("http://127.0.0.1:8000/")
        mobile_page.wait_for_load_state("networkidle")
        trigger_scroll_animations(mobile_page)

        mobile_page.screenshot(path="verification/screenshots/mobile_light.png", full_page=True)
        print("Captured mobile_light.png")

        # Test Mobile Menu Drawer
        nav_toggle = mobile_page.locator("#nav-toggle")
        nav_toggle.click(force=True)
        mobile_page.wait_for_timeout(300)
        mobile_page.screenshot(path="verification/screenshots/mobile_menu_open.png")
        print("Captured mobile_menu_open.png")

        nav_close = mobile_page.locator("#nav-close")
        nav_close.click(force=True)
        mobile_page.wait_for_timeout(300)

        # Test Form Submission in simulation mode
        mobile_page.locator("#contact").scroll_into_view_if_needed()
        mobile_page.wait_for_timeout(300)

        mobile_page.fill("#name", "Budi Santoso")
        mobile_page.fill("#email", "budi@example.com")
        mobile_page.fill("#message", "Halo Ar Creative, saya berminat dengan jasa pembuatan website.")

        # Submit form via JavaScript dispatching submit event to ensure AJAX is triggered cleanly
        mobile_page.evaluate("""
            const form = document.getElementById('contact-form');
            const simInput = document.createElement('input');
            simInput.type = 'hidden';
            simInput.name = 'simulation_mode';
            simInput.value = '1';
            form.appendChild(simInput);
            form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
        """)

        mobile_page.wait_for_function("document.getElementById('contact-message').textContent.length > 0")
        mobile_page.wait_for_timeout(500)
        mobile_page.screenshot(path="verification/screenshots/mobile_contact_success.png")
        print("Captured mobile_contact_success.png")

        mobile_context.close()
        browser.close()

if __name__ == "__main__":
    verify_ui()
