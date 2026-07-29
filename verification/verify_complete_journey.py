import os
from playwright.sync_api import sync_playwright

def run_cuj(page):
    # Navigate to the local server URL
    url = "http://127.0.0.1:8000/index.html"
    print(f"Navigating to {url}...")
    page.goto(url)
    page.wait_for_timeout(1000)

    # 1. Toggle dark theme to show visual change
    print("Action: Click theme toggle to dark theme")
    page.locator("#theme-button").click()
    page.wait_for_timeout(1000)

    # Toggle dark theme back to light theme
    print("Action: Click theme toggle back to light theme")
    page.locator("#theme-button").click()
    page.wait_for_timeout(1000)

    # 2. Incremental scrolling to trigger ScrollReveal animations
    print("Action: Incremental scrolling to load animations")
    for i in range(1, 6):
        page.mouse.wheel(0, 500)
        page.wait_for_timeout(400)

    # 3. Fill and submit the contact form
    print("Action: Filling the Contact Form")
    # Scroll to the contact section first
    page.locator("#contact").scroll_into_view_if_needed()
    page.wait_for_timeout(500)

    # Set the hidden simulation_mode field to '1'
    page.evaluate("document.getElementById('simulation-mode').value = '1'")

    page.fill("#user_name", "Ahmad Ari")
    page.wait_for_timeout(500)
    page.fill("#user_email", "ari@arcreative.studio")
    page.wait_for_timeout(500)
    page.fill("#user_project", "Halo Ar Creative, saya ingin memesan layanan Jasa Kreatif Digital untuk membuat website landing page UMKM kami!")
    page.wait_for_timeout(500)

    print("Action: Submit Contact Form")
    page.locator("#contact-form button[type='submit']").click()

    # Wait for the confirmation message
    contact_message = page.locator("#contact-message")
    contact_message.wait_for(state="visible", timeout=5000)
    page.wait_for_timeout(1000)

    # Take screenshot at the key moment of success
    screenshot_path = "/home/jules/verification/screenshots/cuj_success.png"
    page.screenshot(path=screenshot_path)
    # Also save to /app/verification/screenshots/cuj_success.png
    page.screenshot(path="/app/verification/screenshots/cuj_success.png")
    print(f"✓ Saved key journey screenshot to {screenshot_path}")

    # Hold final state for the video
    page.wait_for_timeout(1500)

if __name__ == "__main__":
    print("Starting Complete User Journey (CUJ) Video and Screenshot verification...")
    # Ensure folders exist
    os.makedirs('/home/jules/verification/videos', exist_ok=True)
    os.makedirs('/home/jules/verification/screenshots', exist_ok=True)
    os.makedirs('/app/verification/videos', exist_ok=True)
    os.makedirs('/app/verification/screenshots', exist_ok=True)

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="/home/jules/verification/videos"
        )
        page = context.new_page()
        try:
            run_cuj(page)
        finally:
            context.close()  # MUST close context to save the video
            browser.close()

    # Copy video to /app/verification/videos
    video_files = os.listdir("/home/jules/verification/videos")
    if video_files:
        latest_video = video_files[0]
        src_video = os.path.join("/home/jules/verification/videos", latest_video)
        dest_video = os.path.join("/app/verification/videos", "cuj_video.webm")
        os.system(f"cp {src_video} {dest_video}")
        print(f"✓ Copied latest recording to {dest_video}")
    else:
        print("✗ No video file found.")

    print("CUJ Verification complete.")
