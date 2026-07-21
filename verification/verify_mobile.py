import asyncio
import os
import sys
from playwright.async_api import async_playwright

async def run():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        # 375x667 for mobile
        context = await browser.new_context(
            viewport={"width": 375, "height": 667},
            user_agent="Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1"
        )
        page = await context.new_page()

        # Connect to localhost PHP server
        print("Navigating to Ar Creative Mobile...")
        await page.goto("http://127.0.0.1:8000")
        await page.wait_for_timeout(1000)

        # Trigger ScrollReveal animations
        print("Incremental scrolling on mobile...")
        for i in range(15):
            await page.mouse.wheel(0, 300)
            await page.wait_for_timeout(150)

        # Scroll back to top
        await page.evaluate("window.scrollTo(0, 0)")
        await page.wait_for_timeout(500)

        # Take mobile light theme screenshot
        screenshot_path = "verification/screenshots/mobile_light.png"
        await page.screenshot(path=screenshot_path, full_page=True)
        print(f"Captured mobile light theme: {screenshot_path}")

        # Test Mobile Menu toggle open
        print("Testing Mobile Menu toggle open...")
        toggle_btn = page.locator("#nav-toggle")
        # Use force=True as ScrollReveal transitions can intercept clicks
        await toggle_btn.click(force=True)
        await page.wait_for_timeout(500)

        # Check menu is visible (has top class or is on screen)
        menu = page.locator("#nav-menu")
        is_menu_shown = await page.evaluate("document.getElementById('nav-menu').classList.contains('show-menu')")
        print(f"Is mobile menu shown? {is_menu_shown}")

        # Take screenshot with menu open
        menu_screenshot = "verification/screenshots/mobile_menu_open.png"
        await page.screenshot(path=menu_screenshot)
        print(f"Captured mobile menu screenshot: {menu_screenshot}")

        # Test Mobile Menu link click to close menu
        print("Clicking a navigation link to close menu...")
        # Scope the search to the '#nav-menu' locator to ensure correct element is targeted
        nav_link = page.locator("#nav-menu a[href='#about']")
        await nav_link.click(force=True)
        await page.wait_for_timeout(500)

        is_menu_shown_after = await page.evaluate("document.getElementById('nav-menu').classList.contains('show-menu')")
        print(f"Is mobile menu shown after link click? {is_menu_shown_after}")

        # Toggle Dark Theme on mobile
        print("Toggling dark theme on mobile...")
        theme_button = page.locator("#theme-button")
        await theme_button.click(force=True)
        await page.wait_for_timeout(500)

        # Capture dark theme full page mobile screenshot
        screenshot_dark_path = "verification/screenshots/mobile_dark.png"
        await page.screenshot(path=screenshot_dark_path, full_page=True)
        print(f"Captured mobile dark theme: {screenshot_dark_path}")

        # Submit contact form on mobile
        print("Testing Contact Form submission on mobile...")
        await page.evaluate("document.getElementById('simulation_mode').value = '1'")
        await page.fill("#user_name", "Test User Mobile")
        await page.fill("#user_email", "mobile@test.com")
        await page.fill("#user_project", "This is a test message from automated mobile verification.")

        submit_btn = page.locator("#contact-form button[type='submit']")
        await submit_btn.click()

        # Wait for message to appear
        msg_loc = page.locator("#contact-message")
        await msg_loc.wait_for(state="visible")
        msg_text = await msg_loc.text_content()
        print(f"Mobile form submission response: {msg_text}")

        # Capture contact section screenshot
        await page.locator("#contact").scroll_into_view_if_needed()
        await page.wait_for_timeout(500)
        contact_screenshot = "verification/screenshots/mobile_contact_success.png"
        await page.screenshot(path=contact_screenshot)
        print(f"Captured mobile contact success screenshot: {contact_screenshot}")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(run())
