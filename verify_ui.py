import time
from playwright.sync_api import sync_playwright

def trigger_scroll_animations(page):
    # Scroll through page incrementally to trigger ScrollReveal animations
    for i in range(10):
        page.mouse.wheel(0, 500)
        time.sleep(0.2)
    # Scroll back to top
    page.evaluate("window.scrollTo(0, 0)")
    time.sleep(0.5)

def run_tests():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)

        # ---------------- DESKTOP TEST ----------------
        context_desktop = browser.new_context(viewport={'width': 1280, 'height': 800})
        page_desktop = context_desktop.new_page()
        page_desktop.goto('http://127.0.0.1:8000')
        page_desktop.wait_for_load_state('networkidle')

        trigger_scroll_animations(page_desktop)

        # Take Desktop Light Theme Screenshot
        page_desktop.screenshot(path='verification/screenshots/desktop_light.png', full_page=False)

        # Toggle Dark Theme
        theme_button = page_desktop.locator('#theme-button')
        theme_button.click()
        time.sleep(0.5)

        # Take Desktop Dark Theme Screenshot
        page_desktop.screenshot(path='verification/screenshots/desktop_dark.png', full_page=False)

        # Submit Contact Form (Simulation Mode)
        page_desktop.evaluate("document.getElementById('simulation_mode').value = '1'")
        page_desktop.fill('#name', 'Budi Santoso')
        page_desktop.fill('#email', 'budi@example.com')
        page_desktop.fill('#project', 'Saya ingin membuat website portofolio studio kreatif.')

        # Scroll form into view and submit
        page_desktop.locator('#contact-form').scroll_into_view_if_needed()
        time.sleep(0.3)
        page_desktop.click('.contact__form-button')

        page_desktop.locator('#contact-message').wait_for(state="visible")
        time.sleep(1)

        # Take Contact Form Submitted Screenshot
        page_desktop.screenshot(path='verification/screenshots/desktop_contact_submitted.png', full_page=False)
        context_desktop.close()

        # ---------------- MOBILE TEST ----------------
        context_mobile = browser.new_context(
            viewport={'width': 375, 'height': 667},
            user_agent='Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15'
        )
        page_mobile = context_mobile.new_page()
        page_mobile.goto('http://127.0.0.1:8000')
        page_mobile.wait_for_load_state('networkidle')

        trigger_scroll_animations(page_mobile)

        # Take Mobile Light Theme Screenshot
        page_mobile.screenshot(path='verification/screenshots/mobile_light.png', full_page=False)

        # Test Mobile Menu Drawer Toggle
        page_mobile.click('#nav-toggle', force=True)
        time.sleep(0.5)
        page_mobile.screenshot(path='verification/screenshots/mobile_menu_open.png', full_page=False)

        page_mobile.click('#nav-close')
        time.sleep(0.5)

        # Toggle Mobile Dark Theme
        page_mobile.click('#theme-button')
        time.sleep(0.5)
        page_mobile.screenshot(path='verification/screenshots/mobile_dark.png', full_page=False)

        context_mobile.close()

        browser.close()
        print("UI Verification successfully completed!")

if __name__ == '__main__':
    run_tests()
