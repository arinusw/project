import os
import time
from playwright.sync_api import sync_playwright, expect

def run_verification():
    print("Memulai verifikasi UI untuk Ar Creative...")

    # Ensure screenshot and video directories exist
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    with sync_playwright() as p:
        # Launch headless browser
        browser = p.chromium.launch(headless=True)

        # 1. VERIFIKASI DESKTOP (1280x800)
        print("\n--- Menguji Layout Desktop (1280x800) ---")
        desktop_context = browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos"
        )
        desktop_page = desktop_context.new_page()

        # Go to landing page on local server
        desktop_page.goto("http://127.0.0.1:8000/index.html")
        desktop_page.wait_for_load_state("networkidle")

        # Trigger ScrollReveal animations by scrolling down incrementally
        print("Memicu animasi ScrollReveal dengan scroll bertahap di Desktop...")
        for i in range(5):
            desktop_page.mouse.wheel(0, 800)
            time.sleep(0.3)

        # Scroll back to top
        desktop_page.evaluate("window.scrollTo(0, 0)")
        time.sleep(0.5)

        # Capture desktop light mode screenshot
        desktop_page.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)
        print("Screenshot Desktop Mode Terang disimpan.")

        # Test Theme Toggle to Dark Mode
        theme_button = desktop_page.locator("#theme-button")
        theme_button.click()
        time.sleep(0.5)

        # Verify body has dark-theme class
        expect(desktop_page.locator("body")).to_have_class("dark-theme")
        desktop_page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)
        print("Screenshot Desktop Mode Gelap disimpan.")

        # Test Contact Form Submission (Simulation Mode = '1')
        print("Menguji pengisian formulir kontak di Desktop...")
        desktop_page.locator("#user_name").fill("Jules Developer")
        desktop_page.locator("#user_email").fill("jules@example.com")
        desktop_page.locator("#user_project").fill("Saya ingin membuat website portofolio studio kreatif digital modern.")

        # Enable simulation mode via JS
        desktop_page.evaluate("document.getElementById('simulation_mode').value = '1'")

        # Submit the form
        desktop_page.locator("#contact-form button[type='submit']").click()

        # Wait for the success message to appear and verify success message format/value
        contact_message = desktop_page.locator("#contact-message")
        contact_message.wait_for(state="visible")

        expect(contact_message).to_contain_text("Simulasi Mode Sukses")
        desktop_page.screenshot(path="verification/screenshots/desktop_contact_success.png")
        print("Formulir sukses disubmit di Desktop. Selesai.")

        desktop_context.close()


        # 2. VERIFIKASI MOBILE (375x667)
        print("\n--- Menguji Layout Mobile (375x667) ---")
        mobile_context = browser.new_context(
            viewport={"width": 375, "height": 667},
            record_video_dir="verification/videos",
            user_agent="Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1"
        )
        mobile_page = mobile_context.new_page()

        # Go to landing page
        mobile_page.goto("http://127.0.0.1:8000/index.html")
        mobile_page.wait_for_load_state("networkidle")

        # Trigger ScrollReveal animations
        print("Memicu animasi ScrollReveal di Mobile...")
        for i in range(8):
            mobile_page.mouse.wheel(0, 500)
            time.sleep(0.3)

        # Scroll back to top
        mobile_page.evaluate("window.scrollTo(0, 0)")
        time.sleep(0.5)

        # Capture mobile landing screenshot
        mobile_page.screenshot(path="verification/screenshots/mobile_light.png", full_page=True)
        print("Screenshot Mobile disimpan.")

        # Open mobile drawer menu using force=True to bypass potential interceptions
        print("Menguji buka/tutup menu navigasi mobile...")
        nav_toggle = mobile_page.locator("#nav-toggle")
        nav_toggle.click(force=True)
        time.sleep(0.5)

        # Verify menu is visible
        expect(mobile_page.locator("#nav-menu")).to_have_class("nav__menu show-menu")
        mobile_page.screenshot(path="verification/screenshots/mobile_menu_open.png")

        # Close mobile drawer menu
        nav_close = mobile_page.locator("#nav-close")
        nav_close.click(force=True)
        time.sleep(0.5)
        expect(mobile_page.locator("#nav-menu")).not_to_have_class("show-menu")
        print("Menu navigasi mobile berhasil dibuka dan ditutup.")

        mobile_context.close()
        browser.close()
        print("\nVerifikasi UI selesai sukses!")

if __name__ == "__main__":
    run_verification()
