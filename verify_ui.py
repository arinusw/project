import asyncio
import os
import sys
from playwright.async_api import async_playwright

async def run_desktop_verification():
    print("Starting Desktop UI verification...")
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        # Create context to record video
        context = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos"
        )
        page = await context.new_page()

        # Navigate to home
        await page.goto("http://127.0.0.1:8000/")
        await page.wait_for_timeout(1000)

        # Verify initial layout
        title = await page.title()
        print(f"Page Title: {title}")
        assert "Ar Creative" in title, "Title mismatch"

        # Incremental scrolling to trigger ScrollReveal animations
        print("Triggering scroll animations...")
        for y in range(0, 4500, 400):
            await page.mouse.wheel(0, 400)
            await page.wait_for_timeout(100)

        # Scroll back to top
        await page.evaluate("window.scrollTo(0, 0)")
        await page.wait_for_timeout(500)
        await page.screenshot(path="verification/screenshots/desktop_home.png", full_page=True)
        print("Captured desktop_home.png")

        # Verify theme toggle
        print("Testing dark theme toggle...")
        theme_button = page.locator("#theme-button")
        await theme_button.click()
        await page.wait_for_timeout(500)

        # Confirm dark-theme class on body
        has_dark_theme = await page.evaluate("document.body.classList.contains('dark-theme')")
        print(f"Body has dark-theme class: {has_dark_theme}")
        assert has_dark_theme, "Dark theme not applied to body"

        await page.screenshot(path="verification/screenshots/desktop_dark_mode.png", full_page=True)
        print("Captured desktop_dark_mode.png")

        # Revert theme toggle
        await theme_button.click()
        await page.wait_for_timeout(500)

        # Check active link highlighting on scroll
        # Scroll to education section
        education_section = page.locator("#education")
        await education_section.scroll_into_view_if_needed()
        await page.wait_for_timeout(1000)

        # Confirm active-link is on education anchor
        education_link = page.locator(".nav__menu a[href*='education']")
        has_active = await education_link.evaluate("el => el.classList.contains('active-link')")
        print(f"Education link is active on scroll: {has_active}")

        # Fill & submit contact form in simulation mode
        print("Filling out contact form...")
        contact_section = page.locator("#contact")
        await contact_section.scroll_into_view_if_needed()
        await page.wait_for_timeout(500)

        await page.fill("#user-name", "Andi Pratama")
        await page.fill("#user-email", "andi.pratama@example.com")
        await page.fill("#user-project", "Halo Ar Creative, saya butuh landing page modern untuk usaha kecil saya.")

        # Set simulation mode to 1
        await page.evaluate("document.getElementById('simulation-mode').value = '1'")

        # Submit
        print("Submitting contact form...")
        await page.click("#contact-form button[type='submit']")

        # Wait for success message
        success_message = page.locator("#contact-message")
        await success_message.wait_for(state="visible", timeout=5000)
        message_text = await success_message.text_content()
        print(f"Server response message: {message_text}")
        assert "simulasi" in message_text.lower() or "berhasil" in message_text.lower(), "Contact submission failed"

        await page.screenshot(path="verification/screenshots/desktop_contact_success.png")
        print("Captured desktop_contact_success.png")

        # Close context
        await context.close()
        await browser.close()

    print("Desktop UI verification completed successfully!")

async def run_mobile_verification():
    print("Starting Mobile UI verification...")
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(
            viewport={"width": 375, "height": 667},
            record_video_dir="verification/videos"
        )
        page = await context.new_page()

        await page.goto("http://127.0.0.1:8000/")
        await page.wait_for_timeout(1000)

        # Capture home screen
        await page.screenshot(path="verification/screenshots/mobile_home.png")
        print("Captured mobile_home.png")

        # Toggle Mobile Menu
        print("Opening mobile menu...")
        nav_toggle = page.locator("#nav-toggle")
        await nav_toggle.click(force=True)
        await page.wait_for_timeout(500)

        # Confirm menu is visible
        nav_menu = page.locator("#nav-menu")
        is_menu_shown = await nav_menu.evaluate("el => el.classList.contains('show-menu')")
        print(f"Mobile menu shown: {is_menu_shown}")
        assert is_menu_shown, "Mobile menu failed to open"

        await page.screenshot(path="verification/screenshots/mobile_menu_open.png")
        print("Captured mobile_menu_open.png")

        # Click menu link to close and scroll
        print("Clicking Services link from menu...")
        # Scope link selection
        services_link = page.locator("#nav-menu a[href*='services']")
        await services_link.click(force=True)
        await page.wait_for_timeout(1000)

        # Verify menu closed
        is_menu_closed = await nav_menu.evaluate("el => !el.classList.contains('show-menu')")
        print(f"Mobile menu closed after link click: {is_menu_closed}")
        assert is_menu_closed, "Mobile menu failed to close after link click"

        await page.screenshot(path="verification/screenshots/mobile_services_section.png")
        print("Captured mobile_services_section.png")

        # Fill out contact form on mobile
        contact_section = page.locator("#contact")
        await contact_section.scroll_into_view_if_needed()
        await page.wait_for_timeout(500)

        await page.fill("#user-name", "Budi Santoso")
        await page.fill("#user-email", "budi.santoso@example.com")
        await page.fill("#user-project", "Halo Ar Creative, saya butuh workshop edukasi teknologi untuk komunitas lokal kami.")

        await page.evaluate("document.getElementById('simulation-mode').value = '1'")

        print("Submitting contact form on mobile...")
        await page.click("#contact-form button[type='submit']")

        success_message = page.locator("#contact-message")
        await success_message.wait_for(state="visible", timeout=5000)
        message_text = await success_message.text_content()
        print(f"Mobile server response message: {message_text}")
        assert "simulasi" in message_text.lower() or "berhasil" in message_text.lower(), "Contact submission failed on mobile"

        await page.screenshot(path="verification/screenshots/mobile_contact_success.png")
        print("Captured mobile_contact_success.png")

        await context.close()
        await browser.close()

    print("Mobile UI verification completed successfully!")

async def main():
    try:
        await run_desktop_verification()
        await run_mobile_verification()
    except Exception as e:
        print(f"Verification Error: {e}")
        sys.exit(1)

if __name__ == "__main__":
    asyncio.run(main())
