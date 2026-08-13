import subprocess
import time
import os
import sys
from playwright.sync_api import sync_playwright

def start_server():
    print("Starting local PHP server on port 8000 for Complete Journey...")
    subprocess.run("kill $(lsof -t -i :8000) 2>/dev/null || true", shell=True)
    time.sleep(1)

    proc = subprocess.Popen(
        "php -S 127.0.0.1:8000 -t ar-creative > php_server_cuj.log 2>&1 &",
        shell=True,
        preexec_fn=os.setsid
    )
    time.sleep(2)
    return proc

def trigger_scroll_animations(page):
    """Programmatically trigger ScrollReveal animations across the page sections using mouse wheel simulation"""
    print("Simulating mouse wheel scroll for trigger_scroll_animations...")
    page.evaluate("window.scrollTo(0, 0)")
    time.sleep(0.5)

    # Simulate realistic mouse scroll down
    for _ in range(12):
        page.mouse.wheel(0, 400)
        time.sleep(0.2)

    time.sleep(1)
    page.evaluate("window.scrollTo(0, 0)")
    time.sleep(0.5)

def run_complete_journey(playwright, url):
    print("Running Complete Journey Video Capture (Desktop 1280x800)...")
    browser = playwright.chromium.launch(headless=True)

    context = browser.new_context(
        viewport={"width": 1280, "height": 800},
        record_video_dir="verification/videos/",
        record_video_size={"width": 1280, "height": 800}
    )

    page = context.new_page()
    page.goto(url)
    time.sleep(1.5)

    # 1. Capture Hero Section
    print("Step 1: Capture Hero")
    page.screenshot(path="verification/screenshots/cuj_01_hero.png")

    # 2. Trigger ScrollReveal animations
    print("Step 2: Triggering ScrollReveal animations")
    trigger_scroll_animations(page)

    # 3. Dynamic Header on scroll
    print("Step 3: Scroll Header active class check")
    page.mouse.wheel(0, 100)
    time.sleep(0.5)
    page.screenshot(path="verification/screenshots/cuj_02_scrolled_header.png")

    # 4. Scroll Up button visibility
    print("Step 4: Checking Scroll Up button")
    page.mouse.wheel(0, 500)
    time.sleep(0.5)
    page.screenshot(path="verification/screenshots/cuj_03_scroll_up_visible.png")

    # Click Scroll Up button
    scroll_up = page.locator("#scroll-up")
    scroll_up.wait_for(state="visible")
    scroll_up.click()
    time.sleep(1)

    # 5. Theme toggle check
    print("Step 5: Testing Theme Switch")
    theme_button = page.locator("#theme-button")
    theme_button.click()
    time.sleep(1)
    page.screenshot(path="verification/screenshots/cuj_04_dark_theme.png")

    # Switch back to light theme for form submission
    theme_button.click()
    time.sleep(1)

    # 6. Fill and submit Contact Form with simulation_mode=1
    print("Step 6: Form Submission (Simulation)")
    page.goto(url + "?simulation_mode=1")
    time.sleep(1)

    # Navigate directly to section hash contact or scroll
    page.goto(url + "?simulation_mode=1#contact")
    time.sleep(1.5)

    # Ensure inputs are correctly associated with labels and interactive
    page.locator("#user_name").fill("Ar Creative Customer")
    page.locator("#user_email").fill("customer@arcreative.id")
    page.locator("#user_project").fill("Saya sangat tertarik dengan jasa pengembangan produk digital dan edukasi teknologi yang ditawarkan oleh Ar Creative. Mari kita berkolaborasi!")

    page.screenshot(path="verification/screenshots/cuj_05_form_filled.png")

    submit_button = page.locator("#contact-form button[type='submit']")
    submit_button.wait_for(state="visible")
    submit_button.click()

    # Wait for success message
    msg_locator = page.locator("#contact-message")
    msg_locator.wait_for(state="visible")
    time.sleep(1)

    page.screenshot(path="verification/screenshots/cuj_06_success_message.png")

    context.close()

    # Rename recorded video file
    video_path = page.video.path()
    browser.close()

    target_video_path = "/home/jules/verification/videos/cuj_journey.webm"
    os.makedirs(os.path.dirname(target_video_path), exist_ok=True)
    if os.path.exists(video_path):
        os.rename(video_path, target_video_path)
        print(f"Customer User Journey video recorded and saved as: {target_video_path}")
    else:
        print("Warning: CUJ journey video not found.")

if __name__ == "__main__":
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    server_process = start_server()

    try:
        with sync_playwright() as playwright:
            run_complete_journey(playwright, "http://127.0.0.1:8000")
        print("CUJ Verification completed successfully.")
    except Exception as e:
        print(f"Error occurred during CUJ verification: {e}")
        sys.exit(1)
    finally:
        print("Stopping local PHP server...")
        subprocess.run("kill $(lsof -t -i :8000) 2>/dev/null || true", shell=True)
        time.sleep(1)
