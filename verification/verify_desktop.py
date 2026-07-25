import time
from playwright.sync_api import sync_playwright

def run_cuj_desktop(page):
    print("Navigating to local PHP server for desktop journey...")
    page.set_viewport_size({"width": 1280, "height": 800})
    page.goto("http://127.0.0.1:8000")
    page.wait_for_timeout(1000)

    # Trigger ScrollReveal animations by incrementally scrolling
    print("Triggering entrance animations via incremental scrolling...")
    for i in range(1, 9):
        page.evaluate(f"window.scrollTo(0, {i * 500})")
        page.wait_for_timeout(300)

    # Scroll back to top
    print("Scrolling back to top...")
    page.evaluate("window.scrollTo(0, 0)")
    page.wait_for_timeout(1000)

    # Fill and submit form using the required parameters
    print("Interacting with contact form (simulation_mode=1)...")
    page.evaluate("document.getElementById('simulation_mode').value = '1'")
    page.fill("#user_name", "Jules Tester")
    page.wait_for_timeout(300)
    page.fill("#user_email", "jules@example.com")
    page.wait_for_timeout(300)
    page.fill("#user_project", "Halo Ar Creative! Saya ingin memesan sebuah website custom portfolio.")
    page.wait_for_timeout(300)

    # Screenshot before submission
    print("Capturing contact form state before submission...")
    page.locator("#contact").scroll_into_view_if_needed()
    page.wait_for_timeout(500)
    page.screenshot(path="/home/jules/verification/screenshots/contact_form_ready.png")

    # Click submit
    print("Submitting the form...")
    page.click(".contact__submit")

    # Wait for the success message to be visible
    page.locator("#contact-message").wait_for(state="visible", timeout=5000)
    page.wait_for_timeout(1000)

    # Take screenshot of visual success message
    print("Capturing contact submission success screenshot...")
    page.screenshot(path="/home/jules/verification/screenshots/verification_desktop.png")
    page.wait_for_timeout(1000)

if __name__ == "__main__":
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(
            record_video_dir="/home/jules/verification/videos"
        )
        page = context.new_page()
        try:
            run_cuj_desktop(page)
        finally:
            context.close()
            browser.close()
            print("Finished Desktop CUJ.")
