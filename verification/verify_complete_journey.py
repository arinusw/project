import os
import time
from playwright.sync_api import sync_playwright

def record_journey():
    os.makedirs('verification/videos', exist_ok=True)
    os.makedirs('verification/screenshots', exist_ok=True)

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir='verification/videos/'
        )
        page = context.new_page()

        # 1. Load homepage
        page.goto('http://127.0.0.1:8000')
        page.wait_for_selector('.home__title')
        page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
        time.sleep(1)

        # 2. Toggle Theme
        page.click('#theme-button')
        time.sleep(1)

        # 3. Scroll through sections
        sections = ['#about', '#services', '#education', '#social', '#contact']
        for sec in sections:
            page.evaluate(f"document.querySelector('{sec}').scrollIntoView({{behavior: 'smooth'}})")
            time.sleep(1.5)

        # 4. Fill and Submit Contact Form
        page.fill('#contact-name', 'Mitra Digital')
        page.fill('#contact-email', 'mitra@arcreative.id')
        page.fill('#contact-subject', 'Permintaan Kerjasama Studio')
        page.fill('#contact-message', 'Halo Ar Creative, kami berminat bekerja sama dalam pembuatan produk digital.')
        time.sleep(1)
        page.click('#contact-form button[type="submit"]')

        page.wait_for_selector('.contact__message.success')
        time.sleep(2)

        # 5. Capture final screenshot
        page.screenshot(path='verification/screenshots/final_cuj.png')

        context.close()
        browser.close()

    print("Complete user journey recorded and verified successfully.")

if __name__ == '__main__':
    record_journey()
