import asyncio
import time
from playwright.async_api import async_playwright

async def verify_desktop():
    print("Starting Desktop Verification...")
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        # Create a browser context with desktop viewport
        context = await browser.new_context(viewport={"width": 1280, "height": 800})
        page = await context.new_page()

        # Navigate to home page
        await page.goto("http://127.0.0.1:8000")
        await page.wait_for_timeout(2000) # Wait for ScrollReveal

        # Take initial screenshot
        await page.screenshot(path="verification/screenshots/desktop_home.png", full_page=True)
        print("Desktop Home Screenshot saved.")

        # Test Dark Theme Toggle
        theme_btn = page.locator("#theme-button")
        await theme_btn.click()
        await page.wait_for_timeout(1000)
        await page.screenshot(path="verification/screenshots/desktop_dark_theme.png", full_page=True)
        print("Desktop Dark Theme Screenshot saved.")

        # Toggle back to light theme
        await theme_btn.click()
        await page.wait_for_timeout(500)

        # Test Contact form simulation submission
        await page.locator("#user_name").fill("John Doe Desktop")
        await page.locator("#user_email").fill("john.desktop@example.com")
        await page.locator("#user_project").fill("Halo, saya ingin membuat website custom untuk bisnis lokal saya.")

        # Scroll to form to trigger any animations and visual elements
        await page.locator("#contact").scroll_into_view_if_needed()
        await page.wait_for_timeout(1000)

        # Submit form
        await page.locator('#contact-form button[type="submit"]').click()

        # Wait for the success message to be visible
        success_msg = page.locator("#contact-message")
        await success_msg.wait_for(state="visible")
        print("Form response message:", await success_msg.inner_text())

        await page.screenshot(path="verification/screenshots/desktop_form_submitted.png")
        print("Desktop Form Submitted Screenshot saved.")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(verify_desktop())
