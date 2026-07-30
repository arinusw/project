import os
import time
import subprocess
import signal
from playwright.sync_api import sync_playwright

def start_server():
    print("Starting PHP local server on http://127.0.0.1:8000...")
    # Clean up any existing process on port 8000
    try:
        subprocess.run("fuser -k 8000/tcp", shell=True, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
    except Exception:
        pass

    server_process = subprocess.Popen(
        "php -S 127.0.0.1:8000 -t ar-creative > php_server.log 2>&1 &",
        shell=True,
        preexec_fn=os.setsid
    )
    time.sleep(2)  # Allow server to spin up
    return server_process

def stop_server(process):
    print("Stopping PHP local server...")
    try:
        os.killpg(os.getpgid(process.pid), signal.SIGTERM)
    except Exception as e:
        print(f"Error stopping server: {e}")
    try:
        subprocess.run("fuser -k 8000/tcp", shell=True, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
    except Exception:
        pass

def run_desktop_journey(context):
    print("Running desktop journey...")
    page = context.new_page()
    page.set_viewport_size({"width": 1280, "height": 800})

    # 1. Load Homepage
    page.goto("http://127.0.0.1:8000")
    page.wait_for_timeout(1000)

    # 2. Theme Switching (Light -> Dark -> Light)
    theme_btn = page.locator("#theme-button")
    theme_btn.wait_for(state="visible")

    # Click to toggle to Dark Mode
    theme_btn.click()
    page.wait_for_timeout(1000)
    assert page.eval_on_selector("body", "el => el.classList.contains('dark-theme')"), "Dark theme class was not added!"

    # Click to toggle back to Light Mode
    theme_btn.click()
    page.wait_for_timeout(1000)
    assert not page.eval_on_selector("body", "el => el.classList.contains('dark-theme')"), "Dark theme class was not removed!"

    # Click once more to show off Dark Mode in the rest of the journey!
    theme_btn.click()
    page.wait_for_timeout(1000)

    # 3. Incremental Scrolling to trigger ScrollReveal animations
    print("Performing incremental scrolling on desktop...")
    for i in range(1, 6):
        page.mouse.wheel(0, 700)
        page.wait_for_timeout(500)

    # Scroll back to the contact form to fill it
    contact_section = page.locator("#contact")
    contact_section.scroll_into_view_if_needed()
    page.wait_for_timeout(800)

    # 4. Fill and Submit Contact Form under Simulation Mode
    print("Filling and submitting contact form...")
    page.locator("#simulation_mode").evaluate("el => el.value = '1'") # Ensure simulation mode is enabled
    page.fill("#user_name", "Ahmad Fauzi")
    page.wait_for_timeout(300)
    page.fill("#user_email", "ahmad.fauzi@example.com")
    page.wait_for_timeout(300)
    page.fill("#user_project", "Saya tertarik untuk membuat website portofolio bisnis dan belajar programming bersama Ar Creative.")
    page.wait_for_timeout(500)

    # Click Submit
    page.locator("button[type='submit']").click()
    page.wait_for_timeout(500)

    # Wait for response message
    msg_loc = page.locator("#contact-message")
    msg_loc.wait_for(state="visible")
    page.wait_for_timeout(2000)

    # Capture Screenshot in Desktop Viewport
    page.screenshot(path="/home/jules/verification/screenshots/desktop_verification.png", full_page=True)
    print("Desktop verification screenshot captured!")
    page.wait_for_timeout(1000)
    page.close()

def run_mobile_journey(context):
    print("Running mobile journey...")
    page = context.new_page()
    page.set_viewport_size({"width": 375, "height": 667})

    # 1. Load Homepage
    page.goto("http://127.0.0.1:8000")
    page.wait_for_timeout(1000)

    # 2. Test Mobile Nav Toggle (Open Menu)
    print("Testing mobile nav menu toggle...")
    # Using force=True to bypass ScrollReveal animation transition overlays
    toggle_btn = page.locator("#nav-toggle")
    toggle_btn.wait_for(state="visible")
    toggle_btn.click(force=True)
    page.wait_for_timeout(1000)
    assert page.eval_on_selector("#nav-menu", "el => el.classList.contains('show-menu')"), "Mobile menu was not opened!"

    # 3. Test Mobile Nav Close (Close Menu)
    close_btn = page.locator("#nav-close")
    close_btn.wait_for(state="visible")
    close_btn.click(force=True)
    page.wait_for_timeout(1000)
    assert not page.eval_on_selector("#nav-menu", "el => el.classList.contains('show-menu')"), "Mobile menu was not closed!"

    # 4. Open Menu and click on a link
    toggle_btn.click(force=True)
    page.wait_for_timeout(800)
    # Scope link searching to #nav-menu to avoid ambiguity
    about_link = page.locator("#nav-menu").get_by_role("link", name="Tentang")
    about_link.click(force=True)
    page.wait_for_timeout(1500)

    # 5. Incremental scrolling to trigger ScrollReveal animations
    print("Performing incremental scrolling on mobile...")
    for i in range(1, 8):
        page.mouse.wheel(0, 500)
        page.wait_for_timeout(400)

    # Scroll to the contact form
    contact_section = page.locator("#contact")
    contact_section.scroll_into_view_if_needed()
    page.wait_for_timeout(800)

    # 6. Fill and Submit Contact Form
    print("Submitting contact form on mobile...")
    page.locator("#simulation_mode").evaluate("el => el.value = '1'")
    page.fill("#user_name", "Budi Santoso")
    page.wait_for_timeout(300)
    page.fill("#user_email", "budi.santoso@example.com")
    page.wait_for_timeout(300)
    page.fill("#user_project", "Proyek multimedia kreatif untuk promosi produk UMKM.")
    page.wait_for_timeout(500)

    # Submit Form
    page.locator("button[type='submit']").click()
    page.wait_for_timeout(500)

    # Wait for feedback
    msg_loc = page.locator("#contact-message")
    msg_loc.wait_for(state="visible")
    page.wait_for_timeout(2000)

    # Capture Screenshot in Mobile Viewport
    page.screenshot(path="/home/jules/verification/screenshots/mobile_verification.png", full_page=True)
    print("Mobile verification screenshot captured!")
    page.wait_for_timeout(1000)
    page.close()

if __name__ == "__main__":
    os.makedirs("/home/jules/verification/screenshots", exist_ok=True)
    os.makedirs("/home/jules/verification/videos", exist_ok=True)

    server_process = start_server()
    try:
        with sync_playwright() as p:
            browser = p.chromium.launch(headless=True)
            # Create a context with video recording
            context = browser.new_context(
                record_video_dir="/home/jules/verification/videos"
            )

            # Execute Journeys
            run_desktop_journey(context)
            run_mobile_journey(context)

            context.close()
            browser.close()
    finally:
        stop_server(server_process)
    print("Verification completed successfully!")
