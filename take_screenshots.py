from playwright.sync_api import sync_playwright
import subprocess
import time
import os

def take_screenshots():
    # Start local server
    server = subprocess.Popen(['python3', '-m', 'http.server', '8080'], cwd='./ar-creative')
    time.sleep(2) # Wait for server to start

    try:
        with sync_playwright() as p:
            browser = p.chromium.launch(headless=True)
            context = browser.new_context()
            page = context.new_page()

            # Desktop screenshot
            page.set_viewport_size({"width": 1280, "height": 800})
            page.goto('http://localhost:8080')

            # Scroll to ensure all animations trigger and elements are loaded
            page.evaluate("window.scrollTo(0, document.body.scrollHeight)")
            time.sleep(1)
            page.evaluate("window.scrollTo(0, 0)")
            time.sleep(1)

            page.screenshot(path='desktop_home.png', full_page=True)
            print("Desktop screenshot saved.")

            # Mobile screenshot
            page.set_viewport_size({"width": 375, "height": 667})
            page.goto('http://localhost:8080')

            page.evaluate("window.scrollTo(0, document.body.scrollHeight)")
            time.sleep(1)
            page.evaluate("window.scrollTo(0, 0)")
            time.sleep(1)

            page.screenshot(path='mobile_home.png', full_page=True)
            print("Mobile screenshot saved.")

            browser.close()
    finally:
        server.terminate()

if __name__ == "__main__":
    take_screenshots()
