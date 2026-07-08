from playwright.sync_api import sync_playwright
import subprocess
import time
import os

def run_cuj(page):
    # Adjust to PHP server port
    url = "http://127.0.0.1:8000"

    # 1. Desktop Journey: Scroll and Explore
    page.set_viewport_size({"width": 1280, "height": 800})
    page.goto(url)
    page.wait_for_timeout(1000)

    # Scroll through sections to trigger animations
    for i in range(1, 6):
        page.mouse.wheel(0, 800)
        page.wait_for_timeout(800)

    page.screenshot(path="verification/screenshots/desktop_full.png", full_page=True)
    page.wait_for_timeout(1000)

    # 2. Theme Toggle
    page.click("#theme-button")
    page.wait_for_timeout(1000)
    page.screenshot(path="verification/screenshots/desktop_dark_mode.png")
    page.click("#theme-button") # Toggle back
    page.wait_for_timeout(500)

    # 3. Mobile Journey: Navigation
    page.set_viewport_size({"width": 375, "height": 667})
    page.goto(url)
    page.wait_for_timeout(1000)

    page.click("#nav-toggle")
    page.wait_for_timeout(1000)
    page.screenshot(path="verification/screenshots/mobile_menu.png")

    # Click a link to close menu
    page.click("text=Layanan")
    page.wait_for_timeout(1000)

    # 4. Contact Form Submission
    # Scroll to contact
    page.goto(url + "#contact")
    page.wait_for_timeout(1000)

    page.fill("#user_name", "Arinus")
    page.wait_for_timeout(500)
    page.fill("#user_email", "ari@example.com")
    page.wait_for_timeout(500)
    page.fill("#user_project", "Halo Ar Creative, saya tertarik dengan layanan Jasa Kreatif Digital Anda.")
    page.wait_for_timeout(500)

    # Inject simulation mode
    page.evaluate("""
        const form = document.getElementById('contact-form');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'simulation_mode';
        input.value = '1';
        form.appendChild(input);
    """)

    page.click("button[type='submit']")
    page.wait_for_timeout(2000)

    page.screenshot(path="verification/screenshots/contact_success.png")
    page.wait_for_timeout(1000)

if __name__ == "__main__":
    # Start PHP server
    php_server = subprocess.Popen(
        ['php', '-S', '127.0.0.1:8000', '-t', 'ar-creative'],
        stdout=subprocess.DEVNULL,
        stderr=subprocess.DEVNULL
    )
    time.sleep(2)

    try:
        with sync_playwright() as p:
            browser = p.chromium.launch(headless=True)
            context = browser.new_context(
                record_video_dir="verification/videos"
            )
            page = context.new_page()
            run_cuj(page)
            context.close()
            browser.close()
    finally:
        php_server.terminate()
