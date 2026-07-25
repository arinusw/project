import time
from playwright.sync_api import sync_playwright

def run_cuj_mobile(page):
    print("Navigating to local PHP server for mobile journey...")
    page.set_viewport_size({"width": 375, "height": 667})
    page.goto("http://127.0.0.1:8000")
    page.wait_for_timeout(1000)

    # Click the mobile menu toggle using force=True to bypass ScrollReveal transition overlay block
    print("Toggling mobile menu navigation...")
    page.click("#nav-toggle", force=True)
    page.wait_for_timeout(1000)

    # Take screenshot of the mobile navigation menu
    print("Capturing mobile navigation menu screenshot...")
    page.screenshot(path="/home/jules/verification/screenshots/mobile_menu.png")
    page.wait_for_timeout(500)

    # Click on the 'Tentang Kami' link to slide menu away and navigate
    print("Navigating via mobile link...")
    # Target via #nav-menu to ensure correct element is chosen
    page.locator("#nav-menu").get_by_text("Tentang Kami").click(force=True)
    page.wait_for_timeout(1000)

    # Take screenshot of the visual view
    page.screenshot(path="/home/jules/verification/screenshots/verification_mobile.png")
    page.wait_for_timeout(1000)

if __name__ == "__main__":
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(
            record_video_dir="/home/jules/verification/videos"
        )
        page = context.new_page()
        try:
            run_cuj_mobile(page)
        finally:
            context.close()
            browser.close()
            print("Finished Mobile CUJ.")
