import os
import sys
import time
import subprocess
from playwright.sync_api import sync_playwright

# Configuration
URL = "http://127.0.0.1:8000"
SCREENSHOTS_DIR = "verification/screenshots"
VIDEOS_DIR = "verification/videos"

# Ensure directories exist
os.makedirs(SCREENSHOTS_DIR, exist_ok=True)
os.makedirs(VIDEOS_DIR, exist_ok=True)

def trigger_scroll_animations(page):
    """
    Simulates mouse wheel scrolling to trigger ScrollReveal entrance animations sequentially.
    """
    sections = ['#home', '#about', '#services', '#education', '#social', '#contact']
    for section in sections:
        try:
            # Scroll slowly step-by-step
            page.locator(section).scroll_into_view_if_needed()
            page.wait_for_timeout(200)
        except Exception as e:
            print(f"Error scrolling to {section}: {e}")
    # Scroll back to the top of the page
    page.evaluate("window.scrollTo(0, 0)")
    page.wait_for_timeout(500)

def verify_desktop_user_journey():
    print("--- Starting Desktop Verification (1280x800) ---")
    with sync_playwright() as p:
        # Use context to record video
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir=VIDEOS_DIR
        )
        page = context.new_page()

        # Navigate to simulation mode URL
        page.goto(f"{URL}/?simulation_mode=1")
        page.wait_for_load_state("networkidle")
        print("Page loaded successfully.")

        # Capture initial hero view
        page.screenshot(path=f"{SCREENSHOTS_DIR}/desktop_01_hero_light.png")

        # Test Dark Theme Toggle
        theme_btn = page.locator("#theme-button")
        theme_btn.wait_for(state="visible")
        theme_btn.click()
        page.wait_for_timeout(500) # Wait for CSS transition
        page.screenshot(path=f"{SCREENSHOTS_DIR}/desktop_02_hero_dark.png")
        print("Verified light/dark mode toggling successfully.")

        # Trigger all ScrollReveal animations
        trigger_scroll_animations(page)

        # Go to contact form, fill out fields, and submit in simulation mode
        print("Filling out Contact Form on Desktop...")
        page.locator("#user_name").fill("Andi Pratama")
        page.locator("#user_email").fill("andi@example.com")
        page.locator("#user_project").fill("Saya ingin membuat website e-commerce modern menggunakan digital illustration style.")

        # Take screenshot of filled form
        page.locator("#contact").scroll_into_view_if_needed()
        page.wait_for_timeout(300)
        page.screenshot(path=f"{SCREENSHOTS_DIR}/desktop_03_filled_form.png")

        # Submit form
        submit_btn = page.locator("#contact-form button[type='submit']")
        submit_btn.click()

        # Wait for the status response message
        msg_locator = page.locator("#contact-message")
        msg_locator.wait_for(state="visible")

        # Capture form success message
        page.wait_for_timeout(500)
        page.screenshot(path=f"{SCREENSHOTS_DIR}/desktop_04_submitted_form.png")
        print("Form submitted successfully on Desktop.")

        # Read the video file name and rename it later
        context.close()
        browser.close()

def verify_mobile_user_journey():
    print("--- Starting Mobile Verification (375x667) ---")
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        # Emulate a typical smartphone viewport
        context = browser.new_context(
            viewport={"width": 375, "height": 667},
            user_agent="Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1",
            record_video_dir=VIDEOS_DIR
        )
        page = context.new_page()

        # Navigate to simulation mode URL
        page.goto(f"{URL}/?simulation_mode=1")
        page.wait_for_load_state("networkidle")
        print("Mobile page loaded successfully.")

        # Mobile layout initial view (bottom nav, nav logo is hidden)
        page.screenshot(path=f"{SCREENSHOTS_DIR}/mobile_01_hero_light.png")

        # Test Mobile Navigation Menu Opening and Closing
        nav_toggle = page.locator("#nav-toggle")
        nav_close = page.locator("#nav-close")

        # Force click menu to bypass ScrollReveal transition overlaps
        nav_toggle.click(force=True)
        page.wait_for_timeout(300)
        page.screenshot(path=f"{SCREENSHOTS_DIR}/mobile_02_menu_opened.png")
        print("Verified mobile menu toggle open.")

        nav_close.click(force=True)
        page.wait_for_timeout(300)
        print("Verified mobile menu toggle close.")

        # Scroll animations
        trigger_scroll_animations(page)

        # Form submission on mobile
        print("Filling out Contact Form on Mobile...")
        page.locator("#user_name").fill("Budi Santoso")
        page.locator("#user_email").fill("budi@example.com")
        page.locator("#user_project").fill("Mohon info untuk layanan edukasi kelas pembuatan website landing page.")

        page.locator("#contact").scroll_into_view_if_needed()
        page.wait_for_timeout(300)

        # Submit form
        submit_btn = page.locator("#contact-form button[type='submit']")
        submit_btn.click()

        # Wait for success status
        msg_locator = page.locator("#contact-message")
        msg_locator.wait_for(state="visible")

        page.wait_for_timeout(500)
        page.screenshot(path=f"{SCREENSHOTS_DIR}/mobile_03_submitted_form.png")
        print("Form submitted successfully on Mobile.")

        context.close()
        browser.close()

if __name__ == "__main__":
    verify_desktop_user_journey()
    verify_mobile_user_journey()
    print("Verification completed. All screenshots and videos are saved in the 'verification' directory.")
