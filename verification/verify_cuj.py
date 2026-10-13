import time
import os
import glob
import subprocess
from playwright.sync_api import sync_playwright

def run_cuj(page):
    # Navigate to local PHP server
    page.goto("http://127.0.0.1:8000/")
    page.wait_for_load_state("networkidle")
    page.wait_for_timeout(500)

    # 1. Scroll through sections
    sections = ["#about", "#services", "#education", "#social", "#contact"]
    for sec in sections:
        page.evaluate(f"document.querySelector('{sec}').scrollIntoView({{behavior: 'smooth'}})")
        page.wait_for_timeout(600)

    # 2. Scroll back up to contact form and fill it out
    page.locator("#contact-name").fill("Rina Wijaya")
    page.wait_for_timeout(300)
    page.locator("#contact-email").fill("rina@example.com")
    page.wait_for_timeout(300)
    page.locator("#contact-message").fill("Halo, kami ingin bekerja sama untuk pelatihan literasi teknologi.")
    page.wait_for_timeout(300)

    # Append simulation flag for testing
    page.evaluate("""
        const form = document.getElementById('contact-form');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'simulation_mode';
        input.value = '1';
        form.appendChild(input);
    """)

    # Submit form
    page.locator("#contact-form button[type='submit']").click()
    page.wait_for_selector("#contact-message-response.success")
    page.wait_for_timeout(1000)

    # Switch theme to Dark Mode
    page.locator("#theme-button").click()
    page.wait_for_timeout(1000)

    # Take final CUJ screenshot
    page.screenshot(path="verification/screenshots/final_cuj.png")
    page.wait_for_timeout(1000)

if __name__ == "__main__":
    server_process = subprocess.Popen(["php", "-S", "127.0.0.1:8000", "-t", "ar-creative"])
    time.sleep(2)

    try:
        with sync_playwright() as p:
            browser = p.chromium.launch(headless=True)
            context = browser.new_context(
                viewport={'width': 1280, 'height': 800},
                record_video_dir="verification/videos"
            )
            page = context.new_page()
            try:
                run_cuj(page)
            finally:
                context.close()
                browser.close()
            print("CUJ video recording finished!")
    finally:
        server_process.terminate()
