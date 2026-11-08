import asyncio
import os
from playwright.async_api import async_playwright

async def run():
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    async with async_playwright() as p:
        # 1. Desktop Test & Journey
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos"
        )
        page = await context.new_page()

        print("Navigating to local site...")
        await page.goto("http://127.0.0.1:8000/index.html", wait_until="networkidle")
        await page.wait_for_timeout(500)

        # Trigger scroll reveal across page
        for y in range(0, 3000, 300):
            await page.evaluate(f"window.scrollTo(0, {y})")
            await page.wait_for_timeout(100)

        # Scroll back to top for clean initial view
        await page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
        await page.wait_for_timeout(300)

        # Desktop Light Viewport Screenshot
        await page.screenshot(path="verification/screenshots/desktop_light.png")
        print("Desktop light screenshot saved.")

        # Test Dark Theme Toggle
        theme_button = page.locator("#theme-button")
        await theme_button.click()
        await page.wait_for_timeout(400)
        await page.screenshot(path="verification/screenshots/desktop_dark.png")
        print("Desktop dark screenshot saved.")

        # Toggle back to light or keep dark to test form
        await theme_button.click()
        await page.wait_for_timeout(300)

        # Scroll down to services
        await page.locator(".nav__menu a[href*='services']").click()
        await page.wait_for_timeout(600)
        await page.screenshot(path="verification/screenshots/desktop_services.png")

        # Scroll to contact form and submit
        await page.locator(".nav__menu a[href*='contact']").click()
        await page.wait_for_timeout(600)

        await page.fill("#contact-name", "Andi Pratama")
        await page.fill("#contact-email", "andi.pratama@example.com")
        await page.fill("#contact-subject", "Jasa Web & Edukasi Digital")
        await page.fill("#contact-message", "Halo Ar Creative, kami ingin berdiskusi mengenai proyek web dan materi workshop edukasi.")
        await page.wait_for_timeout(300)

        await page.click("#contact-submit-btn")
        # Wait for success status
        await page.wait_for_selector(".contact__message--success", timeout=5000)
        await page.wait_for_timeout(400)
        await page.screenshot(path="verification/screenshots/desktop_contact_success.png")
        print("Contact form submitted successfully.")

        await context.close()
        await browser.close()

        # 2. Mobile Viewport Test (375x667)
        browser_mobile = await p.chromium.launch(headless=True)
        context_mobile = await browser_mobile.new_context(
            viewport={"width": 375, "height": 667}
        )
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto("http://127.0.0.1:8000/index.html", wait_until="networkidle")
        await page_mobile.wait_for_timeout(300)

        # Mobile Light Home Screenshot
        await page_mobile.screenshot(path="verification/screenshots/mobile_light.png")

        # Open mobile navigation menu
        nav_toggle = page_mobile.locator("#nav-toggle")
        await nav_toggle.click(force=True)
        await page_mobile.wait_for_timeout(400)
        await page_mobile.screenshot(path="verification/screenshots/mobile_menu_open.png")
        print("Mobile menu opened and captured.")

        # Close mobile menu by clicking a nav link
        await page_mobile.locator("#nav-menu a[href*='services']").click(force=True)
        await page_mobile.wait_for_timeout(600)
        await page_mobile.screenshot(path="verification/screenshots/mobile_services.png")

        # Mobile scroll to contact
        await page_mobile.evaluate("window.scrollTo(0, document.body.scrollHeight)")
        await page_mobile.wait_for_timeout(600)
        await page_mobile.screenshot(path="verification/screenshots/mobile_contact.png")

        await context_mobile.close()
        await browser_mobile.close()

        print("All verification steps completed!")

if __name__ == "__main__":
    asyncio.run(run())
