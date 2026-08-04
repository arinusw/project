import asyncio
import os
import time
from playwright.async_api import async_playwright

async def verify_desktop():
    print("Starting Desktop Verification...")
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        # Standard Desktop viewport
        context = await browser.new_context(viewport={"width": 1280, "height": 800})
        page = await context.new_page()

        # Navigate to local PHP server
        await page.goto("http://127.0.0.1:8000")
        await page.wait_for_timeout(2000)

        # 1. Verify desktop header elements
        nav_logo = page.locator(".nav__logo")
        await nav_logo.wait_for(state="visible")
        print("Logo is visible on desktop.")

        nav_toggle = page.locator("#nav-toggle")
        is_toggle_hidden = await nav_toggle.evaluate("el => getComputedStyle(el).display === 'none'")
        print(f"Mobile toggle button is hidden on desktop: {is_toggle_hidden}")

        # 2. Test Light/Dark theme toggle
        theme_button = page.locator("#theme-button")
        await theme_button.click()
        await page.wait_for_timeout(500)

        body_has_dark = await page.locator("body").evaluate("el => el.classList.contains('dark-theme')")
        print(f"Theme toggled successfully, body has dark-theme class: {body_has_dark}")

        # Toggle back to light
        await theme_button.click()
        await page.wait_for_timeout(500)

        # 3. Perform incremental scrolling to trigger ScrollReveal animations
        print("Performing incremental scrolling to activate ScrollReveal animations...")
        for y in range(0, 4000, 400):
            await page.mouse.wheel(0, 400)
            await page.wait_for_timeout(100)

        # Scroll back to top
        await page.evaluate("window.scrollTo(0, 0)")
        await page.wait_for_timeout(1000)

        # 4. Capture screenshots of each section
        sections = ["home", "about", "services", "education", "social", "contact"]
        os.makedirs("verification/screenshots", exist_ok=True)

        # Capture full page
        await page.screenshot(path="verification/screenshots/desktop_fullpage.png", full_page=True)
        print("Desktop full page screenshot captured.")

        for section in sections:
            loc = page.locator(f"#{section}")
            await loc.scroll_into_view_if_needed()
            await page.wait_for_timeout(300)
            await page.screenshot(path=f"verification/screenshots/desktop_{section}.png")
            print(f"Screenshot captured for section: #{section}")

        await browser.close()
    print("Desktop Verification Completed Successfully!")

if __name__ == "__main__":
    asyncio.run(verify_desktop())
