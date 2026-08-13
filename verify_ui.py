import subprocess
import time
import os
import sys
from playwright.sync_api import sync_playwright

def start_server():
    print("Starting local PHP server on port 8000...")
    # Kill existing PHP server on port 8000 if any
    subprocess.run("kill $(lsof -t -i :8000) 2>/dev/null || true", shell=True)
    time.sleep(1)

    # Start server
    proc = subprocess.Popen(
        "php -S 127.0.0.1:8000 -t ar-creative > php_server.log 2>&1 &",
        shell=True,
        preexec_fn=os.setsid
    )
    time.sleep(2)
    return proc

def trigger_scroll_animations(page):
    """Programmatically trigger ScrollReveal animations across the page sections using incremental scroll"""
    print("Triggering scroll animations...")
    page.evaluate("window.scrollTo(0, 0)")
    time.sleep(0.5)

    # Scroll incrementally to reveal elements
    for offset in range(0, 5000, 400):
        page.evaluate(f"window.scrollTo(0, {offset})")
        time.sleep(0.1)

    page.evaluate("window.scrollTo(0, 0)")
    time.sleep(1)

def run_desktop_verification(playwright, url):
    print("Running desktop verification (1280x800)...")
    browser = playwright.chromium.launch(headless=True)

    # Set up context with video recording
    context = browser.new_context(
        viewport={"width": 1280, "height": 800},
        record_video_dir="verification/videos/",
        record_video_size={"width": 1280, "height": 800}
    )

    page = context.new_page()
    page.goto(url)
    time.sleep(1)

    # Initial Home screenshot
    page.screenshot(path="verification/screenshots/desktop_01_home.png")

    # Trigger ScrollReveal animations
    trigger_scroll_animations(page)
    page.screenshot(path="verification/screenshots/desktop_02_all_revealed.png")

    # Theme toggle check
    theme_button = page.locator("#theme-button")
    theme_button.wait_for(state="visible")
    theme_button.click()
    time.sleep(1)
    page.screenshot(path="verification/screenshots/desktop_03_dark_theme.png")

    # Contact Form simulation mode
    page.goto(url + "?simulation_mode=1")
    time.sleep(1)

    # Scroll down to contact form
    page.locator("#contact").scroll_into_view_if_needed()
    time.sleep(1)

    # Fill in the form
    page.locator("#user_name").fill("Automated Test Desktop")
    page.locator("#user_email").fill("desktop@test.com")
    page.locator("#user_project").fill("Halo, ini adalah pesan simulasi otomatis dari pengetesan desktop Playwright.")

    # Submit contact form
    submit_button = page.locator("#contact-form button[type='submit']")
    submit_button.wait_for(state="visible")
    submit_button.click()

    # Wait for success feedback message to appear
    msg_locator = page.locator("#contact-message")
    msg_locator.wait_for(state="visible")
    time.sleep(1)

    page.screenshot(path="verification/screenshots/desktop_04_form_submitted.png")

    context.close()
    browser.close()

def run_mobile_verification(playwright, url):
    print("Running mobile verification (375x667)...")
    browser = playwright.chromium.launch(headless=True)

    # Set up context for mobile viewport
    context = browser.new_context(
        viewport={"width": 375, "height": 667},
        is_mobile=True,
        has_touch=True,
        record_video_dir="verification/videos/",
        record_video_size={"width": 375, "height": 667}
    )

    page = context.new_page()
    page.goto(url)
    time.sleep(1)

    # Mobile Initial Home screenshot
    page.screenshot(path="verification/screenshots/mobile_01_home.png")

    # Mobile Navigation toggle check
    nav_toggle = page.locator("#nav-toggle")
    nav_toggle.wait_for(state="visible")
    # Using force=True to click mobile drawer trigger to bypass ScrollReveal overlays
    nav_toggle.click(force=True)
    time.sleep(1)
    page.screenshot(path="verification/screenshots/mobile_02_drawer_open.png")

    # Close mobile navigation
    nav_close = page.locator("#nav-close")
    nav_close.wait_for(state="visible")
    nav_close.click(force=True)
    time.sleep(1)
    page.screenshot(path="verification/screenshots/mobile_03_drawer_closed.png")

    # Trigger ScrollReveal animations
    trigger_scroll_animations(page)

    # Scroll to Contact Section
    page.goto(url + "?simulation_mode=1")
    time.sleep(1)
    page.locator("#contact").scroll_into_view_if_needed()
    time.sleep(1)

    # Fill in the form
    page.locator("#user_name").fill("Automated Test Mobile")
    page.locator("#user_email").fill("mobile@test.com")
    page.locator("#user_project").fill("Halo, ini adalah pesan simulasi otomatis dari pengetesan mobile Playwright.")

    # Submit contact form
    submit_button = page.locator("#contact-form button[type='submit']")
    submit_button.wait_for(state="visible")
    submit_button.click()

    # Wait for success feedback message to appear
    msg_locator = page.locator("#contact-message")
    msg_locator.wait_for(state="visible")
    time.sleep(1)
    page.screenshot(path="verification/screenshots/mobile_04_form_submitted.png")

    context.close()
    browser.close()

if __name__ == "__main__":
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    server_process = start_server()

    try:
        with sync_playwright() as playwright:
            run_desktop_verification(playwright, "http://127.0.0.1:8000")
            run_mobile_verification(playwright, "http://127.0.0.1:8000")
        print("UI Verification Completed Successfully! Screenshots saved in verification/screenshots/")
    except Exception as e:
        print(f"Error occurred during UI verification: {e}")
        sys.exit(1)
    finally:
        print("Stopping local PHP server...")
        subprocess.run("kill $(lsof -t -i :8000) 2>/dev/null || true", shell=True)
        time.sleep(1)
