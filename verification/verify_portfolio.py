import asyncio
from playwright.async_api import async_playwright
import os
import time

async def run_verification():
    async with async_playwright() as p:
        # Launch browser
        browser = await p.chromium.launch()
        context = await browser.new_context(viewport={'width': 1280, 'height': 800})
        page = await context.new_page()

        url = "http://127.0.0.1:8000"

        try:
            print(f"Navigating to {url}...")
            await page.goto(url)
            await asyncio.sleep(2) # Wait for animations

            # 1. Verify Title
            title = await page.title()
            print(f"Page Title: {title}")
            assert "Ar Creative" in title

            # 2. Verify Sections visibility (Scroll and trigger ScrollReveal)
            sections = ["home", "about", "services", "education", "contact"]
            for section in sections:
                print(f"Checking section: {section}")
                locator = page.locator(f"#{section}")
                await locator.scroll_into_view_if_needed()
                await asyncio.sleep(1) # Wait for animation
                assert await locator.is_visible()

            # Take desktop screenshot
            await page.screenshot(path="verification/desktop_home.png", full_page=True)
            print("Desktop screenshot saved.")

            # 3. Test Theme Toggle
            theme_button = page.locator("#theme-button")
            await theme_button.click()
            await asyncio.sleep(1)
            is_dark = await page.evaluate("document.body.classList.contains('dark-theme')")
            print(f"Dark theme active: {is_dark}")
            assert is_dark
            await page.screenshot(path="verification/desktop_dark_mode.png", full_page=True)

            # 4. Test Contact Form (Simulation Mode)
            print("Testing contact form...")
            await page.fill("#user_name", "Test User")
            await page.fill("#user_email", "test@example.com")
            await page.fill("#user_project", "This is a test message for verification.")

            # Set simulation_mode to 1 for the form submission
            await page.evaluate("document.getElementById('contact-form').querySelector('button').click()")
            # Wait for response
            message_locator = page.locator("#contact-message")
            await message_locator.wait_for(state="visible", timeout=5000)
            message_text = await message_locator.inner_text()
            print(f"Contact form response: {message_text}")
            assert "Pesan Anda telah terkirim" in message_text or "Mode Simulasi" in message_text

            # 5. Mobile Responsiveness
            print("Testing mobile layout...")
            mobile_context = await browser.new_context(viewport={'width': 375, 'height': 667})
            mobile_page = await mobile_context.new_page()
            await mobile_page.goto(url)
            await asyncio.sleep(2)

            # Check if nav toggle is visible
            nav_toggle = mobile_page.locator("#nav-toggle")
            assert await nav_toggle.is_visible()

            # Open menu
            await nav_toggle.click()
            await asyncio.sleep(1)
            nav_menu = mobile_page.locator("#nav-menu")
            assert await nav_menu.is_visible()

            await mobile_page.screenshot(path="verification/mobile_menu.png")
            print("Mobile menu screenshot saved.")

        except Exception as e:
            print(f"Verification failed: {e}")
            raise
        finally:
            await browser.close()

if __name__ == "__main__":
    asyncio.run(run_verification())
