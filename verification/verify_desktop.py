import asyncio
import os
import sys
from playwright.async_api import async_playwright

async def run():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        # 1280x800 for desktop
        context = await browser.new_context(viewport={"width": 1280, "height": 800})
        page = await context.new_page()

        # Connect to localhost PHP server
        print("Navigating to Ar Creative Desktop...")
        await page.goto("http://127.0.0.1:8000")
        await page.wait_for_timeout(1000)

        # Incrementally scroll down to trigger ScrollReveal animations
        print("Incremental scrolling to trigger animations...")
        for i in range(10):
            await page.mouse.wheel(0, 400)
            await page.wait_for_timeout(200)

        # Scroll back to top
        await page.evaluate("window.scrollTo(0, 0)")
        await page.wait_for_timeout(500)

        # Take full page screenshot of the default light theme
        screenshot_path = "verification/screenshots/desktop_light.png"
        await page.screenshot(path=screenshot_path, full_page=True)
        print(f"Captured light theme: {screenshot_path}")

        # Toggle Dark Theme
        print("Toggling dark theme...")
        theme_button = page.locator("#theme-button")
        await theme_button.click()
        await page.wait_for_timeout(500)

        # Confirm class exists on body
        is_dark = await page.evaluate("document.body.classList.contains('dark-theme')")
        print(f"Is body in dark theme? {is_dark}")

        # Capture dark theme full page screenshot
        screenshot_dark_path = "verification/screenshots/desktop_dark.png"
        await page.screenshot(path=screenshot_dark_path, full_page=True)
        print(f"Captured dark theme: {screenshot_dark_path}")

        # Navigate to sections via navigation menu
        print("Testing desktop navigation click...")
        about_link = page.locator("#nav-menu a[href='#about']")
        await about_link.click()
        await page.wait_for_timeout(500)

        # Verify scroll-to element
        scroll_y = await page.evaluate("window.scrollY")
        print(f"Scroll Y after about click: {scroll_y}")

        # Form filling and submission
        print("Testing Contact Form submission with simulation_mode=1...")
        # Set hidden field to '1' using page.evaluate
        await page.evaluate("document.getElementById('simulation_mode').value = '1'")

        await page.fill("#user_name", "Test User Desktop")
        await page.fill("#user_email", "desktop@test.com")
        await page.fill("#user_project", "This is a test message from automated desktop verification.")

        # Click submit
        submit_btn = page.locator("#contact-form button[type='submit']")
        await submit_btn.click()

        # Wait for message to appear
        msg_loc = page.locator("#contact-message")
        await msg_loc.wait_for(state="visible")
        msg_text = await msg_loc.text_content()
        print(f"Form submission response: {msg_text}")

        # Capture contact section screenshot
        await page.locator("#contact").scroll_into_view_if_needed()
        await page.wait_for_timeout(500)
        contact_screenshot = "verification/screenshots/desktop_contact_success.png"
        await page.screenshot(path=contact_screenshot)
        print(f"Captured contact success screenshot: {contact_screenshot}")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(run())
