import asyncio
import os
from playwright.async_api import async_playwright

async def run_verification():
    print("Starting Playwright verification...")
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Verification (Light Theme)
        context_desktop = await browser.new_context(viewport={"width": 1280, "height": 800})
        page = await context_desktop.new_page()
        await page.goto("http://127.0.0.1:8000/index.html?simulation_mode=1")
        await page.wait_for_timeout(1000)

        # Trigger ScrollReveal entrance animations by incremental scrolling
        for i in range(0, 3000, 500):
            await page.mouse.wheel(0, 500)
            await page.wait_for_timeout(200)

        await page.evaluate("window.scrollTo(0, 0)")
        await page.wait_for_timeout(500)
        await page.screenshot(path="verification/screenshots/desktop_light.png")
        print("Captured verification/screenshots/desktop_light.png")

        # Toggle Dark Theme
        theme_btn = page.locator("#theme-button")
        await theme_btn.click()
        await page.wait_for_timeout(500)
        await page.screenshot(path="verification/screenshots/desktop_dark.png")
        print("Captured verification/screenshots/desktop_dark.png")

        # Contact Form Submission (Simulation Mode)
        await page.locator("#name").fill("Budi Santoso")
        await page.locator("#email").fill("budi@example.com")
        await page.locator("#subject").fill("Konsultasi Website Studio")
        await page.locator("#message").fill("Halo Ar Creative, saya ingin berkonsultasi mengenai pembuatan website portofolio.")

        submit_btn = page.locator("#contact-form button[type='submit']")
        await submit_btn.click()

        message_el = page.locator("#contact-message")
        await message_el.wait_for(state="visible")
        await page.screenshot(path="verification/screenshots/desktop_contact_success.png")
        print("Captured verification/screenshots/desktop_contact_success.png")

        await context_desktop.close()

        # 2. Mobile Verification
        context_mobile = await browser.new_context(viewport={"width": 375, "height": 667})
        page_m = await context_mobile.new_page()
        await page_m.goto("http://127.0.0.1:8000/index.html?simulation_mode=1")
        await page_m.wait_for_timeout(1000)

        # Open mobile drawer
        toggle_btn = page_m.locator("#nav-toggle")
        await toggle_btn.click(force=True)
        await page_m.wait_for_timeout(500)
        await page_m.screenshot(path="verification/screenshots/mobile_menu_open.png")
        print("Captured verification/screenshots/mobile_menu_open.png")

        # Close mobile drawer
        close_btn = page_m.locator("#nav-close")
        await close_btn.click(force=True)
        await page_m.wait_for_timeout(300)

        await page_m.screenshot(path="verification/screenshots/mobile_light.png")
        print("Captured verification/screenshots/mobile_light.png")

        await context_mobile.close()
        await browser.close()

if __name__ == "__main__":
    asyncio.run(run_verification())
