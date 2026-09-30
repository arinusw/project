import sys
import time
from playwright.sync_api import sync_playwright

def trigger_scroll_animations(page):
    # Scroll down gradually to trigger ScrollReveal entrance animations
    for i in range(10):
        page.mouse.wheel(0, 800)
        time.sleep(0.3)
    # Scroll back to top instantly so screenshots start at top
    page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
    time.sleep(0.5)

def run_verification():
    print("Starting Playwright Verification...")
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)

        # 1. Desktop Verification
        print("--- Desktop Verification (1280x800) ---")
        context_desktop = browser.new_context(viewport={"width": 1280, "height": 800})
        page_desktop = context_desktop.new_page()
        page_desktop.goto("http://1280.0.0.1:8000" if False else "http://127.0.0.1:8000")
        page_desktop.wait_for_load_state("networkidle")

        trigger_scroll_animations(page_desktop)

        # Capture Desktop Light Theme
        page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=False)
        print("Saved desktop_light.png")

        # Test Dark Theme Toggle Desktop
        theme_btn = page_desktop.locator("#theme-button")
        theme_btn.click()
        time.sleep(0.5)
        page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=False)
        print("Saved desktop_dark.png")

        # Toggle back to light
        theme_btn.click()
        time.sleep(0.3)

        # Test Contact Form Submission Desktop
        print("Testing Contact Form...")
        page_desktop.locator("#contact-name").fill("Penguji Studio")
        page_desktop.locator("#contact-email").fill("penguji@example.com")
        page_desktop.locator("#contact-subject").fill("Inkuiri Layanan Studio")
        page_desktop.locator("#contact-message").fill("Halo Ar Creative, saya tertarik dengan layanan pembuatan produk digital.")

        # Inject simulation mode input
        page_desktop.evaluate("""
            const form = document.getElementById('contact-form');
            const simInput = document.createElement('input');
            simInput.type = 'hidden';
            simInput.name = 'simulation_mode';
            simInput.value = '1';
            form.appendChild(simInput);
        """)

        # Submit form
        page_desktop.locator("#contact-form button[type='submit']").click()
        time.sleep(1)

        page_desktop.locator("#contact-response-message").wait_for(state="visible")
        response_text = page_desktop.locator("#contact-response-message").inner_text()
        print("Form response message:", response_text)

        # Scroll down to contact section to capture form response screenshot
        page_desktop.locator("#contact").scroll_into_view_if_needed()
        time.sleep(0.5)
        page_desktop.screenshot(path="verification/screenshots/desktop_contact_response.png", full_page=False)
        print("Saved desktop_contact_response.png")

        context_desktop.close()

        # 2. Mobile Verification
        print("--- Mobile Verification (375x667) ---")
        context_mobile = browser.new_context(
            viewport={"width": 375, "height": 667},
            user_agent="Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1"
        )
        page_mobile = context_mobile.new_page()
        page_mobile.goto("http://127.0.0.1:8000")
        page_mobile.wait_for_load_state("networkidle")

        trigger_scroll_animations(page_mobile)

        # Capture Mobile Light
        page_mobile.screenshot(path="verification/screenshots/mobile_light.png", full_page=False)
        print("Saved mobile_light.png")

        # Test Mobile Navigation Drawer
        print("Testing Mobile Drawer Menu...")
        nav_toggle = page_mobile.locator("#nav-toggle")
        nav_toggle.click(force=True)
        time.sleep(0.5)
        page_mobile.screenshot(path="verification/screenshots/mobile_menu_open.png", full_page=False)
        print("Saved mobile_menu_open.png")

        # Click link in mobile drawer using nav-menu scope
        nav_link_about = page_mobile.locator("#nav-menu a[href='#about']")
        nav_link_about.click(force=True)
        time.sleep(0.5)

        # Capture Mobile About Section
        page_mobile.screenshot(path="verification/screenshots/mobile_about.png", full_page=False)
        print("Saved mobile_about.png")

        context_mobile.close()
        browser.close()

    print("Playwright Verification Finished Successfully.")

if __name__ == "__main__":
    run_verification()
