import time
from playwright.sync_api import sync_playwright

def trigger_scroll_animations(page):
    # Scroll down incrementally to trigger ScrollReveal animations
    for i in range(1, 10):
        page.mouse.wheel(0, 500)
        time.sleep(0.1)
    # Scroll back to top
    page.evaluate("window.scrollTo(0, 0)")
    time.sleep(0.3)

def run_tests():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)

        # --- DESKTOP TEST ---
        page_desk = browser.new_page(viewport={"width": 1280, "height": 800})
        page_desk.goto("http://127.0.0.1:8000/index.html")
        page_desk.wait_for_load_state("networkidle")
        trigger_scroll_animations(page_desk)

        # Light mode screenshot
        page_desk.screenshot(path="verification/screenshots/desktop_light.png", full_page=False)

        # Toggle Dark mode
        page_desk.click("#theme-button")
        time.sleep(0.3)
        page_desk.screenshot(path="verification/screenshots/desktop_dark.png", full_page=False)

        # Contact form submission test
        page_desk.fill("#name", "Penguji Sistem")
        page_desk.fill("#email", "tester@example.com")
        page_desk.fill("#message", "Halo Ar Creative, ini adalah pesan pengujian otomatis.")

        # Inject simulation_mode in form submit via JS or hidden input
        page_desk.evaluate("""
            const form = document.getElementById('contact-form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'simulation_mode';
            input.value = '1';
            form.appendChild(input);
        """)

        page_desk.click('#contact-form button[type="submit"]')
        page_desk.locator("#contact-message").wait_for(state="visible")
        time.sleep(0.5)

        # Screenshot of contact form success
        page_desk.locator("#contact").screenshot(path="verification/screenshots/contact_success.png")

        page_desk.close()

        # --- MOBILE TEST ---
        page_mob = browser.new_page(viewport={"width": 375, "height": 667})
        page_mob.goto("http://127.0.0.1:8000/index.html")
        page_mob.wait_for_load_state("networkidle")
        trigger_scroll_animations(page_mob)

        page_mob.screenshot(path="verification/screenshots/mobile_home.png", full_page=False)

        # Open mobile menu
        page_mob.click("#nav-toggle", force=True)
        time.sleep(0.3)
        page_mob.screenshot(path="verification/screenshots/mobile_menu_open.png", full_page=False)

        # Close mobile menu
        page_mob.click("#nav-close")
        time.sleep(0.3)

        page_mob.close()
        browser.close()

    print("UI Playwright verification completed successfully!")

if __name__ == "__main__":
    run_tests()
