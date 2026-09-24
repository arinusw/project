import os
import glob
from playwright.sync_api import sync_playwright

def run_cuj():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)

        # 1. Desktop Context Verification & Video
        context_desktop = browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos"
        )
        page_desktop = context_desktop.new_page()
        page_desktop.goto("http://127.0.0.1:8000")
        page_desktop.wait_for_timeout(500)

        # Desktop Screenshot Light Mode
        page_desktop.screenshot(path="verification/screenshots/desktop_light.png")
        page_desktop.wait_for_timeout(500)

        # Toggle Dark Mode
        theme_btn = page_desktop.locator("#theme-button")
        theme_btn.click()
        page_desktop.wait_for_timeout(500)
        page_desktop.screenshot(path="verification/screenshots/desktop_dark.png")
        page_desktop.wait_for_timeout(500)

        # Toggle back to light
        theme_btn.click()
        page_desktop.wait_for_timeout(500)

        # Scroll to contact and fill form
        page_desktop.goto("http://127.0.0.1:8000#contact")
        page_desktop.wait_for_timeout(500)

        page_desktop.fill("#name", "Pengunjung Testing")
        page_desktop.wait_for_timeout(300)
        page_desktop.fill("#email", "pengunjung@example.com")
        page_desktop.wait_for_timeout(300)
        page_desktop.fill("#message", "Halo Ar Creative, ini pesan pengujian otomatis dari Playwright.")
        page_desktop.wait_for_timeout(300)

        # Submit contact form
        page_desktop.click(".contact__button")
        page_desktop.wait_for_timeout(1000)
        page_desktop.screenshot(path="verification/screenshots/contact_success.png")

        context_desktop.close()

        # 2. Mobile Context Verification
        context_mobile = browser.new_context(
            viewport={"width": 375, "height": 667},
            is_mobile=True,
            user_agent="Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1",
            record_video_dir="verification/videos"
        )
        page_mobile = context_mobile.new_page()
        page_mobile.goto("http://127.0.0.1:8000")
        page_mobile.wait_for_timeout(500)

        # Mobile screenshot
        page_mobile.screenshot(path="verification/screenshots/mobile_light.png")
        page_mobile.wait_for_timeout(500)

        # Open mobile drawer navigation
        nav_toggle = page_mobile.locator("#nav-toggle")
        nav_toggle.click()
        page_mobile.wait_for_timeout(500)
        page_mobile.screenshot(path="verification/screenshots/mobile_menu.png")
        page_mobile.wait_for_timeout(500)

        context_mobile.close()
        browser.close()

if __name__ == "__main__":
    run_cuj()
