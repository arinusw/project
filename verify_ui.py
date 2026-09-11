#!/usr/bin/env python3
import os
import time
from playwright.sync_api import sync_playwright

def trigger_scroll_animations(page):
    """Incremental scroll to trigger ScrollReveal animations across all sections."""
    viewport_height = page.viewport_size['height'] if page.viewport_size else 800
    total_height = page.evaluate('document.body.scrollHeight')
    steps = max(1, total_height // 400)

    for i in range(steps + 1):
        page.mouse.wheel(0, 400)
        page.wait_for_timeout(200)

    # Scroll back to top
    page.evaluate('window.scrollTo(0, 0)')
    page.wait_for_timeout(300)

def main():
    base_url = "http://127.0.0.1:8000"
    output_dir = "verification/screenshots"
    video_dir = "verification/videos"

    os.makedirs(output_dir, exist_ok=True)
    os.makedirs(video_dir, exist_ok=True)

    with sync_playwright() as p:
        # Launch Chromium Browser
        browser = p.chromium.launch(headless=True)

        # ----------------------------------------------------
        # 1. Desktop Verification (1280 x 800)
        # ----------------------------------------------------
        print("--- Starting Desktop Verification ---")
        context_desktop = browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir=video_dir
        )
        page_desktop = context_desktop.new_page()
        page_desktop.goto(base_url, wait_until="networkidle")
        page_desktop.wait_for_timeout(500)

        # Trigger ScrollReveal animations
        trigger_scroll_animations(page_desktop)

        # Capture Desktop Light Mode Full Page Screenshot
        page_desktop.screenshot(path=f"{output_dir}/desktop_light.png", full_page=True)
        print("Saved: desktop_light.png")

        # Toggle Dark Mode
        theme_btn = page_desktop.locator("#theme-button")
        theme_btn.click()
        page_desktop.wait_for_timeout(300)

        # Capture Desktop Dark Mode Full Page Screenshot
        page_desktop.screenshot(path=f"{output_dir}/desktop_dark.png", full_page=True)
        print("Saved: desktop_dark.png")

        # Test Contact Form Submission in Simulation Mode
        print("Testing Contact Form Submission on Desktop...")
        page_desktop.locator("#contact").scroll_into_view_if_needed()
        page_desktop.wait_for_timeout(300)

        page_desktop.fill("#name", "Budi Pratama")
        page_desktop.fill("#email", "budi@example.com")
        page_desktop.fill("#subject", "Konsultasi Web Design Studio")
        page_desktop.fill("#message", "Halo Ar Creative, saya tertarik untuk menggunakan jasa pembuatan website kreatif.")

        # Inject simulation_mode = 1 into form
        page_desktop.evaluate("""() => {
            const form = document.getElementById('contact-form');
            if (form) {
                let simInput = document.getElementById('simulation_mode');
                if (!simInput) {
                    simInput = document.createElement('input');
                    simInput.type = 'hidden';
                    simInput.id = 'simulation_mode';
                    simInput.name = 'simulation_mode';
                    simInput.value = '1';
                    form.appendChild(simInput);
                }
            }
        }""")

        page_desktop.locator("#contact-form button[type='submit']").click()

        # Wait for success message
        contact_msg = page_desktop.locator("#contact-message")
        contact_msg.wait_for(state="visible")
        print(f"Contact form response: {contact_msg.inner_text()}")

        page_desktop.screenshot(path=f"{output_dir}/desktop_contact_submitted.png")
        print("Saved: desktop_contact_submitted.png")

        context_desktop.close()

        # ----------------------------------------------------
        # 2. Mobile Verification (375 x 667)
        # ----------------------------------------------------
        print("--- Starting Mobile Verification ---")
        context_mobile = browser.new_context(
            viewport={"width": 375, "height": 667},
            is_mobile=True,
            record_video_dir=video_dir
        )
        page_mobile = context_mobile.new_page()
        page_mobile.goto(base_url, wait_until="networkidle")
        page_mobile.wait_for_timeout(500)

        # Trigger ScrollReveal animations
        trigger_scroll_animations(page_mobile)

        # Capture Mobile Light Mode Screenshot
        page_mobile.screenshot(path=f"{output_dir}/mobile_light.png", full_page=True)
        print("Saved: mobile_light.png")

        # Open Mobile Navigation Menu
        print("Testing Mobile Drawer Navigation...")
        nav_toggle = page_mobile.locator("#nav-toggle")
        nav_toggle.click(force=True)
        page_mobile.wait_for_timeout(400)

        page_mobile.screenshot(path=f"{output_dir}/mobile_menu_open.png")
        print("Saved: mobile_menu_open.png")

        # Navigate via hash to #services
        page_mobile.locator("#nav-menu a[href='#services']").click()
        page_mobile.wait_for_timeout(500)

        # Capture Mobile Dark Mode
        mobile_theme_btn = page_mobile.locator("#theme-button")
        mobile_theme_btn.click()
        page_mobile.wait_for_timeout(300)

        page_mobile.screenshot(path=f"{output_dir}/mobile_dark.png", full_page=True)
        print("Saved: mobile_dark.png")

        context_mobile.close()
        browser.close()

    print("=== Visual UI Verification Completed Successfully ===")

if __name__ == '__main__':
    main()
